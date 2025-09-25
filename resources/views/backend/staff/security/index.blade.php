@extends('backend.layouts.app')
@section('title')
    {{ __('Manage Staff') }}
@endsection
@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="flex justify-between flex-wrap items-center mb-6">
            <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
                {{ __('Manage 2FA Security') }}
            </h4>
            <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                <a href="{{ url()->previous() }}" class="btn btn-sm btn-primary inline-flex items-center justify-center">
                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:corner-down-left"></iconify-icon>
                    {{ __('Back') }}
                </a>
            </div>
        </div>
        @include('backend.staff.include.__two_fa')
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            let $inputs = $(".otp-input");

            // move to next on input
            $inputs.on("input", function() {
                let $this = $(this);
                let value = $this.val();

                if (value.length === 1) {
                    $this.next(".otp-input").focus();
                }

                updateHiddenInput();
            });

            // move to prev on backspace
            $inputs.on("keydown", function(e) {
                if (e.key === "Backspace" && !$(this).val()) {
                    $(this).prev(".otp-input").focus();
                }
            });

            // allow paste of full OTP
            $inputs.first().on("paste", function(e) {
                let paste = (e.originalEvent || e).clipboardData.getData('text').trim();
                if (/^\d+$/.test(paste) && paste.length === $inputs.length) {
                    $inputs.each(function(i) {
                        $(this).val(paste[i]);
                    });
                    updateHiddenInput();
                }
            });

            function updateHiddenInput() {
                let otp = "";
                $inputs.each(function() {
                    otp += $(this).val();
                });
                $("#one_time_password").val(otp);
            }
        });
    </script>
@endsection
