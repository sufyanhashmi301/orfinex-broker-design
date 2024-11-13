<div class="flex items-center justify-between mb-5">
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
<ul class="divide-y divide-slate-100 dark:divide-slate-700 border border-slate-100 dark:border-slate-700 rounded mb-5">
    <li class="list-group-item dark:text-slate-300 block py-2 px-3">
        {{ __('Withdraw Amount') }}: <strong>{{ $data->amount .' '. $currency }}</strong>
    </li>
    <li class="list-group-item dark:text-slate-300 block py-2 px-3">
        {{ __('Pay Amount') }}: <strong>{{ $data->pay_amount .' '. $data->pay_currency }}</strong>
    </li>
</ul>

<ul class="divide-y divide-slate-100 dark:divide-slate-700 border border-slate-100 dark:border-slate-700 rounded mb-5">
    @foreach( json_decode($data->manual_field_data,true) as $name => $data)
        <li class="list-group-item dark:text-slate-300 block py-2 px-3">
            {{ $name }}: @if( $data['type'] == 'file' )
                <img src="{{ asset($data['value']) }}" alt=""/>
            @else
                <strong>{{ $data['value'] }}</strong>
            @endif
        </li>
    @endforeach
</ul>

<form action="{{ route('admin.withdraw.action.now') }}" method="post" class="space-y-5">
    @csrf
    <input type="hidden" name="id" value="{{ $id }}">

    <div class="input-area">
        <label for="" class="form-label">{{ __('Details Message(Optional)') }}</label>
        <textarea name="message" class="form-control mb-0" rows="6" placeholder="Details Message"></textarea>
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
