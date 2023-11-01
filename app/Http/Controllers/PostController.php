<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePostRequest;
use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use App\Models\Nice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Can;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with('user')->latest()->paginate(9);
        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $post = new Post($request->all());
        $post->user_id = $request->user()->id;

        $file = $request->file('image');
        $post->image = self::createFileName($file);

        // トランザクション開始
        DB::beginTransaction();
        try {
            // 登録
            $post->save();

            // 画像アップロード
            if (!Storage::putFileAs('images/posts', $file, $post->image)) {
                throw new \Exception('画像のファイルの保存に失敗しました');
            }

            // トランザクション終了(成功)
            DB::commit();
        } catch (\Exception $e) {
            //トランザクション失敗
            DB::rollBack();
            return back()->withInput()->withErrors($e->getMessage());
        }

        return redirect()
            ->route('posts.show', $post);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::find($id);
        $post->nices = Nice::all();
        $request = request();
        $ip = $request->ip();
        $nices = Nice::where('post_id', $post->id)
            ->count();

        if (Auth::check()) {
            $nice = Nice::where('post_id', $post->id)
                ->where('user_id', auth()->user()->id)
                ->first();

            
        } else {
            $nice = Nice::where('post_id', $post->id)
                ->where('user_id', null)
                ->where('ip', $ip)
                ->first();
        }

        return view('posts.show', compact('post', 'nices', 'nice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $post = Post::find($id);

        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, string $id)
    {
        $post = Post::find($id);

        if ($request->user()->cannot('update', $post)) {
            return redirect()->route('posts.show', $post)
                ->withErrors('自分の記事以外は更新できません');
        }

        $file = $request->file('image');
        if ($file) {
            $delete_file_path = $post->image_path;
            $post->image = self::createFileName($file);
        }
        $post->fill($request->all());

        // トランザクション開始
        DB::beginTransaction();
        try {
            $post->save();

            if ($file) {
                // 画像アップロード
                if (!Storage::putFileAs('images/posts', $file, $post->image)) {
                    Storage::delete($delete_file_path);
                    throw new \Exception('画像ファイルの削除に失敗しました');
                }

                // 画像削除
                if (!Storage::delete($delete_file_path)) {
                    // アップロードした画像を削除する
                    Storage::delete($post->image_path);
                    // 例外を投げてロールバックする
                    throw new \Exception('画像ファイルの削除に失敗しました。');
                }
            }

            // トランザクション終了
            DB::commit();
        } catch (\Exception $e) {
            // トランザクション失敗
            DB::rollback();
            return back()->withInput()->withErrors($e->getMessage());
        }

        return redirect()->route('posts.show', $post)
            ->with('notice', '記事を更新しました');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::find($id);

        // トランザクション開始
        DB::beginTransaction();
        try {
            $post->delete();

            // 画像削除
            if (!Storage::delete($post->image_path)) {
                // 例外を投げてロールバックする
                throw new \Exception('画像ファイルの削除に失敗しました。');
            }

            // トランザクション終了(成功)
            DB::commit();
        } catch (\Exception $e) {
            // トランザクション失敗
            DB::rollBack();
            return back()->withErrors($e->getMessage());
        }
        return redirect()->route('posts.index')
            ->with('notice', '記事を削除しました');
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $query = Post::query();

        if (!empty($keyword)) {
            $query->where('title', 'LIKE', "%{$keyword}%")
                ->orwhere('description', 'LIKE', "%{$keyword}%")
                ->latest()->paginate(9);;

        }
            $posts = $query->get();

        return view('posts.search', compact('posts'));
    }

        public function profile(string $user_id)
    {
        $posts = Post::where('user_id', $user_id)->latest()->paginate(9);
        $user = Post::where('user_id', $user_id)->first();
        
        return view('posts.profile', compact('posts', 'user'));
    }

        public function bookmark()
    {
        $user_id = Auth::user()->id;
        $bookmarks = Nice::with(['post'])->where('user_id', $user_id)->latest()->paginate(9);

        return view('posts.bookmark', compact('bookmarks'));
    }

    public function welcome()
    {
        $posts = Post::with('user')->latest()->paginate(6);
        return view('posts.welcome', compact('posts'));
    }


    private static function createFileName($file)
    {
        return date('YmdHis') . '_' . $file->getClientOriginalName();
    }
}
