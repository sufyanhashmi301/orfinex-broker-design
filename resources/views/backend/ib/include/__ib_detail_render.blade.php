<!-- resources/views/ib_data_modal.blade.php -->

<div>
    <h4 class="text-lg font-medium mb-3">IB Data for {{ $user->full_name ?? 'User' }}</h4>

    @if (isset($error))
        <div
            class="alert alert-warning p-3 mb-3 rounded bg-yellow-50 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200">
            <iconify-icon class="text-lg mr-2" icon="lucide:alert-circle"></iconify-icon>
            {{ $error }}
        </div>
    @elseif(!$ibData)
        <div class="alert alert-info p-3 mb-3 rounded bg-blue-50 dark:bg-blue-900/20 text-blue-800 dark:text-blue-200">
            <iconify-icon class="text-lg mr-2" icon="lucide:info"></iconify-icon>
            <strong>No Questionnaire Data Available</strong><br>
            <span class="text-sm mt-1 block">This user has not submitted any IB questionnaire data yet.</span>
        </div>
    @elseif(isset($isEmpty) && $isEmpty)
        <div class="alert alert-info p-3 mb-3 rounded bg-blue-50 dark:bg-blue-900/20 text-blue-800 dark:text-blue-200">
            <iconify-icon class="text-lg mr-2" icon="lucide:info"></iconify-icon>
            <strong>No Valid Data Found</strong><br>
            <span class="text-sm mt-1 block">The user may not have submitted valid data, or no questionnaire was set by
                the admin at the time of submission.</span>
        </div>
    @else
        @php
            // Handle both string (JSON) and array cases for backward compatibility
            $fields = $ibData->fields;

            // Handle string "null" case
            if ($fields === 'null' || $fields === null) {
                $fields = [];
            } elseif (is_string($fields)) {
                // Try to decode JSON
                $decoded = json_decode($fields, true);
                $fields = json_last_error() === JSON_ERROR_NONE && is_array($decoded) ? $decoded : [];
            } elseif (!is_array($fields)) {
                $fields = [];
            }
        @endphp

        @if (empty($fields))
            <div
                class="alert alert-info p-3 mb-3 rounded bg-blue-50 dark:bg-blue-900/20 text-blue-800 dark:text-blue-200">
                <iconify-icon class="text-lg mr-2" icon="lucide:info"></iconify-icon>
                <strong>Empty Questionnaire Data</strong><br>
                <span class="text-sm mt-1 block">The user may not have submitted valid data, or no questionnaire was set
                    by the admin at the time of submission.</span>
            </div>
        @else
            <ul class="space-y-4">
                @foreach ($fields as $question => $answer)
                    <li class="text-sm">
                        <span class="font-medium dark:text-slate-300">{{ $question }}:</span>
                        @if (is_array($answer))
                            <ul class="list-by-slash mt-1">
                                @foreach ($answer as $option)
                                    <li class="dark:text-slate-300">{{ $option }}</li>
                                @endforeach
                            </ul>
                        @else
                            <span class="dark:text-slate-300">{{ $answer ?? 'N/A' }}</span>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    @endif
</div>
