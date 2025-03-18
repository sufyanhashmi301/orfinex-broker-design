<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="kyc-details-modal{{ $kyc->id }}" tabindex="-1" aria-labelledby="kyc-details-modal{{ $kyc->id }}ModalLabel" aria-modal="true" role="dialog" >
    <div class="modal-dialog modal-xl top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current" >
          
          <div class="flex items-center justify-between p-5 rounded-t">
            <h3 class="text-xl font-medium dark:text-white capitalize">
                {{ __('KYC Details') }}
            </h3>
            <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                <svg aria-hidden="true" class="w-5 h-5 dark:fill-white" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
          </div>
          <div class="p-6 pt-0">
            <ul class="grid md:grid-cold-2 grid-cols-2 gap-5">
                @foreach( $kyc->data as $key => $value)
                  <li class="dark:text-slate-300 dark:border-slate-700 last:border-b-0 pb-3 last:pb-0">
                    @php
                        if (empty((array)$value)) {
                            continue;
                        }
                    @endphp
                    @if(file_exists('assets/' . $value) || str_contains($value, 'amazonaws.com'))
                        <span class="block mb-2 capitalize">{{ str_replace('_', ' ', $key) }}</span>
                        <div class="h-[260px] bg-no-repeat bg-contain bg-center bg-slate-100 mb-2" style="background-image: url('{{ asset($value) }}')"></div>
                        <div class="flex justify-end gap-3">
                            <a href="{{ asset($value) }}" class="btn-link" download>{{ __('Download') }}</a>
                            <a href="{{ asset($value) }}" class="btn-link" target="_blank">{{ __('View') }}</a>
                        </div>
                    @endif
                  </li>
                @endforeach
            </ul>

            @foreach( $kyc->data as $key => $value)

              @php
                  if (empty((array)$value)) {
                      continue;
                  }
              @endphp

              @if(!file_exists('assets/' . $value) && !str_contains($value, 'amazonaws.com'))
              
                <div class="mt-2">
                  <b class="capitalize">{{ str_replace('_', ' ', $key) }}: </b> {{$value}}
                </div>
              @endif
            @endforeach
            <br>
            
            @if($kyc->status == \App\Enums\KycStatusEnums::PENDING)
              <form action="{{ route('admin.kyc.action') }}" method="post" class="space-y-5">
                @csrf
                <input type="hidden" name="id" value="{{ $kyc->id }}">
                <div class="input-area">
                  <label for="" class="form-label">{{ __('Detail Message') }}</label>
                  <input type="text" name="message" class="form-control mb-0" placeholder="Details Message">
                </div>
                <br>
                  <div class="action-btns text-right">

                    
                    <button type="submit" name="action" value="approve" class="btn btn-dark inline-flex items-center justify-center mr-2">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                        {{ __('Approve') }}
                    </button>
                    
                    
                    <button type="submit" name="action" value="reject" class="btn btn-danger inline-flex items-center justify-center">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                        Reject
                    </button>
                  </div>
                </form>
              @endif
          </div>

        </div>
    </div>
</div>


