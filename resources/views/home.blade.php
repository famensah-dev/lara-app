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
                            <div class="trix-content fixed-height d-flex flex-column">
                                {!! $post->short_content !!}
                                <!-- {!!  $post->content !!} -->
                                @if (strlen($post->content) > 250)
                                <span class="read-more"><a href="{{route('posts.viewPost', ['post' => $post->id])}}">Read More</a></span>
                                @endif
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
                    @empty
                    
                    <div class="d-flex flex-column align-items-center gap-3">
                        <img src="{{ asset('/images/girl-studying.png') }}" id="no-post-img" alt="No Posts Image">                       
                        <p style="font-style:italic;">No Posts Available</p>
                    </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center">
                <button type="button" class="cs_btn cs_btn-primary rounded-full mt-2" data-bs-toggle="modal" data-bs-target="#addPostModal">Add New</button>
            </div>

           

            
            <!-- Add New Post Modal -->
            <div class="modal fade" id="addPostModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addPostModal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header d-flex justify-content-between">
                            <span>Add New Post</span>
                            <i class="uil uil-multiply cs_icon" type="button" data-bs-dismiss="modal" aria-label="Close"></i>
                        </div>
                        <div class="modal-body">
                        <form id="addPostForm" method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <input type="text" name="title" id="addPost_title" class="form-control" placeholder="Enter Title">
                                <div class="invalid-feedback" id="addPost_title_error"></div>
                            </div>
                            <div class="mb-3">
                                <input id="addPost_content" type="hidden" name="content">
                                <trix-editor input="addPost_content" class="form-control trix-content" rows="3"></trix-editor>
                            </div>
                            <button type="submit" class="cs_btn cs_btn-primary">Create Post</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>                            
        </div>
    </section>

</div>
@endsection

@push('scripts')
<script src="{{ asset('js/script.js') }}" defer></script>
<script defer>
    document.addEventListener('DOMContentLoaded', function() {
        let attachments = [];

        // document.addEventListener('trix-attachment-add', function(event) {
        //     if (event.attachment.file) {
        //         // console.log(event.attachment.file.type)
        //         attachments.push(event.attachment);
        //     }
        // });

        // document.addEventListener('trix-file-accept', function(event) {
        //     // event.preventDefault()
        //     console.log(event.file)
        //     // if (event.attachment.file) {
        //     //     console.log(event.attachment.file)
        //     //     attachments.push(event.attachment);
        //     // }
        // });

        // document.addEventListener('trix-change', function(event) {
        //     const editor = event.target;
        //     const images = editor.querySelectorAll("img");
            
        //     images.forEach(image => {
        //         if (!image.getAttribute('data-uploaded')) {
        //             uploadImage(image);
        //         }
        //     });
        // });

        // const insertedImages = [];

        let trixEditor = document.querySelector("trix-editor")

        // document.addEventListener('trix-file-accept', function(event){
        //     event.preventDefault()
        //     trixEditor.editor.insertString("Hello")
        // })
        
        document.addEventListener('trix-attachment-add', function(event) {

            const file = event.attachment.file;
            if (file && file.type.startsWith('image/')) {
                console.log(file);
                console.log(trixEditor.editor);
                // trixEditor.editor.insertHTML("<strong>Hello</strong>")
                //upload file

                //insert img tag in trix editor at that position
                
            } else {
                attachments.push(file);
                console.log(attachments)
            }
        });



            // function uploadImage(image) {
            //     const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            //     const file = image.file;
            //     const formData = new FormData();
            //     formData.append('file', file);
            //     formData.append('_token', csrfToken);

            //     const xhr = new XMLHttpRequest();
            //     xhr.open('POST', '/upload-image', true);
            //     xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
            //     xhr.responseType = 'json';

            //     xhr.onload = function() {
            //         if (xhr.status === 200) {
            //             const response = xhr.response;
            //             image.setAttribute('src', response.url);
            //             image.setAttribute('data-uploaded', 'true');
            //         } else {
            //             console.error('Image upload failed');
            //         }
            //     };

            //     xhr.send(formData);
            // }
        // function uploadFile(attachment, postId) {
        //     const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        //     const file = attachment.file;
        //     const formData = new FormData();
            
        //     // Generate custom filename
        //     const customFilename = `${postId}_${Date.now()}.${file.name.split('.').pop()}`;
            
        //     formData.append('file', file, customFilename);
        //     formData.append('_token', csrfToken);
        //     formData.append('postId', postId);

        //     const xhr = new XMLHttpRequest();
        //     xhr.open('POST', '/posts/attachments/upload', true);
        //     xhr.responseType = 'json';

        //     xhr.upload.addEventListener('progress', function(event) {
        //         const progress = (event.loaded / event.total) * 100;
        //         attachment.setUploadProgress(progress);
        //     });

        //     xhr.onload = function() {
        //         if (xhr.status === 200) {
        //             const response = xhr.response;
        //             attachment.setAttributes({
        //                 url: response.url,
        //                 href: response.url
        //             });
        //         } else {
        //             attachment.remove();
        //         }
        //     };

        //     xhr.send(formData);
        // }

        const addPostForm = document.querySelector('#addPostForm');
        
        addPostForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formUrl = this.getAttribute('action'); 
            const formData = new FormData(this);

            attachments.forEach((attachment, index) => {
                formData.append('attachments[]', attachment.file);
            });

            submitAddPostForm(formUrl, formData);
        });


        // function submitAddPostForm(url, formData) {
        //     $.ajax({
        //         url: url,
        //         type: 'POST',
        //         dataType: 'json',
        //         data: formData,
        //         contentType: false,
        //         processData: false,
        //         success: function(response) {
        //             console.log(response);
        //             $('#addPostModal').modal('hide');
        //             reloadPage();
        //         },
        //         error: function(response) {
        //             console.log(response);
        //             if (response.status === 422) {
        //                 $('.is-invalid').removeClass('is-invalid');
        //                 $('.invalid-feedback').empty();

        //                 $.each(response.responseJSON.errors, function(key, value) {
        //                     $('#addPost_' + key).addClass('is-invalid');

        //                     $.each(value, function(i, message) {
        //                         $('#addPost_' + key + "_error").append('<div>' + message + '</div>');
        //                     });
        //                 });
        //             } else {
        //                 console.log(response);
        //             }
        //         }
        //     });
        // }

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

            attachments.forEach((attachment, index) => {
                formData.append('attachments[]', attachment.file);
            });

            console.log(formData)
            submitUpdatePostForm(formUrl, formData);
        });


        $('.deletePostBtn').on('click', function() {
            const postId = $(this).data('post-id');
            $('#deletePostId').val(postId);
        });
    });
</script>
@endpush
