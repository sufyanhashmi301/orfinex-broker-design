<div
    class="modal fade"
    id="addTags"
    tabindex="-1"
    aria-labelledby="addTagsLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content site-table-modal">
            <div class="modal-header">
                <h5 class="modal-title" id="addTagsLabel">
                    {{ __('Tag Add') }}
                </h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.risk-profile-tag.tag.update',$user->id) }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="site-input-groups">
                                <label class="box-input-label" for="">{{ __('Risk Profile Tags:') }}</label>
                                <select id="" class="site-nice-select w-100" name="risk_profile_tag_id"
                                        placeholder="Tags">
                                    <option value="">{{__('Select Tag')}}</option>
                                    @foreach( $tags as $tag)
                                        <option value="{{$tag->id}}">{{$tag->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <br>
                    <br>
                    <div class="row">
                        <div class="action-btns">

                            <button type="submit" class="site-btn primary-btn w-100">
                                {{ __('Add Tag') }}
                            </button>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
