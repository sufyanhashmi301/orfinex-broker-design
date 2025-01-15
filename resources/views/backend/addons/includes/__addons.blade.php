@if (count($addons) != 0)
    <div class="card p-3">
        <div class="card-body p-6 pt-1">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden basicTable_wrapper">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                                <tr>
                                  <th scope="col" class="table-th">{{ __('Name') }}</th>
                                  <th scope="col" class="table-th">{{ __('Description') }}</th>
                                  <th scope="col" class="table-th">{{ __('Type') }}</th>
                                  <th scope="col" class="table-th">{{ __('Amount') }}</th>
                                  <th scope="col" class="table-th">{{ __('Status') }}</th>
                                  <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($addons as $addon)

                                    <tr class="pt-1">
                                        <td scope="col" class="table-td " >{{ $addon->name }}</td>
                                        <td scope="col" class="table-td " >{{ $addon->description }}</td>
                                        <td scope="col" class="table-td" style="text-transform: capitalize">{{ $addon->amount_type }}</td>
                                        <td scope="col" class="table-td">{{ $addon->amount }}{{ $addon->amount_type == 'fixed' ? ' ' . $currency : '%' }}</td>                                        
                                        <td scope="col" class="table-td">
                                          <span class="badge {{ $addon->status == 1 ? 'badge-success' : 'badge-danger' }}">{{ $addon->status == 1 ? 'Enabled' : 'Disabled' }}</span>
                                        </td>

                                        <td scope="col" class="table-td">
                                          <button type="button" data-bs-toggle="modal" data-bs-target="#editAddonModal{{ $addon->id }}" data-id="{{ $addon->id }}" class="btn btn-sm btn-primary mr-1">Edit Addon</button>
                                        </td>

                                    </tr>

                                    @include('backend.addons.includes.__edit_modal', ['addon' => $addon])
                                    
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
