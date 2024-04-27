@extends('layouts.sidebar')

@section('content')
<div class="post_create_container d-flex">
  <div class="post_create_area border w-50 m-5 p-5">
    <div class="">
      <!-- 対応中240216：formタグで囲む必要はない。ただサブカテゴリーのidを値で持ってくればよいので14行目のようにする。 -->
      <p class="mb-0">カテゴリー</p>
      <select class="w-100" form="postCreate" name="post_category_id">
        @foreach($main_categories as $main_category)
        <optgroup label="{{ $main_category->main_category }}" class="category_color">
          @foreach($main_category -> subCategories as $sub_category )
          <!-- サブカテゴリー表示 optgroup = <option>タグをグループ化するためのタグ。 -->
          <option label="{{ $sub_category->sub_category }}" value="{{ $sub_category->id }}" class="sub_category_color"></option>
          @endforeach
        </optgroup>
        @endforeach
      </select>
    </div>
    <div class="mt-3">
      @if($errors->first('post_title'))
      <span class="error_message">{{ $errors->first('post_title') }}</span>
      @endif
      <p class="mb-0">タイトル</p>
      <input type="text" class="w-100" form="postCreate" name="post_title" value="{{ old('post_title') }}">
    </div>
    <div class="mt-3">
      @if($errors->first('post_body'))
      <span class="error_message">{{ $errors->first('post_body') }}</span>
      <!-- エラーが合ったらここにバリデーション表示 -->
      @endif
      <p class="mb-0">投稿内容</p>
      <textarea class="w-100" form="postCreate" name="post_body">{{ old('post_body') }}</textarea>
    </div>
    <div class="mt-3 text-right">
      <input type="submit" class="btn btn-primary" value="投稿" form="postCreate">
    </div>
    <form action="{{ route('post.create') }}" method="post" id="postCreate">{{ csrf_field() }}</form>
  </div>
  @can('admin')
  <div class="w-25 ml-auto mr-auto">
    <div class="category_area mt-5 p-5">
      <!-- カテゴリー選択#773 -->
      <div class="">
        <p class="m-0">メインカテゴリー</p>
        @if($errors->has('main_category_name')) <span class="text-danger">{{ $errors->first('main_category_name') }}</span> @endif
        <form action="{{ route('main.category.create')}}" method="post" id="mainCategoryRequest">{{ csrf_field() }}
          <input type=" text" class="w-100" name="main_category_name" form="mainCategoryRequest">
          <input type="submit" value="追加" class="w-100 btn btn-primary p-0" form="mainCategoryRequest">
        </form>
      </div>
      <form action="{{ route('sub.category.create') }}" method="post" id="subCategoryRequest">{{ csrf_field() }}
        <!-- サブカテゴリー追加(修正：mainからsubに)  -->
        <div class="">
          <p class="m-0">サブカテゴリー</p>
          @if($errors->has('sub_category_name')) <span class="text-danger">{{ $errors->first('sub_category_name') }}</span> @endif
          <select class="main_category_name" name="main_category_name">
            <!-- メインカテゴリーの数だけ表示を増やす -->
            @foreach($main_categories as $main_category)
            <option value="{{ $main_category->id }}">{{ $main_category->main_category }}</option>
            @endforeach
          </select>
          <input type="text" class="w-100" name="sub_category_name" form="subCategoryRequest">
          <input type="submit" value="追加" class="w-100 btn btn-primary p-0" form="subCategoryRequest">
        </div>
      </form>
      <!-- 右ペイン：サブカテゴリー追加項目 -->
    </div>
  </div>
  @endcan
</div>
@endsection
