@php
    $userBranchId = getUserBranchId($row->id, $row);
@endphp

@if ($userBranchId)
    @php
        $userBranch = \App\Models\Branch::find($userBranchId);
    @endphp
    @if ($userBranch)
        <span class="badge badge-secondary text-xs">{{ $userBranch->name }}</span>
    @else
        <span class="text-slate-500 text-xs">{{ __('N/A') }}</span>
    @endif
@else
    <span class="text-slate-500 text-xs">{{ __('N/A') }}</span>
@endif
