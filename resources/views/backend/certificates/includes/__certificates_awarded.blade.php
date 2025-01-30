@if( count($user_certificates) != 0 )
    <div class="card">
        <div class="card-body px-6 pt-3">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden basicTable_wrapper">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                                <tr>

                                  <th scope="col" class="table-th">{{ __('User') }}</th>
                                  <th scope="col" class="table-th">{{ __('Hook Type') }}</th>
                                  <th scope="col" class="table-th">{{ __('Used Nickname') }}</th>
                                  <th scope="col" class="table-th">{{ __('Certificate Awarded at') }}</th>
                                  <th scope="col" class="table-th">{{ __('View Certificate') }}</th>
                                    
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                @foreach($user_certificates as $record)
                                    
                                    <tr>
                                       
                                      <td class="table-td" style="text-decoration: underline; ">
                                        <a href="{{ route('admin.certificates.index', ['user' => $record->user->id]) }}">
                                          <b>{{ $record->user->first_name . ' ' .  $record->user->last_name . ' (' . $record->user->email . ')' }}</b>  
                                        </a>  
                                      </td>  
                                      <td class="table-td" >
                                        <span class="badge badge-primary" style="text-transform: lowercase">{{ $record->hook }}</span>  
                                      </td> 
                                      <td class="table-td">
                                        @if ($record->use_nickname == 1)
                                          <span class="text-success-500" data-bs-toggle="tooltip" data-bs-title="Active">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1.75rem" height="1.75rem" viewBox="0 0 24 24">
                                                <path fill="currentColor" fill-rule="evenodd" d="M12 1.25C6.063 1.25 1.25 6.063 1.25 12S6.063 22.75 12 22.75S22.75 17.937 22.75 12S17.937 1.25 12 1.25M7.53 11.97a.75.75 0 0 0-1.06 1.06l3 3a.75.75 0 0 0 1.06 0l7-7a.75.75 0 0 0-1.06-1.06L10 14.44z" clip-rule="evenodd"></path>
                                            </svg>
                                          </span>
                                        @else
                                          <span class="text-danger-500" data-bs-toggle="tooltip" data-bs-title="Unverified">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1.75rem" height="1.75rem" viewBox="0 0 24 24">
                                                <path fill="currentColor" fill-rule="evenodd" d="M12 1.25C6.063 1.25 1.25 6.063 1.25 12S6.063 22.75 12 22.75S22.75 17.937 22.75 12S17.937 1.25 12 1.25M9.702 8.641a.75.75 0 0 0-1.061 1.06L10.939 12l-2.298 2.298a.75.75 0 0 0 1.06 1.06L12 13.062l2.298 2.298a.75.75 0 0 0 1.06-1.06L13.06 12l2.298-2.298a.75.75 0 1 0-1.06-1.06L12 10.938z" clip-rule="evenodd"></path>
                                            </svg>
                                          </span>
                                        @endif
                                        
                                      </td>
                                       
                                      <td class="table-td">{{ date('d F, Y', strtotime($record->hook_triggered_at)) }}</td>  
                                      <td class="table-td">
                                        @can('certificate-awarded-view')
                                          <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#view-certificate-image-modal{{$record->id}}">View Certificate</button>
                                        @endcan
                                      </td> 
                                      
                                      
                                    </tr>

                                    @can('certificate-awarded-view')
                                      @include('backend.certificates.includes.__view_certificate', ['certificate' => $record])
                                    @endcan
                                @endforeach
                            </tbody>
                        </table>
                        <div class="flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 mt-auto">
                            <div>
                                @php
                                    $from = $user_certificates->firstItem();
                                    $to = $user_certificates->lastItem();
                                    $total = $user_certificates->total();
                                @endphp
                                <p class="text-sm text-gray-700 py-3">
                                    Showing <span class="font-medium">{{ $from }}</span> to <span class="font-medium">{{ $to }}</span> of <span class="font-medium">{{ $total }}</span> results
                                </p>
                            </div>
                            {{ $user_certificates->appends(request()->except('page'))->links() }}
                        </div>
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
                {{ __("Nothing to see here.") }}
            </p>
        </div>
    </div>
@endif
