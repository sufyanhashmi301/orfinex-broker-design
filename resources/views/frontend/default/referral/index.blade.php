@extends('frontend::layouts.user')
@section('title')
    {{ __('Dashboard') }}
@endsection
@section('content')
    <div class="flex justify-between flex-wrap flex-col md:flex-row items-start md:items-center mb-6">
        <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">
            IB Dashboard
        </h4>
        <div>
            <ul class="nav nav-tabs flex flex-wrap list-none border-b-0 pl-0">
                <li class="nav-item">
                    <a href="{{ route('user.referral') }}" class="nav-link w-full block font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 md:px-4 pb-2 my-2 hover:border-transparent focus:border-transparent dark:text-slate-300 {{ isActive('user.referral') }}">
                        IB Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('user.referral.network') }}" class="nav-link w-full block font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 md:px-4 pb-2 my-2 hover:border-transparent focus:border-transparent dark:text-slate-300 {{ isActive('user.referral.network') }}">
                        Network
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('user.referral.advertisement.material') }}" class="nav-link w-full block font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 md:px-4 pb-2 my-2 hover:border-transparent focus:border-transparent dark:text-slate-300 {{ isActive('user.referral.advertisement.material') }}">
                        Resources
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('user.referral.reports') }}" class="nav-link w-full block font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 md:px-4 pb-2 my-2 hover:border-transparent focus:border-transparent dark:text-slate-300 {{ isActive('user.referral.reports') }}">
                        Reports
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">
                {{ __('Become a Introducing Broker') }}
            </h4>
        </div>
        <div class="card-body p-6">
            <form action="{{ route('user.ib-program.store') }}" method="POST" id="ib-from-create">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @foreach($ibQuestions as $ibQuestion)
                    @foreach(json_decode($ibQuestion->fields) as $field)
                        <div class="input-area relative">
                            <label class="form-label">{{ $field->name }}</label>
                            @if($field->type === 'text')
                                <input name="fields[{{ $ibQuestion->name }}]" class="form-control" type="text" value="">
                            @elseif($field->type === 'checkbox')
                                @foreach($field->options as $index=>$option)
                                    <div class="checkbox-area">
                                        <label for="flexCheckDefault{{$index}}" class="inline-flex items-center cursor-pointer">
                                            <input class="hidden" type="checkbox" name="fields[{{ $ibQuestion->name }}][]"
                                                value="{{ $option }}" id="flexCheckDefault{{$index}}" required
                                            />
                                            <span class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                                <img src="{{ asset('frontend/images/icon/ck-white.svg') }}" alt="" class="h-[10px] w-[10px] block m-auto opacity-0">
                                            </span>
                                            <span class="text-slate-500 dark:text-slate-400 text-sm leading-6">
                                                {{ $option }}
                                            </span>
                                        </label>
                                    </div>
                                @endforeach
                                @elseif($field->type === 'radio')
                                    <div class="basicRadio">
                                        @foreach($field->options as $option)
                                            <label for="" class="flex items-center cursor-pointer">
                                                <input type="radio" id="active" class="hidden" name="fields[{{ $ibQuestion->name }}]" checked="" value="{{ $option }}">
                                                <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                                <span class="text-secondary-500 text-sm leading-6 capitalize">
                                                    {{ $option }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                @elseif($field->type === 'dropdown')
                                    <select name="fields[{{ $ibQuestion->name }}]" class="select2 form-control w-full mt-2 py-2">
                                        @foreach($field->options as $option)
                                            <option value="{{ $option }}" class="inline-block font-Inter font-normal text-sm text-slate-600"">{{ $option }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>

                    @endforeach
                @endforeach

                    <div class="md:col-span-2">
                        <div class="text-right">
                            <button type="button" class="btn btn-dark save-btn">Register</button>
                        </div>
                    </div>
                </form>
            </form>
        </div>
    </div>

    @if(request()->routeIs('user.referral'))
        @include('frontend.default.referral.include.__dashboard')
        @include('frontend.default.referral.modal.__qr_code')
    @endif
    @if(request()->routeIs('user.referral.advertisement.material'))
        @include('frontend.default.referral.include.__advertisement_material')
    @endif
    @if(request()->routeIs('user.referral.network'))
        @include('frontend.default.referral.include.__network')
    @endif
    @if(request()->routeIs('user.referral.reports'))
        @include('frontend.default.referral.include.__reports')
    @endif
    {{--    @if(!isset(auth()->user()->ib_status))--}}
        {{-- IB account modal --}}
        @include('frontend.default.referral.modal.__ib_form')
    {{--    @endif--}}

@endsection
@section('script')
    <script>
        $('body').on('click', '.save-btn', function () {
            var btn = $(this);
            btn.prop('disabled', true);
            let form = document.querySelector('#ib-from-create');
            let formData = new FormData(form);
            // formData.append('amount', $('#active_wallet_amount').val());
            console.log('aa');
            var url = $('#ib-from-create').attr('action');
            submit_form(formData, btn, url, '', 'ibForm');
        });

        function copyRef() {
            /* Get the text field */
            var copyApi = document.getElementById("refLink");
            /* Select the text field */
            copyApi.select();
            copyApi.setSelectionRange(0, 999999999); /* For mobile devices */
            /* Copy the text inside the text field */
            document.execCommand('copy');
            $('#copy').text($('#copied').val())
        }
    </script>
@endsection
