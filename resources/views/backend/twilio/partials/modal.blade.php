<!-- Modal -->
<div class="modal fade" id="call_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-twilio">
        <div class="modal-content">
            <form action="" id="request_form" class="m-0">
                <div class="modal-header text-start">
                    <p class="modal-title" id="staticBackdropLabel">
                        Direkter Anruf beim Kunden  <br>
                        <span class="fw-bold" id="receiver_name"></span>
                    </p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="pad-header text-right">
                        <div class="contact" style="background-color: #1e284b; height: 45px;">
                            <button class="custom-twilio-button book_now make_new_appoint px-2" data-phone="1">
                                <i class="fa fa-calendar-days" aria-hidden="true"></i> Termin
                            </button>
                        </div>
                    </div>
                    <div class="dailpad">
                        <div class="pad" id="call_dev">
                            <div class="dial-pad">
                                <div class="phoneString">
                                    <span class="code-label">Typnummer nach
                                        ( {{ env('COUNTRY_CODE') }} )</span>
                                    <input id="receiver_number" type="text" value="+8801846221990" class="receiver_number">
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
                                    <i class="fa fa-phone"></i>
                                </div>
                            </div>
                        </div>
                        <div class="pad" id="recieved_dev" style="display:none">
                            <div class="dial-pad">
                                <div class="contact">
                                    <div class="avatar"
                                        style="background-image: url('http://127.0.0.1:8000/assets/images/d-user.jpg')">
                                    </div>
                                    <div class="contact-buttons">
                                        <button class="icon-message"
                                            style="background-image: url(https://s2.postimg.org/bpik42e39/comment_Bubble.png)"></button>
                                        <button class="icon-video"
                                            style="background-image: url(https://s10.postimg.org/e7vjpqao5/camera.png)"></button>
                                    </div>
                                </div>
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
                                <div class="ca-avatar" style="background-image: url(http://127.0.0.1:8000/img/user.jpg);">
                                    <img src="https://fixfinanz.de/assets/images/d-user.jpg" alt="Profile Image" class="call-profile-img">
                                </div>
                                <div>
                                    <p  class="contact-name text-white fw-bold">Unknown Name</p>
                                </div>
                                <div> <span class="new_text" data-code="{{ env('COUNTRY_CODE') }}"></span> </div>
                                <div class="ca-status" data-dots="...">Calling</div>
                                <div class="ca-message"></div>
    
                            </div>
    
                            <div class="call action-dig bg-danger">
                                <div class="call-change"><span></span></div>
                                <div class="call-icon close-call-icon">
                                    <i class="fa fa-phone"></i>
                                </div>
                            </div>
                        </div>
                    </div>
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


                <div id="contact_details" style="display: none;">
                    <!-- Contact information will be injected here -->
                </div>

        
                <div class="footer-nav d-flex w-100">
                    <div class="footer-section w-50 text-center py-1" id="callhistory">
                        <i class="fa fa-history"></i>
                        <br>
                        Call History
                    </div>
                    <div class="footer-section w-50 text-center py-1 call-dial" id="dailpad">
                        <img style="height:20px;" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAACXBIWXMAAAsTAAALEwEAmpwYAAADs0lEQVR4nO2Zy05UQRCG/5MIbkAhsBPWCoQ3EHwEFYS4VxFZeY9RgfAAKt4vyG0WIvEFFNAYhYGJG5Gt+AQCmjGDiQNjKqmTdNq+1TGYiTlf0gkZzl/ddbq7qroPkJKSkgJgD4ABACsANgFsAJgF0AkgcryhCgB9AJYA/OC2CKCX/2cjAtAFYI772uS++wFUJ52RAwC+AChZ2jSASoOungdt02X5GR2y9cKhW+UxiWfC5UTc7hve6NsA3RvDjD4M0K1KZ2YgwCi1LQBNiq4zUEftqKJrYVulgHZN4siKYEDXFd1zge5ZghdXArAscaQgMDyp6D4IdPRsTEagK0gc2RAYfqTo5gW6d4ruiUC3JnFkVmD4hKIbFuhuKLoege6lxJHQTfsVQI22aYsBul8AmhVdDb/pkjBIeIk4T7gMbrPDOoMBg6EEp9PNNl26KSSAEtQ9S1ikmTjm0F7irGzaqBcdum7LzNAY7loScDBNHGIpsjwGcBLA3gDdPgDnAIwDGANwln/zUQPgFPeV4b7FGT2l3Il4Q1PGznGeGObo5OMQgFEuILP8d3uArhXAbe4rxxu8w1NtO6l3FIBFjk4mdnsy9QQ/o0MDHXLUXFRo1kmdqPCU4nGj6KQTUm6QMzpXAnRZz3nmD/oCkxOF2AZtOYVm6DZF12gJ1yVDo8NZMEuCAVGIjRkV6EYU3QWBLitxJC8wTIOPyQl0tHRjxgW6/E458jThTKpvdkyg+y5xZLGMl9aCxJEzgUYLWtnRLhjQQUXXINjsPRJHKnjqfUbpTepMBOhoKelcDtBRktwFIXWchGznCVMpDk52Ex4nbAlx0HGemUuSEFXjR/iigCLSewA3tUORjTYOBFluI9pystEC4Bb3leO+D/9NiZJSrrTwvVOGbzt6tHO6jUYOBuPczmvljI1aAKe5rwz3HbKUrVTyNaapGl3jY6mJiAvAnwbdJkcn23o/DmDdoNvi61nxUTfyXCiX+KLA5MxQQBgdtDix7dFNSzd9V2ByWtOWWWvgdVBRO5zVWmaiZGimmxsrrwUZmi4KYu4IdBRiY3oFuhmJI98Ehum2I2ZBoKM8ETMi0K2X8yX25E5dYn9K+Flh6h98VvgocaQ/0GhRuzzrEAyISp+Y5p360FPNn7l8RukaUyVyFJp6AaiH0QcBus8AqiBkv8eZKcfH0KynFDdVsWRr2uMEjSkRVbwHlnmTUd54pS0L23mml6NYnts8lzeu80TEy3OG+yrwnriaZCZSUlL+Q34DtVe4rJOt3aAAAAAASUVORK5CYII=" alt="external-Deal-pad-cellphone-ui-basicons-solid-edtgraphics-3">
                        <br>
                        Dial Pad
                    </div>
                </div>    
                <div class="activity_section" >
                    <div class="pad-footer" style="display: flex; justify-content: space-between; padding: 3px 0; background-color: #f7f7f7;">
                        <span class="mt-1 mx-2 fw-bold h6">Activity</span>
                        <div>
                            <button id="yesBtn" type="button" class="btn btn-success mt-1 mx-1">YES</button>
                            <button id="noBtn" type="button" class="btn btn-danger mt-1 mx-1 ">NO</button>
                        </div>
                   </div>
                   <div class="justify-content-between gap-1 mt-2"  id="status_buttons">
                        <button type="button" name="action" value="Terminiert" class="badge p-1  btn-success  me-1 mt-2 small">Terminiert</button>
                        <button type="button" name="action" value="Follow up" class="badge p-1  btn-warning small me-1  mt-2">Follow up</button>
                        <button type="button" name="action" value="Ungeeignet" class="badge p-1  btn-danger small  me-1  mt-2">Ungeeignet</button>
                    </div>
                </div>  
            </form>
        </div>
    </div>
