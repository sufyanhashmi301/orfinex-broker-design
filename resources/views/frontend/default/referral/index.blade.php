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
                    <a href="" class="nav-link w-full block font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 md:px-4 pb-2 my-2 hover:border-transparent focus:border-transparent dark:text-slate-300 {{ isActive('user.referral') }}">
                        IB Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link w-full block font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 md:px-4 pb-2 my-2 hover:border-transparent focus:border-transparent dark:text-slate-300" href="">
                        Network
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link w-full block font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 md:px-4 pb-2 my-2 hover:border-transparent focus:border-transparent dark:text-slate-300" href="">
                        Resources
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link w-full block font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 md:px-4 pb-2 my-2 hover:border-transparent focus:border-transparent dark:text-slate-300" href="">
                        Reports
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="grid grid-cols-12 gap-5">
        <div class="col-span-8">
            <div class="space-y-5">
                <div class="alert alert-dismissible py-[18px] px-6 font-normal text-sm rounded-md bg-primary-500 bg-opacity-[14%] text-white" role="alert">
                    <div class="flex items-center space-x-3 rtl:space-x-reverse">
                        <p class="flex-1 text-primary-500 font-Inter">
                            Your commission for the day is paid out on the next day before 23:59 (GMT-2).
                        </p>
                        <div class="flex-0 text-xl cursor-pointer text-primary-500">
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                <iconify-icon icon="line-md:close"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex space-x-3 rtl:space-x-reverse">
                                <div class="flex-none">
                                    <div class="h-12 w-12 rounded-lg flex flex-col items-center justify-center text-2xl bg-slate-200 dark:bg-slate-900 dark:text-white">
                                        <iconify-icon icon="bi:currency-dollar"></iconify-icon>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                                        {{ __('Balance') }}
                                    </div>
                                    <div class="text-slate-900 dark:text-white text-lg font-medium">
                                        $ 120.00
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <a href="{{route('user.deposit.amount')}}" class="btn btn-outline-dark mr-2">
                                    Go to Finance
                                </a>
                                <a href="{{route('user.withdraw.view')}}" class="btn btn-dark mt-0">
                                    Withdraw
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header noborder">
                        <h4 class="card-title">
                            {{ __('Referral URL') }} 
                            @if(setting('site_referral','global') == 'level')
                                {{ __('and Tree') }}
                            @endif
                        </h4>
                    </div>
                    <div class="card-body px-6 pb-6">
                        <div class="referral-link">
                            <div class="referral-link-form">
                                <input type="text" class="form-control !text-lg text-sm" value="{{ $getReferral->link }}" id="refLink"/>
                            </div>
                            <div class="flex justify-between mt-3">
                                <p class="referral-joined text-sm dark:text-white">
                                    {{ $getReferral->relationships()->count() }} {{ __('peoples are joined by using this URL') }}
                                </p>
                                <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                                    <button type="button" class="btn inline-flex items-center justify-center btn-light btn-sm">
                                        <span class="flex items-center">
                                            <iconify-icon class="text-xl" icon="bx:qr"></iconify-icon>
                                        </span>
                                    </button>
                                    <button type="submit" onclick="copyRef()" class="btn inline-flex items-center justify-center btn-dark btn-sm">
                                        <span class="flex items-center">
                                            <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="bi:copy"></iconify-icon>
                                            <span id="copy">{{ __('Copy Url') }}</span>
                                            <input id="copied" hidden value="{{ __('Copied') }}">
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
    
                        {{-- level referral tree --}}
                        @if(setting('site_referral','global') == 'level' && auth()->user()->referrals->count() > 0)
                            <section class="management-hierarchy">
                                <div class="hv-container">
                                    <div class="hv-wrapper">
                                        <!-- tree component -->
                                        @include('frontend::referral.include.__tree',['levelUser' => auth()->user(),'level' => $level,'depth' => 1, 'me' => true])
                                    </div>
                                </div>
                            </section>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-4">
            <div class="space-y-5">
                <div class="card">
                    <div class="card-body p-6">
                        <p class="mb-1">{{ __('Grade 2') }}</p>
                        <h3 class="card-title mb-2">{{ __('33% of spread') }}</h3>
                        <p class="text-sm mb-1">
                            {{ __('To get the next grade earn at least $652.50 in commission up until 31.12') }}
                        </p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body p-6">
                        <div class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-200 dark:bg-slate-900 dark:text-white">
                            <iconify-icon icon="bi:gift"></iconify-icon>
                        </div>
                        <h5 class="card-title my-2">Attach to level a partner</h5>
                        <p class="text-sm dark:text-white mb-4">Join casecade and get guidance and support you need to grow!</p>
                        <a href="" class="btn btn-dark block-btn">Attach</a>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body p-6">
                        <div class="text-center">
                            <img src="" class="rounded-full" alt="...">
                            <h5 class="card-title my-2">Rebate</h5>
                            <p class="text-sm dark:text-white mb-4">Boost your income by increasing client retention and attracting new clients.</p>
                            <a href="" class="btn btn-dark block-btn">More details</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row hidden">
        <div class="col-xl-12">
            <div class="site-card">
                <div class="site-card-header">
                    <h3 class="title">{{ __('All Referral Logs') }}</h3>
                    <div class="card-header-links">
                        <span
                            class="card-header-link rounded-pill"> {{ __('Referral Profit:').' '. $totalReferralProfit .' '.$currency }}</span>
                    </div>
                </div>
                <div class="site-card-body table-responsive">


                    <div class="site-tab-bars">
                        <ul class="nav nav-pills" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a
                                    href=""
                                    class="nav-link active"
                                    id="generalTarget-tab"
                                    data-bs-toggle="pill"
                                    data-bs-target="#generalTarget"
                                    type="button"
                                    role="tab"
                                    aria-controls="generalTarget"
                                    aria-selected="true"
                                ><i icon-name="network"></i>{{ __('General') }}</a>
                            </li>

                            @foreach($referrals->keys() as $raw)

                                @php
                                    $target = json_decode($raw,true);
                                @endphp

                                <li class="nav-item" role="presentation">
                                    <a
                                        href=""
                                        class="nav-link"
                                        id="t{{ $target['id'] }}-tab"
                                        data-bs-toggle="pill"
                                        data-bs-target="#t{{ $target['id'] }}"
                                        type="button"
                                        role="tab"
                                        aria-controls="t{{ $target['id'] }}"
                                        aria-selected="true"
                                    ><i icon-name="boxes"></i>
                                        @if(setting('site_referral','global') == 'level')
                                            Level {{ $target['the_order'] }}
                                        @else
                                            {{ $target['name'] }}
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>


                    <div class="tab-content" id="pills-tabContent">

                        <div
                            class="tab-pane fade show active"
                            id="generalTarget"
                            role="tabpanel"
                            aria-labelledby="generalTarget-tab"
                        >

                            <div class="row">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 desktop-screen-show">
                                    <div class="site-datatable">
                                        <div class="row table-responsive">
                                            <div class="col-xl-12">
                                                <table class="display data-table">
                                                    <thead>
                                                    <tr>
                                                        <th>{{ __('Description') }}</th>
                                                        <th>{{ __('Transactions ID') }}</th>
                                                        <th>{{ __('Amount') }}</th>
                                                        <th>{{ __('Status') }}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>


                                                    @foreach($generalReferrals as $raw)
                                                        <tr>
                                                            <td>
                                                                <div class="table-description">
                                                                    <div class="icon">
                                                                        <i icon-name="arrow-down-left"></i>
                                                                    </div>
                                                                    <div class="description">
                                                                        <strong>{{ $raw->description }}</strong>
                                                                        <div
                                                                            class="date">{{ $raw->created_at }}</div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td><strong>{{$raw->tnx}}</strong></td>
                                                            <td><strong
                                                                    class="green-color">+{{ $raw->amount.' '. $currency }} </strong>
                                                            </td>
                                                            <td>
                                                                <div
                                                                    class="site-badge success">{{ $raw->status }}</div>
                                                            </td>
                                                        </tr>
                                                    @endforeach


                                                    </tbody>
                                                </table>

                                                @if($generalReferrals->isEmpty())
                                                    <p class="centered">{{ __('No Data Found') }}</p>
                                                @endif

                                                {{ $generalReferrals->links() }}
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-12 mobile-screen-show">
                                    <!-- Transactions -->
                                    <div class="all-feature-mobile mobile-transactions mb-3">
                                        <div class="contents">
                                            @foreach($generalReferrals as $raw )
                                                <div class="single-transaction">
                                                    <div class="transaction-left">
                                                        <div class="transaction-des">
                                                            <div
                                                                class="transaction-title">{{ $raw->description }}</div>
                                                            <div class="transaction-id">{{ $raw->tnx }}</div>
                                                            <div
                                                                class="transaction-date">{{ $raw->created_at }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="transaction-right">
                                                        <div
                                                            class="transaction-amount add">
                                                            + {{$raw->amount .' '.$currency}}</div>
                                                        <div class="transaction-gateway">{{ $raw->method }}</div>

                                                        @if($raw->status->value == App\Enums\TxnStatus::Pending->value)
                                                            <div
                                                                class="transaction-status pending">{{ __('Pending') }}</div>
                                                        @elseif($raw->status->value ==  App\Enums\TxnStatus::Success->value)
                                                            <div
                                                                class="transaction-status success">{{ __('Success') }}</div>
                                                        @elseif($raw->status->value ==  App\Enums\TxnStatus::Failed->value)
                                                            <div
                                                                class="transaction-status canceled">{{ __('canceled') }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        {{  $generalReferrals->onEachSide(1)->links() }}
                                    </div>

                                </div>
                            </div>


                        </div>

                        @foreach($referrals as $target => $referral)

                            @php
                                $target = json_decode($target,true);
                            @endphp

                            <div
                                class="tab-pane fade"
                                id="t{{ $target['id'] }}"
                                role="tabpanel"
                                aria-labelledby="t{{ $target['id'] }}-tab"
                            >
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 desktop-screen-show">
                                        <div class="site-datatable">
                                            <div class="row table-responsive">
                                                <div class="col-xl-12">
                                                    <table class="display data-table">
                                                        <thead>
                                                        <tr>
                                                            <th>{{ __('Description') }}</th>
                                                            <th>{{ __('Transactions ID') }}</th>
                                                            <th>{{ __('Type') }}</th>
                                                            <th>{{ __('Amount') }}</th>
                                                            <th>{{ __('Status') }}</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        @foreach($referral->sortDesc() as $raw )
                                                            <tr>
                                                                <td>
                                                                    <div class="table-description">
                                                                        <div class="icon">
                                                                            <i icon-name="arrow-down-left"></i>
                                                                        </div>
                                                                        <div class="description">
                                                                            <strong>{{ $raw->description }}</strong>
                                                                            <div
                                                                                class="date">{{ $raw->created_at }}</div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td><strong>{{$raw->tnx}}</strong></td>
                                                                <td>
                                                                    <div
                                                                        class="site-badge primary-bg">{{ $raw->target_type }}</div>
                                                                </td>
                                                                <td><strong
                                                                        class="green-color">+{{ $raw->amount.' '. $currency }} </strong>
                                                                </td>
                                                                <td>
                                                                    <div
                                                                        class="site-badge success">{{ $raw->status }}</div>
                                                                </td>
                                                            </tr>
                                                        @endforeach

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 mobile-screen-show">
                                        <!-- Transactions -->
                                        <div class="all-feature-mobile mobile-transactions mb-3">
                                            <div class="contents">
                                                @foreach($referral->sortDesc() as $raw )
                                                    <div class="single-transaction">
                                                        <div class="transaction-left">
                                                            <div class="transaction-des">
                                                                <div
                                                                    class="transaction-title">{{ $raw->description }}
                                                                </div>
                                                                <div class="transaction-id">{{ $raw->tnx }}</div>
                                                                <div
                                                                    class="transaction-date">{{ $raw->created_at }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="transaction-right">
                                                            <div
                                                                class="transaction-amount add">
                                                                +{{$raw->amount .' '.$currency}}</div>
                                                            <div
                                                                class="transaction-gateway"> {{  $raw->target_type }}</div>

                                                            @if($raw->status->value == App\Enums\TxnStatus::Pending->value)
                                                                <div
                                                                    class="transaction-status pending">{{ __('Pending') }}</div>
                                                            @elseif($raw->status->value ==  App\Enums\TxnStatus::Success->value)
                                                                <div
                                                                    class="transaction-status success">{{ __('Success') }}</div>
                                                            @elseif($raw->status->value ==  App\Enums\TxnStatus::Failed->value)
                                                                <div
                                                                    class="transaction-status canceled">{{ __('canceled') }}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                    </div>
                                </div>


                            </div>
                        @endforeach

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
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
