<form action="{{route('admin.swap-based-accounts.update',$swapBasedAccount->id)}}" method="post">
    @method('put')
                        @csrf
                        <div class="grid lg:grid-cols-2 grid-cols-1 gap-5">
                            <div class="input-area">
                                <label for="" class="form-label">{{ __('Title') }}</label>
                                <input
                                    type="text"
                                    name="title"
                                    class="form-control mb-0"
                                    placeholder="Title"
                                    value="{{$swapBasedAccount->title}}"
                                    required
                                />
                            </div>
                            <input type="hidden" name="account_type_id" value="{{$swapBasedAccount->account_type_id}}">
                            <div class="input-area">
                                <label for="" class="form-label">{{ __('Level Order') }}</label>
                                <input
                                    type="text"
                                    name="level_order"
                                    class="form-control mb-0"
                                    placeholder="2"
                                    value="{{$swapBasedAccount->level_order}}"
                                    required
                                />
                            </div>
                            <div class="lg:col-span-2 input-area">
                                <label for="" class="form-label">{{ __('Group Tag') }}</label>
                                <input
                                    type="text"
                                    name="group_tag"
                                    class="form-control mb-0"
                                    placeholder="real\Promo\nb50s"
                                    value="{{$swapBasedAccount->group_tag}}"
                                    required
                                />
                            </div>
                            <div class="lg:col-span-2 input-area">
                                <label for="" class="form-label">{{ __('Short Description') }}</label>
                                <input
                                    type="text"
                                    name="description"
                                    class="form-control mb-0"
                                    placeholder="Short Description"
                                     value="{{$swapBasedAccount->description}}"
                                    required
                                />
                            </div>
                            <div class="lg:col-span-2 input-area">
                                <label for="status" class="form-label">{{ __('Status') }}</label>
                                <select name="status" class="form-control w-full">
                                    <option value="1" {{$swapBasedAccount->status==1?'selected':''}}>{{ __('Enable') }}</option>
                                    <option value="0" {{$swapBasedAccount->status==0 ?'selected':''}}>{{ __('Disable') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="action-btns text-right mt-10">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center mr-2">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                {{ __('Save') }}
                            </button>
                            <a href="#" class="btn btn-danger inline-flex items-center justify-center" data-bs-dismiss="modal" aria-label="Close">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                                {{ __('Close') }}
                            </a>
                        </div>
                    </form>