@extends('layouts.app')

@include('modals.edit-post-modal')
@include('modals.delete-post-modal')

@section('content')
<div class="container">
    <div class="mx-auto col-5">  
        <div class="alert alert-light alert-dismissible fade show" role="alert">
            <div>Welcome {{ Auth::user()->name }}!</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>  

        @if (session('status'))  
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{session('status')}}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>  
        @endif
    </div>

    <section class="container-sm d-flex justify-content-center">
        <div class="d-flex flex-column gap-3 col-5 bg-white cs_padding rounded">   
            <div class="mt-8 cs_post-wrapper">
                @foreach ($posts as $post)
                    <div id="{{ $post->id }}" class="p-6 my-2 cs_box-shadow {{ $post->user->is(auth()->user()) ? 'cs_self-post' : 'cs_other-post'}}">
                        <div class="px-4 py-1">
                            <div class="d-flex justify-content-between py-2">
                                <div class="d-flex align-items-center gap-1">
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
                                <div class="dropdown">
                                    <i type="button" class="uil uil-ellipsis-h cs_icon" data-bs-toggle="dropdown" aria-expanded="false"></i>
                                    <ul class="dropdown-menu">
                                        <li data-post-id="{{ $post->id }}" class="editPostBtn dropdown-item cs_cursor-pointer" data-bs-toggle="modal" data-bs-target="#editPostModal">Edit</li>
                                        <li data-post-id="{{ $post->id }}" class="deletePostBtn dropdown-item cs_cursor-pointer" data-bs-toggle="modal" data-bs-target="#deletePostModal">Delete</li>
                                    </ul>
                                </div>
                                @endcanany
                            </div>
                            <p>{{ $post->message }}</p>
                        </div>
                    </div>
                @endforeach
                <div id="cs_scroller"></div>
            </div>

            <form method="POST" action="{{ route('posts.store') }}">
                @csrf
                <div class="mb-3">
                    <textarea class="form-control" rows="3" name="message" placeholder="Message..." autofocus></textarea>
                </div>
                <button type="submit" class="cs_btn cs_btn-primary">Post</button>
            </form>
        </div>
    </section>

</div>
@endsection

@push('scripts')
<script src="{{ asset('js/script.js') }}"></script>
<script>
    $(document).ready(function(){
        
        $('.editPostBtn').on('click', function(){
            const postId = $(this).data('post-id');
            const url = "{{ route('posts.show', ':postId') }}".replace(':postId', postId);
            showEditPostModal(url);
        });


        $('#updatePostForm').on('submit', function(e){
            e.preventDefault();
            // const formUrl = $(this).attr('action');
            const formUrl = "{{ route('posts.update') }}"
            const formData = $(this).serialize();
            submitUpdatePostForm(formUrl, formData, $('#editPostModal'));
        });


        $('.deletePostBtn').on('click', function(){
            const postId = $(this).data('post-id');
            $('#deletePostId').val(postId);
        });
    });
</script>
@endpush
