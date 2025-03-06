@extends('backend.layouts.app')
@section('title')
    {{ __('Create New Deal') }}
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>
    <div class="card">
        <form action="{{ route('admin.deal.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card-body p-6">
                <div class="grid md:grid-cols-2 grid-cols-1 gap-5">
                    <div class="input-area">
                        <label class="form-label" for="">
                            {{ __('Lead Contact:') }}
                            <span class="text-xs text-danger">*</span>
                        </label>
                        <select name="lead_id" id="lead_id" class="select2 form-control">
                            <option value="">{{ __('Select Contact') }}</option>
                            @foreach($leads as $lead)
                                <option value="{{ $lead->id }}">{{ ucfirst($lead->salutation).' '.$lead->first_name.' '.$lead->last_name }}</option>
                            @endforeach
                        </select>
                        @error('lead_id')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="">
                            {{ __('Deal Name:') }}
                            <span class="text-xs text-danger">*</span>
                        </label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Acme Corporation">
                        @error('name')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="">
                            {{ __('Pipeline:') }}
                            <span class="text-xs text-danger">*</span>
                        </label>
                        <select name="lead_pipeline_id" id="pipelineData" class="select2 form-control">
                            <option value="">{{ __('Select Pipeline') }}</option>
                            @foreach($pipelines as $pipeline)
                                <option value="{{ $pipeline->id }}">{{ $pipeline->name }}</option>
                            @endforeach
                        </select>
                        @error('lead_pipeline_id')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="">
                            {{ __('Deal Stages:') }}
                            <span class="text-xs text-danger">*</span>
                        </label>
                        <select name="pipeline_stage_id" id="stages" class="select2 form-control">
                            <option value="">{{ __('select stage') }}</option>
                        </select>
                        @error('pipeline_stage_id')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="input-area">
                        <label for="" class="form-label">
                            {{ __('Deal Value:') }}
                            <span class="text-xs text-danger">*</span>
                        </label>
                        <div class="joint-input relative">
                            <input type="text" class="form-control" name="value" value="0">
                            <span class="absolute right-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center px-2">
                                {{ setting('site_currency', 'global') }}
                            </span>
                        </div>
                        @error('value')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="input-area">
                        <label for="" class="form-label">
                            {{ __('Close Date') }}
                            <span class="text-xs text-danger">*</span>
                        </label>
                        <input type="text" name="close_date" class="form-control py-2 flatpickr flatpickr-input" readonly>
                        @error('close_date')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="card-header">
                <h4 class="card-title">{{ __('Lead Contact Detail') }}</h4>
            </div>
            <div class="card-body p-6" id="contact-details">
                <ul class="contact-details__list space-y-3">
                    <li class="text-sm text-slate-600 dark:text-slate-300 py-2">
                        <div class="flex justify-between">
                            <span class="font-medium">{{ __('Lead Contact:') }}</span>
                            <span class="w-2/3">{{ __('-') }}</span>
                        </div>
                    </li>
                    <li class="text-sm text-slate-600 dark:text-slate-300 py-2">
                        <div class="flex justify-between">
                            <span class="font-medium">{{ __('Email:') }}</span>
                            <span class="w-2/3">{{ __('-') }}</span>
                        </div>
                    </li>
                    <li class="text-sm text-slate-600 dark:text-slate-300 py-2">
                        <div class="flex justify-between">
                            <span class="font-medium">{{ __('Mobile:') }}</span>
                            <span class="w-2/3">{{ __('-') }}</span>
                        </div>
                    </li>
                    <li class="text-sm text-slate-600 dark:text-slate-300 py-2">
                        <div class="flex justify-between">
                            <span class="font-medium">{{ __('Company Name:') }}</span>
                            <span class="w-2/3">{{ __('-') }}</span>
                        </div>
                    </li>
                    <li class="text-sm text-slate-600 dark:text-slate-300 py-2">
                        <div class="flex justify-between">
                            <span class="font-medium">{{ __('Company Phone:') }}</span>
                            <span class="w-2/3">{{ __('-') }}</span>
                        </div>
                    </li>
                    <li class="text-sm text-slate-600 dark:text-slate-300 py-2">
                        <div class="flex justify-between">
                            <span class="font-medium">{{ __('Website:') }}</span>
                            <span class="w-2/3">{{ __('-') }}</span>
                        </div>
                    </li>
                    <li class="text-sm text-slate-600 dark:text-slate-300 py-2">
                        <div class="flex justify-between">
                            <span class="font-medium">{{ __('Address:') }}</span>
                            <span class="w-2/3">{{ __('-') }}</span>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="text-right p-6">
                <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                    {{ __('Add Deal') }}
                </button>
            </div>
        </form>
    </div>

@endsection
@section('style')
    <style>
        .contact-details__list {
            columns: 2;
        }
    </style>
@endsection
@section('script')
    <script !src="">
        function getStages(pipelineId) {
            var url = "{{ route('admin.deal.get-stage', ':id') }}";
            url = url.replace(':id', pipelineId);

            $.get(url, function (response){
                var options = [];

                $.each(response.data, function (index, value) {
                    options.push({
                        id: value.id,
                        text: value.name,
                        label_color: value.label_color
                    });
                });

                $('#stages').empty();
                $.each(options, function (index, item) {
                    var option = new Option(item.text, item.id, false, false);
                    $(option).attr('data-color', item.label_color);
                    $('#stages').append(option);
                });

                $('#stages').select2({
                    templateResult: function (data) {
                        var $result = $(`
                            <span class="flex space-x-2 rtl:space-x-reverse items-center">
                                <span class="inline-flex h-[10px] w-[10px] rounded-full" style="background-color: ${$(data.element).attr('data-color')}"></span>
                                <span>${data.text}</span>
                            </span>`
                        );
                        return $result;
                    },
                    templateSelection: function (data) {
                        var $result = $(`
                            <span class="flex space-x-2 rtl:space-x-reverse items-center">
                                <span class="inline-flex h-[10px] w-[10px] rounded-full" style="background-color: ${$(data.element).attr('data-color')}"></span>
                                <span>${data.text}</span>
                            </span>`
                        );
                        return $result;
                    }
                });
            });
        }

        $('#pipelineData').on("change", function (e) {
            let pipelineId = $(this).val();
            getStages(pipelineId);
        });

        $('#lead_id').on("change", function (e) {
            let id = $(this).val();

            var url = "{{ route('admin.lead.getLead', ':id') }}";
            url = url.replace(':id', id);

            $('#contact-details').empty();

            $.get(url, function (response){
                $('#contact-details').append(response);
            });

        });

    </script>
@endsection
