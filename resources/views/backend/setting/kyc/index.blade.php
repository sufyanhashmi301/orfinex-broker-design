@extends('backend.setting.user_management.index')
@section('title')
    {{ __('KYC Methods') }}
@endsection
@section('user-management-content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-10">
        <div>
            <h4 class="font-medium text-xl capitalize dark:text-white inline-block ltr:pr-4 rtl:pl-4 mb-1">
                @yield('title')
            </h4>
            <p class="text-sm text-slate-500 dark:text-slate-300">
                {{ __('Configure verification levels and requirements') }}
            </p>
        </div>
    </div>

    <div class="grid xl:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5">
        @foreach ($kyc_methods as $method)
            <div class="card border hover:shadow-lg">
                <div class="card-header items-center noborder !p-4">

                    @if ($method->icon != '')
                        <img class="inline-block h-10"
                            src="{{ filter_var($method->icon, FILTER_VALIDATE_URL) ? $method->icon : asset($method->icon) }}"
                            alt="" />
                    @else
                        <div>
                            <iconify-icon style="font-size: 26px; color: #198754; position: relative; top: 3px"
                                class="inline-block h-8" icon="lucide:shield-check"></iconify-icon>
                        </div>
                    @endif


                    
                    <div style="position: relative; top: -5px">

                        @if ($method->slug == 'manual')
                          <button type="button" style="display: inline-block" class="action-btn cursor-pointer dark:text-slate-300" data-bs-toggle="modal" data-bs-target="#editManualMethod">
                              <iconify-icon style="position: relative; top: 2px" icon="lucide:settings-2"></iconify-icon>
                          </button>
                          <button type="button" id="manual-method-add-option" style="display: inline-block" data-bs-toggle="modal" data-bs-target="#addManualMethodOption"
                              class="action-btn cursor-pointer dark:text-slate-300">
                              <iconify-icon style="position: relative; top: 2px" icon="lucide:plus"></iconify-icon>
                          </button>
                        @endif

                    </div>
                    

                </div>
                <div class="card-body p-4 pt-2">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-base font-medium dark:text-white mr-1">{{ $method->name }}</h4>
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
                    @if ($method->status)
                        <a href="{{ route('admin.kyc_method.method_toggle', ['id' => $method->id, 'action' => 'inactive']) }}" type="button" class="btn btn-outline-danger btn-sm float-right" style="margin-right: 10px; margin-bottom: 10px; min-width: initial">Deactivate</a>
                    @else
                        <a href="{{ route('admin.kyc_method.method_toggle', ['id' => $method->id, 'action' => 'active']) }}" type="button" class="btn btn-primary btn-sm float-right" {{ $method->status ? 'disabled' : '' }} style="margin-right: 10px; margin-bottom: 10px; min-width: initial">Activate</a>
                    @endif
                </div>
            </div>

            <style>
                button:disabled {
                    pointer-events: none;
                    opacity: 0.6;
                }
            </style>
        @endforeach
    </div>

    @include('backend.setting.kyc.includes.__add_fields_manual_modal')
    @include('backend.setting.kyc.includes.__edit_fields_manual')
@endsection

@section('user-management-script')
    <script>

      let manualAddFieldHtml = (index, is_edit = false, edit_field_index = 0) => {
        let name_attr_config = `fields[${index}]`
        if(is_edit) {
          name_attr_config = `data[${index}][fields][${edit_field_index}]`
        }
        
        return `<div class="option-remove-row grid grid-cols-12 gap-5">
                  <div class="xl:col-span-4 col-span-12">
                      <div class="input-area">
                          <input name="${name_attr_config}[name]" class="form-control" type="text" value="" required placeholder="Field Name">
                      </div>
                  </div>
                  <div class="xl:col-span-4 col-span-12">
                      <div class="input-area">
                          <select name="${name_attr_config}[type]" class="form-control w-full mb-3">
                              <option value="text">Input Text</option>
                              <option value="textarea">Textarea</option>
                              <option value="file">File upload</option>
                          </select>
                      </div>
                  </div>
                  <div class="xl:col-span-3 col-span-12">
                      <div class="input-area">
                          <select name="${name_attr_config}[validation]" class="form-control w-full mb-3">
                              <option value="required">Required</option>
                              <option value="nullable">Optional</option>
                          </select>
                      </div>
                  </div>
                  <div class="col-span-1">
                      <button class="btn-dark h-[32px] w-[32px] flex items-center justify-center rounded-full text-xl ${is_edit ? 'edit-modal-delete-field' : 'delete-option-row'} delete_desc" type="button">
                          <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                              <path fill="currentColor" d="M19 6.41L17.59 5L12 10.59L6.41 5L5 6.41L10.59 12L5 17.59L6.41 19L12 13.41L17.59 19L19 17.59L13.41 12z" />
                          </svg>
                      </button>
                  </div>
              </div>`
      }

      var addFieldIndex = 0;
      $(".generateCreate").on('click', function() {
          ++addFieldIndex;
          var form = manualAddFieldHtml(addFieldIndex)
          $('.addOptions').append(form);
      });

      $('.edit-modal-add-field').on('click', function() {
        let options_container = $(this).parents('.options-container')
        let field_index = options_container.find('.edit-modal-delete-field').length + 1;
        options_container.append( manualAddFieldHtml($(this).attr('data-index'), true, field_index))
      })

      $(document).on('click', '.delete-option-row', function() {
        if($('.delete-option-row').length == 1) {
          alert('You cannot delete the only option.')
        } else {
          $(this).closest('.option-remove-row').remove();
        }
      });

      $(document).on('click', '.edit-modal-delete-field', function() {
        if($(this).parents('.options-container').find('.edit-modal-delete-field').length == 1) {
          alert('You cannot delete the only option.')
        } else {
          $(this).closest('.option-remove-row').remove();
        }
      });

      $('.delete-edit-option-row').on('click', function() {
        let options_container = $(this).parents('.options-container')
      })

      $('#manual-method-add-option').on('click', function() {
        $('.addOptions').html('')
        $(".generateCreate").trigger('click')
      })

      $('.show-fields').on('click', function() {
        let options = $('.options-container[data-id="' + $(this).attr('data-id') + '"]')

        if(options.hasClass('show')) {
          options.removeClass('show')
          options.slideUp(100)
        } else {
          options.addClass('show')
          options.slideDown(100)
        }

      })
    </script>
@endsection
