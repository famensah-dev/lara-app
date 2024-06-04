<!-- Modal -->
<div class="modal" id="removeAttachmentModal${attachment.id}" tabindex="-1" aria-labelledby="removeAttachmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="removeAttachmentModalLabel">Remove Attachment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to remove this attachment?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" onclick="removeAttachment(${attachment.id})">Remove</button>
            </div>
        </div>
    </div>
</div>