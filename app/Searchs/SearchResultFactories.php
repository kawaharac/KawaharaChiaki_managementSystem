<?php
namespace App\Searchs;

use App\Models\Users\User;

class SearchResultFactories{

  // 改修課題：選択科目の検索機能#777
  //bladeからまずここに飛ぶ
  public function initializeUsers($keyword, $category, $updown, $gender, $role, $subjects){
    if($category == 'name'){//名前だったら
      if(is_null($subjects)){
        $searchResults = new SelectNames();
      }else{
        $searchResults = new SelectNameDetails();//subjectsが入っていたら
      }
      return $searchResults->resultUsers($keyword, $category, $updown, $gender, $role, $subjects);
    }else if($category == 'id'){//IDだったら
      if(is_null($subjects)){
        $searchResults = new SelectIds();
      }else{
        $searchResults = new SelectIdDetails();
      }
      return $searchResults->resultUsers($keyword, $category, $updown, $gender, $role, $subjects);
    }else{//初期状態
      $allUsers = new AllUsers();
      //同じディレクトリならば上でUSE宣言しなくてよい
    return $allUsers->resultUsers($keyword, $category, $updown, $gender, $role, $subjects);
    }
  }
}
