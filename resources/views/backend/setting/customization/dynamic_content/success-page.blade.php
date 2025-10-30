@extends('backend.setting.customization.index')
@section('title')
    {{ __('Success Page') }}
@endsection
@section('customization-content')
    <!-- top navigation tabs -->
    @include('backend.setting.customization.dynamic_content.__tabs_nav')

    <!-- content -->
    <div class="card">
        <div class="card-body px-6 pt-3">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden basicTable_wrapper">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('#') }}</th>
                                    <th scope="col" class="table-th">{{ __('Name') }}</th>
                                    <th scope="col" class="table-th">{{ __('Type') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                @foreach ($successPages as $successPage)
                                    <tr>
                                        <td class="table-td">{{ $loop->index + 1 }}</td>
                                        <td class="table-td">
                                            <strong>{{ $successPage->name }}</strong>
                                        </td>
                                        <td class="table-td">
                                            <span class="badge badge-secondary uppercase">{{ $successPage->type }}</span>
                                        </td>
                                        <td class="table-td">
                                            <a href="{{ route('admin.settings.dynamic-content.success-page.edit', $successPage->id) }}" class="action-btn" data-tippy-content="{{ __('Edit') }}">
                                                <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection