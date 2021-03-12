<?php

namespace App\Http\Controllers;

use App\Gmail;
use App\User;
use Dacastro4\LaravelGmail\Facade\LaravelGmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class GmailController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('emails.index');
    }

    public function loadAjax(Request $request)
    {
        $user = auth()->user();
        $emailsCount = Gmail::where('user_id', $user->id)->count();
        $start = $request->input('start');
        $length = $request->input('length');

        if (!$emailsCount) {
            $data = LaravelGmail::message()->take(50)->preload()->all();

            $user->next_page_token = $data->getPageToken();
            $user->save();

            $emails = [];

            foreach ($data as $email) {
                $emailData['id'] = $email->getId();
                $emailData['user_id'] = $user->id;
                $emailData['from'] = $email->getFromName();
                $emailData['subject'] = $email->getSubject();
                $emailData['date'] = $email->getDate();

                $emails[] = $emailData;
            }

            DB::table('gmails')->insert($emails);

        } elseif ($emailsCount === ($start + $length)) {
            $nextPageToken = $user->next_page_token;

            if ($nextPageToken) {
                $data = LaravelGmail::message()->take(50)->preload()->all($nextPageToken);

                $user->next_page_token = $data->getPageToken();
                $user->save();

                $emails = [];

                foreach ($data as $email) {
                    $emailData['id'] = $email->getId();
                    $emailData['user_id'] = $user->id;
                    $emailData['from'] = $email->getFromName();
                    $emailData['subject'] = $email->getSubject();
                    $emailData['date'] = $email->getDate();

                    $emails[] = $emailData;
                }

                DB::table('gmails')->insert($emails);
            }
        }

        $emails = DB::table('gmails')->where('user_id', $user->id);

        return Datatables::of($emails)->make(true);
    }

}
