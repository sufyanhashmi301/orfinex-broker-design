<div class="space-y-6">
    @foreach ($data['changelog'] as $item)
        <div class="card hover:shadow-lg">
            <div class="card-body p-6">
                <div class="flex items-center justify-between mb-5">
                    <!-- Version Tag with Rounded Box -->
                    <div class="flex items-center gap-2">
                        <span class="text-lg font-medium dark:text-white">V. {{ $item['version'] }}</span>
                        @foreach ($item['badges'] as $badge)
                            @if($badge == 'new')
                                <span class="badge badge-success">
                                    {{ __('New') }}
                                </span>
                            @elseif($badge == 'update')
                                <span class="badge badge-warning">
                                    {{ __('Improved') }}
                                </span>
                            @elseif($badge == 'delete')
                                <span class="badge badge-danger">
                                    {{ __('Removed') }}
                                </span>
                            @endif
                        @endforeach
                    </div>
                    <span class="text-sm text-slate-600 dark:text-slate-400">
                        {{ $item['date'] }}
                    </span>
                </div>
                <div class="grid @if($item['image'] != null) md:grid-cols-2 @endif grid-cols-1 gap-x-10 gap-y-5">
                    <ul class="space-y-3 pl-5">
                        @foreach ($item['changes'] as $change)
                            <li class="text-base text-slate-900 dark:text-slate-300 flex space-x-2 items-start rtl:space-x-reverse">
                                <iconify-icon class="relative top-1" icon="heroicons-outline:chevron-right"></iconify-icon>
                                <span>{{ $change }}</span>
                            </li>
                        @endforeach
                    </ul>
                    @if($item['image'] != null)
                        <img src="{{ $item['image'] }}" class="w-full" alt="">
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>
