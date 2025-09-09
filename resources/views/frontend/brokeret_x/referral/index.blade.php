@extends('frontend::layouts.partner')
@section('title')
    {{ __('Partner Dashboard') }}
@endsection
@section('content')
<div x-data="referralApp()" x-init="init()">
    @if(auth()->user()->ib_status == \App\Enums\IBStatus::PENDING )
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] py-10 px-10">
            <div class="card-body p-6">
                <div class="max-w-2xl progress-steps-form">
                    <div class="transaction-status text-center">
                        <div class="icon h-20 w-20 bg-warning text-warning bg-opacity-30 rounded-full flex flex-col items-center justify-center mx-auto">
                            <iconify-icon icon="icomoon-free:hour-glass" class="text-4xl"></iconify-icon>
                        </div>
                        <h2 class="text-3xl dark:text-white my-5">{{ __('Partner Request Pending') }}</h2>
                        <p class="text-sm mb-3 dark:text-white">
                            {{ __("Your partnership request is under review and we'll confirm with you shortly. Stay tuned!") }}
                        </p>
                        <div class="flex flex-wrap items-center justify-center gap-3">
                            <a href="{{setting('IB_partner_agreement_link','document_links',false)}}" target="_blank" class="btn btn-light inline-flex items-center justify-center mr-2">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="carbon:document"></iconify-icon>
                                <span>{{ __('Read Partner Agreement') }}</span>
                            </a>
                            <a href="{{setting('trust_pilot_review_link','platform_links','javascript:void(0);')}}" target="_blank" class="btn btn-dark inline-flex items-center justify-center">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="simple-icons:trustpilot"></iconify-icon>
                                <span>{{ __('Read Our Reviews on Trustpilot') }}</span>
                            </a>
                        </div>
                        <div class="mt-5">
                            <p class="text-sm dark:text-slate-300">
                                {{ __('If you face any issue, please visit our') }}
                                <a href="{{setting('customer_support_link','platform_links','javascript:void(0);')}}" class="btn-link">{{ __('Customer Support') }}</a>
                                {{ __('or Email us at') }}
                                <a href="mailto:{{ setting('support_email','global')}}" class="btn-link">{{ setting('support_email','global')}}</a>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif((auth()->user()->ib_status == \App\Enums\IBStatus::UNPROCESSED) && !isset(auth()->user()->ref_id))
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] sm:p-6">
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
                            <div class="input-area">
                                <div class="grid grid-cols-12">
                                    <div class="col-span-12">
                                        <label class="form-label text-lg font-medium">{{ $field->name }}</label>
                                    </div>
                                    @if($field->type === 'text')
                                        <div class="md:col-span-6 col-span-12">
                                            <input name="fields[{{ $field->name }}]" class="form-control !text-lg" type="text" value="" @if($field->validation === 'required') required @endif>
                                        </div>
                                    @elseif($field->type === 'checkbox')
                                        <div class="col-span-12">
                                            @foreach($field->options as $index=>$option)
                                                <div class="checkbox-area mb-2">
                                                    <label for="flexCheckDefault{{$qIndex}}{{$index}}" class="inline-flex items-center cursor-pointer">
                                                        <input class="hidden" type="checkbox"
                                                            name="fields[{{ $field->name }}][]"
                                                            value="{{ $option }}" id="flexCheckDefault{{$qIndex}}{{$index}}"
                                                            @if($field->validation === 'required') required @endif />
                                                        <span class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                                            <img
                                                                src="{{ asset('frontend/images/icon/ck-white.svg') }}"
                                                                alt=""
                                                                class="h-[10px] w-[10px] block m-auto opacity-0">
                                                        </span>
                                                        <span class="text-slate-500 dark:text-slate-400 text-sm leading-6">
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
                                                        <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                                        <span class="text-slate-500 dark:text-slate-400 text-sm leading-6 capitalize">
                                                            {{ $option }}
                                                        </span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @elseif($field->type === 'dropdown')
                                        <div class="md:col-span-6 col-span-12 select2-lg">
                                            <select name="fields[{{ $field->name }}]" class="select2 form-control w-full mt-2 py-2" @if($field->validation === 'required') required @endif>
                                                @foreach($field->options as $option)
                                                    <option value="{{ $option }}" class="inline-block font-Inter font-normal text-sm text-slate-600"">
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
                                <span class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                    <img src="{{ asset('frontend/assets/images/icon/ck-white.svg') }}" alt="" class="h-[10px] w-[10px] block m-auto opacity-0">
                                </span>
                                <span class="text-slate-500 dark:text-slate-400 text-sm leading-6">
                                    {{ __('I have read and agree with the ') }}
                                    <a href="javascript:;" class="btn-link">{{ __('IB Agreement') }}</a>
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <div class="text-right">
                            <button type="button" class="btn btn-dark save-btn" @click="submitIBForm()">{{ __('Register') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
        
    @if(request()->routeIs('user.referral.members'))
        @include('frontend::referral.include.__members')
    @endif
    @if(request()->routeIs('user.referral.advertisement.material'))
        @include('frontend::referral.include.__advertisement_material')
    @endif
    @if(request()->routeIs('user.referral.network'))
        @include('frontend::referral.include.__network')
    @endif
    @if(request()->routeIs('user.referral.reports'))
        @include('frontend::referral.include.__reports')
    @endif
    @if(request()->routeIs('user.referral.history'))
        @include('frontend::referral.include.__history')
    @endif
</div>

@endsection
@section('script')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('referralApp', () => ({
                transactionStatus: '',
                transactionDate: '',
                selectedLanguage: '',
                loading: false,

                init() {
                    window.addEventListener('pageshow', e => {
                        if (e.persisted || performance.getEntriesByType("navigation")[0]?.type === "back_forward") {
                            sessionStorage.setItem('navigatedBack', 'true');
                        }
                    });

                    this.resetFiltersIfNavigatedBack();
                    this.restoreSelections();

                    this.$watch('selectedLanguage', value => {
                        if (value) this.changeLanguage();
                    });

                    this.fetchTransactions();
                },

                async fetchTransactions(url = '{{ route("user.referral.history") }}') {
                    this.loading = true;

                    try {
                        const params = new URLSearchParams({
                            transaction_status: this.transactionStatus,
                            transaction_date: this.transactionDate
                        });

                        const res = await fetch(`${url}?${params}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });

                        if (!res.ok) throw new Error('Network error');
                        const data = await res.json();

                        document.getElementById('transaction-table-body').innerHTML = data.html.trim() || 
                            `<tr><td colspan="7" class="text-center px-5 py-4 sm:px-6">
                                <span class="text-gray-500 text-theme-sm dark:text-gray-400">{{ __('No transactions found') }}</span>
                            </td></tr>`;

                        document.querySelector('.pagination-container').innerHTML = data.pagination || '';

                        localStorage.setItem('transaction_status', this.transactionStatus);
                        localStorage.setItem('transaction_date', this.transactionDate);

                        if (window.renderLucideIcons) window.renderLucideIcons();
                        this.attachPaginationEvents();

                    } catch (err) {
                        tNotify('error', 'Error loading transactions.');
                        console.error(err);
                    } finally {
                        this.loading = false;
                    }
                },

                attachPaginationEvents() {
                    document.querySelectorAll('.pagination a').forEach(link => {
                        const clone = link.cloneNode(true);
                        link.replaceWith(clone);
                        clone.addEventListener('click', e => {
                            e.preventDefault();
                            const url = clone.getAttribute('href');
                            if (url) this.fetchTransactions(url);
                        });
                    });
                },

                onFilterChange() {
                    this.fetchTransactions();
                },

                resetFiltersIfNavigatedBack() {
                    const isBack =
                        performance.getEntriesByType("navigation")[0]?.type === "back_forward" ||
                        sessionStorage.getItem('navigatedBack') === 'true';

                    if (isBack) {
                        localStorage.removeItem('transaction_status');
                        localStorage.removeItem('transaction_date');
                        this.transactionStatus = '';
                        this.transactionDate = '';
                        sessionStorage.removeItem('navigatedBack');
                    }
                },

                restoreSelections() {
                    this.transactionStatus = localStorage.getItem('transaction_status') || '';
                    this.transactionDate = localStorage.getItem('transaction_date') || '';
                },

                async changeLanguage() {
                    try {
                        const res = await fetch('{{ route("user.referral.advertisement.material") }}?' + 
                            new URLSearchParams({ language: this.selectedLanguage }), {
                                headers: { 'X-Requested-With': 'XMLHttpRequest' }
                            });

                        if (!res.ok) throw new Error('Network error');
                        const html = await res.text();

                        const container = document.getElementById('advertisement-container');
                        if (container) container.innerHTML = html;

                        const activeTab = document.querySelector('.nav-pills .nav-link.active');
                        if (activeTab) {
                            const target = activeTab.getAttribute('href');
                            document.getElementById('tabs-socialMediaMaterial')?.classList.remove('show', 'active');
                            document.getElementById('tabs-websiteBanners')?.classList.remove('show', 'active');
                            document.querySelector(target)?.classList.add('show', 'active');
                        }

                    } catch (err) {
                        tNotify('error', 'Error fetching advertisements.');
                        console.error(err);
                    }
                },

                submitIBForm() {
                    if (!document.getElementById('agreement-check')?.checked) {
                        tNotify('error', '{{ __('Kindly check the agreement before proceeding!') }}');
                        return;
                    }
                    const btn = document.querySelector('.save-btn');
                    if (btn) btn.disabled = true;

                    const form = document.getElementById('ib-from-create');
                    const formData = new FormData(form);
                    const url = form?.getAttribute('action');

                    if (typeof submit_form === 'function') {
                        submit_form(formData, btn, url, '', 'ibForm');
                    }
                },

                copyRef() {
                    const refLink = document.getElementById("refLink");
                    const copyLabel = document.getElementById('copy');
                    const copiedValue = document.getElementById('copied')?.value;

                    if (refLink) {
                        navigator.clipboard.writeText(refLink.value)
                            .then(() => { if (copyLabel && copiedValue) copyLabel.textContent = copiedValue; })
                            .catch(() => tNotify('error', 'Copy failed.'));
                    }
                }
            }));
        });
    </script>
@endsection
