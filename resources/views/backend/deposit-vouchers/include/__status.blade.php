@php
    $statusClass = [
        'active' => 'success',
        'used' => 'info',
        'expired' => 'danger'
    ][$status] ?? 'secondary';
@endphp

<span class="badge bg-{{ $statusClass }}">
    {{ ucfirst($status) }}
</span> 