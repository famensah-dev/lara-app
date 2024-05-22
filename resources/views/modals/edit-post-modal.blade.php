<div class="modal fade" id="editPostModal" tabindex="-1" aria-labelledby="editPostModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header d-flex justify-content-between">
        <span>Edit Post</span>
        <i class="uil uil-multiply cs_icon" type="button" data-bs-dismiss="modal" aria-label="Close"></i>
      </div>
      <div class="modal-body">
        <form method="POST" id="updatePostForm" action="{{ route('posts.update') }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <input type="hidden" name="id" id="edit_postId">
                <textarea id="editPostTextbox" class="form-control" rows="3" name="message"></textarea>
            </div>
            <!-- <div class="invalid-feedback"></div> -->
            <button id="updatePostBtn" type="submit" class="cs_btn cs_btn-primary">Save</button>
        </form>
      </div>
    </div>
  </div>
</div>