<div class="flex items-center justify-between p-5">
    <h3 class="text-xl font-medium dark:text-white capitalize">
        {{ __('Withdraw Approval Action') }}
    </h3>
    <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
        <svg aria-hidden="true" class="w-5 h-5 dark:fill-white" fill="#000000" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                        11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
        </svg>
        <span class="sr-only">Close modal</span>
    </button>
</div>
<div class="max-h-[calc(100vh-200px)] overflow-y-auto p-6">
    <ul class="divide-y divide-slate-100 dark:divide-slate-700 border border-slate-100 dark:border-slate-700 rounded mb-5">
        <li class="list-group-item dark:text-slate-300 block py-2 px-3">
            <span class="mr-2">{{ __('Account:') }}</span>
            <span class="font-medium">{{ $data->target_id}}</span>
        </li>
        <li class="list-group-item dark:text-slate-300 block py-2 px-3">
            <span class="mr-2">{{ __('Withdraw Amount:') }}</span>
            <span class="font-medium">{{ $data->amount .' '. $currency }}</span>
        </li>
        <li class="list-group-item dark:text-slate-300 block py-2 px-3">
            <span class="mr-2">{{ __('Pay Amount:') }}</span>
            <span class="font-medium">{{ $data->pay_amount .' '. $data->pay_currency }}</span>
        </li>
    </ul>

    <ul class="divide-y divide-slate-100 dark:divide-slate-700 border border-slate-100 dark:border-slate-700 rounded mb-5">
        @foreach( json_decode($data->manual_field_data,true) as $name => $field_data)
            <li class="list-group-item dark:text-slate-300 block py-2 px-3">
                <span class="mr-2">{{ $name }}:</span>
                @if( $field_data['type'] == 'file' )
                    <div class="h-[260px] bg-no-repeat bg-contain bg-center bg-slate-100 my-2" style="background-image: url('{{ asset($field_data['value']) }}')"></div>
                    <div class="flex justify-end gap-3">
                        <a href="{{ asset($field_data['value']) }}" class="btn-link" download>{{ __('Download') }}</a>
                        <a href="{{ asset($field_data['value']) }}" class="btn-link" target="_blank">{{ __('View') }}</a>
                    </div>
                @else
                    <span class="font-medium">{{ $field_data['value'] }}</span>
                @endif
            </li>
        @endforeach
    </ul>
    <form action="{{ route('admin.withdraw.action.now') }}" method="post" class="space-y-5">
        @csrf
        <input type="hidden" name="id" value="{{ $id }}">

        <div class="input-area">
            <label for="" class="form-label">{{ __('Detail Message') }}</label>
            <textarea name="message" class="form-control mb-0" rows="6" placeholder="Details Message">{{ $data->approval_cause }}</textarea>
        </div>

        <div class="action-btns text-right">
            <button type="submit" name="approve" value="yes" class="btn btn-dark inline-flex items-center justify-center mr-2">
                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                {{ __('Approve') }}
            </button>
            <button type="submit" name="reject" value="yes" class="btn btn-danger inline-flex items-center justify-center">
                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                {{ __('Reject') }}
            </button>
        </div>

    </form>
</div>
