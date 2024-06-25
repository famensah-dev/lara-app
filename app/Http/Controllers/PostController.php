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
use Illuminate\Support\Facades\Log;

use function PHPUnit\Framework\isEmpty;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with('user')->get();
    
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
        $validatedData = $request->validated();
        
        $post = new Post();
        $post->user_id = Auth::id();
        $post->title = $validatedData['title'];
        $post->content = $validatedData['content'];
        $post->save();

        if($request->has('imageIds')){
            $attachmentIds = $request->imageIds;
            
            foreach(explode(",", $attachmentIds) as $id){
                $attachment = Attachment::find($id);
                if ($attachment) {
                    $attachment->update(['post_id' => $post->id]);
                }
            }
        }

        return back()->with('success', 'Post created successfully.');
    }


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
        
        return response()->json(['message' => 'Post updated successfully!'], 200);
    }



    public function viewPost(Post $post){
        $post->load('attachments');

        return view('posts.view', compact('post'));
    }



    public function uploadAttachment(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = Carbon::now()->format('Ymd') . '_' . Str::lower(Str::random(5)) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/attachments', $filename);
            $path = $file->storeAs('attachments', $filename, 'public');
            $fileType = $file->getClientMimeType();
    
            $attachment = new Attachment();
            $attachment->filename = $filename; 
            $attachment->filepath = $path;
            $attachment->filetype = $fileType;
            $attachment->save();

            return response()->json([
                'id' => $attachment->id,
                'url' => asset(Storage::url($path)),
            ], 200);
        }

        return response()->json(['error' => 'File not uploaded'], 400);
    }



    public function removeAttachment(Attachment $attachment)
    {
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
    
        $attachments = $post->attachments; 
    
        foreach ($attachments as $attachment) {
            Storage::disk('public')->delete($attachment->filepath);

            $attachment->delete();
        }
    
        $post->delete();
    
        return back()->with('success', 'Post and its attachments deleted successfully.');
    }
}
