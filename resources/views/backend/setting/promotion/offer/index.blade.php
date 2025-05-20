@extends('backend.setting.promotion.index')
@section('title')
    {{ __('Offers Settings') }}
@endsection
@section('content')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>

        <a href="javascript:void(0)" data-bs-target="#new-offer-modal" data-bs-toggle="modal" class="btn btn-primary" >
            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" style="position: relative; top: 4px" icon="lucide:plus"></iconify-icon>
            {{ __('Create New') }} 
        </a>
    </div>

    @if (count($offers) > 0)
    <div class="grid grid-cols-3 gap-5">
        @foreach($offers as $offer)
            <div class="lg:col-span-1 col-span-3">
                <div class="h-full rounded transition-all duration-100 shadow-none bg-slate-200 dark:bg-slate-700">
                    <form action="{{ route('admin.offer.update', ['id' => $offer->id]) }}" method="post">
                        @csrf
                        <div class="relative flex justify-between items-center bg-white dark:bg-slate-800 rounded shadow-base px-6 py-5">
                            <span class="absolute left-0 top-1/2 -translate-y-1/2 h-8 w-[2px] bg-primary"></span>
                            <h3 class="text-lg text-slate-900 dark:text-white font-medium capitalize">
                                {{ $offer->name }}
                            </h3>
                            <div class="form-switch ps-0" style="line-height: 0;">
                                <input type="hidden" value="0" name="status">
                                <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer toggle-switch">
                                    <input type="checkbox" name="status" value="1" class="sr-only peer" {{ $offer->status ? 'checked' : '' }}>
                                    <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                </label>
                            </div>
                        </div>
                        <div class="px-2 py-4 h-full space-y-4 rounded-bl rounded-br">
                            <input type="hidden" name="type" value="user_dashboard">
                            <!-- BEGIN: Cards -->
                            <div class="card rounded-md bg-white dark:bg-slate-800 shadow-base custom-class card-body space-y-5 p-6">
                                <div class="input-area">
                                    <label for="" class="form-label">{{ __('Name') }}</label>
                                    <input type="text" name="name" class="form-control" value="{{ $offer->name }}">
                                </div>
                                <div class="input-area">
                                    <label for="" class="form-label">{{ __('Discount') }}</label>
                                    <select name="discount_id" id="" class="form-control">
                                        @foreach ($discount_codes as $discount)
                                            <option value="{{ $discount->id }}" {{ $discount->id == $offer->discount_id ? 'selected' : '' }}>{{ $discount->code_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-area">
                                    <label for="" class="form-label">{{ __('Condition (Show offer at...)') }}</label>
                                    <select name="condition" id="" class="form-control">
                                      {{-- <option value="always" {{ $offer->condition == "always" ? 'selected' : '' }}>Always</option> --}}
                                      <option value="trial_expiry" {{ $offer->condition == "trial_expiry" ? 'selected' : '' }}>Trial Expiry</option>
                                      <option value="account_violation" {{ $offer->condition == "account_violation" ? 'selected' : '' }}>Account Violation</option>
                                    </select>
                                </div>
                                <div class="input-area"> 
                                    <label for="" class="form-label">{{ __('Description') }}</label>
                                    <textarea name="description" id="" cols="30" rows="2" class="form-control">{{ $offer->description }}</textarea>
                                </div>
                                <div class="input-area">
                                    <label for="" class="form-label">{{ __('Multiple Time Usage') }}</label>
                                    <select name="multiple_time_usage" id="" class="form-control">
                                        <option value="1" {{ $offer->multiple_time_usage ? 'selected' : '' }}>Enable</option>
                                        <option value="0" {{ !$offer->multiple_time_usage ? 'selected' : '' }}>Disable</option>
                                    </select>
                                </div>
                                <div class="input-area">
                                    <label for="" class="form-label">{{ __('Validity (Days)') }}</label>
                                    <input type="text" name="validity" class="form-control" value="{{ $offer->validity }}">
                                </div>
                                <div class="input-area grid grid-cols-2 gap-2">
                                    <button type="submit" class="btn btn-dark block-btn">
                                        <span class="flex items-center">
                                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                            <span>{{ __('Save Changes') }}</span>
                                        </span>
                                    </button>
                                    
                                    <button type="button" class="btn btn-outline-danger delete-offer block-btn" data-id="{{ $offer->id }}" data-url="{{ route('admin.offer.destroy', ['id' => ':id']) }}">Delete</button>
                                    
                                
                                    
                                </div>

                                
                            </div>
                            <!-- END: Cards -->
                        </div>
                    </form>
                </div>
            </div>
        @endforeach

        <form action="" id="delete-offer-form" method="POST">
            @method('DELETE')
            @csrf
        </form>
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

    @include('backend.setting.promotion.offer.includes.__new_offer_modal')
@endsection

@section('script')
    <script>
        $('.delete-offer').on('click', function () {
            var id = $(this).data('id');
            var url = $(this).data('url').replace(':id', id);

            $('#delete-offer-form').attr('action', url).submit();
            $('.delete-offer').prop('disabled', true)
        });
    </script>
@endsection
