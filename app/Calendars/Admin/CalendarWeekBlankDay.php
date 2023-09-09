<?php

namespace App\Calendars\Admin;

class CalendarWeekBlankDay extends CalendarWeekDay
{

  function getClassName()
  {
    return "day-blank";
  }

  function render()
  {
    return '';
  }

  function everyDay()
  {
    return '';
  }

  function dayPartCounts($ymd = null)
  {
    return ''; //返し値が空白。
  }

  function dayNumberAdjustment()
  {
    return '';
  }
}
