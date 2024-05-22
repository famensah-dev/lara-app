@extends('layouts.app')

@include('modals.add-user-modal')
@include('modals.edit-user-modal')
@include('modals.delete-user-modal')

@section('content')
<div class="container">
    <div class="row">
        <div>
            <button type="button" class="cs_btn cs_btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                Add user
            </button>
        </div>
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
<script src="{{ asset('js/script.js') }}"></script>
<script>
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
                    }

                ]


    $("document").ready(function() {
        $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 100,
            info: false,
            dom: '<"cs_dt-top"flB>rt<"bottom"p><"clear">',
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


        $('#updateUserForm').on('submit', function(e){
            e.preventDefault();
            const formUrl = "{{ route('users.update') }}";
            const formData = $(this).serialize();
            console.log(formData, formUrl)

            submitUpdateUserForm(formUrl, formData, $('#editUserModal'));
        });


        $('#users-table').on('click', '.editUserBtn', function(){
            const userId = $(this).data('user-id');
            const url = "{{ route('users.show', ':userId') }}".replace(':userId', userId);
            console.log(url)
            showEditUserModal(url);
        });


        $('#updateUserForm').on('submit', function(e){
            e.preventDefault();
            // const formUrl = $(this).attr('action');
            const formUrl = "{{ route('users.update') }}";
            const formData = $(this).serialize();
            console.log(formData, formUrl)

            submitUpdateUserForm(formUrl, formData, $('#editUserModal'));
        });


        $('#users-table').on('click', '.deleteUserBtn', function(){
            const userId = $(this).data('user-id');
            $('#deleteUserId').val(userId);
            $('#deleteUserModal').modal('show');
        });
    });
</script>
@endpush