<!-- Confirmation Modal -->
<div class="modal" id="addIBModal" tabindex="-1" role="dialog" aria-labelledby="addIBModalModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addIBModalModalLabel">Confirm IB Add</h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>
            <div class="modal-body">
                Are you sure you want to add IB Account?
            </div>
            <form id="addIBModalForm" action="{{ route('admin.ib.approve') }}" method="POST">
            @csrf
            <!-- Other form fields go here -->
                <input type="hidden" name="user_id"  value="{{$user->id}}">

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal"
                            aria-label="Close">Cancel</button>
                    <button type="submit" class="btn btn-success" >Add IB</button>

                </div>
            </form>
        </div>
    </div>
</div>

