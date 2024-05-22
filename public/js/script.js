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
            $('#editPostTextbox').val(data.message)
            $('#edit_postId').val(data.id)
        },
        error: function(){

        }
    })
}

function showEditUserModal(url){
    $.ajax({
        url,
        type: 'GET',
        dataType: 'json',
        success: function(data){
            $('#editUserTextbox').val(data.message)
            $('#editUser_id').val(data.id)
            $('#editUser_name').val(data.name)
            $('#editUser_email').val(data.email)
            $('#editUser_password').val(data.password)
            $('#editUserModal').modal('show')
            console.log(data)
        },
        error: function(){

        }
    })
}



function submitUpdatePostForm(url, formData, modal){    
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
            console.log(response)
            $('#updatePostForm').modal('hide')
            reloadPage()
        },
        error: function(response){
            console.log(response)
            $('#updatePostForm').modal('hide')
            reloadPage()
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










