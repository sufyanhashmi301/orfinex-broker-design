<div class="hv-item">
    <div class="hv-item-parent">
        <div class="person">
            <img src="{{ asset($levelUser->avatar ?? 'global/materials/user.png')}}" class="inline-flex" alt="">
            <p class="name">
                @if($me)
                    {{ __("It's Me") }}( {{ $levelUser->full_name }} )
                @else
                    <b>{{ $levelUser->full_name }} <br>
{{--                        @if(setting('deposit_level'))--}}
                            {{ __('Deposit') }} {{ $currencySymbol.$levelUser->totalDeposit() }},
{{--                        @endif--}
{{--                        {{dd($levelUser->id)}}--}}
{{--                        @if(setting('profit_level'))--}}
                            {{ __('Accounts Balance') }} {{ mt5_total_balance($levelUser->id) }}
{{--                        @endif--}}
                    </b>
                @endif
            </p>
        </div>
    </div>

    @if($depth && $level > $depth && $levelUser->referrals->count() > 0)

        <div class="hv-item-children">

            @foreach($levelUser->referrals as $levelUser)
                <div class="hv-item-child">
                    <!-- Key component -->
                    @include('frontend::referral.include.__tree',['levelUser' => $levelUser,'level' => $level,'depth' => $depth + 1,'me' => false])
                </div>
            @endforeach

        </div>

    @endif

</div>
