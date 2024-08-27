<br>
<h4 class="card-title">{{ __('Swap Accounts') }}</h4>
@if($swapSchemas->isEmpty())

    <tr>
        <td class="table-td text-center" colspan="3">{{ __('No Data Found') }}</td>
    </tr>
@else


    @foreach($swapSchemas as $index => $schema)

        <div class="input-area grid grid-cols-12 items-center gap-5">
            <div class="lg:col-span-2 col-span-12 form-label !mb-0">
                {{ $schema->title }}
            </div>
            <div class="lg:col-span-10 col-span-12">
                <div class="relative">
                    <?php
                    $level = '';
                    $multiLevel = $schema->multiLevels()->where('level_order', $levelOrder)->where('type', \App\Enums\MultiLevelType::SWAP)->where('status', true)->first();
                    if ($multiLevel) {
//                   $level =  the_hash($multiLevel->id);
                        $level = $multiLevel->id;
                    }

                    ?>
                    <input type="text" class="form-control !pr-32" id="standard-input{{ $index }}"
                           value="{{ $getReferral->link }}@if($level)&level={{$level}} @endif " readonly>
                    <span
                        class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-none flex items-center justify-center">
                    <a href="javascript:;" class="copy-button" type="button"
                       data-target="#standard-input{{ $index }}">{{ __('Copy Link') }}</a>
                </span>
                </div>
            </div>
        </div>
    @endforeach
@endif
@if($levelOrder != 0 )
    <br>
    <br>
    <h4 class="card-title">{{ __('Swap Free/Islamic Accounts') }}</h4>
    @if($swapFreeSchemas->isEmpty())
        <tr>
            <td class="table-td text-center" colspan="3">{{ __('No Data Found') }}</td>
        </tr>
    @else
        @foreach($swapFreeSchemas as $index => $schema)

            <div class="input-area grid grid-cols-12 items-center gap-5">
                <div class="lg:col-span-2 col-span-12 form-label !mb-0">
                    {{ $schema->title }}
                </div>
                <div class="lg:col-span-10 col-span-12">
                    <div class="relative">
                        <?php
                        $level = '';
                        $multiLevel = $schema->multiLevels()->where('level_order', $levelOrder)->where('type', \App\Enums\MultiLevelType::SWAP_FREE)->where('status', true)->first();
                        if ($multiLevel) {
//                   $level =  the_hash($multiLevel->id);
                            $level = $multiLevel->id;
                        }

                        ?>
                        <input type="text" class="form-control !pr-32" id="standard-input{{ $index }}"
                               value="{{ $getReferral->link }}@if($level)&level={{$level}} @endif " readonly>
                        <span
                            class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-none flex items-center justify-center">
                    <a href="javascript:;" class="copy-button" type="button"
                       data-target="#standard-input{{ $index }}">{{ __('Copy Link') }}</a>
                </span>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
@endif
