@extends('frontend::layouts.user')
@section('title')
    {{ __('Withdraw Account Create') }}
@endsection
@section('content')
    <div class="flex justify-end flex-wrap items-center mb-5">
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            <a href="{{ route('user.withdraw.account.index') }}" class="btn btn-primary inline-flex items-center">
                {{ __('Withdraw Accounts') }}
            </a>
        </div>
    </div>
    <div class="grid grid-cols-12 gap-5">
        <div class="col-span-12">
            <div class="card">
                <div class="card-body p-6">
                    <div class="progress-steps-form">
                        <form action="{{ route('user.withdraw.account.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-5 selectMethodRow">
                                <div class="input-area relative selectMethodCol">
                                    <label for="exampleFormControlInput1" class="form-label">
                                        {{ __('Choice Method:') }}
                                    </label>
                                    <div class="input-group select2-lg">
                                        <select name="withdraw_method_id" id="selectMethod" class="select2 form-control !text-lg w-full mt-2 py-2">
                                            <option selected>{{ __('Select Method') }}</option>
                                            @foreach($withdrawMethods as $raw)
                                                <option value="{{ $raw->id }}">{{ $raw->name }}
                                                    ({{ ucwords($raw->type) }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="buttons text-right mt-4">
                                <button type="submit" class="btn inline-flex justify-center btn-dark">
                                    {{ __('Add New Withdraw Account') }}
                                    <i class="anticon anticon-double-right"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $("#selectMethod").on('change', function (e) {
            "use strict"
            e.preventDefault();

            //$('.manual-row').empty();
            $('.selectMethodRow').children().not(':first').remove();

            var id = $(this).val()

            var url = '{{ route("user.withdraw.method",":id") }}';
            url = url.replace(':id', id);
            $.get(url, function (data) {
                $(data).insertAfter(".selectMethodCol");
                imagePreview()
            })
        })
    </script>
@endsection
