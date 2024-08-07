<div class="modal fade" id="editUserRoleModal" tabindex="-1" aria-labelledby="editUserRoleModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header d-flex justify-content-between">
        <span>Edit User Role</span>
        <i class="uil uil-multiply cs_icon" type="button" data-bs-dismiss="modal" aria-label="Close"></i>
      </div>
      <div class="modal-body">
        <form method="POST" id="updateUserRoleForm" action="{{ route('users.role.update') }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
              <input type="hidden" name="id" id="editRoleUser_id" class="form-control">
            </div>
            <div class="mb-3">
              <input type="text" name="name" id="editRoleUser_name" class="form-control" disabled>
            </div>
            <div class="mb-3">
              <input type="email" name="email" id="editRoleUser_email" class="form-control" disabled>
            </div>
            <div class="mb-3">
              <input type="password" name="password" id="editRoleUser_password" class="form-control" placeholder="New Password">
              <div class="invalid-feedback" id="editRoleUser_password_error"></div>
            </div>
            <div class="mb-3">
              <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password">
            </div>
            <button id="updateUserRoleBtn" type="submit" class="cs_btn cs_btn-primary">Save</button>
            <button type="button" data-bs-dismiss="modal" aria-label="Close" class="btn btn-light">Cancel</button>
        </form>
      </div>
    </div>
  </div>
</div>