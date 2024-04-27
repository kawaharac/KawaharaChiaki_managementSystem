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
            $getPart = $request->getPart; //値が渡せていない。Requestはちゃんとできている 23/12/19
            $getDate = $request->getData; //getData = getDate
            $reserveDays = array_filter(array_combine($getDate, $getPart));

            //$reserveDaysは　array_filter()を使い、第一引数に指定された配列を、第二引数でフィルタリングしている。パートとデートを紐づけ？

            foreach ($reserveDays as $key => $value) { //name属性=キー
                //複数の日付を予約できるから、何日も処理できるように繰り返し処理している（foreach処理の中のこの3行だけが予約に必要な処理）
                $reserve_settings = ReserveSettings::where('setting_reserve', $key)->where('setting_part', $value)->first(); //getDateを特定した後getPartを特定
                $reserve_settings->decrement('limit_users'); //予約枠登録上限人数を1人削減
                $reserve_settings->users()->attach(Auth::id());
                //Calendarの予約。usersテーブルからattach()でログイン中のユーザーのIDを取得する
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback(); //例外処理
        }
        return redirect()->route('calendar.general.show', ['user_id' => Auth::id()]);
    }

    //cancel用のメソッド追加
    public function cancel(Request $request) //cancelが必要なデータをここの引数に入れる
    {
        $getPart = $request->cancelGetPart;
        $getDate = $request->cancelGetDay;
        //cancelしたいデータを入れる
        //getPartに数字だけ情報を出したい　その為の変数を作る
        $reserve_settings = ReserveSettings::where('setting_reserve', $getDate)->where('setting_part', $getPart)->first();
        $reserve_settings->increment('limit_users'); //予約枠登録
        $reserve_settings->users()->detach(Auth::id());
        //Calendarのcancel
        return redirect()->route('calendar.general.show', ['user_id' => Auth::id()]);
    }
}
