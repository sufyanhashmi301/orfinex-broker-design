<div class="input-area relative">
    <label for="" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
        {{ __('Method Name') }}
    </label>
    <input type="text" name="method_name" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" placeholder="{{ __('eg. Withdraw Method - USD') }}"
           value="{{ $withdrawMethod->name .'-'. $withdrawMethod->currency }}">
</div>

@foreach(json_decode($withdrawMethod->fields, true) as $key => $field)

    @if($field['type'] == 'file')

        <input type="hidden" name="credentials[{{ $field['name']}}][type]" value="{{ $field['type'] }}">
        <input type="hidden" name="credentials[{{ $field['name']}}][validation]" value="{{ $field['validation'] }}">

        <div class="input-area relative">
            <label for="" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                {{ __($field['name']) }}
            </label>
            <input type="file" name="credentials[{{ $field['name'] }}][value]" 
                class="focus:border-ring-brand-300 shadow-theme-xs focus:file:ring-brand-300 h-11 w-full overflow-hidden rounded-lg border border-gray-300 bg-transparent text-sm text-gray-500 transition-colors file:mr-5 file:border-collapse file:cursor-pointer file:rounded-l-lg file:border-0 file:border-r file:border-solid file:border-gray-200 file:bg-gray-50 file:py-3 file:pr-3 file:pl-3.5 file:text-sm file:text-gray-700 placeholder:text-gray-400 hover:file:bg-gray-100 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:text-white/90 dark:file:border-gray-800 dark:file:bg-white/[0.03] dark:file:text-gray-400 dark:placeholder:text-gray-400"
                accept=".gif, .jpg, .png" 
                @if($field['validation'] == 'required') required @endif>

        </div>
    @elseif($field['type'] == 'textarea')
        <input type="hidden" name="credentials[{{ $field['name']}}][type]" value="{{ $field['type'] }}">
        <input type="hidden" name="credentials[{{ $field['name']}}][validation]" value="{{ $field['validation'] }}">

        <div class="input-area relative col-span-1 sm:col-span-2">
            <label for="" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                {{ __($field['name']) }}
            </label>
            <textarea class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-24 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" @if($field['validation'] == 'required') required
                          @endif placeholder="{{ __('Send Money Note') }}" name="credentials[{{$field['name']}}][value]"></textarea>
        </div>

    @else
        <input type="hidden" name="credentials[{{ $field['name']}}][type]" value="{{ $field['type'] }}">
        <input type="hidden" name="credentials[{{ $field['name']}}][validation]" value="{{ $field['validation'] }}">
        
        <div class="input-area relative">
            <label for="" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                {{ ucwords( str_replace('_',' ', $field['name']) ) }}
            </label>
            <input type="text" name="credentials[{{ $field['name']}}][value]"
                   @if($field['validation'] == 'required') required @endif class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" aria-label="Amount"
                   id="amount" aria-describedby="basic-addon1" placeholder="{{ __($field['name']) }}">
        </div>
    @endif

@endforeach
