@extends('backend.layouts.app')
@section('title')
    {{ __('Configure Certificate Template') }}
@endsection
@section('content')

    @if (isset($certificate->config))
      @if ($certificate->config['coordinate_x_date'] == 0 && $certificate->config['coordinate_y_date'] == 0)
        <style>
          #coordinate-box-date {
            right: 0
          }
        </style>
      @endif
    @else
      <style>
        #coordinate-box-date {
          right: 0
        }
      </style>
    @endif
    <form action="{{ route('admin.certificate_config.update') }}" method="POST" class="space-y-3">
      <div class="innerMenu flex justify-between flex-wrap items-center gap-5 mb-5">
        
          @csrf
          <!-- Hidden inputs to store coordinates -->
          <input type="hidden" class="coordinate-x-name" name="coordinate_x_name" value="{{ $certificate->config['coordinate_x_name'] ?? 0.00 }}">
          <input type="hidden" class="coordinate-y-name" name="coordinate_y_name" value="{{ $certificate->config['coordinate_y_name'] ?? 0.00 }}">
          <input type="hidden" class="coordinate-x-date" name="coordinate_x_date" value="{{ $certificate->config['coordinate_x_date'] ?? 0.00 }}">
          <input type="hidden" class="coordinate-y-date" name="coordinate_y_date" value="{{ $certificate->config['coordinate_y_date'] ?? 0.00 }}">
          <input type="hidden" name="certificate_id" value="{{ $certificate->id }}">
          <button type="submit" class="btn btn-primary" >Save Configuration</button>
        
    
        <div class="btn-group">
          @if (isset($certificate->config) && isset($certificate->config['example_certificate']) && $certificate->config['example_certificate'] != '')
            <button data-bs-toggle="modal" data-bs-target="#view-example-certificate-image-modal{{$certificate->id}}" type="button" class="btn btn-primary mr-1" >View Example</button>
            @include('backend.certificates.includes.__example_certificate_image_modal')
          @endif
          
          <button data-bs-toggle="modal" data-bs-target="#config-modal" type="button" class="btn btn-primary" >
            <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" style="position: relative; top: 3px" icon="lucide:bolt"></iconify-icon>
            Configure Template
          </button>
          @include('backend.certificates.includes.__configure_template_modal')
        </div>
        
      </div>
    </form>
    
    

    <div class="card" id="" style="position: relative">

      

      <div class="card-body">
          <div id="certificate-container" style="position: relative; display: inline-block;">
              <img id="certificate-template" src="{{ asset($certificate->image) }}" width="1080" height="1080" />

              <div id="coordinate-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; cursor: crosshair;">
                  <!-- Name Box -->
                  <div id="coordinate-box-name" 
                    style="
                      top: {{ isset($certificate->config) && $certificate->config['coordinate_y_name'] != 0 ? $certificate->config['coordinate_y_name'] . 'px' : '' }};
                      left: {{ isset($certificate->config) && $certificate->config['coordinate_x_name'] != 0 ? $certificate->config['coordinate_x_name'] . 'px' : '' }}
                    "
                  >
                      <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%)">Name</div>
                  </div>

                  <!-- Date Box -->
                  <div id="coordinate-box-date"
                    style="
                      top: {{ isset($certificate->config) && $certificate->config['coordinate_y_date'] != 0 ? $certificate->config['coordinate_y_date'] . 'px' : '' }};
                      left: {{ isset($certificate->config) && $certificate->config['coordinate_x_date'] != 0 ? $certificate->config['coordinate_x_date'] . 'px' : '' }}
                    "
                  >
                      <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%)">Date</div>
                  </div>
              </div>
          </div>

          
          
      </div>
    </div>



    <style>


      #certificate-template {
          max-width: 100%; /* Ensures that the certificate image fits within its container */
      }

      #coordinate-overlay {
          display: block;
          width: 100%;
          height: 100%;
          position: absolute;
          top: 0;
          left: 0;
      }

    
      
      
      #coordinate-box-name, #coordinate-box-date {
          position: absolute;
          width: 350px;
          height: 70px;
          border: 2px dashed grey;
          background: rgba(239, 204, 204, 0.3);
          color: #ffffffcd;
          cursor: move; /* Makes it clear the box can be dragged */
      }

      #coordinate-box-date {
          background: rgba(204, 239, 204, 0.3); /* Slightly different background for the date box */
      }

    </style>

