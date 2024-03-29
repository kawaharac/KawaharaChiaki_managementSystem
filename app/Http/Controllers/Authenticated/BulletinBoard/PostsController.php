<?php

namespace App\Http\Controllers\Authenticated\BulletinBoard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories\MainCategory;
use App\Models\Categories\SubCategory;
use App\Models\Posts\Post;
use App\Models\Posts\PostComment;
use App\Models\Posts\Like;
use App\Models\Users\User;
use App\Http\Requests\BulletinBoard\PostFormRequest;
use App\Http\Requests\MainCategoryRequest;
use App\Http\Requests\SubCategoryRequest;
use App\Http\Requests\CommentCreateRequest;
use Auth;

class PostsController extends Controller
{
    //<!-- PostsController.php -->
    public function show(PostFormRequest $request)
    {
        //Request>PostFormRequestに変更23/2/25
        //web.phpで紐付けしているblade=posts/{keyword?}(※キーワードがない場合、URLは/posts以下はない事に留意)
        $posts = Post::with('user', 'postComments', 'subCategories')->get(); //withで他のテーブルの情報を持ってくる　リレーションでできている model内のメソッド名を追加
        //Postを全部ＧＥＴしている
        $categories = MainCategory::get();
        //変数$categoriesはDB（MainCategory)からすべてゲットする。
        $subcategories = SubCategory::get();
        $like = new Like;
        //変数＄likeをインスタンス化。
        $post_comment = new Post;
        if (!empty($request->keyword)) {
            //もしリクエストからキーワードがあれば、下の処理を行う。❶withメソッド＝Posts.phpよりリレーションの記述
            $posts = Post::with('user', 'postComments', 'subCategories')
                ->where('post_title', 'like', '%' . $request->keyword . '%')
                ->orWhere('post', 'like', '%' . $request->keyword . '%')
                ->orWhereHas('subCategories', function ($query) use ($request) {
                    $query->where('sub_category', '=', $request->keyword);
                })->get();
        } else if ($request->category_word) {
            //そうでない、かつ変数category_word(posts.blade内で$category)
            $sub_category = $request->category_word;
            $posts = Post::with('user', 'postComments', 'subCategories')->WhereHas('subCategories', function ($query) use ($request) {
                $query->where('sub_category', '=', $request->category_word);
            })->get();
        } else if ($request->like_posts) {
            $likes = Auth::user()->likePostId()->get('like_post_id');
            //変数$likesは認証ユーザのlikePostId()（User.php内のメソッド：いいねした人のIDを割り出す）からいいねした人のIDを複数抜き出す。
            $posts = Post::with('user', 'postComments', 'subCategories')
                ->whereIn('id', $likes)->get();
        } else if ($request->my_posts) {
            $posts = Post::with('user', 'postComments', 'subCategories')
                ->where('user_id', Auth::id())->get();
        }
        return view('authenticated.bulletinboard.posts', compact('posts', 'categories', 'like', 'post_comment', 'subcategories'));
        //compactでここに表記した複数の変数を紐付け。（bladeで変数宣言せずいきなり使うことができる）
    }

    public function postDetail($post_id)
    {
        $post = Post::with('user', 'postComments')->findOrFail($post_id);
        return view('authenticated.bulletinboard.post_detail', compact('post'));
    }

    public function postInput()
    { //投稿部分
        $main_categories = MainCategory::get(); //ﾈｰﾑｽﾍﾟｰｽのmodel:MainCategoryを参照
        return view('authenticated.bulletinboard.post_create', compact('main_categories'));
    }

    public function postCreate(PostFormRequest $request)
    { //Postmodelではサブカテゴリーを追加しない?
        $post = Post::create([
            'user_id' => Auth::id(),
            'post_title' => $request->post_title,
            'post' => $request->post_body
        ]);
        $hoge = Post::findOrFail($post->id)->subCategories()->attach($request->post_category_id); //post_create.blade.phpのnameがpost_category_id。
        return redirect()->route('post.show');
    }
    //投稿を編集する
    public function postEdit(PostFormRequest $request)
    {
        Post::where('id', $request->post_id)->update([
            'post_title' => $request->post_title,
            'post' => $request->post_body,
        ]);
        return redirect()->route('post.detail', ['id' => $request->post_id]);
    }

    public function postDelete($id)
    {
        Post::findOrFail($id)->delete();
        return redirect()->route('post.show');
    }
    //メインカテゴリー作成
    public function mainCategoryCreate(MainCategoryRequest $request)
    {
        MainCategory::create(['main_category' => $request->main_category_name]);
        return redirect()->route('post.input');
    }
    //サブカテゴリー作成
    public function subCategoryCreate(SubCategoryRequest $request)
    {
        SubCategory::create([
            'main_category_id' => $request->main_category_name,
            'sub_category' => $request->sub_category_name
        ]);
        return redirect()->route('post.input');
    }
    //コメントを作る
    public function commentCreate(CommentCreateRequest $request)
    {
        PostComment::create([
            'post_id' => $request->post_id,
            'user_id' => Auth::id(),
            'comment' => $request->comment
        ]);
        return redirect()->route('post.detail', ['id' => $request->post_id]);
    }

    public function myBulletinBoard()
    {
        //自分のlikeカウント？=>右の「自分の投稿」をクリックした時の動作
        $posts = Auth::user()->posts()->get();
        $like = new Like;
        return view('authenticated.bulletinboard.post_myself', compact('posts', 'like'));
    }

    public function likeBulletinBoard()
    {
        //相手のLikeカウント？=>>右の「いいねした投稿」をクリックした時の動作
        $like_post_id = Like::with('users')->where('like_user_id', Auth::id())->get('like_post_id')->toArray();
        $posts = Post::with('user')->whereIn('id', $like_post_id)->get();
        $like = new Like;
        return view('authenticated.bulletinboard.post_like', compact('posts', 'like'));
    }

    //いいねはここ？（2023-2-12）like_user_idをcountsすればいいと思っている
    public function postLike(Request $request)
    {
        Auth::user()->likes()->attach($request->post_id);
        return response()->json();
    }

    public function postUnLike(Request $request)
    {
        Auth::user()->likes()->detach($request->post_id);
        return response()->json();
    }
}
