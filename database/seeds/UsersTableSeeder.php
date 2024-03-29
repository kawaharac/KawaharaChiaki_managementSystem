<?php

use Illuminate\Database\Seeder;
use App\Models\Users\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'over_name' => 'anpan',
                'under_name' => 'man',
                'over_name_kana' => 'アンパン',
                'under_name_kana' => 'マン',
                'mail_address' => '123@com',
                'sex' => '1', //性別(1が男)
                'birth_day' => '2000-01-01', //誕生日
                'role' => '1', //権限
                'password' => bcrypt('123') //ハッシュ化する>bcrypt('')
                //'remember_token' => ''
                //リメンバートークンはNULLでオッケーと書いてあったのでコメントアウトして様子見
            ],
            [
                'over_name' => 'baikin',
                'under_name' => 'man',
                'over_name_kana' => 'バイキン',
                'under_name_kana' => 'マン',
                'mail_address' => '456@com',
                'sex' => '1', //性別(1が男)
                'birth_day' => '2000-10-11', //誕生日
                'role' => '1', //権限
                'password' => bcrypt('456')
                //追加4/29
            ]
        ]);

    }
}
