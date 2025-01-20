<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="view-certificate-image-modal{{$certificate->id}}" tabindex="-1"  aria-modal="true"
  role="dialog">
  <div class="modal-dialog top-1/2 !-translate-y-1/2 relative modal-lg w-auto pointer-events-none">
      <div
          class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
          <div class="flex items-center justify-between p-5">
              <h3 class="text-xl font-medium dark:text-white capitalize" id="view-certificate-image-modalLabel{{$certificate->id}}">
                  View Certificate
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
            <img src="{{  asset($certificate->certificate_image) }}"  width="1080" height="1080" alt="">

                  
            <div class="action-btns text-right mt-3">
                <button type="button" class="btn btn-dark inline-flex items-center justify-center" data-bs-dismiss="modal">
                    Close
                </button>
            </div>
              
          </div>
      </div>
  </div>
</div>