<!-- Include Bootstrap modal markup at the end of the file -->
<div class="modal fade" id="deleteReferralConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteReferralConfirmationModalLabel">Confirm Deletion</h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>
            <div class="modal-body">
                Are you sure you want to remove referral of <strong><span id="referralName"></span></strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal"
                        aria-label="Close">Cancel</button>
                <form id="deleteDirectReferral" action="{{route('admin.referral.direct.delete')}}" method="POST" style="display: inline;">
                    <input type="hidden" name="id"  id="referralId">

                    @method('DELETE')
                    @csrf
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
