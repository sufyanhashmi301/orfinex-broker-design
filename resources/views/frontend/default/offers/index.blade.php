@extends('frontend::layouts.user')
@section('title')
    {{ __('All Schema') }}
@endsection
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
                {{ __('Dashboard') }}
                <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
            </li>
            <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
                {{ __('Offers') }}
            </li>
        </ul>
    </div>
    <div class="card">
        <div class="card-header noborder">
            <h4 class="card-title">Offers</h4>
        </div>
        <div class="card-body px-6 pb-6">
            <div class="md:flex mb-6 md:space-y-0 space-y-4">
                <div class="flex-1 flex items-center space-x-3 rtl:space-x-reverse">
                    <div class="input-area relative pl-[60px]">
                        <label for="select" class="inline-inputLabel">Show:</label>
                        <select name="" id="" class="select2 w-[150px]">
                            <option value="0">-- Select Option --</option>
                            <option value="bonus">Bonus</option>
                            <option value="gift">Gift</option>
                            <option value="cash back">Cash Back</option>
                        </select>  
                    </div>
                </div>
                <div class="flex items-center space-x-2 sm:space-x-4 sm:rtl:space-x-reverse">
                    <button type="button" class="btn btn-outline-dark btn-sm flex items-center justify-center">
                        <iconify-icon class="text-xl" icon="heroicons:view-columns"></iconify-icon>
                    </button>
                    <button type="button" class="btn btn-outline-dark btn-sm flex items-center justify-center">
                        <iconify-icon class="text-xl" icon="heroicons:list-bullet"></iconify-icon>
                    </button>
                </div>
            </div>
            <div class="grid 2xl:grid-cols-3 xl:grid-cols-3 lg:grid-cols-2 md:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-3 h-max">
                <div class="card rounded-md bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 group">
                    <main class="card-body p-4 rounded-md">
                        <a href="/products/1">
                            <div class="dark:rounded relative h-[191px] flex flex-col justify-center items-center mb-3 rounded-md bg-cover" style="background-image: url(https://cloud.orfinex.com/crm/offer1.png)">
                                <div class="h-[146px]">
                                        <span class="badge font-normal bg-danger-600 text-white absolute ltr:left-2 rtl:right-2 top-3">
                                            <span class="inline-flex items-center">40%</span>
                                        </span>
                                    <div class="hover-box flex flex-col invisible absolute ltr:right-2 rtl:left-2 top-2 opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-300 ">
                                        <button class="flex items-center justify-center bg-white p-2.5 mb-1 rounded-full">
                                            <iconify-icon icon="heroicons:heart-20-solid"></iconify-icon>
                                        </button>
                                        <button class="flex items-center justify-center bg-white p-2.5 mb-1 rounded-full  ">
                                            <iconify-icon icon="heroicons:eye"></iconify-icon>
                                        </button>
                                        <button class="flex items-center justify-center bg-white p-2.5 mb-1 rounded-full ">
                                            <iconify-icon icon="heroicons:arrow-path-20-solid"></iconify-icon>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <div>
                            <div class="flex justify-between items-center ">
                                <p class="text-xs text-slate-900 dark:text-slate-300 uppercase font-normal">bonus</p>
                                <span class="flex items-center text-slate-900 dark:text-slate-300 font-normal text-xs space-x-1 rtl:space-x-reverse ">
                                    <iconify-icon class="text-warning-500" icon="heroicons:star-16-solid"></iconify-icon>
                                    <span>4.8</span>
                                </span>
                            </div>
                            <h6 class="text-slate-900 dark:text-slate-300 text-base	font-medium	mt-2 truncate">
                                <a href="/products/1">50% Margin Bonus</a>
                            </h6>
                            <p class="text-slate-500 dark:text-slate-500  text-[11px]  font-normal mt-1.5">
                                Increase Your Trading Capacity
                            </p>
                            <p class="pb-4">
                                <span class="text-slate-900 dark:text-slate-300 text-base font-medium mt-2 ltr:mr-2 rtl:mr-2">$489</span>
                                <del class="text-slate-500 dark:text-slate-500 font-normal text-base">$700</del>
                            </p>
                            <button type="button" class="btn btn inline-flex justify-center btn-outline-dark w-full btn-sm  font-medium hover:bg-slate-900 hover:text-white dark:hover:text-white  dark:hover:bg-slate-700 ">
                                <span class="flex items-center">
                                    <span class="ltr:mr-2 rtl:ml-2 text-sm leading-none">
                                        <iconify-icon icon="heroicons:shopping-bag"></iconify-icon>
                                    </span>
                                    <span>Apply Bonus</span>
                                </span>
                            </button>
                        </div>
                    </main>
                </div>
                <div class="card rounded-md bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 group">
                    <main class="card-body p-4 rounded-md">
                        <a href="/products/1">
                            <div class="dark:rounded relative h-[191px] flex flex-col justify-center items-center mb-3 rounded-md bg-cover" style="background-image: url(https://cloud.orfinex.com/crm/offer1.png)">
                                <div class="h-[146px]">
                                        <span class="badge font-normal bg-danger-600 text-white absolute ltr:left-2 rtl:right-2 top-3">
                                            <span class="inline-flex items-center">40%</span>
                                        </span>
                                    <div class="hover-box flex flex-col invisible absolute ltr:right-2 rtl:left-2 top-2 opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-300 ">
                                        <button class="flex items-center justify-center bg-white p-2.5 mb-1 rounded-full">
                                            <iconify-icon icon="heroicons:heart-20-solid"></iconify-icon>
                                        </button>
                                        <button class="flex items-center justify-center bg-white p-2.5 mb-1 rounded-full  ">
                                            <iconify-icon icon="heroicons:eye"></iconify-icon>
                                        </button>
                                        <button class="flex items-center justify-center bg-white p-2.5 mb-1 rounded-full ">
                                            <iconify-icon icon="heroicons:arrow-path-20-solid"></iconify-icon>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <div>
                            <div class="flex justify-between items-center ">
                                <p class="text-xs text-slate-900 dark:text-slate-300 uppercase font-normal">bonus</p>
                                <span class="flex items-center text-slate-900 dark:text-slate-300 font-normal text-xs space-x-1 rtl:space-x-reverse ">
                                    <iconify-icon class="text-warning-500" icon="heroicons:star-16-solid"></iconify-icon>
                                    <span>4.8</span>
                                </span>
                            </div>
                            <h6 class="text-slate-900 dark:text-slate-300 text-base	font-medium	mt-2 truncate">
                                <a href="/products/1">50% Margin Bonus</a>
                            </h6>
                            <p class="text-slate-500 dark:text-slate-500  text-[11px]  font-normal mt-1.5">
                                Increase Your Trading Capacity
                            </p>
                            <p class="pb-4">
                                <span class="text-slate-900 dark:text-slate-300 text-base font-medium mt-2 ltr:mr-2 rtl:mr-2">$489</span>
                                <del class="text-slate-500 dark:text-slate-500 font-normal text-base">$700</del>
                            </p>
                            <button type="button" class="btn btn inline-flex justify-center btn-outline-dark w-full btn-sm  font-medium hover:bg-slate-900 hover:text-white dark:hover:text-white  dark:hover:bg-slate-700 ">
                                <span class="flex items-center">
                                    <span class="ltr:mr-2 rtl:ml-2 text-sm leading-none">
                                        <iconify-icon icon="heroicons:shopping-bag"></iconify-icon>
                                    </span>
                                    <span>Apply Bonus</span>
                                </span>
                            </button>
                        </div>
                    </main>
                </div>
                <div class="card rounded-md bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 group">
                    <main class="card-body p-4 rounded-md">
                        <a href="/products/1">
                            <div class="dark:rounded relative h-[191px] flex flex-col justify-center items-center mb-3 rounded-md bg-cover" style="background-image: url(https://cloud.orfinex.com/crm/offer1.png)">
                                <div class="h-[146px]">
                                        <span class="badge font-normal bg-danger-600 text-white absolute ltr:left-2 rtl:right-2 top-3">
                                            <span class="inline-flex items-center">40%</span>
                                        </span>
                                    <div class="hover-box flex flex-col invisible absolute ltr:right-2 rtl:left-2 top-2 opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-300 ">
                                        <button class="flex items-center justify-center bg-white p-2.5 mb-1 rounded-full">
                                            <iconify-icon icon="heroicons:heart-20-solid"></iconify-icon>
                                        </button>
                                        <button class="flex items-center justify-center bg-white p-2.5 mb-1 rounded-full  ">
                                            <iconify-icon icon="heroicons:eye"></iconify-icon>
                                        </button>
                                        <button class="flex items-center justify-center bg-white p-2.5 mb-1 rounded-full ">
                                            <iconify-icon icon="heroicons:arrow-path-20-solid"></iconify-icon>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <div>
                            <div class="flex justify-between items-center ">
                                <p class="text-xs text-slate-900 dark:text-slate-300 uppercase font-normal">bonus</p>
                                <span class="flex items-center text-slate-900 dark:text-slate-300 font-normal text-xs space-x-1 rtl:space-x-reverse ">
                                    <iconify-icon class="text-warning-500" icon="heroicons:star-16-solid"></iconify-icon>
                                    <span>4.8</span>
                                </span>
                            </div>
                            <h6 class="text-slate-900 dark:text-slate-300 text-base	font-medium	mt-2 truncate">
                                <a href="/products/1">50% Margin Bonus</a>
                            </h6>
                            <p class="text-slate-500 dark:text-slate-500  text-[11px]  font-normal mt-1.5">
                                Increase Your Trading Capacity
                            </p>
                            <p class="pb-4">
                                <span class="text-slate-900 dark:text-slate-300 text-base font-medium mt-2 ltr:mr-2 rtl:mr-2">$489</span>
                                <del class="text-slate-500 dark:text-slate-500 font-normal text-base">$700</del>
                            </p>
                            <button type="button" class="btn btn inline-flex justify-center btn-outline-dark w-full btn-sm  font-medium hover:bg-slate-900 hover:text-white dark:hover:text-white  dark:hover:bg-slate-700 ">
                                <span class="flex items-center">
                                    <span class="ltr:mr-2 rtl:ml-2 text-sm leading-none">
                                        <iconify-icon icon="heroicons:shopping-bag"></iconify-icon>
                                    </span>
                                    <span>Apply Bonus</span>
                                </span>
                            </button>
                        </div>
                    </main>
                </div>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-12 gap-5">
        <div class="col-span-12">
            
        </div>
    </div>

@endsection