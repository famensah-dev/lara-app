@extends('layouts.app')

@include('modals.edit-post-modal')
@include('modals.delete-post-modal')

@section('content')
<div class="container">
    <div class="mx-auto">  
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

    <section class="container d-flex flex-column align-items-center">
        <div class="container">
            <form id="addPostForm" method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"" id="addPost_title" class="form-control" placeholder="Enter Title">
                    @error('title')
                        <div class="invalid-feedback" id="addPost_title_error">
                            {{$message}}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    @error('content')
                        <div class="invalid-feedback" id="addPost_title_error">
                            {{$message}}
                        </div>
                    @enderror
                    <input id="addPost_content" type="hidden" name="content">
                    <input type="hidden" name="imageIds" id="imageIds">
                    <trix-editor input="addPost_content" class="@error('content') is-invalid @enderror form-control trix-content" rows="3" data-scrollbar></trix-editor>
                </div>
                <button type="submit" class="cs_btn cs_btn-primary">Create Post</button>
            </form>
        </div>

        <div class="d-flex flex-column gap-4 bg-white cs_padding">
            <div class="mt-8 cs_post-wrapper" data-scrollbar>
                @forelse ($posts as $post)
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
                                        <li class="dropdown-item cs_hover-pointer"><a href="{{route('posts.viewPost', ['post' => $post->id])}}">View</a></li>
                                        <li data-post-id="{{ $post->id }}" class="editPostBtn dropdown-item cs_hover-pointer" data-bs-toggle="modal" data-bs-target="#editPostModal">Edit</li>
                                        <li data-post-id="{{ $post->id }}" class="deletePostBtn dropdown-item cs_hover-pointer" data-bs-toggle="modal" data-bs-target="#deletePostModal">Delete</li>
                                    </ul>
                                </div>
                                @endcanany
                            </div>

                            <!-- Display Post -->
                            <p class="post-title main">Caption: {{ $post->title }}</p>

                            <div class="trix-content fixed-height d-flex flex-column" data-scrollbar>
                                {!! $post->content !!}
                            </div>
                        </div>
                    </div>
                    @empty
                    
                    <div class="d-flex flex-column align-items-center gap-3">
                        <img src="{{ asset('/images/girl-studying.png') }}" id="no-post-img" alt="No Posts Image">                       
                        <p style="font-style:italic;">No Posts Available</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

</div>
@endsection

@push('scripts')
<script src="{{ asset('js/script.js') }}" defer></script>
<script defer>
    document.addEventListener('DOMContentLoaded', function() {

        let imageIds = []

        $('.editPostBtn').on('click', function() {
            const postId = $(this).data('post-id');
            const url = "{{ route('posts.show', ':postId') }}".replace(':postId', postId);
            showEditPostModal(url);
        });


        const updatePostForm = document.querySelector('#updatePostForm');

        updatePostForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formUrl = "{{ route('posts.update') }}";
            const formData = new FormData(this);
            submitUpdatePostForm(formUrl, formData);
        });


        $('.deletePostBtn').on('click', function() {
            const postId = $(this).data('post-id');
            $('#deletePostId').val(postId);
        });



        (function() {
            var HOST = "{{ route('post.attachment.upload') }}"

            addEventListener("trix-attachment-add", function(event) {
                if (event.attachment.file) {
                    uploadFileAttachment(event.attachment)
                }
            })

            function uploadFileAttachment(attachment) {
                uploadFile(attachment.file, setProgress, setAttributes)

                function setProgress(progress) {
                    attachment.setUploadProgress(progress)
                }

                function setAttributes(attributes) {
                    attachment.setAttributes(attributes)
                }
            }

            function uploadFile(file, progressCallback, successCallback) {
                var key = createStorageKey(file)
                var formData = createFormData(key, file)
                var xhr = new XMLHttpRequest()

                const csrfToken = $('meta[name="csrf-token"]').attr('content');

                formData.append('_token', csrfToken);

                xhr.open("POST", HOST, true)

                xhr.upload.addEventListener("progress", function(event) {
                    var progress = event.loaded / event.total * 100
                    progressCallback(progress)
                })

                xhr.addEventListener("load", function(event) {
                    const responseObject = JSON.parse(xhr.responseText)

                    if (xhr.status == 200) {
                        var attributes = {
                            url: responseObject.url,
                            href: responseObject.url + "?content-disposition=attachment",
                            attachmentId: responseObject.id
                        }

                        imageIds.push(responseObject.id)

                        let attachmentIds = document.querySelector('#imageIds')
                        attachmentIds.value = imageIds

                        console.log(imageIds)

                        successCallback(attributes)
                    }
                })

                xhr.send(formData)
            }

            function createStorageKey(file) {
                var date = new Date()
                var day = date.toISOString().slice(0,10)
                var name = date.getTime() + "-" + file.name
                return [ "tmp", day, name ].join("/")
            }

            function createFormData(key, file) {
                var data = new FormData()
                data.append("key", key)
                data.append("Content-Type", file.type)
                data.append("file", file)
                return data
            }
        })();



    document.addEventListener('trix-attachment-remove', function(event){
        const attachment = event.attachment
        const attachmentId = attachment.attachment.attributes.values['attachmentId']
        const url = "{{ route('post.attachment.remove', ['attachment' => ':attachmentId']) }}".replace(':attachmentId', attachmentId)

        if (confirm('Are you sure you want to remove this attachment? This action cannot be undone!')) {
            removeAttachment(url)
        }
    });




    function removeAttachment(url){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: url,
                type: 'DELETE',
                success: function(response) {
                    console.log('File successfully deleted');
                },
                error: function(xhr, status, error) {
                    console.error('Error deleting file:', error);
                }
            });
                console.log(attachmentId)
    }

    });
</script>
@endpush
