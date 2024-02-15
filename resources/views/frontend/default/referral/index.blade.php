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
                    <a href="{{ route('user.referral') }}"
                       class="nav-link w-full block font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 md:px-4 pb-2 my-2 hover:border-transparent focus:border-transparent dark:text-slate-300 {{ isActive('user.referral') }}">
                        IB Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('user.referral.network') }}"
                       class="nav-link w-full block font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 md:px-4 pb-2 my-2 hover:border-transparent focus:border-transparent dark:text-slate-300 {{ isActive('user.referral.network') }}">
                        Network
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('user.referral.advertisement.material') }}"
                       class="nav-link w-full block font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 md:px-4 pb-2 my-2 hover:border-transparent focus:border-transparent dark:text-slate-300 {{ isActive('user.referral.advertisement.material') }}">
                        Resources
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('user.referral.reports') }}"
                       class="nav-link w-full block font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 md:px-4 pb-2 my-2 hover:border-transparent focus:border-transparent dark:text-slate-300 {{ isActive('user.referral.reports') }}">
                        Reports
                    </a>
                </li>
            </ul>
        </div>
    </div>


    @if(request()->routeIs('user.referral'))
        @if(auth()->user()->ib_status == \App\Enums\IBStatus::APPROVED && auth()->user()->ibQuestionAnswers)
            @include('frontend.default.referral.include.__dashboard')
            @include('frontend.default.referral.modal.__qr_code')
        @elseif(auth()->user()->ib_status == \App\Enums\IBStatus::PENDING)
            <div class="card">
                <div class="card-body p-6">
                    <div class="progress-steps-form">
                        <div class="transaction-status text-center px-7 py-12">
                            <div
                                class="icon h-20 w-20 bg-warning-500 text-warning-500 bg-opacity-30 rounded-full flex flex-col items-center justify-center mx-auto">
                                <iconify-icon icon="icomoon-free:hour-glass" class="text-4xl"></iconify-icon>
                            </div>
                            <h2 class="text-3xl my-5">Partner Request Pending</h2>
                            <p class="text-sm mb-3 dark:text-white">
                                Your partnership request is under review and we'll confirm with you shortly. Stay tuned!
                            </p>
                            <a href="{{setting('IB_partner_agreement_link','document_links',false)}}" target="_blank" class="btn btn-light inline-flex items-center justify-center mr-2">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="carbon:document"></iconify-icon>
                                <span>Read Partner Agreement</span>
                            </a>
                            <a href="{{setting('trust_pilot_review_link','platform_links','javascript:void(0);')}}" target="_blank" class="btn btn-dark inline-flex items-center justify-center">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="simple-icons:trustpilot"></iconify-icon>
                                <span>Read Our Reviews on Trustpilot</span>
                            </a>
                            <div class="mt-5">
                                <p class="text-sm">If you face any issue, please visit our <a href="{{setting('customer_support_link','platform_links','javascript:void(0);')}}" class="btn-link">Partner Support</a> or Email us at <a href="mailto:{{ setting('support_email','global')}}" class="btn-link">{{ setting('support_email','global')}}</a>.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="card">
                <div class="p-6">
                    <h4 class="card-title mb-2">
                        {{ __('Become a Introducing Broker') }}
                    </h4>
                    <p class="dark:text-white">
                        {{ __('Make sure your details are correct-after applying, we will reach you to discuss your experience. We will also answer all the questions you might have.') }}
                    </p>
                </div>
                <div class="card-body px-6 pb-6">
                    <form action="{{ route('user.ib-program.store') }}" method="POST" id="ib-from-create"
                          class="space-y-4">
                        @csrf

                        @foreach($ibQuestions as $qIndex=>$ibQuestion)
                            @foreach(json_decode($ibQuestion->fields) as $field)
{{--                                {{dd($field)}}--}}
                                <div class="input-area">
                                    <div class="grid grid-cols-12">
                                        <div class="col-span-12">
                                            <label class="form-label text-lg font-medium">{{ $field->name }}</label>
                                        </div>
                                        @if($field->type === 'text')
                                            <div class="md:col-span-6 col-span-12">
                                                <input name="fields[{{ $field->name }}]"
                                                       class="form-control !text-lg" type="text" value="" @if($field->validation === 'required') required @endif>
                                            </div>
                                        @elseif($field->type === 'checkbox')
                                            <div class="col-span-12">
                                                @foreach($field->options as $index=>$option)
                                                    <div class="checkbox-area mb-2">
                                                        <label for="flexCheckDefault{{$qIndex}}{{$index}}"
                                                               class="inline-flex items-center cursor-pointer">
                                                            <input class="hidden" type="checkbox"
                                                                   name="fields[{{ $field->name }}][]"
                                                                   value="{{ $option }}" id="flexCheckDefault{{$qIndex}}{{$index}}"
                                                                   @if($field->validation === 'required') required @endif />
                                                            <span
                                                                class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                                                <img
                                                                    src="{{ asset('frontend/images/icon/ck-white.svg') }}"
                                                                    alt=""
                                                                    class="h-[10px] w-[10px] block m-auto opacity-0">
                                                            </span>
                                                            <span
                                                                class="text-slate-500 dark:text-slate-400 text-sm leading-6">
                                                                {{ $option }}
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @elseif($field->type === 'radio')
                                            <div class="col-span-12">
                                                @foreach($field->options as $option)
                                                    <div class="basicRadio mb-2">
                                                        <label class="flex items-center cursor-pointer">
                                                            <input type="radio" class="hidden"
                                                                   name="fields[{{ $field->name }}]"
                                                                   value="{{ $option }}" @if($field->validation === 'required') required @endif>
                                                            <span
                                                                class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                                            <span
                                                                class="text-slate-500 dark:text-slate-400 text-sm leading-6 capitalize">
                                                                {{ $option }}
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @elseif($field->type === 'dropdown')
                                            <div class="md:col-span-6 col-span-12 select2-lg">
                                                <select name="fields[{{ $field->name }}]"
                                                        class="select2 form-control w-full mt-2 py-2" @if($field->validation === 'required') required @endif>
                                                    @foreach($field->options as $option)
                                                        <option value="{{ $option }}"
                                                                class="inline-block font-Inter font-normal text-sm text-slate-600"
                                                        ">
                                                        {{ $option }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                            @endforeach
                        @endforeach
                        <br>
                        <br>
                        <div class="input-area">
                            <div class="checkbox-area">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="hidden" name="checkbox" id="agreement-check" required>
                                    <span
                                        class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                            <img src="{{ asset('frontend/assets/images/icon/ck-white.svg') }}" alt=""
                                                 class="h-[10px] w-[10px] block m-auto opacity-0"></span>
                                    <span class="text-slate-500 dark:text-slate-400 text-sm leading-6">
                                        {{ __('I have read and agree with the ') }}
                                        <a href="javascript:;" class="btn-link">IB Agreement</a>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <div class="text-right">
                                <button type="button" class="btn btn-dark save-btn">Register</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif
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
        $('body').on('change', '#language', function () {
            var selectedLanguage = $(this).val();
            $.ajax({
                url: '{{ route("user.referral.advertisement.material") }}',
                type: 'GET',
                data: { language: selectedLanguage },
                success: function(data) {
                    // Update the content of the active tab with the filtered advertisements
                    $('#advertisement-container').html(data);
                    var activeTabContent = $('.nav-pills .nav-link.active').attr('href');
                    $('#tabs-socialMediaMaterial').removeClass('show active');
                    $('#tabs-websiteBanners').removeClass('show active');
                    $(activeTabContent).addClass('show active');

                },
                error: function() {
                    // t.error('Error fetching advertisements.');
                }
            });
        });
        $('body').on('click', '.save-btn', function () {
            if ($('#agreement-check').is(':checked')) {
                var btn = $(this);
                btn.prop('disabled', true);
                let form = document.querySelector('#ib-from-create');
                let formData = new FormData(form);
                // formData.append('amount', $('#active_wallet_amount').val());
                console.log('aa');
                var url = $('#ib-from-create').attr('action');
                submit_form(formData, btn, url, '', 'ibForm');
            } else {
                tNotify('error','Kindly check the agreement before proceed!')
            }

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
