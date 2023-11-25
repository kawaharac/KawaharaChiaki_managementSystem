<?php

namespace App\Http\Controllers\Authenticated\Calendar\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Calendars\General\CalendarView;
use App\Models\Calendars\ReserveSettings;
use App\Models\Calendars\Calendar;
use App\Models\USers\User;
use Auth;
use DB;

class CalendarsController extends Controller
{
    public function show()
    {
        $calendar = new CalendarView(time());
        return view('authenticated.calendar.general.calendar', compact('calendar'));
    }

    public function reserve(Request $request)
    { //予約機能
        DB::beginTransaction();
        try {
            $getPart = $request->getPart;
            $getDate = $request->getData; //getData = getDate
            $reserveDays = array_filter(array_combine($getDate, $getPart));
            foreach ($reserveDays as $key => $value) {
                $reserve_settings = ReserveSettings::where('setting_reserve', $key)->where('setting_part', $value)->first();
                $reserve_settings->decrement('limit_users');
                $reserve_settings->users()->attach(Auth::id()); //Calendarの予約
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback(); //例外処理
        }
        return redirect()->route('calendar.general.show', ['user_id' => Auth::id()]);
    }

    //cancel用のメソッド追加
}
