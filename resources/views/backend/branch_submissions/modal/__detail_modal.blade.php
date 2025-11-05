@can('branches-form-action')
@php
    $user = $submission->user;
    $fields = $submission->fields ?? [];
@endphp
<div class="p-4">
    <div class="mb-4">
        <h4 class="text-lg font-medium dark:text-white">{{ __('Submission Details') }}</h4>
        <p class="text-sm text-slate-500 dark:text-slate-300">{{ __('User:') }} {{ $user?->full_name }} ({{ $user?->email }})</p>
        <p class="text-sm text-slate-500 dark:text-slate-300">{{ __('Branch:') }} {{ $submission->branch?->name }}</p>
        <p class="text-sm text-slate-500 dark:text-slate-300">{{ __('Submitted at:') }} {{ $submission->created_at->format('Y-m-d H:i') }}</p>
    </div>

    <div class="space-y-3">
        @foreach($fields as $name => $value)
            <div class="border border-slate-200 dark:border-slate-700 rounded p-3">
                <div class="text-xs uppercase text-slate-500 dark:text-slate-300 mb-1">{{ $name }}</div>
                @php
                    $url = $value;
                    if (is_string($value)) {
                        if (\Illuminate\Support\Str::startsWith($value, ['http://','https://'])) {
                            $url = $value;
                        } elseif (\Illuminate\Support\Str::startsWith($value, ['assets/'])) {
                            $url = asset($value);
                        } elseif (\Illuminate\Support\Str::startsWith($value, ['global/'])) {
                            // ImageUpload trait stores paths like 'global/images/...'
                            // These are directly under the site's asset root.
                            $url = asset($value);
                        } elseif (\Illuminate\Support\Str::startsWith($value, ['/'])) {
                            $url = asset(ltrim($value, '/'));
                        } else {
                            // storage fallback
                            $url = asset('storage/'.ltrim($value, 'storage/'));
                        }
                    }
                @endphp
                @if(is_string($value) && preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $value))
                    <img src="{{ $url }}" alt="{{ $name }}" class="max-h-56 rounded">
                @elseif(is_string($value) && preg_match('/\.(pdf|docx?|txt)$/i', $value))
                    <a href="{{ $url }}" target="_blank" class="btn-link inline-flex items-center">
                        <iconify-icon class="text-lg ltr:mr-1 rtl:ml-1" icon="lucide:file"></iconify-icon>
                        {{ __('View File') }}
                    </a>
                @else
                    <div class="text-sm dark:text-white">{{ is_array($value) ? implode(', ', $value) : (string)$value }}</div>
                @endif
            </div>
        @endforeach
    </div>

    <div class="flex justify-end gap-2 mt-6">
        <form class="inline" method="post" action="{{ route('admin.branch-form-submissions.update-status') }}" onsubmit="return submitSubmissionAction(event, this)">
            @csrf
            <input type="hidden" name="id" value="{{ $submission->id }}">
            <input type="hidden" name="status" value="approved">
            <button type="submit" class="btn btn-success btn-approve inline-flex items-center">
                <iconify-icon class="text-lg ltr:mr-1 rtl:ml-1" icon="lucide:check"></iconify-icon>
                {{ __('Approve') }}
            </button>
        </form>
        <form class="inline" method="post" action="{{ route('admin.branch-form-submissions.update-status') }}" onsubmit="return submitSubmissionAction(event, this)">
            @csrf
            <input type="hidden" name="id" value="{{ $submission->id }}">
            <input type="hidden" name="status" value="rejected">
            <button type="submit" class="btn btn-danger btn-reject inline-flex items-center">
                <iconify-icon class="text-lg ltr:mr-1 rtl:ml-1" icon="lucide:x"></iconify-icon>
                {{ __('Reject') }}
            </button>
        </form>
    </div>
</div>

<script>
    function submitSubmissionAction(e, formEl) {
        e.preventDefault();
        var $form = $(formEl);
        $.post($form.attr('action'), $form.serialize(), function(resp) {
            if (resp && resp.success) {
                $('#submission-action-modal').modal('hide');
                $(document).trigger('branchFormSubmissionActionCompleted');
            }
        }).fail(function(){
        });
        return false;
    }
</script>


@endcan