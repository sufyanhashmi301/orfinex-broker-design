@extends('frontend::layouts.user')
@section('title')
    {{ __('Offers') }}
@endsection
@section('content')

@if (count($userOffers) > 0)
    <div class="flex justify-between flex-wrap items-center mb-3">
      <h4 class="text-xl text-slate-600 dark:text-slate-300">
        All Offers
      </h4>
    </div>
    <div class="card p-6 mb-6" style="min-height: 82vh">
        
        <div class="grid md:grid-cols-4 grid-cols-1 gap-7">
            @foreach ($userOffers as $userOffer)
              @php
                $offer = $userOffer->offer;
              @endphp
              <div class="card h-[300px] rounded-xl overflow-hidden cert-container" style="box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);">
              
            
                <div class="card-body relative h-full p-5" style="text-align: center">
                  <h4 class="card-title">{{ $offer->name }}</h4>
                  <div class="text-sm mt-3 text-slate-500">{{ $offer->description }}</div>

                  <div class="coupon-code {{ $userOffer->status }}" style="position: relative; width: 270px; position: relative; left: 50%; transform: translateX(-50%)">
                    <iconify-icon icon="lucide:tag" class="text-2xl mr-2" style="position: relative; top: 3px"></iconify-icon>
                    <span class="code" style="text-transform: uppercase">{{ $offer->discount->code }}</span>

                    <svg xmlns="http://www.w3.org/2000/svg" width="18px" height="18px" style="position: absolute; left: 20px; transform: rotate(90deg);bottom: -10px" fill="#475569" viewBox="0 0 97.529 97.529" >
                      <g>
                        <path xmlns="http://www.w3.org/2000/svg" d="M92.358,62.188c-6.326-6.28-16.271-6.722-23.109-1.327l-6.527-6.431l21.753-21.638c7.641-7.6,7.68-19.953,0.087-27.601   l-35.73,35.557L13.743,6.187c-3.641,3.667-5.672,8.634-5.645,13.801s2.11,10.112,5.79,13.741l21.09,20.807l-6.699,6.666   c-6.837-5.396-16.784-4.953-23.109,1.328c-6.837,6.784-6.909,17.836-0.123,24.672c6.787,6.836,17.837,6.844,24.672,0.057   l19.136-19.035l18.954,18.695c6.835,6.787,17.886,6.781,24.673-0.057C99.269,80.025,99.195,68.976,92.358,62.188z M22.122,79.604   c-2.578,2.558-6.754,2.542-9.313-0.034c-2.558-2.576-2.542-6.755,0.035-9.312c2.577-2.557,6.754-2.543,9.312,0.033   C24.714,72.869,24.699,77.045,22.122,79.604z M48.993,61.193c-3.656,0-6.621-2.964-6.621-6.621c0-3.656,2.964-6.621,6.621-6.621   s6.621,2.964,6.621,6.621C55.614,58.229,52.649,61.193,48.993,61.193z M84.72,79.231c-2.56,2.576-6.735,2.593-9.313,0.033   c-2.576-2.56-2.592-6.735-0.033-9.313c2.559-2.576,6.735-2.59,9.312-0.033C87.262,72.478,87.277,76.656,84.72,79.231z"/>
                      </g>
                    </svg>
                  </div>

                  <div style="color: #999; float: right; margin-bottom: 25px; ">
                    <svg xmlns="http://www.w3.org/2000/svg" style="height: 15px; display: inline-block; position: relative; top: -2px" viewBox="0 0 24 24" fill="none">
                      <g id="Arrow / Arrow_Sub_Left_Up">
                      <path id="Vector" d="M12.5 11.5L7.5 6.5M7.5 6.5L2.5 11.5M7.5 6.5V14.3C7.5 15.4201 7.5 15.9798 7.71799 16.4076C7.90973 16.7839 8.21547 17.0905 8.5918 17.2822C9.0192 17.5 9.57899 17.5 10.6969 17.5H20.5002" stroke="#999" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                      </g>
                    </svg>
                    <small class="copy-text">Click to copy</small>

                    
                  </div>
                  <div style="clear: both"></div>

                  <div class="mt-2 ">
                    <div class="text-sm text-slate-500" style="float: left">
                      Valid Till: {{ \Carbon\Carbon::parse($userOffer->created_at)->addDays($offer->validity)->format('jS F, Y') }}
                    </div>
                    @php
                      $status = "success";
                      if($userOffer->status == 'expired') {
                        $status = "danger";
                      }
                      if($userOffer->status == 'used') {
                        $status = "dark";
                      }
                    @endphp
                    <div class="btn btn-sm btn-{{ $status }} min-w-[100px]" style="float: right; position: relative; top: -6px">
                      {{ $userOffer->status }}
                    </div>
                  </div>

                </div>
                
              </div>
            @endforeach
        

        </div>
    </div>

    <style>
      .coupon-code {
        font-size: 24px;
        text-align: center;
        margin-top: 25px;
        background: #eee;
        padding: 13px;
        border: 2px dashed #333;
        cursor: default;
      }
      .coupon-code.available {
        cursor: pointer;

      }
      .coupon-code.available:hover {
        color: #198754;
        border: 2px dashed #198754;
        background: #19875424
      }
    </style>

    

@else

    <div class="card p-6 mt-2">
        <div class="max-w-xl text-center py-10 mx-auto space-y-5">
            <div class="w-20 h-20 bg-danger-500 text-white rounded-full inline-flex items-center justify-center">
                <iconify-icon icon="lucide:tag" class="text-5xl"></iconify-icon>
            </div>
            <h4 class="text-xl text-slate-900 dark:text-white">
                {{ __('No Offers Available') }}
            </h4>
            <a href="" class="btn btn-primary inline-flex items-center justify-center">
                {{ __('Start a new challenge') }}
            </a>
        </div>
    </div>

@endif

@endsection

@section('script')

  <script>
    $('.coupon-code.available').on('click', function() {
      // Get the text content from the element inside the div
      var text = $(this).find('.code').text();

      // Create a temporary input to hold the text
      var $temp = $('<input>');
      $('body').append($temp);
      $temp.val(text).select();
      
      // Execute the copy command
      document.execCommand('copy');
      
      // Remove the temporary input
      $temp.remove();

      $(this).parents('.card-body').find('.copy-text').text('Code copied!')
      setTimeout(() => {
        $(this).parents('.card-body').find('.copy-text').text('Click to copy')
      }, 3000);
    });
  </script>
@endsection