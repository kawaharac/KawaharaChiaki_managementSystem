<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class MainCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; //認可するかしないか　Falseだと一も二もなくリクエスト拒否（アクセス拒否）になる。以下のメソッドは実行されない。
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //新規登録のバリデーション内容コピペから★修正★
            //bladeから送られてくる値（name）が左に入る。
            'main_category_name' => 'required|string|max:100|unique:main_categories,main_category' //bladeから送られてくる値（name）とカラム名（main_category）が一致していれば、カンマでカラム名を書く必要はない
        ]; //バリデーションは出来たのでエラーメッセージ表示、日本語で表示できるようにする。
    }
}
