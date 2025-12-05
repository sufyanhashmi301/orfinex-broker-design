@can('customer-network-stats')
    <a href="{{ route('admin.referral-network.report', ['email' => $user->email]) }}" target="_blank" class="btn btn-sm btn-dark inline-flex items-center justify-center">
        <span>{{ __('Network stats') }}</span>
    </a>
@endcan