@endsection

@section('script')
  <script>
    $(document).ready(function() {
    // Cache the boxes
    var $coordinateBoxName = $('#coordinate-box-name');
    var $coordinateBoxDate = $('#coordinate-box-date');

    // Get the image dimensions
    var $certificateTemplate = $('#certificate-template');
    var imageWidth = $certificateTemplate.width();
    var imageHeight = $certificateTemplate.height();

    // Variables for dragging
    var isDraggingName = false, isDraggingDate = false;
    var startX, startY, boxType;

    // Allow user to drag the "Name" box
    $coordinateBoxName.on('mousedown', function(e) {
        e.preventDefault();
        isDraggingName = true;
        boxType = 'name'; // Set which box is being dragged

        var offset = $certificateTemplate.offset();
        startX = e.pageX - offset.left - $coordinateBoxName.width() / 2;
        startY = e.pageY - offset.top - $coordinateBoxName.height() / 2;

        // Position the box
        $coordinateBoxName.css({ top: startY, left: startX });
    });

    // Allow user to drag the "Date" box
    $coordinateBoxDate.on('mousedown', function(e) {
        e.preventDefault();
        isDraggingDate = true;
        boxType = 'date'; // Set which box is being dragged

        var offset = $certificateTemplate.offset();
        startX = e.pageX - offset.left - $coordinateBoxDate.width() / 2;
        startY = e.pageY - offset.top - $coordinateBoxDate.height() / 2;

        // Position the box
        $coordinateBoxDate.css({ top: startY, left: startX });
    });

    // Mousemove event for dragging both boxes
    $(document).on('mousemove', function(e) {
        if (isDraggingName || isDraggingDate) {
            var offset = $certificateTemplate.offset();
            var moveX = e.pageX - offset.left - (boxType === 'name' ? $coordinateBoxName.width() : $coordinateBoxDate.width()) / 2;
            var moveY = e.pageY - offset.top - (boxType === 'name' ? $coordinateBoxName.height() : $coordinateBoxDate.height()) / 2;

            // Restrict the box to stay inside the image bounds
            if (moveX < 0) moveX = 0; // Prevent moving left
            if (moveX > imageWidth - (boxType === 'name' ? $coordinateBoxName.width() : $coordinateBoxDate.width())) moveX = imageWidth - (boxType === 'name' ? $coordinateBoxName.width() : $coordinateBoxDate.width()); // Prevent moving right
            if (moveY < 0) moveY = 0; // Prevent moving up
            if (moveY > imageHeight - (boxType === 'name' ? $coordinateBoxName.height() : $coordinateBoxDate.height())) moveY = imageHeight - (boxType === 'name' ? $coordinateBoxName.height() : $coordinateBoxDate.height()); // Prevent moving down

            // Move the selected box
            if (boxType === 'name') {
                $coordinateBoxName.css({ top: moveY, left: moveX });
            } else if (boxType === 'date') {
                $coordinateBoxDate.css({ top: moveY, left: moveX });
            }
        }
    });

    // Mouseup event for both boxes
    $(document).on('mouseup', function() {
        if (isDraggingName) {
            isDraggingName = false;
            var boxOffset = $coordinateBoxName.position();
            $('.coordinate-x-name').val(boxOffset.left);
            $('.coordinate-y-name').val(boxOffset.top);
        } else if (isDraggingDate) {
            isDraggingDate = false;
            var boxOffset = $coordinateBoxDate.position();
            $('.coordinate-x-date').val(boxOffset.left);
            $('.coordinate-y-date').val(boxOffset.top);
        }
    });
});



  </script>
@endsection
