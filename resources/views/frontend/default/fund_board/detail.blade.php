@extends('frontend::layouts.user')
@section('title')
    {{ __('Challenge Funding Dashboard') }}
@endsection
@push('style')
    <style>
        #account_credentials_card {
            width: 21rem;
        }
    </style>
@endpush
@section('content')
    <div class="mb-5">
        <ul class="m-0 p-0 list-none">
            <li class="inline-block relative top-[3px] text-base text-primary-500 font-Inter ">
                <a href="{{route('user.dashboard')}}">
                    <iconify-icon icon="heroicons-outline:home"></iconify-icon>
                    <iconify-icon icon="heroicons-outline:chevron-right" class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                </a>
            </li>
            <li class="inline-block relative text-sm text-primary-500 font-Inter ">
                Fund Board
                <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
            </li>
            <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
                Dashboard
            </li>
        </ul>
    </div>

    <div class="grid grid-cols-3 gap-5">
        <div class="xl:col-span-2 col-span-12">
            <div class="card mb-5">
                <div class="card-body p-6 space-y-4">
                    <div class="flex items-center flex-wrap gap-4">
                        <div class="flex-none w-1/5 md:block hidden">
                            <span class="flex relative h-12 w-12 mx-auto">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-success-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-12 w-12 bg-success-500"></span>
                            </span>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-center mb-1">
                                <h5 class="text-xl text-slate-900 font-medium">Funded Account: 9997452</h5>
                                <div class="flex items-setrech gap-2">
                                    <span class="badge bg-success-500 flex-inline items-center justify-center text-white capitalize !px-3">
                                        Active
                                    </span>
                                    <a href="" class="btn btn-sm btn-light w-8 h-8 inline-flex items-center justify-center">
                                        <iconify-icon class="text-xl" icon="zondicons:reload"></iconify-icon>
                                    </a>
                                </div>
                            </div>
                            <p class="text-sm text-slate-600 font-light mb-4">INV-87420146</p>
                            <div class="flex justify-between flex-wrap gap-4">
                                <div class="flex-1">
                                    <div class="bg-slate-50 dark:bg-slate-900 rounded p-4 min-w-[184px] space-y-5">
                                        <div class="flex justify-between text-slate-600 dark:text-slate-300">
                                            <span class="text-sm">Challenge Funding Program</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="bg-slate-50 dark:bg-slate-900 rounded p-4 min-w-[184px] space-y-5">
                                        <div class="flex justify-between text-slate-600 dark:text-slate-300">
                                            <span class="text-sm">Starting Balance</span>
                                            <span class="text-sm font-medium text-slate-900">$10,000.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-between flex-wrap gap-4">
                        <div class="flex-1">
                            <div class="bg-slate-50 dark:bg-slate-900 rounded p-4 space-y-5">
                                <div class="flex justify-between gap-3 text-slate-600 dark:text-slate-300">
                                    <span class="text-sm">P&L:</span>
                                    <span class="text-sm font-medium text-slate-900">$8154.16</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="bg-slate-50 dark:bg-slate-900 rounded p-4 space-y-5">
                                <div class="flex justify-between gap-3 text-slate-600 dark:text-slate-300">
                                    <span class="text-sm">Equity:</span>
                                    <span class="text-sm font-medium text-slate-900">$8154.16</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="bg-slate-50 dark:bg-slate-900 rounded p-4 space-y-5">
                                <div class="flex justify-between gap-3 text-slate-600 dark:text-slate-300">
                                    <span class="text-sm">Balance:</span>
                                    <span class="text-sm font-medium text-slate-900">$8154.16</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <ul class="relative w-full m-0 flex list-none justify-between overflow-hidden p-0 transition-[height] duration-200 ease-in-out">
                        <!--First item-->
                        <li class="w-[4.5rem] flex-auto">
                            <div class="flex cursor-pointer items-center pl-2 leading-[1.3rem] no-underline after:ml-2 after:h-px after:w-full after:flex-1 after:bg-[#e0e0e0] after:content-[''] hover:bg-[#f9f9f9] focus:outline-none dark:after:bg-neutral-600 dark:hover:bg-[#3b3b3b]">
                                <span class="my-3 mr-2 flex shrink-0 h-[30px] w-[30px] items-center justify-center rounded-full bg-slate-900 text-sm font-medium text-white">
                                    <iconify-icon icon="bx:check-double" class="text-xl"></iconify-icon>
                                </span>
                                <span class="font-medium text-neutral-500 after:flex after:text-[0.8rem] after:content-[data-content] dark:text-neutral-300">
                                    step1
                                </span>
                            </div>
                        </li>
                    
                        <!--Second item-->
                        <li class="w-[4.5rem] flex-auto">
                            <div class="flex cursor-pointer items-center leading-[1.3rem] no-underline before:mr-2 before:h-px before:w-full before:flex-1 before:bg-[#e0e0e0] before:content-[''] after:ml-2 after:h-px after:w-full after:flex-1 after:bg-[#e0e0e0] after:content-[''] hover:bg-[#f9f9f9] focus:outline-none dark:before:bg-neutral-600 dark:after:bg-neutral-600 dark:hover:bg-[#3b3b3b]">
                                <span class="my-3 mr-2 flex shrink-0 h-[30px] w-[30px] items-center justify-center rounded-full bg-slate-300 text-sm font-medium text-[#40464f]">
                                    2
                                </span>
                                <span class="text-neutral-500 after:flex after:text-[0.8rem] after:content-[data-content] dark:text-neutral-300">
                                    step2
                                </span>
                            </div>
                        </li>
                    
                        <!--Third item-->
                        <li class="w-[4.5rem] flex-auto">
                            <div class="flex cursor-pointer items-center pr-2 leading-[1.3rem] no-underline before:mr-2 before:h-px before:w-full before:flex-1 before:bg-[#e0e0e0] before:content-[''] hover:bg-[#f9f9f9] focus:outline-none dark:before:bg-neutral-600 dark:after:bg-neutral-600 dark:hover:bg-[#3b3b3b]">
                                <span class="my-3 mr-2 flex shrink-0 h-[30px] w-[30px] items-center justify-center rounded-full bg-slate-300 text-sm font-medium text-[#40464f]">
                                    3
                                </span>
                                <span class="text-neutral-500 after:flex after:text-[0.8rem] after:content-[data-content] dark:text-neutral-300">
                                    step3
                                </span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-5">
                <div class="card">
                    <div class="card-header noborder">
                        <h6 class="card-title">
                            Trading Objective
                        </h6>
                    </div>
                    <div class="card-body px-4 pb-6 pt-0">
                        <div class="grid md:grid-cols-2 grid-cols-1 gap-3">
                            <div class="bg-slate-50 dark:bg-slate-900 p-3 rounded">
                                <div class="flex items-center justify-between mb-4">
                                    <h5 class="text-slate-900 dark:text-slate-300 text-sm">
                                        Minimum Trading Days
                                    </h5>
                                    <span class="badge bg-success-500 text-white capitalize">Ongoing</span>
                                </div>
                                <ul class="space-y-3">
                                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                                        <span>Minimum</span>
                                        <span class="text-slate-900 font-medium">4 days</span>
                                    </li>
                                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                                        <span>Current Result</span>
                                        <span class="text-slate-900 font-medium">7 days</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-900 p-3 rounded">
                                <div class="flex items-center justify-between mb-4">
                                    <h5 class="text-slate-900 dark:text-slate-300 text-sm">
                                        Daily Loss Limit
                                    </h5>
                                    <span class="badge bg-success-500 text-white capitalize">Ongoing</span>
                                </div>
                                <ul class="space-y-3">
                                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                                        <span>Max Loss</span>
                                        <span class="text-slate-900 font-medium">$500</span>
                                    </li>
                                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                                        <span>Max Loss Recorded</span>
                                        <span class="text-slate-900 font-medium">$0.00</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-900 p-3 rounded">
                                <div class="flex items-center justify-between mb-4">
                                    <h5 class="text-slate-900 dark:text-slate-300 text-sm">
                                        Stopout Level
                                    </h5>
                                    <span class="badge bg-success-500 text-white capitalize">Ongoing</span>
                                </div>
                                <ul class="space-y-3">
                                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                                        <span>Minimum Level</span>
                                        <span class="text-slate-900 font-medium">$1000</span>
                                    </li>
                                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                                        <span>Current</span>
                                        <span class="text-slate-900 font-medium">$0.00</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-900 p-3 rounded">
                                <div class="flex items-center justify-between mb-4">
                                    <h5 class="text-slate-900 dark:text-slate-300 text-sm">
                                        Profit Target
                                    </h5>
                                    <span class="badge bg-success-500 text-white capitalize">Ongoing</span>
                                </div>
                                <ul class="space-y-3">
                                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                                        <span>Minimum</span>
                                        <span class="text-slate-900 font-medium">1000</span>
                                    </li>
                                    <li class="flex items-center justify-between text-sm text-slate-500 gap-2">
                                        <span>Current Result</span>
                                        <span class="text-slate-900 font-medium">$0.00</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header noborder">
                        <h6 class="card-title">
                            Details Stats
                        </h6>
                    </div>
                    <div class="card-body p-6 pt-0">
                        <ul class="divide-y divide-slate-100 dark:divide-slate-700">
                            <li class="block py-[8px]">
                                <div class="flex space-x-2 rtl:space-x-reverse">
                                    <div class="flex-1 flex space-x-2 rtl:space-x-reverse">
                                        <div class="flex-none">
                                            <iconify-icon icon="material-symbols:balance"></iconify-icon>
                                        </div>
                                        <div class="flex-1">
                                            <span class="block text-slate-600 font-medium text-sm dark:text-slate-300">
                                                Equity
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-none">
                                        <span class="block text-slate-600 font-medium text-sm">$10,000.00</span>
                                    </div>
                                </div>
                            </li>
                            <li class="block py-[8px]">
                                <div class="flex space-x-2 rtl:space-x-reverse">
                                    <div class="flex-1 flex space-x-2 rtl:space-x-reverse">
                                        <div class="flex-none">
                                            <iconify-icon icon="material-symbols:account-balance-wallet"></iconify-icon>
                                        </div>
                                        <div class="flex-1">
                                            <span class="block text-slate-600 font-medium text-sm dark:text-slate-300">
                                                Balance
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-none">
                                        <span class="block text-slate-600 font-medium text-sm">$10,000.00</span>
                                    </div>
                                </div>
                            </li>
                            <li class="block py-[8px]">
                                <div class="flex space-x-2 rtl:space-x-reverse">
                                    <div class="flex-1 flex space-x-2 rtl:space-x-reverse">
                                        <div class="flex-none">
                                            <iconify-icon icon="heroicons:arrow-trending-up"></iconify-icon>
                                        </div>
                                        <div class="flex-1">
                                            <span class="block text-slate-600 font-medium text-sm dark:text-slate-300">
                                                Avg. Winning Trade
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-none">
                                        <span class="block text-slate-600 font-medium text-sm">$11.68</span>
                                    </div>
                                </div>
                            </li>
                            <li class="block py-[8px]">
                                <div class="flex space-x-2 rtl:space-x-reverse">
                                    <div class="flex-1 flex space-x-2 rtl:space-x-reverse">
                                        <div class="flex-none">
                                            <iconify-icon icon="heroicons:arrow-trending-down-20-solid"></iconify-icon>
                                        </div>
                                        <div class="flex-1">
                                            <span class="block text-slate-600 font-medium text-sm dark:text-slate-300">
                                                Avg. Losing Trade
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-none">
                                        <span class="block text-slate-600 font-medium text-sm">$0.00</span>
                                    </div>
                                </div>
                            </li>
                            <li class="block py-[8px]">
                                <div class="flex space-x-2 rtl:space-x-reverse">
                                    <div class="flex-1 flex space-x-2 rtl:space-x-reverse">
                                        <div class="flex-none">
                                            <iconify-icon icon="fe:line-chart"></iconify-icon>
                                        </div>
                                        <div class="flex-1">
                                            <span class="block text-slate-600 font-medium text-sm dark:text-slate-300">
                                                Trades
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-none">
                                        <span class="block text-slate-600 font-medium text-sm">4</span>
                                    </div>
                                </div>
                            </li>
                            <li class="block py-[8px]">
                                <div class="flex space-x-2 rtl:space-x-reverse">
                                    <div class="flex-1 flex space-x-2 rtl:space-x-reverse">
                                        <div class="flex-none">
                                            <iconify-icon icon="ic:baseline-grid-on"></iconify-icon>
                                        </div>
                                        <div class="flex-1">
                                            <span class="block text-slate-600 font-medium text-sm dark:text-slate-300">
                                                Lots
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-none">
                                        <span class="block text-slate-600 font-medium text-sm">0.06</span>
                                    </div>
                                </div>
                            </li>
                            <li class="block py-[8px]">
                                <div class="flex space-x-2 rtl:space-x-reverse">
                                    <div class="flex-1 flex space-x-2 rtl:space-x-reverse">
                                        <div class="flex-none">
                                            <iconify-icon icon="system-uicons:chevron-close"></iconify-icon>
                                        </div>
                                        <div class="flex-1">
                                            <span class="block text-slate-600 font-medium text-sm dark:text-slate-300">
                                                Average RRR
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-none">
                                        <span class="block text-slate-600 font-medium text-sm">0</span>
                                    </div>
                                </div>
                            </li>
                            <li class="block py-[8px]">
                                <div class="flex space-x-2 rtl:space-x-reverse">
                                    <div class="flex-1 flex space-x-2 rtl:space-x-reverse">
                                        <div class="flex-none">
                                            <iconify-icon icon="dashicons:chart-line"></iconify-icon>
                                        </div>
                                        <div class="flex-1">
                                            <span class="block text-slate-600 font-medium text-sm dark:text-slate-300">
                                                Win Rate
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-none">
                                        <span class="block text-slate-600 font-medium text-sm">75%</span>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card mt-5">
                <div class="card-header noborder">
                    <h6 class="card-title">Fund Matrics</h6>
                </div>
                <div class="card-body p-6 pt-0">
                    <div class="flex flex-wrap gap-3">
                        <div class="flex-1 p-2">
                            <div class="text-xs text-slate-500 mb-2">Total Alloted Fund</div>
                            <div class="text-xl font-medium text-slate-900">$10,000.00</div>
                        </div>
                        <div class="flex-1 p-2">
                            <div class="text-xs text-slate-500 mb-2">Max Draw Down</div>
                            <div class="text-xl font-medium text-slate-900">$1,000.00</div>
                        </div>
                        <div class="flex-1 p-2">
                            <div class="text-xs text-slate-500 mb-2">Daily Max Draw Down</div>
                            <div class="text-xl font-medium text-slate-900">$500.00</div>
                        </div>
                        <div class="flex-1 p-2">
                            <div class="text-xs text-slate-500 mb-2">Profit Split</div>
                            <div class="text-xl font-medium text-slate-900">80 / 20</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-5">
                <div class="card-header noborder">
                    <h6 class="card-title">Overall Performance</h6>
                </div>
                <div class="card-body p-6 pt-0">
                    <div class="flex flex-wrap gap-3">
                        <div class="flex-1 p-2">
                            <div class="text-xs text-slate-500 mb-2">Balance</div>
                            <div class="text-xl font-medium text-slate-900">$10,000.00</div>
                        </div>
                        <div class="flex-1 p-2">
                            <div class="text-xs text-slate-500 mb-2">Profit</div>
                            <div class="text-xl font-medium text-slate-900">$0.00</div>
                        </div>
                        <div class="flex-1 p-2">
                            <div class="text-xs text-slate-500 mb-2">Growth</div>
                            <div class="text-xl font-medium text-slate-900">0%</div>
                        </div>
                        <div class="flex-1 p-2">
                            <div class="text-xs text-slate-500 mb-2">Days</div>
                            <div class="text-xl font-medium text-slate-900">7</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-5">
                <div class="card-header noborder">
                    <h6 class="card-title">Today's Performance</h6>
                </div>
                <div class="card-body p-6 pt-0">
                    <div class="flex flex-wrap gap-3">
                        <div class="flex-1 p-2">
                            <div class="text-xs text-slate-500 mb-2">Previous Day Balance</div>
                            <div class="text-xl font-medium text-slate-900">$10,000.00</div>
                        </div>
                        <div class="flex-1 p-2">
                            <div class="text-xs text-slate-500 mb-2">Current Equity</div>
                            <div class="text-xl font-medium text-slate-900">10,000.00</div>
                        </div>
                        <div class="flex-1 p-2">
                            <div class="text-xs text-slate-500 mb-2">Today's Draw Down</div>
                            <div class="text-xl font-medium text-slate-900">$0</div>
                        </div>
                        <div class="flex-1 p-2">
                            <div class="text-xs text-slate-500 mb-2">Remaining Draw Down</div>
                            <div class="text-xl font-medium text-slate-900">$500.00</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-auto">
            <div class="card top-24" id="account_credentials_card">
                <div class="card-body p-6">
                    <div class="mb-3">
                        <h5 class="text-xl text-slate-900 font-medium">
                            Account Credentials
                        </h5>
                        <p class="text-sm text-slate-600">
                            Here's account credentials. Login via MetaTrader 5.
                        </p>
                    </div>
                    <div class="space-y-3">
                        <div class="bg-slate-100 dark:bg-slate-900 rounded-md p-3 py-2">
                            <label class="text-xs text-slate-500 dark:text-slate-400 block mb-1 cursor-pointer font-normal" for="">Login</label>
                            <div class="fromGroup">
                                <div class="relative">
                                    <input class="form-control !py-1 !pr-9 !bg-transparent p-0 text-slate-900 dark:text-white text-sm placeholder:text-slate-400 h-auto" value="9997452" id="copyLogin" readonly>
                                    <button class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-none flex items-center justify-center copy-button" data-target="copyLogin">
                                        <iconify-icon icon="lucide:copy"></iconify-icon>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-100 dark:bg-slate-900 rounded-md p-3 py-2">
                            <label class="text-xs text-slate-500 dark:text-slate-400 block mb-1 cursor-pointer font-normal" for="">Password</label>
                            <div class="fromGroup">
                                <div class="relative">
                                    <input class="form-control !py-1 !pr-9 !bg-transparent p-0 text-slate-900 dark:text-white text-sm placeholder:text-slate-400 h-auto" type="password" id="password" value="9997452" readonly>
                                    <button class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-none flex items-center justify-center toggle-password">
                                        <iconify-icon icon="heroicons:eye-slash"></iconify-icon>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-100 dark:bg-slate-900 rounded-md p-3 py-2">
                            <label class="text-xs text-slate-500 dark:text-slate-400 block mb-1 cursor-pointer font-normal" for="">Server</label>
                            <div class="fromGroup">
                                <div class="relative">
                                    <input class="form-control !py-1 !pr-9 !bg-transparent p-0 text-slate-900 dark:text-white text-sm placeholder:text-slate-400 h-auto" type="text" value="OrfinexPrime-MT5" id="copyServer" readonly>
                                    <button class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-none flex items-center justify-center copy-button" data-target="copyServer">
                                        <iconify-icon icon="lucide:copy"></iconify-icon>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <a href="" class="btn btn-sm btn-dark inline-flex items-center justify-center w-full h-10">
                            Trade on web trader
                        </a>
                        <a href="" class="btn btn-sm btn-outline-dark inline-flex items-center justify-center w-full h-10">
                            Download MT 5
                        </a>
                        <a href="" class="btn btn-sm btn-danger inline-flex items-center justify-center w-full h-10">
                            Download Certificate
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script>
        window.onscroll = function() {myFunction()};

        var header = document.getElementById("account_credentials_card");
        var sticky = header.offsetTop;

        function myFunction() {
            if (window.pageYOffset > sticky) {
                header.classList.add("xl:fixed");
            } else {
                header.classList.remove("xl:fixed");
            }
        }

        $(document).ready(function() {
            $('.toggle-password').on('click', function() {
                var passwordInput = $('#password');
                var toggleButton = $('.toggle-password');

                if (passwordInput.attr('type') === 'password') {
                        passwordInput.attr('type', 'text');
                    } else {
                        passwordInput.attr('type', 'password');
                    }
                });

                $('.copy-button').on('click', function() {
                    var targetId = $(this).data('target');
                    var copyText = $('#' + targetId);
                    
                    copyText.select();
                    document.execCommand('copy');
                });
                
            });

    </script>
@endpush