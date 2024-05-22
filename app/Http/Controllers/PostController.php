<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $validated = $request->validated();
        $request->user()->posts()->create($validated);
        return redirect('/home');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return response()->json($post);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return response()->json($post);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $post = Post::find($request->id);

        $this->authorize('update', $post);

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:255'],
        ]);

        $post->update($validated);

        return redirect(route('home'));
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $post = Post::find($request->id);

        $this->authorize($post);

        $post->delete();

        return back();
    }
}
