<div class="modal fade" id="editUserPasswordModal" tabindex="-1" aria-labelledby="editUserPasswordModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header d-flex justify-content-between">
        <span class="modal-title">Edit User Password</span>
        <i class="uil uil-multiply cs_icon" type="button" data-bs-dismiss="modal" aria-label="Close"></i>
      </div>
      <div class="modal-body">
        <form method="POST" id="updateUserPasswordForm" action="{{ route('users.updateUserPassword') }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
              <input type="hidden" name="id" id="editPasswordUser_id" class="form-control">
            </div>
            <div class="mb-3">
              <input type="text" name="name" id="editPasswordUser_name" class="form-control" disabled>
            </div>
            <div class="mb-3">
              <input type="email" name="email" id="editPasswordUser_email" class="form-control" disabled>
            </div>
            <div class="mb-3">
              <input type="password" name="password" id="editPasswordUser_password" class="form-control" placeholder="New Password">
              <div class="invalid-feedback" id="editPasswordUser_password_error"></div>
            </div>
            <div class="mb-3">
              <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password">
            </div>
            <button id="updateUserPasswordBtn" type="submit" class="cs_btn cs_btn-primary">Change Password</button>
            <button type="button" data-bs-dismiss="modal" aria-label="Close" class="btn btn-light">Cancel</button>
        </form>
      </div>
    </div>
  </div>
</div>