@extends('layouts.app')

@include('modals.edit-post-modal')
@include('modals.delete-post-modal')

@section('content')
<div class="container">
    @if($post)
    <div id="{{ $post->id }}" class="p-6 my-2 rounded-md cs_box-shadow post {{ $post->user->is(auth()->user()) ? 'cs_self-post' : 'cs_other-post'}}">
        <div class="px-4 py-4">
            <div class="d-flex justify-content-between py-2">
                <div class="d-flex align-items-center gap-1 post-tools">
                    <i class="uil uil-comment-dots cs_icon"></i>
                    <div class="cs_text-primary">
                        <span class="text-gray-800 px-2">{{ $post->user->is(auth()->user()) ? 'You' : $post->user->name }}</span>
                        <small>{{ $post->created_at->format('j M Y, g:i a') }}</small>
                        @unless($post->created_at->eq($post->updated_at))
                        <span> &middot; edited</span>
                        @endunless
                    </div>
                </div>                          
                @canany(['delete', 'update'], $post)
                <div class="dropdown cs_dropdown">
                    <i type="button" class="uil uil-ellipsis-v cs_icon" data-bs-toggle="dropdown" aria-expanded="false"></i>
                    <ul class="dropdown-menu">
                        <li data-post-id="{{ $post->id }}" class="editPostBtn dropdown-item cs_hover-pointer" data-bs-toggle="modal" data-bs-target="#editPostModal">Edit</li>
                        <li data-post-id="{{ $post->id }}" class="deletePostBtn dropdown-item cs_hover-pointer" data-bs-toggle="modal" data-bs-target="#deletePostModal">Delete</li>
                    </ul>
                </div>
                @endcanany
            </div>

            <!-- Display Post -->
            <p class="post-title main">Caption: {{ $post->title }}</p>

            @if ($post->attachments->count() > 0)
            <div class="post-images">
                @foreach($post->attachments as $attachment)
                    @if (Str::startsWith($attachment->filetype, 'image/'))
                    <div class="post-image">
                        <img src="{{ asset('storage/' . $attachment->filepath) }}" alt="{{ $attachment->filename }}">
                    </div>
                    @endif
                @endforeach
            </div>
            @endif
            <div class="trix-content" data-scrollbar>
                {!! $post->content !!}
            </div>
            @if ($post->attachments->count() > 0 && !Str::startsWith($attachment->filetype, 'image/'))
            <p class="post-title">Attachments</p>
            <ul class="cs_ul">
                @foreach ($post->attachments as $attachment)
                    <li>
                        <a href="{{ asset('storage/' . $attachment->filepath) }}" target="_blank">{{ $attachment->filename }}</a>
                    </li>
                @endforeach
            </ul>
            @endif
        </div>
    </div>
    @else
        <h5>Post not found</h5>
    @endif
</div>

@endsection