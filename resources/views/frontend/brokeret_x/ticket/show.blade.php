@extends('frontend::layouts.user')
@section('title')
    {{ __('Add New Support Ticket') }}
@endsection
@section('content')
    <div class="grid h-full grid-cols-1 gap-5 xl:grid-cols-12">
        <div class="xl:col-span-8">
            <div class="h-[calc(100vh-186px)] overflow-hidden sm:h-[calc(100vh-174px)]">
                <div class="flex h-full flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="sticky flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between border-b border-gray-200 px-5 py-4 dark:border-gray-800 xl:px-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-800 dark:text-white/90">
                            {{ __('Ticket #') }} {{ $ticket->uuid}} - {{ $ticket->title }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $ticket->created_at }}
                        </p>
                    </div>
                    <div class="flex items-center gap-4">
                        @if( $ticket->status != 'closed')
                                <x-link-button href="{{ route('user.ticket.close.now',$ticket->uuid) }}" variant="primary" icon="check" icon-position="left">
                                {{ __('Mark it close') }}
                                </x-link-button>
                        @endif
                    </div>
                </div>
                    <div class="custom-scrollbar max-h-full flex-1 space-y-6 overflow-auto p-5 xl:space-y-8 xl:p-6">
                        <article>
                            <div class="mb-6 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <img src="{{ getFilteredPath($ticket->user->avatar, 'fallback/user.png') }}" class="h-10 w-10 shrink-0 rounded-full" alt="">
                                    <div>
                                        <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                            {{ $ticket->user->first_name.' '.$ticket->user->last_name }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $ticket->user->email }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="pb-6">                                
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {!! $ticket->message !!}
                                </p>
                                @if($ticket->attach)
                                    <div class="mt-1">
                                        <a href="{{ asset($ticket->attach) }}" class="inline-flex p-2 rounded-[6px] bg-[#EAE5FF] dark:bg-slate-900" target="_blank">
                                            <img src="{{ asset($ticket->attach) }}" class="block h-[70px] w-[70px]" style="object-fit: scale-down;" alt="">
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </article>
                        @foreach($ticket->messages as $message )
                            <article>
                                <div class="flex space-x-2 items-end rtl:space-x-reverse @if( $message->model != 'admin') justify-end w-full @endif">
                                    @if( $message->model == 'admin')
                                        <div class="flex-none">
                                            <div class="h-10 w-10 rounded-full">
                                                <img class="block w-full h-full object-cover rounded-full" src="{{ getFilteredPath($message->user->avatar, 'fallback/user.png') }}" alt="">
                                            </div>
                                        </div>
                                    @endif
                                    <div class="@if( $message->model != 'admin') no @else flex-1 @endif flex space-x-4 rtl:space-x-reverse">
                                        <div class="@if( $message->model != 'admin') text-right @endif">
                                            <span class="font-normal text-xs text-slate-400 dark:text-slate-400 mb-1">
                                                @if( $message->model != 'admin')
                                                    {{ $user->full_name }}
                                                @else
                                                    {{ $message->user->name }}
                                                @endif
                                            </span>
                                            <div class="text-content text-left p-3 bg-slate-100 dark:bg-slate-600 dark:text-slate-300 text-slate-600 text-sm font-normal rounded-md">
                                                {!! $message->message !!}
                                                @if($message->attach)
                                                    <div class="flex items-start gap-3 mt-1">
                                                        <a href="{{ asset($message->attach) }}" class="inline-flex p-2 rounded-[6px] bg-[#EAE5FF] dark:bg-slate-900" target="_blank">
                                                            <img src="{{ asset($message->attach) }}" class="block h-[70px] w-[70px]" style="object-fit: scale-down;" alt="">
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @if( $message->model != 'admin')
                                        <div class="flex-none">
                                            <div class="h-10 w-10 rounded-full">
                                                <img class="block w-full h-full object-cover rounded-full" src="{{ getFilteredPath($ticket->user->avatar, 'fallback/user.png') }}" alt="">
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>
                    <div class="sticky bottom-0 border-t border-gray-200 p-3 dark:border-gray-800" x-data="ticketImageUpload()">
                        <form action="{{ route('user.ticket.reply') }}" method="post" enctype="multipart/form-data" class="flex items-center justify-between">
                            @csrf
                            <input type="hidden" name="uuid" value="{{ $ticket->uuid }}">
                            <div class="relative w-full">
                                <button class="absolute left-1 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white/90 sm:left-3">
                                    <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2ZM3.5 12C3.5 7.30558 7.30558 3.5 12 3.5C16.6944 3.5 20.5 7.30558 20.5 12C20.5 16.6944 16.6944 20.5 12 20.5C7.30558 20.5 3.5 16.6944 3.5 12ZM10.0001 9.23256C10.0001 8.5422 9.44042 7.98256 8.75007 7.98256C8.05971 7.98256 7.50007 8.5422 7.50007 9.23256V9.23266C7.50007 9.92301 8.05971 10.4827 8.75007 10.4827C9.44042 10.4827 10.0001 9.92301 10.0001 9.23266V9.23256ZM15.2499 7.98256C15.9403 7.98256 16.4999 8.5422 16.4999 9.23256V9.23266C16.4999 9.92301 15.9403 10.4827 15.2499 10.4827C14.5596 10.4827 13.9999 9.92301 13.9999 9.23266V9.23256C13.9999 8.5422 14.5596 7.98256 15.2499 7.98256ZM9.23014 13.7116C8.97215 13.3876 8.5003 13.334 8.17625 13.592C7.8522 13.85 7.79865 14.3219 8.05665 14.6459C8.97846 15.8037 10.4026 16.5481 12 16.5481C13.5975 16.5481 15.0216 15.8037 15.9434 14.6459C16.2014 14.3219 16.1479 13.85 15.8238 13.592C15.4998 13.334 15.0279 13.3876 14.7699 13.7116C14.1205 14.5274 13.1213 15.0481 12 15.0481C10.8788 15.0481 9.87961 14.5274 9.23014 13.7116Z" fill=""></path>
                                    </svg>
                                </button>

                                <input type="text" name="message" placeholder="Type your reply here..." class="h-9 w-full border-none bg-transparent pl-12 pr-5 text-sm text-gray-800 outline-hidden placeholder:text-gray-400 focus:border-0 focus:ring-0 dark:text-white/90">
                            </div>
                            <div class="flex items-center gap-2 relative">
                                <!-- File input (hidden) -->
                                <input type="file" 
                                       name="attach" 
                                       x-ref="fileInput"
                                       @change="handleFileSelect($event)"
                                       class="hidden" 
                                       accept=".gif,.jpg,.jpeg,.png"/>
                                
                                <!-- Image Preview Indicator -->
                                <div x-show="imagePreview" 
                                     x-transition
                                     class="relative mr-1">
                                    <img :src="imagePreview" 
                                         alt="Preview" 
                                         class="h-8 w-8 rounded object-cover border border-gray-200 dark:border-gray-600">
                                    <button type="button" 
                                        @click="removeImage()"
                                        class="absolute -right-1 -top-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-white hover:bg-red-600 text-xs">
                                        ×
                                    </button>
                                </div>
                                
                                <!-- Error message for invalid file type -->
                                <div x-show="!isValidFileType" 
                                     x-transition
                                     class="absolute bottom-full left-0 mb-2 rounded bg-red-100 px-2 py-1 text-xs text-red-600 dark:bg-red-900/20 dark:text-red-400">
                                    {{ __('Invalid file type') }}
                                </div>
                                
                                <!-- Attachment Button -->
                                <button type="button" 
                                        @click="$refs.fileInput.click()"
                                        class="text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white/90"
                                        :class="{ 'text-blue-600 dark:text-blue-400': imagePreview }"
                                        :title="imagePreview ? 'Change attachment' : 'Add attachment'">
                                    <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12.9522 14.4422C12.9522 14.452 12.9524 14.4618 12.9527 14.4714V16.1442C12.9527 16.6699 12.5265 17.0961 12.0008 17.0961C11.475 17.0961 11.0488 16.6699 11.0488 16.1442V6.15388C11.0488 5.73966 10.7131 5.40388 10.2988 5.40388C9.88463 5.40388 9.54885 5.73966 9.54885 6.15388V16.1442C9.54885 17.4984 10.6466 18.5961 12.0008 18.5961C13.355 18.5961 14.4527 17.4983 14.4527 16.1442V6.15388C14.4527 6.14308 14.4525 6.13235 14.452 6.12166C14.4347 3.84237 12.5817 2 10.2983 2C8.00416 2 6.14441 3.85976 6.14441 6.15388V14.4422C6.14441 14.4492 6.1445 14.4561 6.14469 14.463V16.1442C6.14469 19.3783 8.76643 22 12.0005 22C15.2346 22 17.8563 19.3783 17.8563 16.1442V9.55775C17.8563 9.14354 17.5205 8.80775 17.1063 8.80775C16.6921 8.80775 16.3563 9.14354 16.3563 9.55775V16.1442C16.3563 18.5498 14.4062 20.5 12.0005 20.5C9.59485 20.5 7.64469 18.5498 7.64469 16.1442V9.55775C7.64469 9.55083 7.6446 9.54393 7.64441 9.53706L7.64441 6.15388C7.64441 4.68818 8.83259 3.5 10.2983 3.5C11.764 3.5 12.9522 4.68818 12.9522 6.15388L12.9522 14.4422Z" fill=""></path>
                                    </svg>
                                </button>
                                
                                <button type="submit" class="flex h-9 w-9 items-center justify-center rounded-lg bg-brand-500 text-white hover:bg-brand-600">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M4.98481 2.44399C3.11333 1.57147 1.15325 3.46979 1.96543 5.36824L3.82086 9.70527C3.90146 9.89367 3.90146 10.1069 3.82086 10.2953L1.96543 14.6323C1.15326 16.5307 3.11332 18.4291 4.98481 17.5565L16.8184 12.0395C18.5508 11.2319 18.5508 8.76865 16.8184 7.961L4.98481 2.44399ZM3.34453 4.77824C3.0738 4.14543 3.72716 3.51266 4.35099 3.80349L16.1846 9.32051C16.762 9.58973 16.762 10.4108 16.1846 10.68L4.35098 16.197C3.72716 16.4879 3.0738 15.8551 3.34453 15.2223L5.19996 10.8853C5.21944 10.8397 5.23735 10.7937 5.2537 10.7473L9.11784 10.7473C9.53206 10.7473 9.86784 10.4115 9.86784 9.99726C9.86784 9.58304 9.53206 9.24726 9.11784 9.24726L5.25157 9.24726C5.2358 9.20287 5.2186 9.15885 5.19996 9.11528L3.34453 4.77824Z" fill="white"></path>
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="xl:col-span-4 hidden xl:block">
            <div class="h-full rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] sm:p-6">
                <ul class="space-y-5">
                    <li>
                        <p class="text-lg font-medium text-gray-800 dark:text-white/90 mb-1">{{ __('Tell Us!') }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __('Please provide as much detail as possible when submitting your support ticket. Clear and complete information helps us assist you more efficiently.') }}
                        </p>
                    </li>
                    <li>
                        <p class="text-lg font-medium text-gray-800 dark:text-white/90 mb-1">{{ __('Show Us!') }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __('If you encounter any issues, attach relevant images or screenshots to your ticket. Visual references help us understand your concern better and resolve it faster.') }}
                        </p>
                    </li>
                    <li>
                        <p class="text-lg font-medium text-gray-800 dark:text-white/90 mb-1">{{ __('Caution') }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __('Please note that the response time for tickets may extend up to 2 business days based on the nature of the inquiry.') }}
                        </p>
                    </li>
                    <li>
                        <p class="text-lg font-medium text-gray-800 dark:text-white/90 mb-1">{{ __('Response Time') }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __('Our support team operates during standard business hours, Monday through Friday. We aim to resolve all inquiries promptly. However, response times may be affected during weekends or public holidays.') }}
                        </p>
                    </li>
                    <li>
                        <p class="text-lg font-medium text-gray-800 dark:text-white/90 mb-1">{{ __('Important Notice') }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __('Tickets that remain inactive for a specified period or are unrelated to support inquiries may be closed. To ensure prompt resolution, please avoid submitting duplicate tickets. We appreciate your understanding and cooperation in providing seamless support.') }}
                        </p>
                    </li>
                </ul>
        </div>
    </div>
