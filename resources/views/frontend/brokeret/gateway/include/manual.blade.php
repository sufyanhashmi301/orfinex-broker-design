<div class="col-span-12">
    <div class="frontend-editor-data space-y-4 text-lg">
        <h6 class="text-slate-900 dark:text-white">
            {{ __('Account Details:') }}
        </h6>
        <div class="text-slate-900 dark:text-slate-300">
            {!! $paymentDetails !!}
        </div>
    </div>
</div>
@foreach(json_decode($fieldOptions, true) as $key => $field)

    @if($field['type'] == 'file')
        <div class="relative">
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400" for="">
                {{ __('' . $field['name']) }}
            </label>
            <input
                type="file"
                name="manual_data[{{ $field['name'] }}]"
                id="{{ $key }}"
                class="focus:border-ring-brand-300 shadow-theme-xs focus:file:ring-brand-300 h-11 w-full overflow-hidden rounded-lg border border-gray-300 bg-transparent text-sm text-gray-500 transition-colors file:mr-5 file:border-collapse file:cursor-pointer file:rounded-l-lg file:border-0 file:border-r file:border-solid file:border-gray-200 file:bg-gray-50 file:py-3 file:pr-3 file:pl-3.5 file:text-sm file:text-gray-700 placeholder:text-gray-400 hover:file:bg-gray-100 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:text-white/90 dark:file:border-gray-800 dark:file:bg-white/[0.03] dark:file:text-gray-400 dark:placeholder:text-gray-400"
                accept=".gif, .jpg, .png"
                @if($field['validation'] == 'required') required @endif/>
        </div>
    @elseif($field['type'] == 'textarea')
        <div class="relative">
            <textarea 
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" 
                rows="5" 
                @if($field['validation'] == 'required') required @endif 
                placeholder="{{ __('Send Money Note') }}" 
                name="manual_data[{{ $field['name'] }}]">
            </textarea>
        </div>
    @else
        <div class="relative">
            <label for="{{ str_replace(' ', '_', $field['name']) }}" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                {{ $field['name'] }}
            </label>
            <input type="text" name="manual_data[{{ $field['name'] }}]"
                @if($field['validation'] == 'required') required @endif 
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                aria-label="{{ str_replace(' ', '_', $field['name']) }}"
                id="{{ str_replace(' ', '_', $field['name']) }}"
                aria-describedby="basic-addon1">
        </div>
    @endif

@endforeach
