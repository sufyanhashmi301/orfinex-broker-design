<div class="">
  <div class="card" style="height: 515px; overflow: auto; position: relative">
      <div class="card-header noborder" style="padding-bottom: 0px">
          
      </div>
      <div class="card-body p-6 pt-0" >
            @if ($slider)
                <div class="slider carousel-interval owl-carousel">
                    @foreach ($slider->slides as $slide)
                        <img class="w-full" src="{{ asset($slide) }}" alt="image">
                    @endforeach
                </div>
            @endif
      </div>
  </div>
  
</div>