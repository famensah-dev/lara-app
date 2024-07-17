@extends('layouts.app')

@include('modals.add-user-modal')
@include('modals.edit-user-modal')
@include('modals.delete-user-modal')
@include('modals.edit-user-password-modal')
@include('modals.edit-user-roles-modal')

@section('content')
<div class="container">
    <div class="row">
        <!-- <div>
            <button type="button" class="cs_btn cs_btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                Add user
            </button>
        </div> -->
    </div>
    <table id="users-table" class="table dataTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Roles</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/script.js') }}" defer></script>
<script defer>
    const buttonOptions = [
                {
                        extend: 'copyHtml5',
                        text: '<i class="uil uil-copy"></i>',
                        titleAttr: 'Copy',
                        exportOptions: {
                            columns: ':not(.excludeFromExport)'
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        text: '<i class="uil uil-file"></i>',
                        titleAttr: 'CSV',
                        exportOptions: {
                            columns: ':not(.excludeFromExport)'
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fas fa-file-excel"></i>',
                        titleAttr: 'Excel',
                        exportOptions: {
                            columns: ':not(.excludeFromExport)'
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fas fa-file-pdf"></i>',
                        titleAttr: 'PDF',
                        exportOptions: {
                            columns: ':not(.excludeFromExport)'
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="uil uil-print"></i>',
                        titleAttr: 'Print',
                        exportOptions: {
                            columns: ':not(.excludeFromExport)'
                        }
                    },
                    {
                        text: '<span data-bs-toggle="modal" data-bs-target="#addUserModal">Add New</span>',
                        className: 'add-user-btn',
                        action: function ( e, dt, node, config ) {
                            //what happens when button is clicked
                        }
                    },

                ]


    $("document").ready(function() {
        $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 25,
            info: false,
            scrollY: '60vh', 
            scrollCollapse: true,
            language: {
                paginate: {
                    next: '<i class="uil uil-angle-right-b"></i>',
                    previous: '<i class="uil uil-angle-left-b"></i>'
                }
            },
            dom: '<"cs_dt-top"lfB>rt<"bottom"p><"clear">',
            buttons: buttonOptions,
            ajax: "{{ route('users.index') }}",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'roles', name: 'roles'},
                {data: 'created_at', name: 'created_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false, class: 'excludeFromExport'},
            ]
        });

        $('#editRoleUser_roles').select2({
            placeholder: 'Select Roles',
            allowClear: true,
            tags: true,
            tokenSeparators: [',', ' '],
            dropdownParent: $('#editUserRolesModal'),
            width: '100%'
        });


        $('#users-table').on('click', '.editUserBtn', function(){
            const userId = $(this).data('user-id');
            const url = "{{ route('users.show', ':userId') }}".replace(':userId', userId);
            // console.log(url)
            showEditUserModal(url);
        });

        $('#users-table').on('click', '.editUserPasswordBtn', function(){
            const userId = $(this).data('user-id');
            const url = "{{ route('users.show', ':userId') }}".replace(':userId', userId);
            // console.log(url)
            showEditUserPasswordModal(url);
        });

        $('#users-table').on('click', '.editUserRoleBtn', function(){
            const userId = $(this).data('user-id');
            const url = "{{ route('users.getUserRoles', ':userId') }}".replace(':userId', userId);
            // console.log(url)
            showEditUserRolesModal(url);
        });

        $('#users-table').on('click', '.deleteUserBtn', function(){
            const userId = $(this).data('user-id');
            $('#deleteUserId').val(userId);
            $('#deleteUserModal').modal('show');
        });



        $('#addUserForm').on('submit', function(e){
            e.preventDefault();
            const formUrl = "{{ route('users.store') }}";
            const formData = $(this).serialize();

            submitAddUserForm(formUrl, formData, $('#addUserModal'));
        });


        $('#updateUserForm').on('submit', function(e){
            e.preventDefault();
            const formUrl = "{{ route('users.update') }}";
            const formData = $(this).serialize();
            // console.log(formData, formUrl)

            submitUpdateUserForm(formUrl, formData, $('#editUserModal'));
        });


        $('#updateUserPasswordForm').on('submit', function(e){
            e.preventDefault();
            // const formUrl = $(this).attr('action');
            const formUrl = "{{ route('users.updateUserPassword') }}";
            const formData = $(this).serialize();
            // console.log(formData, formUrl)

            submitUpdateUserPasswordForm(formUrl, formData, $('#editUserPasswordModal'));
        });


        $('#updateUserRoleForm').on('submit', function(e){
            e.preventDefault();
            // const formUrl = $(this).attr('action');
            const formUrl = "{{ route('users.updateUserRoles') }}";
            const formData = $(this).serialize();
            // console.log(formData, formUrl)

            submitUpdateUserRoleForm(formUrl, formData, $('#editUserRoleModal'));
        });



        // $('#updateUserForm').on('submit', function(e){
        //     e.preventDefault();
        //     // const formUrl = $(this).attr('action');
        //     const formUrl = "{{ route('users.update') }}";
        //     const formData = $(this).serialize();
        //     console.log(formData, formUrl)

        //     submitUpdateUserForm(formUrl, formData, $('#editUserModal'));
        // });
    });
</script>
@endpush