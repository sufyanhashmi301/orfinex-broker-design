@php

    $status = $voucher->is_expired ? 'expired' : $voucher->status;

   $statusClass = [
       'active' => 'success',
       'used' => 'secondary',
       'expired' => 'danger'
   ][$status] ?? 'secondary';

@endphp

<span class="badge badge-{{ $statusClass }}">
    {{ ucfirst($status) }}
</span>
