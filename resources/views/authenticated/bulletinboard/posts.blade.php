<!-- posts.blade.php -->

@extends('layouts.sidebar')

@section('content')
<div class="board_area w-100 border m-auto d-flex">
  <post_statusdiv class="post_view w-75 mt-5">
    @foreach($posts as $post)
    <!-- PostsControllerよりshowメソッドの中の返し値「posts」を変数$postとして使用できるようにしている -->
    <!--  -->
    <div class="post_area border w-75 m-auto p-3">
      <p><span>{{ $post->user->over_name }}</span><span class="ml-3">{{ $post->user->under_name }}</span>さん</p>
      <p><a class="post_title" href="{{ route('post.detail', ['id' => $post->id]) }}">{{ $post->post_title }}</a></p>
      <div class="post_bottom_area">
        <div class="d-flex post_status"><!-- サブカテゴリーをいいねとコメントと並列で表示させる -->
          <div class="sub_category_area"><!-- コメント用のエリア・四角で囲むcategoriesからforeachすればいいのかな？ -->
            <div class="sub_category_btn">
              @foreach($post->subCategories as $sub_category)
              <span>{{ $sub_category->sub_category }}</span>
              @endforeach
            </div><!-- 文字を四角で囲む -->
          </div>
          <div class="comment_icon_area">
            <div class="mr-5 icon_area">
              <!--機能追加（掲示板） #772コメントの数を表示 -->
              <!-- 3/11 コメントのカウント数を入れた＞#771同様UsersControllerへis_Comment -->
              <!--コメント位置調節・右寄せ  -->
              <i class="fas fa-comment icon_space"></i><span class="" post_id="{{ $post->id }}">{{ $post->commentCounts($post->id)->count() }}</span></span>
            </div>
            <div class="mr-5 icon_area">
              <!-- 機能追加（掲示板） #771いいねの数を表示 -->
              @if(Auth::user()->is_Like($post->id))
              <p class="m-0">
                <i class="fas fa-heart un_like_btn" post_id="{{ $post->id }}"></i>
                <span class="like_counts{{ $post->id }}">{{ $like->likeCounts($post->id) }}</span>
              </p>
              <!-- いいねの数正常に表示、完了（謎：$likeはどこから来たのか=>解決：web.php紐付けはpostscontroller.phpより　likeCountsはLike.phpより） -->
              @else
              <p class="m-0"><i class="fas fa-heart like_btn" post_id="{{ $post->id }}"></i><span class="like_counts{{ $post->id }}">{{ $like->likeCounts($post->id) }}</span></p>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </post_statusdiv>
  <div class="other_area border w-25">
    <div class="border m-4">
      <div class="post_btn"><a href="{{ route('post.input') }}">投稿</a></div>
      <div class="search_form">
        <input type="text" class="free_word" placeholder="キーワードを検索" name="keyword" form="postSearchRequest">
        <input class="post_btn" type="submit" value="検索" form="postSearchRequest">
      </div>
      <div class="posts_btn">
        <input type="submit" name="like_posts" class="category_btn" value="いいねした投稿" form="postSearchRequest">
        <input type="submit" name="my_posts" class="category_btn" value="自分の投稿" form="postSearchRequest">
      </div>
      <p>カテゴリー検索</p>
      <ul>
        @foreach($categories as $category)
        <li class="main_categories" category_id="{{ $category->id }}"><span class="category_switch">{{ $category->main_category }}<span></li>
        <ul>
          @foreach($category -> subCategories as $sub_category )
          <!-- サブカテゴリー表示 optgroup = <option>タグをグループ化するためのタグ。 -->
          <span class="sub_categories">
            <input type="submit" name="category_word" value="{{ $sub_category->sub_category }}" form="postSearchRequest"></span>
          <!-- sub_categoryを$Requestにして表示する。postControllerの42行目を発火させるように -->
          @endforeach
        </ul>
        @endforeach
      </ul>
    </div>
  </div>
  <form action="{{ route('post.show') }}" method="get" id="postSearchRequest"></form><!-- postControllerのshowに行く -->
</div>
@endsection
