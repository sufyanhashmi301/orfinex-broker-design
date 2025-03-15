@extends('frontend::layouts.user')
@section('title')
{{ __('Advance KYC') }}
@endsection
@section('content')
<div class="mb-5">
    <ul class="m-0 p-0 list-none">
        <li class="inline-block relative top-[3px] text-base text-primary font-Inter ">
            <a href="{{route('user.dashboard')}}">
                <iconify-icon icon="heroicons-outline:home"></iconify-icon>
                <iconify-icon icon="heroicons-outline:chevron-right" class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
            </a>
        </li>
        <li class="inline-block relative text-sm text-primary font-Inter ">
            {{ __('KYC') }}
            <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
        </li>
        <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
            {{ __('Advance') }}
        </li>
    </ul>
</div>
<div class="card">
    @if (auth()->user()->kyc === 1)
    {{-- verification completed--}}
    <div class="p-5">
        <p class="text-center"><b>Your Verification is completed</b></p>
    </div>
    @elseif ($sumsubstatus === 0)
    {{-- sumsub deactivated --}}
    <div class="p-5">
        <p class="text-center"><b>Somthing went wrong.</b></p>
    </div>
    @else
    {{-- Sumsub account verification --}}
    <div id="sumsub-websdk-container"></div>
    @endif
</div>
@endsection
@section('script')

<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

const launchWebSdk = (accessToken) => {
    let snsWebSdkInstance = snsWebSdk.init(
        accessToken,
        () => this.getNewAccessToken()
    ).withConf({
        lang: 'en',
        email: '{{auth()->user()->email}}'
    }).withOptions({ addViewportTag: false, adaptIframeHeight: true })
        .on('idCheck.onApplicantStatusChanged', (payload) => {
            console.log('onStepCompleted', payload);
            if (payload.confirmed === true && '{{auth()->user()->kyc}}' === '0') {
                $.ajax({
                    url: "{{ route('user.kyc.status') }}",
                    method: 'POST',
                    success: function(response) {
                        if (response.status ===200) {
                            console.log(response.success);
                        }else{
                            console.log(response.error);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error updating KYC status:', error);
                    }
                });
            }
        })
        .on('idCheck.onError', (error) => {
            console.log('onError', error);
        }).build();
    snsWebSdkInstance.launch('#sumsub-websdk-container');
}

if ('{{auth()->user()->kyc}}' === '0' && '{{$sumsubstatus}}' === '1') {
    launchWebSdk('{{auth()->user()->kyc_token}}')
    // launchWebSdk('_act-sbx-jwt-eyJhbGciOiJub25lIn0.eyJqdGkiOiJfYWN0LXNieC1jMGQzMjg5MS05OTk5LTQzOGQtYjRjZC0xYmI5MjQ0ZmY4YzktdjIiLCJ1cmwiOiJodHRwczovL2FwaS5zdW1zdWIuY29tIn0.-v2')
}
</script>
@endsection
