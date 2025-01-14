@extends('frontend::layouts.user')
@section('title')
    {{ __('Contracts') }}
@endsection
@section('content')
    

    <div class="overflow-x-auto">
        <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden ">
                <div class="flex justify-between flex-wrap items-center mb-3">
                    <ul class="nav nav-tabs custom-tabs inline-flex items-center overflow-hidden rounded list-none border-0 pl-0">
                        <li class="nav-item">
                            <a href="javascript:;" class="btn btn-sm inline-flex justify-center btn-outline-primary !text-nowrap active">
                                All
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="javascript:;" class="btn btn-sm inline-flex justify-center btn-outline-primary !text-nowrap">
                                Pending
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="javascript:;" class="btn btn-sm inline-flex justify-center btn-outline-primary !text-nowrap">
                                Signed
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="javascript:;" class="btn btn-sm inline-flex justify-center btn-outline-primary !text-nowrap">
                                Expired
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @if (count($contracts) > 0)
    
        <div class="card p-6 mb-6">

            <div class="grid md:grid-cols-4 grid-cols-1 gap-7">

                @foreach ($contracts as $contract)

                    @php
                        $meta_info = '';
                        $icon = '';
                        $color = '';
                        $open_in_new_tab = false;
                        $meta_info_date = '';
                        if($contract->status == \App\Enums\ContractStatusEnums::PENDING) {
                            $meta_info = 'Expiry at: ';
                            $icon = 'file-clock';
                            $color = 'gray';
                            $link = route('user.contract.show', ['id' => $contract->id]);
                            $open_in_new_tab = false;
                            $meta_info_date = $contract->expiry_at;
                        } elseif ($contract->status == \App\Enums\ContractStatusEnums::SIGNED) {
                            $meta_info = 'Signed at: ';
                            $icon = 'file-check-2';
                            $color = 'rgb(80 199 147)';
                            $link = asset($contract->file_path);
                            $open_in_new_tab = true;
                            $meta_info_date = $contract->signed_at;
                        } elseif ($contract->status == \App\Enums\ContractStatusEnums::EXPIRED) {
                            $meta_info = 'Expired at: ';
                            $icon = 'file-x-2';
                            $color = '#f1595c';
                            $link = 'javascript:void(0)';
                            $open_in_new_tab = false;
                            $meta_info_date = $contract->expired_at;
                        }

                        
                    @endphp

                    <div class="card h-[300px] rounded-xl overflow-hidden cert-container" style="box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);">
                        <div class="card__corner">
                            <div class="card__corner-triangle"></div>
                        </div>

                        <div class="card-body relative h-full bg-no-repeat bg-cover bg-center flex items-center justify-center" ></div>

                        <div class="flex flex-col items-center w-full z-10 space-y-5 cert-details">
                            <iconify-icon class="text-lg mb-2" style="position: relative; top: 3px; color: gray; font-size: 34px" icon="lucide:{{ $icon }}"></iconify-icon>
                            <a href="{{ $link }}" {{ $open_in_new_tab ? 'target="__blank"' : '' }} class="btn btn-sm" style="color: #f5f5f5; background: {{$color}}">{{ str_replace('_', ' ', $contract->status) }}</a>
                            <div style="text-align: center">
                                <b>#{{ $contract->accountTypeInvestment->login }}</b>
                                <br>
                                <small>Created at: {{ date('F d, Y', strtotime($contract->created_at)) }}</small>
                                <br>
                                
                                <small>{{$meta_info}} {{ date('F d, Y', strtotime($meta_info_date)) }}</small>
                            </div>
                        </div>

                    </div>
                @endforeach
            

            </div>
        </div>

        <style>
            .cert-container {
                position: relative;
            }
            .cert-details {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%)
            }

            .card .card__corner {
                position: absolute;
                top: 0;
                right: 0;
                width: 2em;
                height: 2em;
                background-color: #e6e7e8;
            }

            .card .card__corner .card__corner-triangle {
                position: absolute;
                width: 0;
                height: 0;
                border-style: solid;
                border-width: 0 2em 2em 0;
                border-color: transparent #f1f2f2 transparent transparent;
            }

        </style>

    @else

        <div class="card p-6 mt-2">
            <div class="max-w-xl text-center py-10 mx-auto space-y-5">
                <div class="w-20 h-20 bg-danger-500 text-white rounded-full inline-flex items-center justify-center">
                    <iconify-icon icon="fa6-solid:box-open" class="text-5xl"></iconify-icon>
                </div>
                <h4 class="text-xl text-slate-900 dark:text-white">
                    {{ __('No active or pending contracts') }}
                </h4>
                <a href="" class="btn btn-primary inline-flex items-center justify-center">
                    {{ __('Start a new challenge') }}
                </a>
            </div>
        </div>

    @endif
@endsection
