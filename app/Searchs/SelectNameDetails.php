<?php
namespace App\Searchs;

use App\Models\Users\User;

class SelectNameDetails implements DisplayUsers{

  // 改修課題：選択科目の検索機能#777
  public function resultUsers($keyword, $category, $updown, $gender, $role, $subjects){
    if(is_null($gender)){
      $gender = ['1', '2'];
    }else{
      $gender = array($gender);
    }
    if(is_null($role)){
      $role = ['1', '2', '3', '4', '5'];
    }else{
      $role = array($role);
    }
    $users = User::with('subjects')//リレーション　・$qはpuery
    ->where(function($q) use ($keyword){
      $q->Where('over_name', 'like', '%'.$keyword.'%')
      ->orWhere('under_name', 'like', '%'.$keyword.'%')
      ->orWhere('over_name_kana', 'like', '%'.$keyword.'%')
      ->orWhere('under_name_kana', 'like', '%'.$keyword.'%');
    })
    ->where(function($q) use ($role, $gender){
      $q->whereIn('sex', $gender)
      ->whereIn('role', $role);//配列で複数検索指定したい時はwhereIn
    })
    ->whereHas('subjects', function($q) use ($subjects){
      $q->whereIn('subjects.id', $subjects);
    })//ここにやる777whereをwhereInに修正
    ->orderBy('over_name_kana', $updown)->get();
    return $users;
  }

}
