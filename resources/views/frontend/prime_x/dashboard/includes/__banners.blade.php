@foreach ($banners as $banner)
  <div class="card border dark:border-slate-700">
    <div class="card-body p-6">
        <div class="mb-5">
            <h4 class="mb-1">{{ $banner->title }}</h4>
            <p class="text-sm text-success-500 mb-2">
              {{ $banner->subtitle }}
            </p>
            <p class="text-slate-900 dark:text-white text-sm">
              {{ $banner->description }}
            </p>
        </div>
        @if (count($banner->list) != 0)
          <ul class="bg-slate-50 dark:bg-dark divide-y divide-slate-100 dark:divide-slate-700 px-3 rounded">
            @foreach ($banner->list as $list_item)
              <li class="flex items-center text-sm text-slate-600 dark:text-slate-300 py-3">
                <iconify-icon class="text-lg text-primary mr-2" icon="lucide:check"></iconify-icon>
                {{ $list_item }}
              </li>
            @endforeach
            
          </ul>
        @endif
        
        @if ($banner->button_text != '')
          <a href="{{ $banner->button_link == '' ? 'javascript:void()' : '' }}" class="btn inline-flex justify-center btn-dark dark:bg-body w-full mt-5">
            {{ $banner->button_text }}
          </a>
        @endif
        
    </div>
  </div>
@endforeach