</div>


<!-- Incoming Call Modal -->
<div id="incomingCallModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-[#003366] text-white rounded-lg w-96 shadow-lg">
      <!-- Modal Header -->
      <div class="flex justify-between items-center p-4 bg-white">
        <h5 class="text-lg font-semibold text-gray-800">Incoming Call</h5>
        <button id="closeModalBtn" class="text-gray-800 hover:text-gray-600">
          <i class="fas fa-times"></i>
        </button>
      </div>
  
      <!-- Modal Body -->
      <div class="p-6 text-center h-40 bg-white">
        <h4 id="incomingCallNumber" class="text-2xl mt-4">+1234567890</h4>
        <div id="callTimer" class="hidden mt-4">
          <span id="timer">00:00</span>
        </div>
      </div>
  
      <!-- Modal Footer -->
      <div class="flex justify-center p-4 space-x-4 bg-white">
        <button id="declineCallBtn" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
          <i class="fas fa-phone-slash"></i> Decline
        </button>
        <button id="answerCallBtn" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
          <i class="fas fa-phone"></i> Answer
        </button>
      </div>
    </div>
</div>
  
  
  

<!-- Modal -->
<div class="modal fade" id="history" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-note  modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Call History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul style="height: 100vh;" class="list-group" id="contact_history_body">
                </ul>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="noteModal" tabindex="-1" role="dialog" aria-labelledby="noteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="noteModalLabel">Add Note</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="noteForm">
                    <textarea id="note_field" name="note"></textarea>
                    <div class="note_submit_button p-2 text-right text-end">
                        <button type="submit" class="btn btn-primary bg-dark-blue">Save Note</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
