<!-- Modal -->
<div class="modal fade" id="stepRules" tabindex="-1" aria-labelledby="stepRulesLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content site-table-modal">
            <div class="modal-header">
                <h5 class="modal-title" id="stepRulesLabel">Add Step Rules</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="site-input-groups mb-3">
                                <label for="" class="box-input-label">Initial Deposit</label>
                                <input type="text" class="box-input mb-0" name="" placeholder="1000 USD">
                            </div>
                        </div>
                        <div class="col-xl-12">
                            <div class="site-input-groups mb-3">
                                <label for="" class="box-input-label">{{__('Profit Target')}}</label>
                                <input type="text" class="box-input mb-0" name="" placeholder="1000 USD">
                            </div>
                        </div>
                        <div class="col-xl-12">
                            <div class="site-input-groups mb-3">
                                <label for="" class="box-input-label">{{__('Daily Drawdown')}}</label>
                                <input type="text" class="box-input mb-0" name="" placeholder="1000 USD">
                            </div>
                        </div>
                        <div class="col-xl-12">
                            <div class="site-input-groups mb-3">
                                <label class="box-input-label" for="">{{__('Daily Drawdown Type')}}</label>
                                <select class="form-select mb-0" name="">
                                    <option value="">{{__('From Max Balance or')}}</option>
                                    <option value="">{{__('From Max Equity')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-12">
                            <div class="site-input-groups mb-3">
                                <label for="" class="box-input-label">{{__('Max Drawdown')}}</label>
                                <input type="text" class="box-input mb-0" name="" placeholder="1000 USD">
                            </div>
                        </div>
                        <div class="col-xl-12">
                            <div class="site-input-groups mb-3">
                                <label class="box-input-label" for="">{{__('Daily Drawdown Type')}}</label>
                                <select class="form-select mb-0" name="">
                                    <option value="">{{__('From Max Balance or')}}</option>
                                    <option value="">{{__('From Max Equity')}}</option>
                                    <option value="">{{__('From Assigned Balance')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-12">
                            <div class="site-input-groups mb-3">
                                <label for="" class="box-input-label">{{__('Max Trade Allowed')}}</label>
                                <input type="number" class="box-input mb-0" name="" placeholder="10">
                            </div>
                        </div>
                        <div class="col-xl-12">
                            <div class="site-input-groups mb-3">
                                <label for="" class="box-input-label">{{__('Min Trading Days')}}</label>
                                <input type="number" class="box-input mb-0" name="" placeholder="10">
                            </div>
                        </div>
                        <div class="col-xl-12">
                            <div class="site-input-groups mb-3">
                                <label for="" class="box-input-label">{{__('Min Trading Days')}}</label>
                                <input type="number" class="box-input mb-0" name="" placeholder="10">
                            </div>
                        </div>
                        <div class="col-xl-12">
                            <div class="site-input-groups mb-3">
                                <label class="box-input-label" for="">{{__('Stop Loss')}}</label>
                                <select class="form-select mb-0" name="">
                                    <option value="">{{__('Must')}}</option>
                                    <option value="">{{__('Mix')}}</option>
                                    <option value="">{{__('No Check')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-12">
                            <div class="site-input-groups mb-3">
                                <label class="box-input-label" for="">{{__('Take Profit')}}</label>
                                <select class="form-select mb-0" name="">
                                    <option value="">{{__('Must')}}</option>
                                    <option value="">{{__('Mix')}}</option>
                                    <option value="">{{__('No Check')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-12">
                            <div class="site-input-groups mb-3">
                                <label for="" class="box-input-label">{{__('Expiry Period')}}</label>
                                <input type="number" class="box-input mb-0" name="" placeholder="10">
                            </div>
                        </div>
                    </div>
                    <div class="action-btns mt-4">
                        <button type="submit" class="site-btn primary-btn w-100">
                            {{ __('Add Rules') }}
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
