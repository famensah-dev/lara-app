<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $posts = Post::with(['user', 'attachments'])->latest()->get();

        $posts->each(function ($post) {
            $post->short_content = Str::of($post->content)->length() > 250 ? Str::limit($post->content, 250, '') . "...</div>" : $post->content;
        });

        return view('home', ['posts'=> $posts]);
    }
}
