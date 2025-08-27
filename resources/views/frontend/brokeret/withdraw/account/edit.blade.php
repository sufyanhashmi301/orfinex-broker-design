@extends('frontend::user.setting.index')
@section('title')
    {{ __('Edit Withdraw Account') }}
@endsection
@section('settings-content')
    <div class="rounded-2xl border border-gray-200 bg-white px-5 pb-5 pt-5 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6 sm:pt-5">
        <div class="flex flex-col gap-5 mb-6 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    {{ __('Edit Withdraw Account') }}
                </h3>
                <p class="mt-1 text-gray-500 text-theme-sm dark:text-gray-400">
                    {{ __("Update your withdrawal payment method details and settings.") }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('user.withdraw.account.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m12 19-7-7 7-7m7 7H5"/>
                    </svg>
                    {{ __('Back to Accounts') }}
                </a>
            </div>
        </div>
        <form action="{{ route('user.withdraw.account.update',$withdrawAccount->id) }}" method="post" enctype="multipart/form-data">
            @method('PUT')
            @csrf

            <input type="hidden" name="withdraw_method_id" value="{{$withdrawAccount->withdraw_method_id}}">
            
            <!-- Form Fields -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 selectMethodRow">
                <div class="input-area">
                    <label for="method_name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        {{ __('Method Name') }}
                    </label>
                    <input type="text" 
                        name="method_name" 
                        id="method_name"
                        class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm placeholder-gray-500 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-400 dark:focus:border-brand-400 dark:focus:ring-brand-400dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                        placeholder="{{ __('eg. Withdraw Method - USD') }}"
                        value="{{ $withdrawAccount->method_name }}"
                        required>
                </div>

                @foreach( json_decode($withdrawAccount->credentials, true) as $key => $field)
                    @if($field['type'] == 'file')
                        <input type="hidden" name="credentials[{{ $key }}][type]" value="{{ $field['type'] }}">
                        <input type="hidden" name="credentials[{{ $key }}][validation]" value="{{ $field['validation'] }}">

                        <div class="input-area">
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                {{ $key }}
                            </label>
                            <input
                                type="file"
                                name="credentials[{{ $key}}][value]"
                                id="{{ $key }}"
                                class="focus:border-ring-brand-300 shadow-theme-xs focus:file:ring-brand-300 h-11 w-full overflow-hidden rounded-lg border border-gray-300 bg-transparent text-sm text-gray-500 transition-colors file:mr-5 file:border-collapse file:cursor-pointer file:rounded-l-lg file:border-0 file:border-r file:border-solid file:border-gray-200 file:bg-gray-50 file:py-3 file:pr-3 file:pl-3.5 file:text-sm file:text-gray-700 placeholder:text-gray-400 hover:file:bg-gray-100 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:text-white/90 dark:file:border-gray-800 dark:file:bg-white/[0.03] dark:file:text-gray-400 dark:placeholder:text-gray-400"
                                accept=".gif, .jpg, .png"
                                @if($field['value'] == "" && $field['validation'] == 'required') required @endif
                            />
                        </div>
                    @elseif($field['type'] == 'textarea')
                        <input type="hidden" name="credentials[{{ $key}}][type]" value="{{ $field['type'] }}">
                        <input type="hidden" name="credentials[{{ $key}}][validation]" value="{{ $field['validation'] }}">

                        <div class="input-area relative col-span-12">
                            <label for="" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                {{ $key }}
                            </label>
                            <textarea 
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                rows="5"
                                @if($field['validation'] == 'required') required @endif 
                                placeholder="{{ __('Send Money Note') }}"
                                name="credentials[{{$key}}][value]">{{$field['value']}}</textarea>
                        </div>

                    @else
                        <input type="hidden" name="credentials[{{ $key}}][type]" value="{{ $field['type'] }}">
                        <input type="hidden" name="credentials[{{ $key}}][validation]" value="{{ $field['validation'] }}">

                        <div class="input-area relative">
                            <label for="" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                {{ $key }}
                            </label>
                            <input type="text" name="credentials[{{ $key}}][value]"
                                value="{{ $field['value'] }}"
                                @if($field['validation'] == 'required') required @endif 
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" 
                                aria-label="Amount" id="amount"
                                aria-describedby="basic-addon1">
                        </div>
                    @endif

                @endforeach
            </div>
            <div class="buttons text-right mt-6">
                <button type="submit" class="bg-brand-500 hover:bg-brand-600 flex w-full items-center justify-center gap-2 rounded-lg px-4 py-3 text-sm font-medium text-white xl:w-auto">
                    {{ __('Update Withdraw Account') }}
                    <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M17.4175 9.9986C17.4178 10.1909 17.3446 10.3832 17.198 10.53L12.2013 15.5301C11.9085 15.8231 11.4337 15.8233 11.1407 15.5305C10.8477 15.2377 10.8475 14.7629 11.1403 14.4699L14.8604 10.7472L3.33301 10.7472C2.91879 10.7472 2.58301 10.4114 2.58301 9.99715C2.58301 9.58294 2.91879 9.24715 3.33301 9.24715L14.8549 9.24715L11.1403 5.53016C10.8475 5.23717 10.8477 4.7623 11.1407 4.4695C11.4336 4.1767 11.9085 4.17685 12.2013 4.46984L17.1588 9.43049C17.3173 9.568 17.4175 9.77087 17.4175 9.99715C17.4175 9.99763 17.4175 9.99812 17.4175 9.9986Z" fill=""></path>
                    </svg>
                </button>
            </div>
        </form>
    </div>
@endsection
@section('script')
    <script>
        $("#selectMethod").on('change',function (e) {
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
