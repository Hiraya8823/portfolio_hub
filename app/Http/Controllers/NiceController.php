<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Nice;
use Illuminate\Support\Facades\Auth;

class NiceController extends Controller
{
    public function nice(Post $post, Request $request)
    {
        $nice = new Nice();
        $nice->post_id = $post->id;
        $nice->ip = $request->ip();

        if (Auth::check()) {
            $nice->user_id = Auth::user()->id;
        }

        $nice->save();

        return redirect()->route('posts.show', compact('post'));
    }

    public function unnice(Post $post, Request $request)
    {

        if (Auth::check()) {
            $user = Auth::user()->id;
            $nice = Nice::where('post_id', $post->id)
                ->where('user_id', $user)
                ->first();
        } else {
            $user = $request->ip();
            $nice = Nice::where('post_id', $post->id)
                ->where('user_id', null)
                ->where('ip', $user)
                ->first();
        }

        $nice->delete();

        return redirect()->route('posts.show', compact('post'));
    }
}
