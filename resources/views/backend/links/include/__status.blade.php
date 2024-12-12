<span @class(['badge capitalize', 'badge-success' => $status, 'badge-danger' => !$status])>
{{ $status ? 'Active' : 'Inactive' }}
</span>
