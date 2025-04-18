<ul class="list-item space-y-3 h-full overflow-x-auto">
    @foreach($staff as $staff)
        <li class="flex items-center space-x-3 rtl:space-x-reverse border-b border-slate-100 dark:border-slate-700 last:border-b-0 pb-3 last:pb-0">
            <a href="javascript:;" class="edit-staff flex items-center w-full" data-id="{{$staff->id}}" type="button">
                <div class="flex-none">
                    <div class="w-10 h-10 rounded-[100%] ltr:mr-3 rtl:ml-3 ring-2 ring-slate-100 dark:ring-slate-100">
                        <img src="{{ getFilteredPath($staff->avatar, 'frontend/images/avatar/av-4.svg') }}" alt="" class="w-full h-full rounded-[100%] object-cover">
                    </div>
                </div>
                <div class="flex-1 text-start">
                    <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                        {{$staff->first_name}} {{$staff->last_name}}
                        <span class="badge-primary text-xs capitalize rounded-lg px-2 py-0.5 ml-1">
                            {{ $staff->getRoleNames()->first() }}
                        </span>
                    </h4>
                    <div class="text-xs font-normal text-slate-500 dark:text-slate-400">
                        @if(isset($staff->designation))
                            {{ $staff->designation->name }}
                        @else
                            <span>-</span>
                        @endif
                    </div>
                    <div class="text-xs font-normal text-slate-800 dark:text-slate-400">
                        {{ $staff->email }}
                    </div>
                </div>
            </a>
        </li>
    @endforeach
</ul>
