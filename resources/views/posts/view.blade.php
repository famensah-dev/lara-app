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
                        <li class="dropdown-item cs_hover-pointer"><a href="{{route('home')}}">All Posts</a></li>
                    </ul>
                </div>
                @endcanany
            </div>

            <!-- Display Post -->
            <p class="post-title main">Caption: {{ $post->title }}</p>
            </div>
            <div class="trix-content px-4 py-2" data-scrollbar>
                {!! $post->content !!}
            </div>
        </div>
    </div>
    @else
        <h5>Post not found</h5>
    @endif
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/script.js') }}" defer></script>
<script defer>
    document.addEventListener('DOMContentLoaded', function() {

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
            var HOST = "/upload-image"
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
                        }

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

    });
</script>
@endpush
