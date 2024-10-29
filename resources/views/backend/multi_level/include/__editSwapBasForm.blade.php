<form action="{{ route('admin.swap-multi-level.update', $multiLevelAccount->id) }}" method="post">
    @method('put')
    @csrf
    <input type="hidden" name="type" value="{{ the_hash($multiLevelAccount->type) }}">

    <div class="grid lg:grid-cols-2 grid-cols-1 gap-5">
        <div class="input-area">
            <label for="title" class="form-label">{{ __('Title') }}</label>
            <input
                type="text"
                name="title"
                class="form-control mb-0"
                placeholder="Title"
                value="{{ $multiLevelAccount->title }}"
                required
            />
        </div>
        <input type="hidden" name="forex_scheme_id" value="{{ $multiLevelAccount->forex_scheme_id }}">
        <div class="input-area">
            <label for="level_order" class="form-label">{{ __('Level Order') }}</label>
            <input
                type="text"
                name="level_order"
                class="form-control mb-0"
                placeholder="2"
                value="{{ $multiLevelAccount->level_order }}"
                required
            />
        </div>
        <div class="lg:col-span-2 input-area">
            <label for="group_tag" class="form-label">{{ __('Group Tag') }}</label>
            <input
                type="text"
                name="group_tag"
                class="form-control mb-0"
                placeholder="real\Promo\nb50s"
                value="{{ $multiLevelAccount->group_tag }}"
                required
            />
        </div>
        <div class="lg:col-span-2 input-area">
            <label for="rebate_rules" class="form-label">{{ __('Select Rebate Rules') }}</label>
            <select name="rebate_rules[]" class="select2 form-control w-full" multiple="multiple">
                @foreach($rebateRules as $rebateRule)
                    <option value="{{ $rebateRule->id }}"
                            @selected(in_array($rebateRule->id, $multiLevelAccount->rebateRule->pluck('id')->toArray()))>
                        {{ $rebateRule->title }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="lg:col-span-2 input-area">
            <label for="ib_group_id" class="form-label">{{ __('Select IB Group') }}</label>
            <select name="ib_group_id[]" class="select2 form-control w-full" multiple="multiple">
                @foreach($ibGroups as $ibGroup)
                    <option value="{{ $ibGroup->id }}"
                        @selected($multiLevelAccount->ibGroups->contains('id', $ibGroup->id))>
                        {{ $ibGroup->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="lg:col-span-2 input-area">
            <label for="description" class="form-label">{{ __('Short Description') }}</label>
            <textarea
                name="description"
                class="form-control mb-0"
                placeholder="Short Description"
                required
            >{{ $multiLevelAccount->description }}</textarea>
        </div>
        <div class="lg:col-span-2 input-area">
            <label for="status" class="form-label">{{ __('Status') }}</label>
            <select name="status" class="form-control w-full">
                <option value="1" {{ $multiLevelAccount->status == 1 ? 'selected' : '' }}>{{ __('Enable') }}</option>
                <option value="0" {{ $multiLevelAccount->status == 0 ? 'selected' : '' }}>{{ __('Disable') }}</option>
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
