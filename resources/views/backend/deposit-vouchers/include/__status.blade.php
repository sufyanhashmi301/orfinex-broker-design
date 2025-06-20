@php
    $statusClass = [
        'active' => 'success',
        'used' => 'secondary',
        'expired' => 'danger'
    ][$status] ?? 'secondary';
@endphp

<span class="badge badge-{{ $statusClass }}">
    {{ ucfirst($status) }}
</span> 