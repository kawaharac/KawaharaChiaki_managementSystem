<?php

namespace App\Models\Users;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Posts\PostComment;

use App\Models\Posts\Like;
use Auth;

class User extends Authenticatable
{
    use Notifiable;
    use softDeletes;

    const CREATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'over_name',
        'under_name',
        'over_name_kana',
        'under_name_kana',
        'mail_address',
        'sex',
        'birth_day',
        'role',
        'password'
    ];

    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts(){
        return $this->hasMany('App\Models\Posts\Post');
    }

    public function calendars(){
        return $this->belongsToMany('App\Models\Calendars\Calendar', 'calendar_users', 'user_id', 'calendar_id')->withPivot('user_id', 'id');
    }

    public function reserveSettings(){
        //多対多のリレーション（省略方法があるが、最初は４つ書くこと）
        return $this->belongsToMany('App\Models\Calendars\ReserveSettings', 'reserve_setting_users', 'user_id', 'reserve_setting_id')->withPivot('id');
    }

    public function likes(){
        return $this->belongsToMany('App\Models\Posts\Like', 'likes', 'like_user_id', 'like_post_id')->withPivot('id');
    }

    //ここがsubjectsテーブルとの関係性を出すところかな？2023/1/21
    //belongsToMany('Modelのある場所','中間テーブル名','中間テーブルの該当id名１','中間テーブルの該当id名２')
    public function subjects(){
        return $this->belongsToMany('App\Models\Users\Subjects', 'subject_users', 'user_id', 'subject_id')->withPivot('user_id', 'id');
        // リレーションの定義
    }

    // いいねしているかどうか
    public function is_Like($post_id){
        return Like::where('like_user_id', Auth::id())->where('like_post_id', $post_id)->first(['likes.id']);
        //いいねした人のID＝like_user_idの中から認証している人のidを取っていく>メソッドチェーンでAND検索＞引数で持ってきた$post_idの最初を返す。（firstメソッドは引数の最初のレコードを単一で返す）
    }

    //コメントされているかどうか
    public function is_Comment($post_id){
        return PostComment::where('user_id', Auth::id())->where('post_id', $post_id)->first(['post_comments.id']);
        //ココ変更したらエラー出たSQLSTATE[42S22]: Column not found: 1054 Unknown column 'post_comments.id' in 'field list' (SQL: select `post_comments`.`id` from `likes` where `user_id` = 1 and `post_id` = 4 limit 1) (View: C:\Users\USER\Documents\KawaharaChiaki_managementSystem\resources\views\authenticated\bulletinboard\posts.blade.php)
    }

    public function likePostId(){
        return Like::where('like_user_id', Auth::id());
        //返し値にLikeDBのログイン認証した人のIDがいいねした人のIDを返す
    }
}
