@extends('backend.layouts.app')
@section('title')
    {{ __('Send Email to All') }}
@endsection
@section('style')
@include('backend.twilio.partials.style')
<link rel="stylesheet" href=" https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endsection
@section('content')
<div class="flex flex-row w-full h-screen bg-gray-100 p-4 gap-4">
    <!-- Left Section: Form -->
    <div class="w-1/4 bg-white p-6 rounded-lg shadow-md overflow-y-auto">
        <form action="" id="request_form" class="m-0">
            <div class="modal-header text-start">
                <p class="modal-title" id="staticBackdropLabel">
                    Type a number to call<br>
                    <span class="fw-bold" id="receiver_name"></span>
                </p>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="dailpad">
                    <div class="pad" id="call_dev">
                        <div class="dial-pad">
                            <div class="phoneString">
                                <span class="code-label">Country Code
                                    ( {{ env('COUNTRY_CODE') }} )</span>
                                <input id="receiver_number" type="text" value="" class="receiver_number">
                            </div>
                            <div class="digits" style="    margin-bottom: 35px;margin-top: 0px;">
                                <div class="all-digits">
                                    <div class="dig pound number-dig" name="1">
                                        <span id="1">1</span>
                                    </div>
                                    <div class="dig number-dig" name="2"> <span id="2">2</span>
                                        <div class="sub-dig">ABC</div>
                                    </div>
                                    <div class="dig number-dig" name="3"> <span id="3">3</span>
                                        <div class="sub-dig">DEF</div>
                                    </div>
                                    <div class="dig number-dig" name="4"> <span id="4">4</span>
                                        <div class="sub-dig">GHI</div>
                                    </div>
                                    <div class="dig number-dig" name="5"> <span id="5">5</span>
                                        <div class="sub-dig">JKL</div>
                                    </div>
                                    <div class="dig number-dig" name="6"> <span id="6">6</span>
                                        <div class="sub-dig">MNO</div>
                                    </div>
                                    <div class="dig number-dig" name="7"> <span id="7">7</span>
                                        <div class="sub-dig">PQRS</div>
                                    </div>
                                    <div class="dig number-dig" name="8"> <span id="8">8</span>
                                        <div class="sub-dig">TUV</div>
                                    </div>
                                    <div class="dig number-dig" name="9"> <span id="9">9</span>
                                        <div class="sub-dig">WXYZ</div>
                                    </div>
                                    <div class="dig number-dig astrisk" name="*"> <span id="*">*</span></div>
                                    <div class="dig number-dig pound" name="0"> <span id="0">0</span></div>
                                    <div class="dig number-dig pound" name="#"> <span id="#">#</span></div>
                                </div>
                            </div>
                            <div class="digits">
                                <div class="dig-spacer"></div>
                            </div>
                        </div>

                        <div class="call action-dig">
                            <div class="call-change"><span></span></div>
                            <div class="call-icon call-initiate">
                                <iconify-icon class="nav-icon" style="font-size: 20px" icon="lucide:phone"></iconify-icon>
                            </div>
                        </div>
                    </div>
                    <div class="pad" id="recieved_dev" style="display:none">
                        <div class="dial-pad">
                            <div class="digits" style="    margin-bottom: 90px;margin-top: 0px;">
                                <div class="all-digits" style="opacity:0;">
                                    <div class="dig pound number-dig" name="1">
                                        <span id="1">1</span>
                                    </div>
                                    <div class="dig number-dig" name="2"> <span id="2">2</span>
                                        <div class="sub-dig">ABC</div>
                                    </div>
                                    <div class="dig number-dig" name="3"> <span id="3">3</span>
                                        <div class="sub-dig">DEF</div>
                                    </div>
                                    <div class="dig number-dig" name="4"> <span id="4">4</span>
                                        <div class="sub-dig">GHI</div>
                                    </div>
                                    <div class="dig number-dig" name="5"> <span id="5">5</span>
                                        <div class="sub-dig">JKL</div>
                                    </div>
                                    <div class="dig number-dig" name="6"> <span id="6">6</span>
                                        <div class="sub-dig">MNO</div>
                                    </div>
                                    <div class="dig number-dig" name="7"> <span id="7">7</span>
                                        <div class="sub-dig">PQRS</div>
                                    </div>
                                    <div class="dig number-dig" name="8"> <span id="8">8</span>
                                        <div class="sub-dig">TUV</div>
                                    </div>
                                    <div class="dig number-dig" name="9"> <span id="9">9</span>
                                        <div class="sub-dig">WXYZ</div>
                                    </div>
                                    <div class="dig number-dig astrisk" name="*"> <span id="*">*</span></div>
                                    <div class="dig number-dig pound" name="0"> <span id="0">0</span></div>
                                    <div class="dig number-dig pound" name="#"> <span id="#">#</span></div>
                                </div>
                            </div>
                            <div class="digits">
                                <div class="dig-spacer"></div>
                            </div>
                        </div>
                        <div class="call-pad">
                            <div class='pulsate'>
                                <div></div>
                                <div></div>
                                <div></div>
                            </div>
                            <div style="margin-top:200px;"> 
                                <span class="new_text" data-code="{{ env('COUNTRY_CODE') }}"></span>
                            </div>
                            <div class="ca-status" data-dots="...">Calling</div>

                        </div>

                        <div class="call action-dig bg-danger" style="background-color:rgb(237, 48, 48)">
                            <div class="call-change"><span></span></div>
                            <div class="call-icon close-call-icon">
                                <iconify-icon class="nav-icon" style="font-size: 20px" icon="lucide:phone"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
               
            </div>
        </form>
    </div>

    <!-- Right Section: Call History Table -->
    <div class="w-3/4 bg-white p-6 rounded-lg shadow-md overflow-y-auto">
        <h2 class="text-xl font-semibold mb-4">Call History</h2>
        <div class="call_history">
            <div class="dial-pad">
                <div id="loader" class="d-none position-absolute w-100 h-50 d-flex justify-content-center align-items-center">
                    <div class="spinner-border text-white" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    </div>
                <ul class="list-group" id="call-history-list">
                </ul>
            </div>
        </div>
    </div>
</div>
@include('backend.twilio.partials.modal')
@endsection
@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.5/dist/sweetalert2.all.min.js"></script>
<script src="https://sdk.twilio.com/js/client/v1.14/twilio.min.js"></script>
@include('backend.twilio.partials.script');
@endsection
