@extends('backend.layouts.app')
@section('title')
    {{ __('Deals') }}
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
        <div class="flex flex-wrap sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            <div class="input-area relative w-[285px]">
                <div class="flex items-center">
                    <label for="" class="form-label !w-auto !mb-0 mr-2">{{ __('Pipeline:') }}</label>
                    <select id="pipeline_select" class="select2 form-control">
                        <option value="">{{ __('Select Pipeline') }}</option>
                        @foreach($pipelinesbox as $option)
                            <option value="{{ $option->id }}" @if($pipeline && $pipeline->id == $option->id) selected @endif>
                                {{ $option->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <a href="{{ route('admin.deal.create') }}" class="btn btn-sm btn-primary inline-flex items-center justify-center">
                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
                {{ __('Add New Deal') }}
            </a>
        </div>
    </div>

    <div id="pipeline-stages" class="basicTable_wrapper items-stretch space-x-6 overflow-hidden overflow-x-auto pb-4 rtl:space-x-reverse" style="flex-direction: row;">
        @if($pipeline->stages->isEmpty())
            <div class="w-full flex items-center justify-center flex-col gap-3">
                <img src="{{ asset('frontend/images/icon/danger.png') }}" alt="">
                <p class="text-lg text-center text-slate-600 dark:text-slate-100 mb-3">
                    {{ __("You don't have any Pipeline.") }}
                </p>
                <a href="{{ route('admin.lead.pipeline.index') }}" class="btn btn-primary inline-flex items-center justify-center">
                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
                    {{ __('Add Pipeline') }}
                </a>
            </div>
        @else
            @foreach ($pipeline->stages as $stage)
                <div class="w-[320px] flex-none rounded transition-all duration-100 shadow-none bg-slate-200 dark:bg-slate-700">
                    <div class="relative flex justify-between items-center bg-white dark:bg-slate-800 rounded shadow-base px-6 py-5">
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 h-8 w-[2px]" style="background-color: {{ $stage->label_color }}"></span>
                        <h3 class="text-lg text-slate-900 dark:text-white font-medium capitalize">
                            {{ $stage->name }}
                        </h3>
                    </div>

                    <div id="{{ __('stage-container__').$stage->slug }}" class="min-h-full" data-stage-id="{{ $stage->id }}" data-stage-slug="{{ $stage->slug }}">
                        @if ($stage->deals->isEmpty())
                            <div class="p-2">
                                <a href="{{ route('admin.deal.create') }}" data-pipeline-id="{{ $pipeline->id }}" class="non-draggable w-full leading-0 inline-flex justify-center bg-white text-slate-700 dark:bg-slate-800 dark:text-slate-300 !font-normal px-2 py-5">
                                    <span class="flex items-center">
                                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2 font-light" icon="lucide:plus"></iconify-icon>
                                        <span>{{ __('Add Deal') }}</span>
                                    </span>
                                </a>
                            </div>
                        @else
                            @foreach ($stage->deals as $deal)
                                <div class="p-2 h-full space-y-4 rounded-bl rounded-br" data-deal-id="{{ $deal->id }}">
                                    <div class="card rounded-md bg-white dark:bg-slate-800 shadow-base custom-class card-body p-3">
                                        <div class="flex fle-wrap items-center justify-between gap-3 mb-3">
                                            <a href="{{ route('admin.deal.show', $deal->id) }}" class="font-medium hover:underline">
                                                {{ $deal->name }}
                                            </a>
                                            <p class="text-slate-600 dark:text-slate-400 text-xs">
                                                {{ setting('site_currency', 'global').' '.$deal->value }}
                                            </p>
                                        </div>
                                        <div class="flex items-center text-slate-600 dark:text-slate-400 text-sm">
                                            <iconify-icon class="mr-1" icon="lucide:building-2"></iconify-icon>
                                            {{ $deal->lead->first_name.' '.$deal->lead->last_name }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    {{-- Modal for deal note--}}
    @include('backend.deals.modal.__note')

@endsection
@section('script')
    <script>
        $(document).ready(function (){
            $('.createDeal').click(function() {
                var pipelineId = $(this).data('pipeline-id');
                var url = "{{ route('admin.deal.create') }}?pipeline_id=" + pipelineId;
                window.location.href = url;
            });
        })
        function initializeDragula(containers) {
            dragula(containers)
            .on('drop', function (el, target) {
                var dealId = el.getAttribute('data-deal-id');
                var stageId = target.getAttribute('data-stage-id');
                var stageSlug = target.getAttribute('data-stage-slug');

                updateDealStage(dealId, stageId, stageSlug);
            });
        }

        var stageContainers = [
            @foreach($pipeline->stages as $stage)
                "{{ __('stage-container__') . $stage->slug }}",
            @endforeach
        ];
        var containers = stageContainers.map(function(stageId) {
            return document.getElementById(stageId);
        }).filter(function(container) {
            return container !== null;
        });
        initializeDragula(containers);

        $('#pipeline_select').change(function() {
            var pipelineId = $(this).val();

            $.ajax({
                url: '{{ route('admin.deal.index') }}',
                type: 'GET',
                data: {
                    pipeline_id: pipelineId
                },
                success: function(response) {
                    var stages = response.stages;
                    $('#pipeline-stages').html(response.view);

                    var containers = stages.map(function(stage) {
                        var stageId = "{{ __('stage-container__') }}" + stage.slug;
                        return document.getElementById(stageId);
                    });

                    initializeDragula(containers);
                },
                error: function(xhr, status, error) {
                    console.log('Error:', error);
                }
            });
        });

        function updateDealStage(dealId, stageId, stageSlug) {
            fetch('{{ route('admin.deal.stageUpdate', ':deal') }}'.replace(':deal', dealId), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    stage_id: stageId,
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    if (stageSlug === 'win' || stageSlug === 'lost') {
                        $('#dealIdInput').val(dealId);
                        $('#stageSlugInput').val('Remark('+ stageSlug+')');
                        $('#dealStageInput').val(stageSlug);
                        $('#dealNote').modal('show');
                    }else{
                        tNotify('success', data.message);
                        location.reload();
                    }
                }
                // location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
@endsection
