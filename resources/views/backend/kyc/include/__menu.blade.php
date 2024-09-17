<div class="innerMenu card p-4 mb-5">
    @php
    $kycLevels = \App\Models\KycLevel::get();
    @endphp
    <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4 menu-open">
        @canany(['kyc-list','kyc-action'])
        <li class="nav-item">
                <a href="{{ route('admin.kyc.all') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.kyc.all') }}">
                    {{ __('All KYC Logs') }}
                </a>
            </li>
        @foreach( $kycLevels as $kycLevel)
            @if($kycLevel->slug==\App\Enums\KycLevelSlug::LEVEL2 && $kycLevel->status==1)
            <li class="nav-item">
                <a href="{{ route('admin.kyc.pending') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.kyc.pending') }}">
                   {{$kycLevel->name}} {{ __(' Pending KYC') }}
                </a>
            </li>
            @elseif($kycLevel->slug==\App\Enums\KycLevelSlug::LEVEL3 && $kycLevel->status==1)
            <li class="nav-item">
                <a href="{{ route('admin.kyc.level3.pending') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.kyc.level3.pending') }}">
                {{$kycLevel->name}} {{ __(' Pending KYC') }}
                </a>
            </li>
            @else
            @endif
            @endforeach
            <li class="nav-item">
                <a href="{{ route('admin.kyc.rejected') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.kyc.rejected') }}">
                    {{ __('Rejected KYC') }}
                </a>
            </li>
        @endcanany
        <li class="nav-item">
            <a href="{{ route('admin.kyclevels.index') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.kyclevels*') }}">
                {{ __('KYC Levels') }}
            </a>
        </li>
    </ul>
</div>
