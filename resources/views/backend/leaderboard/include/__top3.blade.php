<div class="bg-slate-900 p-6 pt-10">
    <div class="max-w-4xl flex flex-col sm:items-end justify-between sm:flex-row gap-5 mx-auto">
        @foreach ($top3 as $index => $user)
            @php
                $order = match($index) {
                    0 => 'sm:order-2', // 1st in center
                    1 => 'sm:order-1', // 2nd on left
                    2 => 'sm:order-3', // 3rd on right
                };
            @endphp
            <div class="flex-1 {{ $order }}">
                <div class="h-[70px] w-[70px] rounded-full border border-slate-700 mx-auto mb-1">
                    <img src="{{ getFilteredPath($user->avatar, 'fallback/user.png') }}" alt="" class="w-full h-full rounded-full">
                </div>
                <div class="text-center mb-5">
                    <h4 class="text-base font-semibold text-white whitespace-nowrap">
                        {{ $user->full_name }}
                    </h4>
                    <div class="text-sm font-normal text-slate-100">
                        {{ $user->email }}
                    </div>
                </div>
                <div class="bg-gradient-to-b from-slate-200/70 to-slate-100/70 text-center w-full rounded-lg py-8">
                    <div class="text-3xl font-semibold text-white {{ $index === 0 ? 'mt-10' : '' }}">{{ $loop->iteration }}</div>
                </div>
            </div>
        @endforeach
    </div>
</div>