</div>
@endsection
@section('style')
    <style>
        .msgs > div:last-child {
            margin-bottom: 0.75rem !important;
        }
        
        /* Ensure form container maintains consistent layout */
        .ticket-reply-form {
            min-height: 160px;
            overflow: hidden;
        }
        
        /* Smooth height transitions for textarea */
        .ticket-reply-form textarea {
            transition: height 0.2s ease;
            min-height: 80px;
            max-height: 200px;
        }
        
        /* Image preview container styling */
        .image-preview-container {
            animation: slideDown 0.3s ease-out;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                max-height: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                max-height: 200px;
                transform: translateY(0);
            }
        }
        
        /* Ensure remove button is always accessible */
        .image-remove-btn {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection
@section('script')
    <script>
        function ticketImageUpload() {
            return {
                imagePreview: null,
                fileName: '',
                fileSize: '',
                dragOver: false,
                isValidFileType: true,
                maxFileSize: 2048, // 2MB in KB
                allowedTypes: ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'],
                
                handleFileSelect(event) {
                const file = event.target.files[0];
                    this.processFile(file);
                },
                
                processFile(file) {
                    if (!file) return;
                    
                    // Validate file type
                    this.isValidFileType = this.allowedTypes.includes(file.type);
                    if (!this.isValidFileType) {
                        this.$refs.fileInput.value = '';
                        return;
                    }
                    
                    // Validate file size (2MB max)
                    if (file.size > this.maxFileSize * 1024) {
                        alert('{{ __('File size must be less than 2MB') }}');
                        this.$refs.fileInput.value = '';
                        return;
                    }
                    
                    this.fileName = file.name;
                    this.fileSize = this.formatFileSize(file.size);
                    
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.imagePreview = e.target.result;
                    };
                    reader.readAsDataURL(file);
                },
                
                removeImage() {
                    this.imagePreview = null;
                    this.fileName = '';
                    this.fileSize = '';
                    this.$refs.fileInput.value = '';
                },
                
                formatFileSize(bytes) {
                    if (bytes === 0) return '0 Bytes';
                    const k = 1024;
                    const sizes = ['Bytes', 'KB', 'MB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
                },
                
                handleDrop(event) {
                    event.preventDefault();
                    this.dragOver = false;
                    const file = event.dataTransfer.files[0];
                    if (file && this.allowedTypes.includes(file.type)) {
                        this.$refs.fileInput.files = event.dataTransfer.files;
                        this.processFile(file);
                    }
                },
                
                handleDragOver(event) {
                    event.preventDefault();
                    this.dragOver = true;
                },
                
                handleDragLeave() {
                    this.dragOver = false;
                }
            }
        }
    </script>
@endsection
