<?php

namespace App\Calendars\Admin;

use Carbon\Carbon;
use App\Models\Calendars\ReserveSettings;

class CalendarSettingView
{
  private $carbon;

  function __construct($date)
  {
    $this->carbon = new Carbon($date);
  }

  public function getTitle()
  {
    return $this->carbon->format('Y年n月');
  }

  public function render()
  {
    $html = [];
    $html[] = '<div class="calendar text-center">';
    $html[] = '<table class="table m-auto border adjust-table">';
    $html[] = '<thead>';
    $html[] = '<tr>';
    $html[] = '<th class="border">月</th>';
    $html[] = '<th class="border">火</th>';
    $html[] = '<th class="border">水</th>';
    $html[] = '<th class="border">木</th>';
    $html[] = '<th class="border">金</th>';
    $html[] = '<th class="border">土</th>';
    $html[] = '<th class="border">日</th>';
    $html[] = '</tr>';
    $html[] = '</thead>';
    $html[] = '<tbody>';
    $weeks = $this->getWeeks();

    foreach ($weeks as $week) {
      $html[] = '<tr class="' . $week->getClassName() . '">';
      $days = $week->getDays();
      foreach ($days as $day) {
        $startDay = $this->carbon->format("Y-m-01");
        $toDay = $this->carbon->format("Y-m-d");

        if ($startDay <= $day->everyDay() && $toDay >= $day->everyDay()) {
          $html[] = '<td class="past-day border">';
        } else {
          $html[] = '<td class="border ' . $day->getClassName() . '">';
        }
        $html[] = $day->render();
        $html[] = '<div class="adjust-area">';
        if ($day->everyDay()) {
          if ($startDay <= $day->everyDay() && $toDay >= $day->everyDay()) {
            $html[] = '<p class="d-flex m-0 p-0">1部<input class="w-25" style="height:20px;" name="reserve_day[' . $day->everyDay() . '][1]" type="text" form="reserveSetting" value="' . $day->onePartFrame($day->everyDay()) . '" disabled></p>';
            $html[] = '<p class="d-flex m-0 p-0">2部<input class="w-25" style="height:20px;" name="reserve_day[' . $day->everyDay() . '][2]" type="text" form="reserveSetting" value="' . $day->twoPartFrame($day->everyDay()) . '" disabled></p>';
            $html[] = '<p class="d-flex m-0 p-0">3部<input class="w-25" style="height:20px;" name="reserve_day[' . $day->everyDay() . '][3]" type="text" form="reserveSetting" value="' . $day->threePartFrame($day->everyDay()) . '" disabled></p>';
          } else {
            $html[] = '<p class="d-flex m-0 p-0">1部<input class="w-25" style="height:20px;" name="reserve_day[' . $day->everyDay() . '][1]" type="text" form="reserveSetting" value="' . $day->onePartFrame($day->everyDay()) . '"></p>';
            $html[] = '<p class="d-flex m-0 p-0">2部<input class="w-25" style="height:20px;" name="reserve_day[' . $day->everyDay() . '][2]" type="text" form="reserveSetting" value="' . $day->twoPartFrame($day->everyDay()) . '"></p>';
            $html[] = '<p class="d-flex m-0 p-0">3部<input class="w-25" style="height:20px;" name="reserve_day[' . $day->everyDay() . '][3]" type="text" form="reserveSetting" value="' . $day->threePartFrame($day->everyDay()) . '"></p>';
          }
        }
        $html[] = '</div>';
        $html[] = '</td>';
      }
      $html[] = '</tr>';
    }
    $html[] = '</tbody>';
    $html[] = '</table>';
    $html[] = '</div>';
    $html[] = '<form action="' . route('calendar.admin.update') . '" method="post" id="reserveSetting">' . csrf_field() . '</form>';
    return implode("", $html);
  }

  protected function getWeeks()
  {
    $weeks = [];
    $firstDay = $this->carbon->copy()->firstOfMonth();
    //firstOfMonth()=その月の初日が始まった瞬間が取得できる（00:00:00）
    //$firstDayはつまりgetWeeksメソッドの引数の初日の瞬間を取得している
    $lastDay = $this->carbon->copy()->lastOfMonth();
    //lastOfMonth()=その月の最終日が始まった瞬間が取得できる（00:00:00）
    //$lastDayはつまりgetWeeksメソッドの引数の最終日の瞬間を取得している
    $week = new CalendarWeek($firstDay->copy());
    //CalendarWeekクラスの引数に$firstDay（getWeeksメソッドの引数の初日の瞬間）
    //を入れてインスタンス化する
    $weeks[] = $week;
    $tmpDay = $firstDay->copy()->addDay(7)->startOfWeek();
    //$firstDay（getWeeksメソッドの引数の初日）の次週の月曜日を取得
    //変数　$tmpDayに入れる
    while ($tmpDay->lte($lastDay)) {
      //lte()＝以下　：つまり$tmpDayを$lastDayより小さいか確認している。
      //Whileは（）の中の条件が満たされなくなるまで繰り返すので、つまり$tmpDayが$lastDayを上回るまで繰り返している。
      $week = new CalendarWeek($tmpDay, count($weeks));
      $weeks[] = $week;
      $tmpDay->addDay(7);
    }
    return $weeks;
  }
}
