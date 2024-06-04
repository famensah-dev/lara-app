<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header d-flex justify-content-between">
        <span class="modal-title">Edit User</span>
        <i class="uil uil-multiply cs_icon" type="button" data-bs-dismiss="modal" aria-label="Close"></i>
      </div>
      <div class="modal-body">
        <form method="POST" id="updateUserForm" action="{{ route('users.update') }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
              <input type="hidden" name="id" id="editUser_id" class="form-control">
            </div>
            <div class="mb-3">
              <input type="text" name="name" id="editUser_name" class="form-control">
              <div class="invalid-feedback" id="editUser_name_error"></div>
            </div>
            <div class="mb-3">
              <input type="email" name="email" id="editUser_email" class="form-control">
              <div class="invalid-feedback" id="editUser_email_error"></div>
            </div>
            <!-- <div class="mb-3">
              <input type="password" name="password" id="editUser_password" class="form-control" placeholder="Password">
              <div class="invalid-feedback" id="editUser_password_error"></div>
            </div>
            <div class="mb-3">
              <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password">
            </div> -->
            <button id="updateUserBtn" type="submit" class="cs_btn cs_btn-primary">Save</button>
        </form>
      </div>
    </div>
  </div>
</div>