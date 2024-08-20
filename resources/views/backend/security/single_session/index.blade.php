@extends('backend.security.index')
@section('title')
    {{ __('Single Session') }}
@endsection
@section('security-content')
    <div class="card">
        <div class="card-body p-6">
            <form action="" method="post">
                <div class="input-area">
                    <label for="" class="form-label block mb-4">
                        Prevent user from being logged in more than once ?
                        <span class="text-danger-500 ml-1">*</span>
                    </label>
                    <div class="flex items-center space-x-7 flex-wrap">
                        <div class="basicRadio">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" class="hidden" name="singleSession" value="yes" checked="checked">
                                <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                <span class="text-secondary-500 text-sm leading-6 capitalize">
                                    Yes
                                </span>
                            </label>
                        </div>
                        <div class="basicRadio">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" class="hidden" name="singleSession" value="no">
                                <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                <span class="text-secondary-500 text-sm leading-6 capitalize">
                                    No
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection