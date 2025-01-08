<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="certificate-modal{{$certificate->id}}" tabindex="-1"  aria-modal="true"
    role="dialog">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div
            class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
            <div class="flex items-center justify-between p-5">
                <h3 class="text-xl font-medium dark:text-white capitalize" id="certificate-modalLabel{{$certificate->id}}">
                    Edit Certificate
                </h3>
                <button type="button"
                    class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                          dark:hover:bg-slate-600 dark:hover:text-white"
                    data-bs-dismiss="modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="#000000" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                              11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="modal-body p-6 pt-0">
                <form action="{{ route('admin.certificate.update', ['id' => $certificate->id]) }}" method="post" enctype="multipart/form-data"  class="space-y-3">
                    @csrf
                    
                    {{-- Certificate Image --}}
                    <div class="input-area">
                      <div class="wrap-custom-file">
                          <input
                              type="file"
                              name="image"
                              id="certificate-image{{$certificate->id}}"
                              accept=".gif, .jpg, .png"
                          />
                          @if ($certificate->image == '')
                            <label for="certificate-image{{$certificate->id}}">
                              <img class="upload-icon" src="{{asset('global/materials/upload.svg')}}" alt=""/>
                              <span>{{ __('Upload Certificate Image') }}</span>
                            </label>
                          @else
                            <label for="certificate-image{{$certificate->id}}" class="file-ok" style="background-image: url({{ asset($certificate->image) }})">
                              <img class="upload-icon" src="{{ asset('global/materials/upload.svg') }}" alt="" />
                              <span>{{ __('Update Certificate Image') }}</span>
                            </label>
                          @endif
                          
                      </div>
                    </div>

                    <div class="site-input-area relative">
                      <label for="" class="form-label">Certificate Name</label>
                      <input required type="text" class="form-control" name="title" value="{{$certificate->title}}" required="">
                    </div>  

                    <div class="site-input-area relative">
                      <label for="" class="form-label">Hook</label>
                      <input required type="text" class="form-control" name="hook" value="{{$certificate->hook}}" readonly required="">
                    </div>  

                    <div class="site-input-area relative">
                      <label for="" class="form-label">Nickname</label>
                      <select required class="form-control" name="nickname_allowed" id="">
                        <option value="1" {{ $certificate->nickname_allowed == 1 ? 'selected' : '' }}>Allowed</option>
                        <option value="0" {{ $certificate->nickname_allowed == 0 ? 'selected' : '' }}>Not Allowed</option>
                      </select>
                    </div>  

                    <div class="site-input-area relative">
                      <label for="" class="form-label">Date Info. (Soon)</label>
                      {{-- <select required class="form-control" name="date_info" id="" >
                        <option value="on_success" {{ $certificate->date_info == 'on_success' ? 'selected' : '' }}>On Success</option>
                        <option value="current"  {{ $certificate->date_info == 'current' ? 'selected' : '' }}>Current</option>
                      </select> --}}

                      <input required type="text" class="form-control" name="date_info" value="on_success" readonly required="">
                    </div>  

                    <div class="site-input-area relative">
                      <label for="" class="form-label">Status</label>
                      <select required class="form-control" name="is_enabled" id="">
                        <option value="1" {{ $certificate->is_enabled == 1 ? 'selected' : '' }}>Enable</option>
                        <option value="0" {{ $certificate->is_enabled == 0 ? 'selected' : '' }}>Disable</option>
                      </select>
                    </div>  

              
                    
                    
                    <div class="action-btns text-right">
                        <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

