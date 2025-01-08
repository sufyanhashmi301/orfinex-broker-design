<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="config-modal" tabindex="-1" aria-labelledby="config-modalModalLabel" aria-modal="true"
    role="dialog">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div
            class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
            <div class="flex items-center justify-between p-5">
                <h3 class="text-xl font-medium dark:text-white capitalize" id="config-modalLabel">
                    Configure Template
                </h3>
                <button type="button"
                    class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                          dark:hover:bg-slate-600 dark:hover:text-white"
                    data-bs-dismiss="modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="#000000" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                              11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="modal-body p-6 pt-0">
                  <h6>Name</h6>
                  <div class="site-input-area relative">
                    <label for="" class="form-label">Mention</label>
                    <select name="name_mention" id="" class="form-control">
                      <option value="full_name" {{ $certificate->config['name_mention'] == 'full_name' ? 'selected' : '' }}>Full Name</option>
                      <option value="first_name" {{ $certificate->config['name_mention'] == 'first_name' ? 'selected' : '' }}>First Name</option>
                    </select>
                  </div>  
                  <div class="site-input-area relative">
                    <label for="" class="form-label">Font Size <span style="text-transform: lowercase">(px)</span></label>
                    <input type="number" name="name_font_size" max="100" min="10" value="{{ +$certificate->config['name_font_size'] }}" class="form-control">
                  </div>  
                  <div class="site-input-area relative">
                    <label for="" class="form-label">Font Color</label>
                    <input type="color" name="name_font_color" class="form-control" value="{{ $certificate->config['name_font_color'] }}" style="height: 39px">
                  </div>  

                  <hr style="margin-top: 20px; margin-bottom: 20px">

                  <h6>Date</h6>
                  <div class="site-input-area relative">
                    <label for="" class="form-label">Font Size <span style="text-transform: lowercase">(px)</span></label>
                    <input type="number" name="date_font_size" max="100" min="10" value="{{ +$certificate->config['date_font_size'] }}" class="form-control">
                  </div>  
                  <div class="site-input-area relative">
                    <label for="" class="form-label">Font Color</label>
                    <input type="color" name="date_font_color" class="form-control" value="{{ $certificate->config['date_font_color'] }}" style="height: 39px">
                  </div>  


                  <input type="hidden" class="coordinate-x-name" name="coordinate_x_name" value="{{ $certificate->config['coordinate_x_name'] ?? 0.00 }}">
                  <input type="hidden" class="coordinate-y-name" name="coordinate_y_name" value="{{ $certificate->config['coordinate_y_name'] ?? 0.00 }}">
                  <input type="hidden" class="coordinate-x-date" name="coordinate_x_date" value="{{ $certificate->config['coordinate_x_date'] ?? 0.00 }}">
                  <input type="hidden" class="coordinate-y-date" name="coordinate_y_date" value="{{ $certificate->config['coordinate_y_date'] ?? 0.00 }}">
                  <input type="hidden" name="certificate_id" value="{{ $certificate->id }}">
                
                <div class="action-btns text-right mt-3">
                    <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                        Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>