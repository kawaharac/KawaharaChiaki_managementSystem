<?php

namespace App\Calendars\General;

use Carbon\Carbon;
use Auth;

class CalendarView
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

  function render()
  {
    $html = [];
    $html[] = '<div class="calendar text-center">';
    $html[] = '<table class="table">';
    $html[] = '<thead>';
    $html[] = '<tr>';
    $html[] = '<th>月</th>';
    $html[] = '<th>火</th>';
    $html[] = '<th>水</th>';
    $html[] = '<th>木</th>';
    $html[] = '<th>金</th>';
    $html[] = '<th>土</th>';
    $html[] = '<th>日</th>';
    $html[] = '</tr>';
    $html[] = '</thead>';
    $html[] = '<tbody>';
    $weeks = $this->getWeeks();
    foreach ($weeks as $week) {
      $html[] = '<tr class="' . $week->getClassName() . '">';

      $days = $week->getDays();
      //日付選択＝抽出
      //getdays()1日から一か月分

      foreach ($days as $day) { //1日ずつの処理
        $startDay = $this->carbon->copy()->format("Y-m-01"); //carbon日付を操作するライブラリいきなりcopyじゃだめ　挟む
        $toDay = $this->carbon->copy()->format("Y-m-d");

        if ($startDay <= $day->everyDay() && $toDay >= $day->everyDay()) { //everyday＝範囲内。if(月の最初から今日まで　＆＆　今日を含めた過去日付）
          $html[] = '<td class="calendar-td past-day">'; //td=グレーになるようにする(受付終了は別に記述）
        } else {
          $html[] = '<td class="calendar-td ' . $day->getClassName() . '">';
        }
        $html[] = $day->render();

        if (in_array($day->everyDay(), $day->authReserveDay())) { //if文の中に","は内包している　authReserveDay()＝予約している日
          $reservePart = $day->authReserveDate($day->everyDay())->first()->setting_part; //予約部の定義　1部か2部か3部か
          if ($reservePart == 1) {
            $reservePart = "1";
          } else if ($reservePart == 2) {
            $reservePart = "2";
          } else if ($reservePart == 3) {
            $reservePart = "3"; //ここか？
          }
          if ($startDay <= $day->everyDay() && $toDay >= $day->everyDay()) { //予約していて、かつ過去の場合
            $html[] = '<p class="m-auto p-0 w-75" style="font-size:12px">リモ' . $reservePart . '部参加</p>';
            $html[] = '<input type="hidden" name="getPart[]" value="' . $reservePart . '" form="reserveParts">'; //隠し値で何部参加か送っている　form　Web.phpのnameになっている
            //ここが悪さしているのかな？
          } else {
            $html[] = '<button type="submit" class="btn btn-modal-open btn-danger p-0 w-75" name="delete_date" style="font-size:12px" value="' . $day->authReserveDate($day->everyDay())->first()->setting_reserve . '">リモ' . $reservePart . '部</button>';
            $html[] = '<input type="hidden" class="getPart" name="getPart[]" value="' . $day->authReserveDate($day->everyDay())->first()->setting_part . '" form="reserveParts">';
          }
        } else { //51行目ココまで　下は「予約していない日」
          if ($startDay <= $day->everyDay() && $toDay >= $day->everyDay()) {
            $html[] = '<p class ="">受付終了</p>'; //隠し値で受付終了分の値を送れていない
            $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">'; //追加
          } else {
            $html[] = $day->selectPart($day->everyDay()); //$ymdの引数
          }
        }
        $html[] = $day->getDate();
        $html[] = '</td>';
      }
      $html[] = '</tr>';
    }
    $html[] = '</tbody>';
    $html[] = '</table>';
    //モーダルの中身ココから→先にモーダルを作る！
    $html[] = '<div class ="modal-container">';
    $html[] = '<div class ="modal-body">'; //白い部分を作る
    $html[] = '<p>この予約をキャンセルしてもよろしいですか？</p>';
    $html[] = '<p>予約日：<span class ="modal-cancel-day"></span></p>'; //ここのspanの間に入った値を引数に利用する（⇒calendar.blade.phpでcancelに利用）
    $html[] = '<p>時間：<span class ="modal-cancel-time"></span></p>';
    $html[] = '<form action ="/cancel/calendar" method="post" id="deleteParts" >' . csrf_field();
    $html[] = '<input class="cancel-get-day" type="hidden" value="" name="cancelGetDay">';
    $html[] = '<input class="cancel-get-part" type="hidden" value="" name ="cancelGetPart">';
    $html[] = '<div class="btn-cancel">';
    $html[] = '<button type ="submit" class="btn btn-danger p-0 btn-input-cancel">キャンセル</button>';
    $html[] = '</form>';
    $html[] = '<button type ="submit" class="btn modal-close p-0 btn-primary btn-input-cancel">閉じる</button>';
    $html[] = '</div>';
    $html[] = '</div>';
    $html[] = '</div>';
    //モーダルの中身ココまで
    $html[] = '</div>';
    $html[] = '<form action="/reserve/calendar" method="post" id="reserveParts">' . csrf_field() . '</form>';
    $html[] = '<form action="/delete/calendar" method="post" id="deleteParts">' . csrf_field() . '</form>';

    return implode('', $html);
  }

  protected function getWeeks()
  {
    $weeks = []; //最初に殻をつくっておく$weeksが2回ある理由＝一旦まっさらにする
    $firstDay = $this->carbon->copy()->firstOfMonth(); //その月の初日が始まった瞬間が取得できる
    $lastDay = $this->carbon->copy()->lastOfMonth(); //その月の最終日が始まった瞬間が取得できる
    $week = new CalendarWeek($firstDay->copy()); //最初の週
    $weeks[] = $week;
    $tmpDay = $firstDay->copy()->addDay(7)->startOfWeek(); //週７にして 11/6月曜が格納されている

    while ($tmpDay->lte($lastDay)) { //月末になるまで週を繰り返す
      $week = new CalendarWeek($tmpDay, count($weeks)); //週に
      $weeks[] = $week;
      $tmpDay->addDay(7);
    }
    return $weeks;
  }
}
