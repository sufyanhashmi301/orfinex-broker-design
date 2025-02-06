@extends('frontend::layouts.user')
@section('title')
    {{ __('Advance KYC') }}
@endsection
@section('content')
    <div class="card">
        @if ($sumsub_status === 0)
            <div class="p-5">
                <p class="text-center font-medium dark:text-slate-300">
                    {{ __('Something went wrong.') }}
                </p>
            </div>
        @else
            <div id="sumsub-websdk-container"></div>
        @endif
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

function launchWebSdk(accessToken) {
  let snsWebSdkInstance = snsWebSdk.init(
      accessToken,
      // token update callback, must return Promise
      () => this.getNewAccessToken()
    )
    .withConf({
      //language of WebSDK texts and comments (ISO 639-1 format)
      lang: 'en',
    })
    .on('onError', (error) => {
      console.log('onError', error)
    })
    .onMessage((type, payload) => {
      console.log('onMessage', type, payload)

      // Check if the message type is 'idCheck.onApplicantStatusChanged'
      if (type === 'idCheck.onApplicantStatusChanged') {
        // Send AJAX request only for this specific event
        $.ajax({
          url: "{{ route('user.verification.automatic_kyc.update') }}",
          method: 'GET',
          data: payload.reviewResult,
          success: function(response) {
            console.log(response)
          },
          error: function(xhr, status, error) {
            console.error('Error updating KYC status', error);
          }
        });
      }

    })
    .build();

  // you are ready to go:
  // just launch the WebSDK by providing the container element for it
  
  snsWebSdkInstance.launch('#sumsub-websdk-container')
}
if ('{{ $sumsub_status }}' === '1') {
    launchWebSdk('{{ auth()->user()->kyc_token }}')
}

</script>

@endsection
