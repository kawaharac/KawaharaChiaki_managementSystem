<?php

namespace App\Http\Controllers\Authenticated\Calendar\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Calendars\Admin\CalendarView;
use App\Calendars\Admin\CalendarSettingView;
use App\Models\Calendars\ReserveSettings;
use App\Models\Calendars\Calendar;
use App\Models\USers\User;
use Auth;
use DB;

class CalendarsController extends Controller
{
    public function show()
    {
        $calendar = new CalendarView(time()); //タイムスタンプ登録
        return view('authenticated.calendar.admin.calendar', compact('calendar')); //compact関数で変数名とその値から配列を生成する。viewの変数とControllerで定義した変数名が同じ時にスマートに描くことが出来る。
    }

    public function reserveDetail($user_id = 0, $date = 0, $part = 0)
    {
        $reservePersons = ReserveSettings::with('users')->where('setting_reserve', $date)->where('setting_part', $part)->get();
        return view('authenticated.calendar.admin.reserve_detail', compact('reservePersons', 'date', 'part'));
    }

    public function reserveSettings()
    {
        $calendar = new CalendarSettingView(time());
        return view('authenticated.calendar.admin.reserve_setting', compact('calendar'));
    }

    public function updateSettings(Request $request) //bladeから入る
    {
        $reserveDays = $request->input('reserve_day');
        foreach ($reserveDays as $day => $parts) {
            foreach ($parts as $part => $frame) {
                //上がアップデートする値、下が新規で入れる値（setting_reserve、setting_partが元々入っていれば上、入って無ければ下。）
                ReserveSettings::updateOrCreate([
                    'setting_reserve' => $day,
                    'setting_part' => $part,
                ], [
                    'setting_reserve' => $day,
                    'setting_part' => $part,
                    'limit_users' => $frame,
                ]);
            }
        }
        return redirect()->route('calendar.admin.setting', ['user_id' => Auth::id()]);
    }
}
