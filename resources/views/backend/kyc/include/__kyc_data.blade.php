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
        @foreach( $kycCredential as $key => $value)
            <li class="dark:text-slate-300 border-b border-slate-100 dark:border-slate-700 last:border-b-0 pb-3 last:pb-0">
                <span class="block mb-2">{{ $key }}:</span>
                @if(file_exists('assets/'.$value))
                    <div class="h-[260px] bg-no-repeat bg-contain bg-center bg-slate-100 mb-2" style="background-image: url('{{ asset($value) }}')"></div>
                    <div class="flex justify-end gap-3">
                        <a href="{{ asset($value) }}" class="btn-link" download>{{ __('Download') }}</a>
                        <a href="{{ asset($value) }}" class="btn-link" target="_blank">{{ __('View') }}</a>
                    </div>
                @endif
            </li>
        @endforeach
    </ul>
    <form action="{{ route('admin.kyc.action.now') }}" method="post" class="space-y-5">
        @csrf
        <input type="hidden" name="id" value="{{ $id }}">
        <div class="input-area">
            <label for="" class="form-label">{{ __('Detail Message') }}</label>
            <input type="text" name="message" class="form-control mb-0" placeholder="Details Message">
        </div>
    
        <div class="action-btns text-right">
            @if($kycStatus < \App\Enums\KYCStatus::Level2->value)
            <button type="submit" name="approve" value="1" class="btn btn-dark inline-flex items-center justify-center mr-2">
                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                {{ __('Approve') }}
            </button>
            @endif
            @if($kycStatus !== \App\Enums\KYCStatus::Rejected->value)
                <button type="submit" name="reject" value="1" class="btn btn-danger inline-flex items-center justify-center">
                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                    {{ __('Reject') }}
                </button>
            @endif
        </div>
    </form>
</div>
