<!-- Modal -->
<div class="modal fade" id="configureModal" tabindex="-1" aria-labelledby="configureModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h1 class="modal-title fs-5" id="configureModalLabel">
                    Columns Configuration
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-0">
                <form action="">
                    <div class="site-input-groups mb-2">
                        <input type="text" name="" class="form-control form-control-sm" placeholder="Search...">
                    </div>
                    <h5 class="mb-1">General Info</h5>
                    <div class="site-input-groups">
                        <div class="border border-light">
                            <div class="p-1 bg-light">
                                <div class="form-check mb-0">
                                    <input class="form-check-input" type="checkbox" value="" id="userID" checked>
                                    <label class="form-check-label fw-normal" for="userID">
                                        User ID
                                    </label>
                                </div>
                            </div>
                            <div class="p-1">
                                <div class="form-check mb-0">
                                    <input class="form-check-input" type="checkbox" value="" id="country" checked>
                                    <label class="form-check-label fw-normal" for="country">
                                        Country
                                    </label>
                                </div>
                            </div>
                            <div class="p-1 bg-light">
                                <div class="form-check mb-0">
                                    <input class="form-check-input" type="checkbox" value="" id="email" checked>
                                    <label class="form-check-label fw-normal" for="email">
                                        Email
                                    </label>
                                </div>
                            </div>
                            <div class="p-1">
                                <div class="form-check mb-0">
                                    <input class="form-check-input" type="checkbox" value="" id="balance" checked>
                                    <label class="form-check-label fw-normal" for="balance">
                                        Balance
                                    </label>
                                </div>
                            </div>
                            <div class="p-1 bg-light">
                                <div class="form-check mb-0">
                                    <input class="form-check-input" type="checkbox" value="" id="type" checked>
                                    <label class="form-check-label fw-normal" for="type">
                                        Type
                                    </label>
                                </div>
                            </div>
                            <div class="p-1">
                                <div class="form-check mb-0">
                                    <input class="form-check-input" type="checkbox" value="" id="status" checked>
                                    <label class="form-check-label fw-normal" for="status">
                                        Status
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex mb-2 align-items-end">
                            <h5 class="mb-0 me-2">Selected columns 6/13</h5>
                            <a href="" class="text-sm">Clear selection</a>
                        </div>
                        <p class="text-sm">Order columns by drag-n-drop. Remove columns by clicking on the cross icon.</p>
                        <div class="d-flex flex-wrap gap-2">
                            <div class="tag bg-light px-2 py-1 rounded text-sm">
                                <span class="me-1">User Id</span>
                                <a href="" class="text-dark">
                                    <i icon-name="x"></i>
                                </a>
                            </div>
                            <div class="tag bg-light px-2 py-1 rounded text-sm">
                                <span class="me-1">Country</span>
                                <a href="" class="text-dark">
                                    <i icon-name="x"></i>
                                </a>
                            </div>
                            <div class="tag bg-light px-2 py-1 rounded text-sm">
                                <span class="me-1">Email</span>
                                <a href="" class="text-dark">
                                    <i icon-name="x"></i>
                                </a>
                            </div>
                            <div class="tag bg-light px-2 py-1 rounded text-sm">
                                <span class="me-1">Balance</span>
                                <a href="" class="text-dark">
                                    <i icon-name="x"></i>
                                </a>
                            </div>
                            <div class="tag bg-light px-2 py-1 rounded text-sm">
                                <span class="me-1">Type</span>
                                <a href="" class="text-dark">
                                    <i icon-name="x"></i>
                                </a>
                            </div>
                            <div class="tag bg-light px-2 py-1 rounded text-sm">
                                <span class="me-1">Status</span>
                                <a href="" class="text-dark">
                                    <i icon-name="x"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <label class="input-group-text" for="">Page limit</label>
                        <select class="form-select form-select-sm" name="">
                            <option selected>Choose...</option>
                            <option value="10">10</option>
                            <option value="20" selected>20</option>
                            <option value="30">30</option>
                        </select>
                    </div>
                    <div class="text-end pt-3">
                        <button type="button" class="btn btn-light btn-sm me-2" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success btn-sm">
                            <i icon-name="save"></i>
                            <span class="ms-1">Save changes</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>