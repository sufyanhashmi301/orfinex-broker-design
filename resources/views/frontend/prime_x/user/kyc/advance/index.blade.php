@extends('frontend::layouts.user')
@section('title')
    {{ __('Advance KYC') }}
@endsection
@section('content')
<div class="card">
    @if (auth()->user()->kyc >= kyc_completed_level())
    {{-- verification completed--}}
    <div class="p-5">
        <p class="text-center font-medium dark:text-slate-300">
            {{ __('Your Verification is completed') }}
        </p>
    </div>
    @elseif ($sumsubstatus === 0)
    {{-- sumsub deactivated --}}
    <div class="p-5">
        <p class="text-center font-medium dark:text-slate-300">
            {{ __('Something went wrong.') }}
        </p>
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
        .on('{{ __('idCheck.onApplicantStatusChanged') }}', (payload) => {
            console.log('{{ __('onStepCompleted') }}', payload);
            if (payload.confirmed === true && '{{auth()->user()->kyc}}' === '0') {
                $.ajax({
                    url: "{{ route('user.kyc.status') }}",
                    method: 'POST',
                    success: function(response) {
                        if (response.status ===200) {
                            console.log(response.success);
                        } else {
                            console.log(response.error);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('{{ __('Error updating KYC status:') }}', error);
                    }
                });
            }
        })
        .on('{{ __('idCheck.onError') }}', (error) => {
            console.log('{{ __('onError') }}', error);
        }).build();
    snsWebSdkInstance.launch('#sumsub-websdk-container');
}

if ('{{auth()->user()->kyc}}' === '0' && '{{$sumsubstatus}}' === '1') {
    launchWebSdk('{{auth()->user()->kyc_token}}')
    // launchWebSdk('_act-sbx-jwt-eyJhbGciOiJub25lIn0.eyJqdGkiOiJfYWN0LXNieC1jMGQzMjg5MS05OTk5LTQzOGQtYjRjZC0xYmI5MjQ0ZmY4YzktdjIiLCJ1cmwiOiJodHRwczovL2FwaS5zdW1zdWIuY29tIn0.-v2')
}
</script>
@endsection
