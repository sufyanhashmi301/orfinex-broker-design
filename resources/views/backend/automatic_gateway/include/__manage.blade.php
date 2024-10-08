<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="manage-{{$gateway->id}}" tabindex="-1" aria-labelledby="manage-{{$gateway->id}}" aria-hidden="true">
    <div class="modal-dialog relative max-w-3xl pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <div class="flex items-center justify-between p-5">
                <h3 class="text-xl font-medium dark:text-white capitalize">
                    {{$gateway->gateway_code . __(' Credential Edit')}}
                </h3>
                <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                            dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                    <svg aria-hidden="true" class="w-5 h-5 dark:fill-white" fill="#000000" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                                11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="modal-body p-6 pt-0">
                <form action="{{ route('admin.gateway.update',$gateway->id) }}"
                    method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-col-2 gap-5">
                        <div class="col-span-2">
                            <div class="input-area max-w-xs">
                                <label class="form-label" for="">{{ __('Upload Logo:') }}</label>
                                <div class="wrap-custom-file">
                                    <input type="file" name="logo" id="schema-icon" accept=".gif, .jpg, .png"/>
                                    <label for="schema-icon" class="file-ok" style="background-image: url('{{ $gateway->logo }}')">
                                        <img class="upload-icon" src="{{ asset('global/materials/upload.svg') }}" alt=""/>
                                        <span>{{ __('Update Logo') }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="md:col-span-1 col-span-2">
                            <div class="input-area mb-0">
                                <label class="form-label" for="">{{ __('Name:') }}</label>
                                <input type="text" class="form-control" name="name" value="{{ $gateway->name }}"/>
                            </div>
                        </div>
                        <div class="md:col-span-1 col-span-2">
                            <div class="input-area mb-0">
                                <label class="form-label" for="">{{ __('Code Name:') }}</label>
                                <input type="text" class="form-control" disabled value="{{$gateway->gateway_code}}"/>
                            </div>
                        </div>

                        @foreach(json_decode($gateway->credentials) as $key => $value)
                            <div class="col-span-2">
                                <div class="input-area mb-0">
                                    <label class="form-label" for="">{{ ucwords(str_replace( '_', ' ', $key)) }} :</label>
                                    <input type="text" name="credentials[{{ $key }}] " class="form-control" value="{{ $value }}"/>
                                </div>
                            </div>
                        @endforeach

                        <div class="col-span-2">
                            <div class="input-area">
                                <label class="form-label" for=""></label>
                                <div class="flex items-center space-x-7 flex-wrap">
                                    <label class="form-label !w-auto">
                                        {{ __('Status:') }}
                                    </label>
                                    <div class="form-switch ps-0">
                                        <input type="hidden" value="0" name="status">
                                        <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                            <input type="checkbox" name="status" value="1" class="sr-only peer" @if($gateway->status) checked @endif>
                                            <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-2 text-right">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                                {{ __('Save Changes') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
