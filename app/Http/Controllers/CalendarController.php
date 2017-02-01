<?php
/**
 * Created by PhpStorm.
 * User: Sugito
 * Date: 12/22/2016
 * Time: 1:30 PM
 */

namespace App\Http\Controllers;

use App\User;
use App\Model\EventCalendar;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class CalendarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'retrieveEvents']);
    }

    public function index()
    {
        return view('calendar.index');
    }

    public function retrieveEvents()
    {
        $userCalendar = User::whereId(Input::get('id'))->first()->eventCalendars;

        return response()->json(compact('userCalendar'), 200);
    }

    public function storeEvent(Request $request)
    {
        $user = User::whereId(Auth::user()->id)->first();

        $eventc = new EventCalendar();

        $eventc->event_title = $request->input('event_title');
        $eventc->start_date = date('Y-m-d H:i:s', strtotime($request->input('start_date')));
        $eventc->end_date = date('Y-m-d H:i:s', strtotime($request->input('end_date')));
        $eventc->ext_url = $request->input('ext_url');

        $user->eventCalendars()->save($eventc);

        return redirect()->route('db.user.calendar.show');
    }
}