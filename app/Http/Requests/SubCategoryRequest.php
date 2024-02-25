<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubCategoryRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //新規登録のバリデーション内容コピペから★修正★
            //bladeから送られてくる値（name）が左(変数)に入る。
            'sub_category_name' => 'required|string|max:100|unique:sub_categories,sub_category' //bladeから送られてくる値（name）とカラム名（main_category）が一致していれば、カンマでカラム名を書く必要はない
        ]; //バリデーションは出来たのでエラーメッセージ表示、日本語で表示できるようにする。
    }
}
