
<div class="modal fade" id="deletePostModal" tabindex="-1" aria-labelledby="deletePostModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header d-flex justify-content-between">
        <span class="modal-title">Delete post?</span>
        <i class="uil uil-multiply cs_icon" type="button" data-bs-dismiss="modal" aria-label="Close"></i>
      </div>
      <div class="modal-body">
        <form method="POST" id="deletePostForm" action="{{ route('posts.destroy') }}" class="d-flex">
            @csrf
            @method('DELETE')
            <input type="hidden" name="id" id="deletePostId">
            <div>
              <button type="button" data-bs-dismiss="modal" aria-label="Close" class="cs_btn cs_btn-primary">Cancel</button>
              <button type="submit" class="cs_btn cs_btn-danger">Delete</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
