@if($swapMultiLevels->isEmpty())
    <p class="text-center text-slate-600 dark:text-slate-400 bg-slate-50 dark:bg-slate-900 text-sm rounded py-5">
        {{ __('No Data Found') }}
    </p>
@else
    <div class="col-span-12 pt-3">
        <h4 class="card-title mb-5">
            {{ __('Swap Accounts') }}
        </h4>
    </div>
    <div class="grid grid-cols-12 gap-5">
        <div class="col-span-12">
            <div class="card">
                <div class="card-body px-6 pb-6">
                    <div class="overflow-x-auto -mx-6">
                        <div class="inline-block min-w-full align-middle">
                            <div class="overflow-hidden ">
                                <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                    <thead class=" border-t border-slate-100 dark:border-slate-800">
                                    <th scope="col" class="table-th">{{ __('Account Type') }}</th>
                                    <th scope="col" class="table-th">{{ __('Level') }}</th>
                                    <th scope="col" class="table-th">{{ __('share(%)') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                    @foreach($swapMultiLevels as $index => $level)
                                        <tr>
                                            <td class="table-td">
                                                <div class="flex items-center">
                                                    <div class="flex-none">
                                                        <div class="w-10 h-10 lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center mr-2">
                                                            <iconify-icon icon="heroicons:flag"></iconify-icon>
                                                        </div>
                                                    </div>
                                                    <div class="flex-1 text-start">
                                                        <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                                            {{ $level->forexSchema->title }}
                                                        </h4>
                                                        <div class="text-xs !text-nowrap font-normal text-slate-600 dark:text-slate-400">
                                                            {{ __('Created ') . $level->created_at }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="table-td">
                                                <span class="block text-left">
                                                    <span class="inline-block text-center mx-auto py-1">
                                                        <span class="flex items-center space-x-3 rtl:space-x-reverse">
                                                        {{ $levelOrder }}
                                                        </span>
                                                    </span>
                                                </span>
                                            </td>
                                            <td class="table-td">
                                                <span class="block text-left">
                                                    <span class="inline-block text-center mx-auto py-1">
                                                        <span class="flex items-center space-x-3 rtl:space-x-reverse">
                                                        <?php
                                                            $share = 0;
                                                            $userIbRules = $level->userIbRules()->where('user_id',auth()->user()->id)->first();
                                                            if($userIbRules){
                                                                $share = $userIbRules->share;
                                                            }
                                                        ?>
                                                            {{ $share  }}
                                                        </span>
                                                    </span>
                                                </span>
                                            </td>
                                            <td class="table-td">
                                                <div class="flex space-x-3 rtl:space-x-reverse">
                                                    <button type="button" class="action-btn edit-share-btn" data-id="{{ $level->id }}" data-share="{{ $share }}" data-context="swap"
                                                            data-bs-toggle="modal" data-bs-target="#editShareModal">
                                                        <iconify-icon icon="heroicons:pencil"></iconify-icon>
                                                    </button>

                                                </div>
                                            </td>

                                        </tr>
                                    @endforeach
                                    @if($swapMultiLevels->isEmpty())
                                        <tr>
                                            <td class="table-td text-center" colspan="3">{{ __('No Data Found') }}</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if($levelOrder != 0 )
    <div class="col-span-12 pt-3">
        <h4 class="card-title mb-5">
            {{ __('Swap Free/Islamic Accounts') }}
        </h4>
    </div>
    @if($swapFreeMultiLevels->isEmpty())
        <div class="col-span-12">
            <p class="text-center text-slate-600 dark:text-slate-400 bg-slate-50 dark:bg-slate-900 text-sm rounded py-5">
                {{ __('No Data Found') }}
            </p>
        </div>
    @else
        <div class="grid grid-cols-12 gap-5">
            <div class="col-span-12">
                <div class="card">
                    <div class="card-body px-6 pb-6">
                        <div class="overflow-x-auto -mx-6">
                            <div class="inline-block min-w-full align-middle">
                                <div class="overflow-hidden ">
                                    <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                        <thead class=" border-t border-slate-100 dark:border-slate-800">
                                        <th scope="col" class="table-th">{{ __('Account Type') }}</th>
                                        <th scope="col" class="table-th">{{ __('Level') }}</th>
                                        <th scope="col" class="table-th">{{ __('share(%)') }}</th>
                                        <th scope="col" class="table-th">{{ __('Action') }}</th>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                        @foreach($swapFreeMultiLevels as $index => $level)
                                            <tr>
                                                <td class="table-td">
                                                    <div class="flex items-center">
                                                        <div class="flex-none">
                                                            <div class="w-10 h-10 lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center mr-2">
                                                                <iconify-icon icon="heroicons:flag"></iconify-icon>
                                                            </div>
                                                        </div>
                                                        <div class="flex-1 text-start">
                                                            <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                                                {{ $level->forexSchema->title }}
                                                            </h4>
                                                            <div class="text-xs !text-nowrap font-normal text-slate-600 dark:text-slate-400">
                                                                {{ __('Created ') . $level->created_at }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="table-td">
                                                <span class="block text-left">
                                                    <span class="inline-block text-center mx-auto py-1">
                                                        <span class="flex items-center space-x-3 rtl:space-x-reverse">
                                                        {{ $levelOrder }}
                                                        </span>
                                                    </span>
                                                </span>
                                                </td>
                                                <td class="table-td">
                                                <span class="block text-left">
                                                    <span class="inline-block text-center mx-auto py-1">
                                                        <span class="flex items-center space-x-3 rtl:space-x-reverse">
                                                        <?php
                                                            $share = 0;
                                                            $userIbRules = $level->userIbRules()->where('user_id',auth()->user()->id)->first();
                                                            if($userIbRules){
                                                                $share = $userIbRules->share;
                                                            }
                                                            ?>
                                                            {{ $share  }}
                                                        </span>
                                                    </span>
                                                </span>
                                                </td>
                                                <td class="table-td">
                                                    <div class="flex space-x-3 rtl:space-x-reverse">
                                                        <button type="button" class="action-btn edit-share-btn" data-id="{{ $level->id }}" data-share="{{ $share }}" data-context="swapFree"
                                                                data-bs-toggle="modal" data-bs-target="#editShareModal">
                                                            <iconify-icon icon="heroicons:pencil"></iconify-icon>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @if($swapFreeMultiLevels->isEmpty())
                                            <tr>
                                                <td class="table-td text-center" colspan="3">{{ __('No Data Found') }}</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endif
