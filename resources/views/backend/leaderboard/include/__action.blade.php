@can('customer-network-stats')
    <a href="{{ route('admin.referral-network.report', ['email' => $user->email]) }}" target="_blank" class="btn btn-sm btn-dark inline-flex items-center justify-center">
        <iconify-icon class="text-sm ltr:mr-2 rtl:ml-2" icon="lucide:network"></iconify-icon>
        <span>{{ __('Network stats') }}</span>
    </a>
@endcan