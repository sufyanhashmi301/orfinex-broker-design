@extends('backend.setting.data_management.index')
@section('title')
  Storage Settings
@endsection
@section('data-management-content')
    
<div class="pageTitle flex justify-between flex-wrap items-center mb-10">
  <div>
      <h4 class="font-medium text-xl capitalize dark:text-white inline-block ltr:pr-4 rtl:pl-4 mb-1">
        Storage Settings
      </h4>

      {{-- <p class="text-sm text-slate-500 dark:text-slate-300">
          {{ __('Configure verification levels and requirements') }}
      </p> --}}
  </div>
  {{-- <button style="float:right" class="btn btn-primary inline-flex items-center justify-center" type="button" data-bs-toggle="modal" data-bs-target="#config-modal">
      <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:bolt"></iconify-icon>
      Storage Settings
  </button> --}}
</div>

@if (session('image_location'))
    <div class="alert alert-success mb-4">The test AWS upload has been successful. The test file will be available for the next 10 minutes. <b><a href="{{ session('image_location') }}" target="_blank" style="text-decoration: underline">View Now</a></b></div>
@endif

<div class="grid xl:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5">
  @foreach ($storage_methods as $method)
      <div class="card border hover:shadow-lg">
          <div class="card-header items-center noborder !p-4">

              @if ($method->icon != '')
                  <img class="inline-block h-10"
                      src="{{ filter_var($method->icon, FILTER_VALIDATE_URL) ? $method->icon : asset($method->icon) }}"
                      alt="" />
              @else
                  <div>
                      <iconify-icon style="font-size: 36px; color: royalblue ; position: relative; top: 3px"
                          class="inline-block h-8" icon="lucide:folder-tree"></iconify-icon>
                  </div>
              @endif
              
              <div style="position: relative; top: -5px">
                @if ($method->method != 'filesystem')
                  <button type="button" style="display: inline-block" class="action-btn cursor-pointer dark:text-slate-300" data-bs-toggle="modal" data-bs-target="#configureAmazonS3">
                    <iconify-icon style="position: relative; top: 2px" icon="lucide:settings-2"></iconify-icon>
                  </button>
                  
                @endif
              </div>

          </div>
          <div class="card-body p-4 pt-2">
              <div class="flex items-center justify-between mb-3">
                  <h4 class="text-base font-medium dark:text-white mr-1 capitalize">{{ str_replace('_', ' ', $method->method) }}</h4>
                  @if ($method->status)
                      <div class="badge badge-success capitalize">
                          {{ __('Activated') }}
                      </div>
                  @else
                      <div class="badge badge-danger capitalize">
                          {{ __('Deactivated') }}
                      </div>
                  @endif
              </div>
              <p class="text-sm dark:text-slate-300">{{ $method->description }}</p>
          </div>

          <div>
              
              @if (!$method->status)
                <a href="{{ route('admin.settings.storage.toggle_method', ['id' => $method->id, 'action' => 'active']) }}" type="button" class="btn btn-primary btn-sm float-right {{ $method->method == \App\Enums\StorageMethodEnums::AWS_S3 && empty($method->details) ? 'disabled' : '' }}"  style="margin-right: 10px; margin-bottom: 10px; min-width: initial">Activate</a>
              @endif

              @if ($method->method == \App\Enums\StorageMethodEnums::AWS_S3 && !empty($method->details))
                <button class="mr-2 btn btn-primary btn-sm float-right " data-bs-toggle="modal" data-bs-target="#testAWSUpload">Test Upload</button>
              @endif

          </div>
      </div>

      <style>
          a.disabled, button:disabled {
              pointer-events: none !important;
              opacity: 0.6;
          }
      </style>
  @endforeach

  @include('backend.setting.data_management.storage.includes.__configure_amazon_s3_modal')

  @if (!empty($aws_storage_method->details))
    @include('backend.setting.data_management.storage.includes.__test_upload_modal')
  @endif
</div>


@endsection
@section('data-management-script')
  <script>
    $('#test-upload-form').on('submit', function() {
        $('.test-upload-submit').attr('disabled', true)
    })
  </script>
@endsection
