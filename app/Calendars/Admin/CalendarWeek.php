<?php

namespace App\Calendars\Admin;

use Carbon\Carbon;

class CalendarWeek
{
  //カレンダーの週作成。
  //protected=(アクセス権の指定)
  //クラス外からは呼び出せない。同じインスタンス内で呼び出せる。
  //しかし、他のインスタンスであれば同じクラスであれば呼び出せる。
  protected $carbon;
  protected $index = 0;

  function __construct($date, $index = 0)
  {
    $this->carbon = new Carbon($date);
    $this->index = $index;
  }

  function getClassName()
  {
    return "week-" . $this->index;
  }

  function getDays()
  {
    $days = [];
    $startDay = $this->carbon->copy()->startOfWeek();
    $lastDay = $this->carbon->copy()->endOfWeek();
    $tmpDay = $startDay->copy();

    while ($tmpDay->lte($lastDay)) {
      //初日からスタートして最終日以下(以下＝lte)まで繰り返す
      //month　Carbonパッケージの操作。月を取得。
      if ($tmpDay->month != $this->carbon->month) {
        //もし繰り返している引数が$tmpDayの月と等しくない場合
        $day = new CalendarWeekBlankDay($tmpDay->copy());
        //$dayは　CalendarWeekBlankDayが適用される。
        //引数は$tmpdayの値を使う。
        $days[] = $day;
        //$days[]の配列中に$dayを入れる
        $tmpDay->addDay(1);
        //$tmpdayに1日加算して最初からやり直す。
        continue;
      }
      $day = new CalendarWeekDay($tmpDay->copy());
      //同じ月だった場合の対応
      //$dayにCalendarWeekDay()を適用する。
      $days[] = $day;
      //$days[]の配列中に$dayを入れる。
      //Classの中のデータを配列として入れる。
      $tmpDay->addDay(1);
      //$tmpdayに1日加算して最初からやり直す。
    }
    return $days;
  }
}
