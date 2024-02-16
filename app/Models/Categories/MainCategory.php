<?php

namespace App\Models\Categories;

use Illuminate\Database\Eloquent\Model;

class MainCategory extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;
    protected $fillable = [
        'main_category'
    ];

    public function subCategories()
    {
        return $this->hasMany('App\Models\Categories\SubCategory'); //PostModelではサブカテゴリーが追加できないため、追加の際にwithpivotが活きる。1 ,'sub_category_id'を特定の場所にattachする（今回は中間テーブル）>Postscontroller内で完結する2,'PostSubCategorys'というモデルを作るか。
        // リレーションの定義
    }
}
