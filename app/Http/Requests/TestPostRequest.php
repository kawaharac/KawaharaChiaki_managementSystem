<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    public function authorize()
    {
        return true;
    }

        /**
     *  rules()の前に実行される
     *       $this->merge(['key' => $value])を実行すると、
     *       フォームで送信された(key, value)の他に任意の(key, value)の組み合わせをrules()に渡せる


     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    public function getValidatorInstance() {
        //生年月日をまとめて値に直す
        $old_year = $this->input('old_year');
        $old_mouth = $this->input('old_mouth');
        $old_day = $this->input('old_day');
        $datetime = $old_year .'-'. $old_mouth .'-'. $old_day;
        // 日付を作成(ex. 2020-1-20)
        //$datetime_validation = implode('-', $datetime);

        // rules()に渡す値を追加でセット
        //これで、この場で作った変数にもバリデーションを設定できるようになる
        $this->merge([
            'datetime_validation' => $datetime,
        ]);

        return parent::getValidatorInstance();
        //ここで定義した変数はここでしか使えないようにしている(parentで返しているのがこのメソッドなので)
    }

    public function rules()
    {

        return [
            //新規登録のバリデーション内容
            'over_name' => 'required|string|max:10',
            'under_name' => 'required|string|max:10',
            'over_name_kana' => 'required|string|max:30|regex:/^[ア-ン゛゜ァ-ォャ-ョー]+$/u',
            'under_name_kana' => 'required|string|max:30|regex:/^[ア-ン゛゜ァ-ォャ-ョー]+$/u',
            'mail_address' => 'required|unique:users,mail_address|max:100|email',
            'sex' => 'required|in:1,2,3',
            //ラジオボタンの値を入れる（要：bladeのvalue確認）
            //生年月日　ヒントまず日にちの形に成型するそれからバリデーションをかける
            'datetime_validation' => 'required|date',
            'role' => 'required|in:1,2,3,4',
            'password' => 'required|min:8|max:30|confirmed:password',
        ];

    }

    public function messages(){
        return [
            "required" => "必須項目です",
            "email" => "メールアドレスの形式で入力してください",
            "regex" => "全角カタカナで入力してください",
            "string" => "文字で入力してください",
            "max" => "30文字以内で入力してください",
            "over_name.max" => "10文字以内で入力してください",
            "under_name.max" => "10文字以内で入力してください",
            "min" => "8文字以上で入力してください",
            "mail_address.max" => "100文字以内で入力してください",
            "unique:users,mail_address" => "登録済みのメールアドレスは無効です",
            "confirmed" => "パスワード確認が一致しません",
            "date" => "有効な日付に直してください"
        ];
    }
}
