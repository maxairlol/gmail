<?php

namespace App\Http\Controllers;

use App\Services\GmailLoadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class GmailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('emails.index', ['labels' => Config::get('constants.gmails.filter_labels')]);
    }

    public function load(Request $request)
    {
        $start = $request->get('start');
        $length = $request->get('length');
        $label = $request->get('label');

        $gmailLoadService = new GmailLoadService($start, $length, $label);

        return $gmailLoadService->getEmails();
    }

}
