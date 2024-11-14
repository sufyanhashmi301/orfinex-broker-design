@extends('frontend::layouts.user')
@section('title')
    {{ __('Contracts') }}
@endsection
@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="card">
            <div class="card-body p-6">
                @include('frontend::contracts.include.__contract_template')
                <form action="{{ route('user.contract.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="contract_id" value="12">
                    <input type="hidden" id="signature64" name="signature">
                    <div class="text-right mt-10">
                        <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                            <iconify-icon class="text-lg mr-2" icon="lucide:check"></iconify-icon>
                            {{ __('Submit') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{--Modal for signature--}}
    @include('frontend::contracts.modal.__signature_modal')

@endsection
@section('script')
    <script src="{{ asset('global/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('global/js/jquery.ui.touch-punch.js') }}"></script>
    <script src="{{ asset('global/js/jquery.signature.min.js') }}"></script>
    <script !src="">
        var sig = $('#sig').signature({
            syncField: '#signature64',
            syncFormat: 'PNG'
        });
        $('#clear').click(function(e) {
            e.preventDefault();
            sig.signature('clear');
            $("#signature64").val('');
        });

        $('#saveSignature').click(function (e) {
            e.preventDefault();
            var signatureData = sig.signature('toDataURL');
            $('#signature_container > p').hide();
            $('#signatureImage').attr('src', signatureData).show();
            $('#signature64').val(signatureData);
            $('#signatureModal').modal('hide');
        });
    </script>
@endsection
