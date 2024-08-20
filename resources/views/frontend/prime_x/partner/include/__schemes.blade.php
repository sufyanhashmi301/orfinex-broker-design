@foreach($schemas as $index => $schema)
    <div class="input-area grid grid-cols-12 items-center gap-5">
        <div class="lg:col-span-2 col-span-12 form-label !mb-0">
            {{ $schema->title }}
        </div>
        <div class="lg:col-span-10 col-span-12">
            <div class="relative">
                <input type="text" class="form-control !pr-32" id="standard-input{{ $index }}" value="{{ $getReferral->link }}" readonly>
                <span class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-none flex items-center justify-center">
                    <a href="javascript:;" class="copy-button" type="button" data-target="#standard-input{{ $index }}">{{ __('Copy Link') }}</a>
                </span>
            </div>
        </div>
    </div>
@endforeach
