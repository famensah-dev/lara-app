<div class="modal fade" id="addUserModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addUserModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header d-flex justify-content-between">
        <span>Add New User</span>
        <i class="uil uil-multiply cs_icon" type="button" data-bs-dismiss="modal" aria-label="Close"></i>
      </div>
      <div class="modal-body">
        <form method="POST" id="addUserForm" action="{{ route('users.store') }}">
            @csrf
            <div class="mb-3">
              <input type="text" name="name" id="addUser_name" class="form-control" placeholder="Full Name">
              <div class="invalid-feedback" id="addUser_name_error"></div>
            </div>
            <div class="mb-3">
              <input type="email" name="email" id="addUser_email" class="form-control" placeholder="Email">
              <div class="invalid-feedback" id="addUser_email_error"></div>
            </div>
            <div class="mb-3">
              <input type="password" name="password" id="addUser_password" class="form-control" placeholder="Password">
              <div class="invalid-feedback" id="addUser_password_error"></div>
            </div>
            <div class="mb-3">
              <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password">
            </div>
            <button id="addUserBtn" type="submit" class="cs_btn cs_btn-primary">Submit</button>
        </form>
      </div>
    </div>
  </div>
</div>