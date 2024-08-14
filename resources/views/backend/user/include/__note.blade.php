<div class="tab-pane fade space-y-5" id="pills-note" role="tabpanel" aria-labelledby="pills-note-tab">
    @can('customer-basic-manage')
    <div class="card">
        <div class="card-body p-5">
            <form action="{{route('admin.user.note.add',$user->id)}}" method="post">

                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                    <div class="input-area relative lg:col-span-3">
                        <label for="" class="form-label">{{ __('Note:') }}</label>
                        <textarea type="text"  name="notes" class="form-control"
                        > {{ $user->notes }}</textarea>
                    </div>

                    <div class="input-area relative text-right lg:col-span-3">
                        <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                            {{ __('Save Changes') }}
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
    @endcan


</div>
