<?php


namespace App\Services;

use App\Gmail;
use App\Label;
use Dacastro4\LaravelGmail\Facade\LaravelGmail;
use Dacastro4\LaravelGmail\Services\MessageCollection;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class GmailLoadService
{
    private int $start;
    private int $length;
    private ?string $label;
    private ?Authenticatable $user;

    /**
     * GmailLoadService constructor.
     * @param  int  $start
     * @param  int  $length
     * @param  string|null  $label
     */
    public function __construct(int $start, int $length, string $label = null)
    {
        $this->start = (int) $start;
        $this->length = (int) $length;
        $this->label = $label;
        $this->user = Auth::user();
    }

    public function getEmails(): JsonResponse
    {
        if ($this->isEmptyInbox()) {
            $this->loadAndSaveEmails();
        } elseif ($this->isMoreEmailsNeeded()) {
            $nextPageToken = $this->user->next_page_token;

            if (!is_null($nextPageToken)) {
                $this->loadAndSaveEmails($nextPageToken);
            }
        }

        return $this->loadEmailsFromDatabase();
    }

    private function isEmptyInbox(): bool
    {
        return $this->countEmails() === 0;
    }

    private function countEmails(): int
    {
        return Gmail::where('user_id', $this->user->id)->count();
    }

    private function loadAndSaveEmails(?string $nextPageToken = null): void
    {
        $emails = $this->loadEmailsFromApi($nextPageToken);
        $this->updateNextPageToken($emails->getPageToken());
        $this->saveEmails($emails);
    }

    private function loadEmailsFromApi(?string $nextPageToken = null): MessageCollection
    {
        return LaravelGmail::message()->take(20)->preload()->in('inbox')->all($nextPageToken);
    }

    private function loadEmailsFromDatabase(): JsonResponse
    {
        $emails = Gmail::with('labels')->where('user_id', $this->user->id);

        return DataTables::eloquent($emails)
            ->addColumn('labels', function (Gmail $email) {
                return $this->renderLabels($email->labels);
            })
            ->addColumn('action', function (Gmail $email) {
                return $this->renderAction($email->id);
            })
            ->filter(function ($query) {
                $this->filterByLabel($query);
            })
            ->rawColumns(['labels', 'action'])
            ->toJson();
    }

    private function renderLabels(Collection $labels): string
    {
        return $labels->map(function ($label) {
            return '<span class="badge badge-secondary">'.$label->name.'</span>';
        })->implode('</br>');
    }

    private function renderAction(string $emailId): string
    {
        return '<button type="button" class="btn btn-primary text-center" data-toggle="modal"
                    data-target="#email-modal" value="'.$emailId.'">View
                </button>';
    }

    private function filterByLabel($query)
    {
        $selectedLabel = $this->label;
        if (in_array($this->label, Config::get('constants.gmails.filter_labels'))) {
            $query->whereHas('labels', function ($q) use ($selectedLabel) {
                $q->where('name', $selectedLabel);
            });
        }
    }

    private function updateNextPageToken(?string $nextPageToken): void
    {
        $this->user->next_page_token = $nextPageToken;
        $this->user->save();
    }

    private function saveEmails(MessageCollection $emails): void
    {
        $gmails = [];
        $gmailLabel = [];

        foreach ($emails as $email) {
            $emailData['id'] = $email->getId();
            $emailData['user_id'] = $this->user->id;
            $emailData['from'] = $email->getFromName();
            $emailData['subject'] = $email->getSubject();
            $emailData['content'] = $email->getHtmlBody();
            $emailData['date'] = $email->getDate();

            $gmails[] = $emailData;
            $labelIds = $this->createLabels($email->getLabels());

            foreach ($labelIds as $labelId) {
                $gmailLabel[] = ['gmail_id' => $email->getId(), 'label_id' => $labelId];
            }
        }

        DB::table('gmails')->insert($gmails);
        DB::table('gmail_label')->insert($gmailLabel);
    }

    private function createLabels(array $labelNames): array
    {
        $labelIds = [];

        foreach ($labelNames as $labelName) {
            $labelIds[] = Label::firstOrCreate(['name' => $labelName])->id;
        }

        return $labelIds;
    }

    private function isMoreEmailsNeeded(): bool
    {
        return $this->countEmails() <= ($this->start + $this->length);
    }

}
