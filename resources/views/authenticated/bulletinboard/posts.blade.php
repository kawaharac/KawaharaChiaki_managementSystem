<!-- posts.blade.php -->

@extends('layouts.sidebar')

@section('content')
<div class="board_area w-100 border m-auto d-flex">
  <post_statusdiv class="post_view w-75 mt-5">
    <p class="w-75 m-auto">投稿一覧</p>
    @foreach($posts as $post)
    <!-- PostsControllerよりshowメソッドの中の返し値「posts」を変数$postとして使用できるようにしている -->
    <div class="post_area border w-75 m-auto p-3">
      <p><span>{{ $post->user->over_name }}</span><span class="ml-3">{{ $post->user->under_name }}</span>さん</p><!-- ここの〇〇（ユーザー名）さんエラーが出るので消す-->
      <p><a href="{{ route('post.detail', ['id' => $post->id]) }}">{{ $post->post_title }}</a></p>
      <div class="post_bottom_area d-flex">
        <div class="d-flex post_status"><!-- サブカテゴリーをいいねとコメントと並列で表示させる -->
          @foreach($categories as $main_category)
          @foreach($main_category -> subCategories as $sub_category)
          <div class=""><!-- コメント用のエリア・四角で囲むcategoriesからforeachすればいいのかな？ -->
            <div class="sub_category_btn"><span>{{ $sub_category->sub_category }}</span></div><!-- 文字を四角で囲む -->
          </div>
          @endforeach
          @endforeach
          <div class="mr-5">
            <!--機能追加（掲示板） #772コメントの数を表示 -->
            <!-- 3/11 コメントのカウント数を入れた＞#771同様UsersControllerへis_Comment -->

            <i class="fas fa-comment"></i><span class="" post_id="{{ $post->id }}">{{ $post->commentCounts($post->id)->count() }}</span></span>

          </div>
          <div>
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
    @endforeach
  </post_statusdiv>
  <div class="other_area border w-25">
    <div class="border m-4">
      <div class=""><a href="{{ route('post.input') }}">投稿</a></div>
      <div class="">
        <input type="text" placeholder="キーワードを検索" name="keyword" form="postSearchRequest">
        <input type="submit" value="検索" form="postSearchRequest">
      </div>
      <input type="submit" name="like_posts" class="category_btn" value="いいねした投稿" form="postSearchRequest">
      <input type="submit" name="my_posts" class="category_btn" value="自分の投稿" form="postSearchRequest">
      <ul>
        @foreach($categories as $category)
        <li class="main_categories" category_id="{{ $category->id }}"><span>{{ $category->main_category }}<span></li>
        @endforeach
      </ul>
    </div>
  </div>
  <form action="{{ route('post.show') }}" method="get" id="postSearchRequest"></form>
</div>
@endsection
