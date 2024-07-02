<html>

<head>
    <title>Test</title>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <script src="https://static.sumsub.com/idensic/static/sns-websdk-builder.js"></script>

    <style>
        .custom-btn {
            display: flex;
            align-content: center;
            justify-content: center;
            background-color: rgba(4, 104, 254, 0.979);
            padding: 14px 20px;
            color: white;
            border: none;
            box-shadow: none;
            cursor: pointer;

            border-radius: 10px;
        }
    </style>
</head>

<body>
    <div id="sumsub-websdk-container"></div>
    <button type="button" id="sumsub-verification" class="custom-btn">Verify Account</button>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        // $.ajaxSetup({
        //     headers: {
        //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //     }
        // });

        // $("#sumsub-verification").click(function(e) {
        //     e.preventDefault();

        //     $.ajax({
        //         type: 'POST',
        //         url: "{{ route('Sumsubtest') }}",
        //         success: function(data) {
        //             if (data.status == 200) {
        //                 console.log(data.user.token);
        //                 launchWebSdk(data.user.token)
        //             } else {
        //                 alert('Somthing went wrong');
        //             }
        //         }
        //     });
        // });
launchWebSdk("_act-sbx-jwt-eyJhbGciOiJub25lIn0.eyJqdGkiOiJfYWN0LXNieC05Y2NlNzlmMS1jNjc3LTQ4Y2YtOTQ3OS1mMjUzMTVkYTg5ODctdjIiLCJ1cmwiOiJodHRwczovL2FwaS5zdW1zdWIuY29tIn0.-v2")
function launchWebSdk(accessToken) {
    let snsWebSdkInstance = snsWebSdk.init(
            accessToken,
            // token update callback, must return Promise
            () => this.getNewAccessToken()
        )
        .withConf({
            lang: 'en',
            email: '{{auth()->user()->email}}'
        })
        .on('onError', (error) => {
          console.log('onError', payload)
        })
        .onMessage((type, payload) => {
          console.log('onMessage', type, payload)
        })
        .build();
    snsWebSdkInstance.launch('#sumsub-websdk-container')
}
    </script>

</body>

</html>
