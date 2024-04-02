<?php

namespace App\Models\Calendars;

use Illuminate\Database\Eloquent\Model;

class ReserveSettings extends Model
{
    const UPDATED_AT = null;
    public $timestamps = false; //タイムスタンプの無効化

    protected $fillable = [
        'setting_reserve',
        'setting_part',
        'limit_users',
    ];

    public function users()
    {
        //多対多のリレーション（省略方法があるが、最初は４つ書くこと）
        //第１引数：リレーション先のモデル名
        //第2引数：リレーション先のテーブル名(中間テーブル名)
        //第3引数：中間テーブルの自モデルの主キー
        //第4引数：中間テーブルの相手モデルの主キー
        return $this->belongsToMany('App\Models\Users\User', 'reserve_setting_users', 'reserve_setting_id', 'user_id')->withPivot('id', 'reserve_setting_id');
        //withPivot(中間テーブルの利用したいカラムを引数に入れる。）
        //※デフォルトでpivotからアクセスできる値は繋げる元のモデルキー（order_idやproduct_id）のみのため、
        //それ以外はこれでメソッドチェーンする。)
        //25行目->withPivot('reserve_setting_id'！意味あるのか？削除したが今のところ問題なし！, 'id'☞複数カラムを利用したい時は、さらに引数を増やせばOK);
    }
}
