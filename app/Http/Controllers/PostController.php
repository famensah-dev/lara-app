<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Attachment;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isEmpty;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with('user')->get();

        $posts->each(function ($post) {
            $post->short_content = Str::limit($post->content, 250);
        });
    
        return view('posts.index', compact('posts'));
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

        // dd($request->all());

        $validatedData = $request->validated();
        
        $post = new Post();
        $post->user_id = Auth::id();
        $post->title = $validatedData['title'];
        $post->content = $validatedData['content'];
        $post->save();

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $filename = Str::random(5) . '_' . Carbon::now()->format('Ymd') . '.' . $file->getClientOriginalExtension();
                // $filename = $post->id . '_' . now()->format('YmdHis') . '.' . $file->getClientOriginalExtension();
                
                $path = $file->storeAs('attachments', $filename, 'public');

                $fileType = $file->getClientMimeType();
        
                $attachment = new Attachment();
                $attachment->post_id = $post->id;
                $attachment->filename = $filename; 
                $attachment->filepath = $path;
                $attachment->filetype = $fileType;
                $attachment->save();
            }
        }
        
        return response()->json(['message' => 'Post created successfully'], 200);
    }


    // public function uploadAttachments(Request $request)
    // {
    //     $request->validate([
    //         'file' => 'required|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt|max:2048', // Adjust as needed
    //     ]);

    //     if ($request->hasFile('file')) {
    //         $path = $request->file('file')->store('attachments', 'public');
    //         $url = Storage::url($path);

    //         return response()->json(['url' => $url]);
    //     }

    //     return response()->json(['error' => 'File upload failed.'], 422);
    // }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $post->load('attachments');
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
    public function update(StorePostRequest $request)
    {
        $post = Post::find($request->id);

        $this->authorize('update', $post);

        $validated = $request->validated();

        $post->title = $validated['title'];
        $post->content = $validated['content'];
        $post->save();

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                
                $filename = Carbon::now()->format('Ymd') . '_' . Str::lower(Str::random(5)) . '.' . $file->getClientOriginalExtension();
                
                $path = $file->storeAs('attachments', $filename, 'public');

                $fileType = $file->getClientMimeType();
        
                $attachment = new Attachment();
                $attachment->post_id = $post->id;
                $attachment->filename = $filename; 
                $attachment->filepath = $path;
                $attachment->filetype = $fileType;
                $attachment->save();
            }
        }
        
        return response()->json(['message' => 'Post updated successfully!'], 200);
    }



    public function viewPost(Post $post){
        $post->load('attachments');

        return view('posts.view', compact('post'));
    }



    public function uploadImage(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = Str::random(10) . '_' . now()->format('Ymd') . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/uploads', $filename);

            return response()->json(['url' => Storage::url($path)], 200);
        }

        return response()->json(['error' => 'File not uploaded'], 400);
    }



    public function deleteAttachment(Post $post, Attachment $attachment)
    {
        if ($attachment->post_id !== $post->id) {
            return response()->json(['error' => 'Attachment does not belong to this post'], 403);
        }
    
        Storage::disk('public')->delete($attachment->filepath);
    
        $attachment->delete();
    
        return response()->json(['message' => 'Attachment deleted successfully']);
    }

    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $post = Post::find($request->id);

        $this->authorize('delete', $post);

        $post->delete();

        return back();
    }
}
