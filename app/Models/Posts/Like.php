<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'like_user_id',
        'like_post_id'
    ];

    public function users(){
        return $this->belongsToMany('App\Models\Users\User', 'likes', 'like_post_id', 'like_user_id');
    }

    public function likeCounts($post_id){
        //いいねカウントの処理（post.blade.php参照）
        return $this->where('like_post_id', $post_id)->get()->count();
    }
}
