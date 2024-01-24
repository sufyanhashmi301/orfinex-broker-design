<!-- Confirmation Modal -->
<div class="modal" id="deleteTagModal" tabindex="-1" role="dialog" aria-labelledby="deleteTagModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteTagModalLabel">Confirm Deletion</h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this tag <strong><span id="risk_profile_tag_name"></span></strong>?
            </div>
            <form id="deleteTagForm" action="{{ route('admin.risk-profile-tag.tag.delete',$user->id) }}" method="POST">
            @csrf
            <!-- Other form fields go here -->
                <input type="hidden" name="risk_profile_tag_id" id="risk_profile_tag_id">

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal"
                            aria-label="Close">Cancel</button>
                    <button type="submit" class="btn btn-danger" >Delete</button>

                </div>
            </form>
        </div>
    </div>
</div>

