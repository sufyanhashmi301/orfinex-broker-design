@props([
    'fieldId' => '',
    'fieldName' => '',
    'fieldValue' => '',
    'fieldRequired' => false,
    'fieldReadOnly' => false,
    'placeholder' => 'Select an option',
    'searchPlaceholder' => 'Search...',
    'noResultsText' => 'No results found',
    'options' => [],
    'searchable' => true,
    'maxHeight' => 'max-h-60'
])

<div x-data="{
    open: false,
    search: '',
    selectedValue: '{{ $fieldValue }}',
    selectedLabel: '{{ $fieldValue ? (collect($options)->get($fieldValue) ?? $fieldValue) : '' }}',
    options: @js($options),
    get filteredOptions() {
        if (!this.searchable || this.search === '') {
            return Object.entries(this.options);
        }
        return Object.entries(this.options).filter(([value, label]) => 
            label.toString().toLowerCase().includes(this.search.toLowerCase())
        );
    },
    selectOption(value, label) {
        this.selectedValue = value;
        this.selectedLabel = label;
        this.search = '';
        this.open = false;
        this.$refs.hiddenInput.dispatchEvent(new Event('change'));
    },
    searchable: {{ $searchable ? 'true' : 'false' }}
}" class="relative">
    
    <!-- Hidden input for form submission -->
    <input 
        type="hidden" 
        name="{{ $fieldName }}" 
        x-ref="hiddenInput"
        :value="selectedValue"
        id="{{ $fieldId }}"
        @if($fieldRequired) required @endif
    >
    
    <!-- Select Button -->
    <button
        type="button"
        @click="open = !open"
        :disabled="{{ $fieldReadOnly ? 'true' : 'false' }}"
        class="dark:bg-dark-900 h-10 w-full rounded-sm border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:focus:border-brand-800 flex items-center justify-between disabled:opacity-50 disabled:cursor-not-allowed"
        :class="{ 'border-brand-300 ring-3 ring-brand-500/10': open }"
    >
        <span x-text="selectedLabel || '{{ $placeholder }}'" 
              :class="selectedLabel ? 'text-gray-800 dark:text-white/90' : 'text-gray-400 dark:text-white/30'">
        </span>
        <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" 
             :class="{ 'rotate-180': open }" 
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <!-- Dropdown -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-1"
         @click.away="open = false"
         class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg dark:bg-gray-900 dark:border-gray-700">
        
        <!-- Search Input -->
        <div x-show="searchable" class="p-3 border-b border-gray-200 dark:border-gray-700">
            <input
                type="text"
                x-model="search"
                :placeholder="'{{ $searchPlaceholder }}'"
                class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-400"
                @click.stop
            >
        </div>

        <!-- Options List -->
        <div class="{{ $maxHeight }} overflow-y-auto">
            <template x-for="[value, label] in filteredOptions" :key="value">
                <button
                    type="button"
                    @click="selectOption(value, label)"
                    class="w-full px-4 py-3 text-left text-sm hover:bg-gray-50 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-800"
                    :class="{ 'bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400': selectedValue === value }"
                >
                    <span x-text="label" class="text-gray-800 dark:text-white/90"></span>
                </button>
            </template>
            
            <!-- Slot for custom options -->
            {{ $slot }}
            
            <!-- No results -->
            <div x-show="filteredOptions.length === 0" class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 text-center">
                {{ $noResultsText }}
            </div>
        </div>
    </div>
</div>

@error($fieldName)
    <span class="text-xs text-error-500 mt-1.5 block">{{ $message }}</span>
@enderror