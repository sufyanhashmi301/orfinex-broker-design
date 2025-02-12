<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="configureAmazonS3" tabindex="-1" aria-labelledby="configureAmazonS3" aria-hidden="true">
    <div class="modal-dialog modal-lg top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <div class="modal-body popup-body">
                <div class="flex items-start justify-between gap-3 p-5">
                    <h5 class="modal-title" id="configureAmazonS3Label">Configure AWS Amazon S3</h5>
                    <button type="button"
                        class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                        data-bs-dismiss="modal">
                        <svg aria-hidden="true" class="w-5 h-5 dark:fill-white" fill="#000000" viewbox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="p-6 pt-0 edit-plugin-section">
                    <form action="{{ route('admin.settings.storage.store') }}" method="post" class="space-y-3">
                        @csrf 
                        <input type="hidden" name="method" value="aws_amazon_s3">
                        
                        <div class="input-area">
                            <label class="form-label" for="">AWS Key</label>
                            <input type="text" name="aws_key" value="{{ $aws_storage_method->details['aws_key'] ?? '' }}" class="form-control" placeholder="####" required />
                        </div>

                        <div class="input-area">
                          <label class="form-label" for="">AWS Secret</label>
                          <input type="text" name="aws_secret" value="{{ $aws_storage_method->details['aws_secret'] ?? '' }}" class="form-control" placeholder="####" required />
                        </div>

                        <div class="input-area">
                          <label class="form-label" for="">AWS Bucket</label>
                          <input type="text" name="aws_bucket" value="{{ $aws_storage_method->details['aws_bucket'] ?? '' }}" class="form-control" placeholder="awsbucketname" required />
                        </div>

                        <div class="input-area">
                            <label class="form-label" for="">AWS Region</label>
                            <select name="aws_region" id="" class="form-control" required>
                                  
                              <option value="" {{ count($aws_storage_method->details) == 0 ? 'selected' : '' }} disabled hidden>-- Select AWS Region --</option>
                              @foreach ($aws_regions as $regionCode => $regionName)
                                  <option value="{{ $regionCode }}" {{ isset($aws_storage_method->details['aws_region']) && $aws_storage_method->details['aws_region'] == $regionCode ? 'selected' : '' }}>
                                      {{ $regionName }}
                                  </option>
                              @endforeach
                            </select>

                        </div>
                        
                        <div class="input-area text-right">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                                {{ __('Save Changes') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
