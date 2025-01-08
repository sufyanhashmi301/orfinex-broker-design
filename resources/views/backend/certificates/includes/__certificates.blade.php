@if (count($certificates) != 0)
    <div class="card p-3">
        <div class="card-body p-6 pt-1">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden basicTable_wrapper">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Hook') }}</th>
                                  <th scope="col" class="table-th">{{ __('Name') }}</th>
                                  <th scope="col" class="table-th">{{ __('Template Uploaded') }}</th>
                                  <th scope="col" class="table-th">{{ __('Nickname Allowed') }}</th>
                                    {{-- <th scope="col" class="table-th">{{ __('Date Info.') }}</th> --}}
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($certificates as $certificate)
                                    <tr class="pt-1">
                                        <td scope="col" class="table-td " >
                                            <span class="badge badge-primary" style="text-transform: lowercase">{{ $certificate->hook }}</span>
                                          </td>
                                        <td scope="col" class="table-td">{{ $certificate->title }}</td>
                                        
                                        <td scope="col" class="table-td"> 
                                          @if ($certificate->image != '')
                                                <span class="text-success-500" data-bs-toggle="tooltip"
                                                    data-bs-title="Active">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="1.75rem"
                                                        height="1.75rem" viewBox="0 0 24 24">
                                                        <path fill="currentColor" fill-rule="evenodd"
                                                            d="M12 1.25C6.063 1.25 1.25 6.063 1.25 12S6.063 22.75 12 22.75S22.75 17.937 22.75 12S17.937 1.25 12 1.25M7.53 11.97a.75.75 0 0 0-1.06 1.06l3 3a.75.75 0 0 0 1.06 0l7-7a.75.75 0 0 0-1.06-1.06L10 14.44z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                </span>
                                            @else
                                                <span class="text-danger-500" data-bs-toggle="tooltip"
                                                    data-bs-title="Unverified">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="1.75rem"
                                                        height="1.75rem" viewBox="0 0 24 24">
                                                        <path fill="currentColor" fill-rule="evenodd"
                                                            d="M12 1.25C6.063 1.25 1.25 6.063 1.25 12S6.063 22.75 12 22.75S22.75 17.937 22.75 12S17.937 1.25 12 1.25M9.702 8.641a.75.75 0 0 0-1.061 1.06L10.939 12l-2.298 2.298a.75.75 0 0 0 1.06 1.06L12 13.062l2.298 2.298a.75.75 0 0 0 1.06-1.06L13.06 12l2.298-2.298a.75.75 0 1 0-1.06-1.06L12 10.938z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                </span>
                                            @endif
                                        </td>
                                        <td scope="col" class="table-td">
                                            @if ($certificate->nickname_allowed == 1)
                                                <span class="text-success-500" data-bs-toggle="tooltip"
                                                    data-bs-title="Active">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="1.75rem"
                                                        height="1.75rem" viewBox="0 0 24 24">
                                                        <path fill="currentColor" fill-rule="evenodd"
                                                            d="M12 1.25C6.063 1.25 1.25 6.063 1.25 12S6.063 22.75 12 22.75S22.75 17.937 22.75 12S17.937 1.25 12 1.25M7.53 11.97a.75.75 0 0 0-1.06 1.06l3 3a.75.75 0 0 0 1.06 0l7-7a.75.75 0 0 0-1.06-1.06L10 14.44z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                </span>
                                            @else
                                                <span class="text-danger-500" data-bs-toggle="tooltip"
                                                    data-bs-title="Unverified">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="1.75rem"
                                                        height="1.75rem" viewBox="0 0 24 24">
                                                        <path fill="currentColor" fill-rule="evenodd"
                                                            d="M12 1.25C6.063 1.25 1.25 6.063 1.25 12S6.063 22.75 12 22.75S22.75 17.937 22.75 12S17.937 1.25 12 1.25M9.702 8.641a.75.75 0 0 0-1.061 1.06L10.939 12l-2.298 2.298a.75.75 0 0 0 1.06 1.06L12 13.062l2.298 2.298a.75.75 0 0 0 1.06-1.06L13.06 12l2.298-2.298a.75.75 0 1 0-1.06-1.06L12 10.938z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                </span>
                                            @endif
                                        </td>
                                        {{-- <td scope="col" class="table-td">{{ str_replace('_', ' ', $certificate->date_info) }}</td> --}}
                                        
                                        <td scope="col" class="table-td">
                                            @if ($certificate->is_enabled == 1)
                                                <span class="text-success-500" data-bs-toggle="tooltip"
                                                    data-bs-title="Active">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="1.75rem"
                                                        height="1.75rem" viewBox="0 0 24 24">
                                                        <path fill="currentColor" fill-rule="evenodd"
                                                            d="M12 1.25C6.063 1.25 1.25 6.063 1.25 12S6.063 22.75 12 22.75S22.75 17.937 22.75 12S17.937 1.25 12 1.25M7.53 11.97a.75.75 0 0 0-1.06 1.06l3 3a.75.75 0 0 0 1.06 0l7-7a.75.75 0 0 0-1.06-1.06L10 14.44z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                </span>
                                            @else
                                                <span class="text-danger-500" data-bs-toggle="tooltip"
                                                    data-bs-title="Unverified">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="1.75rem"
                                                        height="1.75rem" viewBox="0 0 24 24">
                                                        <path fill="currentColor" fill-rule="evenodd"
                                                            d="M12 1.25C6.063 1.25 1.25 6.063 1.25 12S6.063 22.75 12 22.75S22.75 17.937 22.75 12S17.937 1.25 12 1.25M9.702 8.641a.75.75 0 0 0-1.061 1.06L10.939 12l-2.298 2.298a.75.75 0 0 0 1.06 1.06L12 13.062l2.298 2.298a.75.75 0 0 0 1.06-1.06L13.06 12l2.298-2.298a.75.75 0 1 0-1.06-1.06L12 10.938z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                </span>
                                            @endif
                                        </td>

                                        <td scope="col" class="table-td">
                                          <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-success mr-1" data-bs-toggle="modal" data-bs-target="#certificate-modal{{$certificate->id}}">Edit Certificate</button>

                                            {{-- <button type="button" class="btn btn-sm btn-success mr-1" {{ $certificate->image == '' ? 'disabled' : '' }} data-bs-toggle="modal" data-bs-target="#view-certificate-image-modal{{$certificate->id}}">View Certificate Template</button> --}}

                                            @if ($certificate->image != '')
                                                <a href="{{ route('admin.view_certificate', ['id' => $certificate->id]) }}"  target="__blank" class="btn btn-sm btn-success mr-1">Configure Certificate Template</a>
                                            @endif
                                          </div>
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
    
@else
    <div class="card basicTable_wrapper items-center justify-center py-10 px-10">
        <div class="flex items-center justify-center flex-col gap-3">
            <img src="{{ asset('frontend/images/icon/danger.png') }}" alt="">
            <p class="text-lg text-center text-slate-600 dark:text-slate-100 mb-3">
                {{ __('Nothing to see here.') }}
            </p>
        </div>
    </div>
@endif

@push('single-script')
  <script>
    


  </script>
@endpush
