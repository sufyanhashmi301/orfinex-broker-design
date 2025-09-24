@if($account->schema)
    <a href="{{ route('admin.accountType.index') }}?filter_schema={{ $account->schema->id }}&title={{ urlencode($account->schema->title) }}">
        <strong>{{ $account->schema->title }}</strong>
    </a>
@else
    <strong>{{ __('N/A') }}</strong>
@endif