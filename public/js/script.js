// import Trix from "trix"

Scrollbar.initAll();

window.onload = function(){
    const el = document.getElementById('cs_scroller')
    if(el){
        el.scrollIntoView({behavior: 'smooth'});
    }
}

function reloadPage(){
    location.reload()
}


function showEditPostModal(url){
    $.ajax({
        url,
        type: 'GET',
        dataType: 'json',
        success: function(data){
            // console.log(data)
            $('#editPost_title').val(data.title)
            $('#editPost_content').val(data.content)
            $('#edit_postId').val(data.id)
            $('#edit_attachments').empty();
            if (data.attachments && data.attachments.length > 0) {
                data.attachments.forEach(function(attachment) {
                    $('#edit_attachments').append(`
                            <div class="post-file" id="attachment-${attachment.id}">
                                <a href="/storage/${attachment.filepath}" target="_blank">${attachment.filename}</a>
                                <!-- Button trigger modal -->
                                <button type="button" class="remove-attachment-btn btn btn-sm btn-secondary" onclick="confirmRemoveAttachment(event, '${data.id}', '${attachment.id}')">
                                    <i class="uil uil-trash-alt"></i>
                                </button>
                            </div>
                        `);

                });
            }
        },
        error: function(){

        }
    })
}

function confirmRemoveAttachment(e, postId, attachmentId){
    e.preventDefault();

    if (confirm('Are you sure you want to remove this attachment? This action cannot be undone!')) {
        removeAttachment(postId, attachmentId);
    }
}


function removeAttachment(postId, attachmentId) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

    // const url = "{{ route('posts.attachment.remove', ['post' => ':postId', 'attachment' => ':attachmentId']) }}"
    // .replace(':postId', postId)
    // .replace(':attachmentId', attachmentId)

    const url = `/posts/${postId}/attachments/${attachmentId}`;

    $.ajax({        
        url,
        type: 'DELETE',
        dataType: 'json',
        success: function (response) {
            console.log('Attachment removed:', attachmentId, response);

            $(`#attachment-${attachmentId}`).remove();
        },
        error: function (response, status, error) {
            console.error('Error removing attachment:', error); 
            console.log('Response', response);
        }
    });
}


function showEditUserModal(url){
    $.ajax({
        url,
        type: 'GET',
        dataType: 'json',
        success: function(data){
            $('#editUser_id').val(data.id)
            $('#editUser_name').val(data.name)
            $('#editUser_email').val(data.email)
            $('#editUser_password').val(data.password)
            $('#editUserModal').modal('show')
            // console.log(data)
        },
        error: function(){

        }
    })
}


function showEditUserPasswordModal(url){
    $.ajax({
        url,
        type: 'GET',
        dataType: 'json',
        success: function(data){
            $('#editPasswordUser_id').val(data.id)
            $('#editPasswordUser_name').val(data.name)
            $('#editPasswordUser_email').val(data.email)
            $('#editPasswordUser_password').val(data.password)
            $('#editUserPasswordModal').modal('show')
            // console.log(data)
        },
        error: function(){

        }
    })
}


function showEditUserRolesModal(url){
    $.ajax({
        url,
        type: 'GET',
        dataType: 'json',
        success: function(data){
            // console.log(data)
            $('#editRoleUser_id').val(data.id)
            $('#editRoleUser_name').val(data.name)
            $('#editRoleUser_email').val(data.email)
            $('#editRoleUser_password').val(data.password)
            $('#editUserRolesModal').modal('show')

            // Set selected roles
            const assignedRoles = data.roles.map(role => role.id); // Adjust according to your data structure
            $('#editRoleUser_roles').val(assignedRoles).trigger('change'); // Set selected roles in Select2

            // console.log(data)
        },
        error: function(response){
            alert('An error occurred. Please try again.');
            console.log(response);
        }
    })  
}


function submitAddUserForm(url, formData, modal){    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        // console.log(url, formData);
        
        $.ajax({
            url,
            type: 'POST',
            dataType: 'json',
            data: formData,
            success: function(response){
                // console.log(response)
                modal.modal('hide')
                // reloadPage()
            },
            error: function(response){
                if (response.status === 422) { 
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').empty();
    
                    $.each(response.responseJSON.errors, function(key, value) {
                        $('#addUser_'+key).addClass('is-invalid');
    
                        $.each(value, function(i, message) {
                            $('#addUser_' + key + "_error").append('<div>' + message + '</div>');
                        });
                    });
                }else{
                    console.log(response)
                }
            }
        })
}




