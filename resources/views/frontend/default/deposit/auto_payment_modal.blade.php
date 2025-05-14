<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="autoPaymentModal" tabindex="-1" aria-labelledby="autoPaymentModalLabel" aria-modal="true" role="dialog" data-backdrop="static" data-keyboard="false" >
    <div class="modal-dialog modal-xl top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current" >
          
          <div class="flex items-center justify-between p-5 rounded-t">
            <h3 class="text-xl font-medium dark:text-white capitalize">
                {{ __('Make Payment') }}
            </h3>
            <a href="{{ route('user.billing.index') }}"class="text-slate-700 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                      dark:hover:bg-slate-600 dark:hover:text-white">
               <b>Payment Completed? Check Status ></b>
            </a>
          </div>
          <div class="p-6 pt-0">

            <div id="iframe-container" style="position: relative; width: 100%; height: 600px">
              <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%)" id="loading-iframe">Loading...</div>
            </div>
            
           
          </div>

        </div>
    </div>
</div>


