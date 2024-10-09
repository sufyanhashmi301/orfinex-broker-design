@if($swapSchemas->isEmpty())
    <p class="text-center text-slate-600 dark:text-slate-400 bg-slate-50 dark:bg-slate-900 text-sm rounded py-5">
        {{ __('No Data Found') }}
    </p>
@else
    @foreach($swapSchemas as $index => $schema)
        <div class="input-area grid grid-cols-12 items-center gap-5 mb-5">
            <div class="lg:col-span-2 col-span-12 form-label !mb-0">
                {{ __('') }}{{ $schema->title }}{{ __('') }}
            </div>
            <div class="lg:col-span-10 col-span-12">
                <div class="relative">
                    <?php
                    $level = '';
                    $multiLevel = $schema->multiLevels()->where('level_order', $levelOrder)->where('type', \App\Enums\MultiLevelType::SWAP)->where('status', true)->first();
                    if ($multiLevel) {
                        $level = $multiLevel->id;
                    }
                    ?>
                    <input type="text" class="form-control !pr-32" id="standard-input{{ $index }}"
                           value="{{ $getReferral->link }}@if($level)&level={{$level}} @endif " readonly>
                    <span class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-none flex items-center justify-center">
                        <a href="javascript:;" class="copy-button" type="button" data-target="#standard-input{{ $index }}">
                            {{ __('Copy Link') }}
                        </a>
                    </span>
                </div>
            </div>
        </div>
    @endforeach
@endif

@if($levelOrder != 0 )
    <div class="col-span-12 pt-3">
        <h4 class="card-title mb-5">
            {{ __('Swap Free/Islamic Accounts') }}
        </h4>
    </div>
    @if($swapFreeSchemas->isEmpty())
        <div class="col-span-12">
            <p class="text-center text-slate-600 dark:text-slate-400 bg-slate-50 dark:bg-slate-900 text-sm rounded py-5">
                {{ __('No Data Found') }}
            </p>
        </div>
    @else
        @foreach($swapFreeSchemas as $index => $schema)
            <div class="input-area grid grid-cols-12 items-center gap-5">
                <div class="lg:col-span-2 col-span-12 form-label !mb-0">
                    {{ __('') }}{{ $schema->title }}{{ __('') }}
                </div>
                <div class="lg:col-span-10 col-span-12">
                    <div class="relative">
                        <?php
                        $level = '';
                        $multiLevel = $schema->multiLevels()->where('level_order', $levelOrder)->where('type', \App\Enums\MultiLevelType::SWAP_FREE)->where('status', true)->first();
                        if ($multiLevel) {
                            $level = $multiLevel->id;
                        }
                        ?>
                        <input type="text" class="form-control !pr-32" id="standard-input{{ $index }}"
                               value="{{ $getReferral->link }}@if($level)&level={{$level}} @endif " readonly>
                        <span class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-none flex items-center justify-center">
                            <a href="javascript:;" class="copy-button" type="button" data-target="#standard-input{{ $index }}">
                                {{ __('Copy Link') }}
                            </a>
                        </span>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
@endif