function submitAddPostForm(url, formData, modal){    
    // $.ajaxSetup({
    //     headers: {
    //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         }
    //     })

        console.log(url, formData);
        
        $.ajax({
            url,
            type: 'POST',
            dataType: 'json',
            data: formData,
            success: function(response){
                console.log(response)
                console.log('Hurray!')
                modal.modal('hide')
                reloadPage()
            },
            error: function(response){
                console.log(response)
                if (response.status === 422) { 
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').empty();
    
                    $.each(response.responseJSON.errors, function(key, value) {
                        $('#addPost_'+ key).addClass('is-invalid');
    
                        $.each(value, function(i, message) {
                            $('#addPost_' + key + "_error").append('<div>' + message + '</div>');
                        });
                    });
                }else{
                    console.log(response)
                }
            }
        })
}



function submitAddPostForm(url, formData) {
    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            console.log(response);
            $('#addPostModal').modal('hide');
            reloadPage();
        },
        error: function(response) {
            console.log(response);
            if (response.status === 422) {
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').empty();

                $.each(response.responseJSON.errors, function(key, value) {
                    $('#addPost_' + key).addClass('is-invalid');

                    $.each(value, function(i, message) {
                        $('#addPost_' + key + "_error").append('<div>' + message + '</div>');
                    });
                });
            } else {
                console.log(response);
            }
        }
    });
}



function submitUpdatePostForm(url, formData){    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

    console.log(formData, url)

    $.ajax({
        url,
        type: 'POST',
        dataType: 'json',
        processData: false,
        contentType: false,
        data: formData,
        success: function(response){
            console.log(response)
            $('#updatePostForm').modal('hide')
            reloadPage()
        },
        error: function(response){
            if (response.status === 422) { 
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').empty();

                $.each(response.responseJSON.errors, function(key, value) {
                    $('#editPost_'+key).addClass('is-invalid');

                    $.each(value, function(i, message) {
                        $('#editPost_' + key + "_error").append('<div>' + message + '</div>');
                    });
                });
            }else{
                console.log(response)
            }
        }
    })

}

function submitUpdateUserForm(url, formData, modal){    
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })

    console.log(formData, url)

    $.ajax({
        url,
        type: 'POST',
        dataType: 'json',
        data: formData,
        success: function(response){
            // console.log(response)
            modal.modal('hide')
            reloadPage()
        },
        error: function(response){

            if (response.status === 422) { 
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').empty();

                $.each(response.responseJSON.errors, function(key, value) {
                    $('#editUser_'+key).addClass('is-invalid');

                    $.each(value, function(i, message) {
                        $('#editUser_' + key + "_error").append('<div>' + message + '</div>');
                    });
                });
            }else{
                console.log(response)
            }
        }
    })
}


function submitUpdateUserPasswordForm(url, formData, modal){    
    $.ajax({
        url,
        type: 'POST',
        dataType: 'json',
        data: formData,
        success: function(response){
            modal.modal('hide')
            reloadPage()
        },
        error: function(response){
            if (response.status === 422) { 
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').empty();

                $.each(response.responseJSON.errors, function(key, value) {
                    $('#editPasswordUser_' + key).addClass('is-invalid');

                    $.each(value, function(i, message) {
                        $('#editPasswordUser_' + key + '_error').append('<div>' + message + '</div>');
                    });
                });
            }else{
                console.log(response)
            }
        }
    })
}



function populateRoles(roles, userRoles) {
    // Clear existing options
    $('#userRoles').empty();

    // Append roles as options
    $.each(roles, function(index, role) {
        $('#userRoles').append(new Option(role.name, role.id, false, userRoles.includes(role.id)));
    });

    // Trigger change to update Select2
    $('#userRoles').trigger('change');
}


function submitUpdateUserRoleForm(url, formData, modal){    
    $.ajax({
        url,
        type: 'POST',
        dataType: 'json',
        data: formData,
        success: function(response){
            modal.modal('hide')
            reloadPage()
        },
        error: function(response){
            if (response.status === 422) { 
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').empty();

                $.each(response.responseJSON.errors, function(key, value) {
                    $('#editPasswordUser_' + key).addClass('is-invalid');

                    $.each(value, function(i, message) {
                        $('#editPasswordUser_' + key + '_error').append('<div>' + message + '</div>');
                    });
                });
            }else{
                console.log(response)
            }
        }
    })
}



