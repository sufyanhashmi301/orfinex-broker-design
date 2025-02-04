@extends('frontend::layouts.user')
@section('title')
    {{ __('Advance KYC') }}
@endsection
@section('content')
    <div class="card">
        {{-- @if ($sumsubstatus === 0)
            <div class="p-5">
                <p class="text-center font-medium dark:text-slate-300">
                    {{ __('Something went wrong.') }}
                </p>
            </div>
        @else --}}
            <div id="sumsub-websdk-container"></div>
        {{-- @endif --}}
    </div>
@endsection

@section('style')
    <style>
        #sumsub-websdk-container iframe {
            min-height: calc(100vh - 140px);
        }
    </style>
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
            if (payload.confirmed === true) {
                $.ajax({
                    url: "{{ route('user.verification.automatic_kyc.update') }}",
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

launchWebSdk('{{ auth()->user()->kyc_token }}.-v2')

</script>

{{-- if ('{{$sumsubstatus}}' === '1') {
    launchWebSdk('{{auth()->user()->kyc_token}}')
    // launchWebSdk('_act-sbx-jwt-eyJhbGciOiJub25lIn0.eyJqdGkiOiJfYWN0LXNieC1jMGQzMjg5MS05OTk5LTQzOGQtYjRjZC0xYmI5MjQ0ZmY4YzktdjIiLCJ1cmwiOiJodHRwczovL2FwaS5zdW1zdWIuY29tIn0.-v2')
} --}}

@endsection
