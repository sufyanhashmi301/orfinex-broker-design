@extends('frontend::layouts.user')
@section('title')
    {{ __('Contracts') }}
@endsection
@section('content')
    <div id="sig" class="h-[200px]"></div>
    <textarea id="signature64" name="signature" style="display: none"></textarea>
    <div class="text-right mt-3">
        <button class="btn btn-sm btn-warning inline-flex items-center justify-center" id="clear">
            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2 font-light" icon="lsicon:clear-outline"></iconify-icon>
            {{ __('Clear Signature') }}
        </button>
    </div>
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
    </script>
@endsection
