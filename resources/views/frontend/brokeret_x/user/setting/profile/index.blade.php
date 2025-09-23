@extends('frontend::user.setting.index')
@section('title')
    {{ __('Profile Settings') }}
@endsection
@section('settings-content')
    <div class="space-y-5 profile-page" x-data="profileSettings()">
        <div class="grid grid-cols-12 gap-6">
            <div class="2xl:col-span-3 lg:col-span-4 col-span-12">
                <div class="profiel-wrap rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] px-[35px] pb-10 pt-10 relative z-[1]">
                    <div class="customer-profile-cover absolute left-0 top-0 h-[115px] w-full z-[-1] rounded-t-lg" style="background-image: url('{{ config('app.r2_asset_url') . '/fallback/user-header.png' }}')"></div>
                    <div class="profile-box">
                        <div class="flex items-center justify-center h-[140px] w-[140px] mb-4 rounded-full ring-4 ring-slate-100 relative bg-slate-300 dark:bg-body dark:text-white text-slate-900 mx-auto">
                            <img
                                class="w-full h-full object-cover rounded-full"
                                src="{{ getFilteredPath($user->avatar, 'fallback/user.png') }}"
                                alt="{{$user->first_name}}"
                            />
                            <label class="absolute right-1 h-8 w-8 bg-slate-50 text-slate-600 rounded-full shadow-sm flex flex-col items-center justify-center top-[100px] cursor-pointer">
                                <input type="file" class="hidden" @change="handleImageUpload" name="image" accept="image/*">
                                <i data-lucide="pencil-line" class="h-4"></i> 
                            </label>
                        </div>
                        <div class="text-center">
                            <div class="flex items-center justify-center text-2xl font-medium text-slate-900 dark:text-slate-200 mb-[3px]">
                                {{$user->first_name .' '. $user->last_name}}
                                @if($user->kyc >= \App\Enums\KYCStatus::Level2->value)
                                    <x-badge variant="success" style="light" size="sm" class="ml-1">
                                        {{ __('Verified') }}
                                    </x-badge>
                                @else
                                    <x-badge variant="error" style="light" size="sm" class="ml-1">
                                        {{ __('Unverified') }}
                                    </x-badge>
                                @endif
                            </div>
                            <div class="text-sm font-light text-slate-600 dark:text-slate-400">
                                {{ucwords($user->city)}}@if($user->city != ''), @endif{{ $user->country }}
                            </div>
                            <div class="text-sm font-light text-slate-600 dark:text-slate-400 my-5">
                                <span class="font-medium">
                                    {{ __('Member since: ') }}
                                </span>
                                {{ carbonInstance($user->created_at)->toDayDateTimeString() }}
                            </div>
                        </div>
                        <ul class="space-y-5">
                            <li class="flex justify-between text-xs text-slate-600 dark:text-slate-300">
                                <span>{{ __('Customer Group: ') }}</span>
                                @if($user->customerGroups->isNotEmpty())
                                    @foreach($user->customerGroups as $group)
                                        <span>{{ $group->name }}</span>
                                    @endforeach
                                @else
                                    <span>{{ 'N/A' }}</span>
                                @endif
                            </li>
                            <li class="flex justify-between text-xs text-slate-600 dark:text-slate-300">
                                <span>{{ __('Risk Profile:') }}</span> <!-- Added colon here -->
                                <span class="flex items-center gap-2">
                                    @if($user->riskProfileTags->isEmpty())
                                        {{ __('N/A') }}
                                    @else
                                        {{ $user->riskProfileTags->pluck('name')->implode(', ') }}
                                    @endif
                                </span>
                            </li>

                            <li class="flex justify-between text-xs text-slate-600 dark:text-slate-300">
                                <span>{{ __('KYC Level:') }}</span>
                                <span>
                                    @php
                                        $displayName = 'N/A';

                                        if(isset($user->kyc) && $user->kyc > 0) {
                                            // Determine the appropriate KycLevel based on the user's KYC status
                                            if ($user->kyc == 1) {
                                                // If KYC is 1, fetch the name from KycLevel where id == 1
                                                $kycLevel = \App\Models\KycLevel::where('id', 1)->first();
                                            } elseif (in_array($user->kyc, [2, 3, 4])) {
                                                // If KYC is 2, 3, or 4, fetch the name from KycLevel where id == 2
                                                $kycLevel = \App\Models\KycLevel::where('id', 2)->first();
                                            } elseif (in_array($user->kyc, [5, 6, 7])) {
                                                // If KYC is 5, 6, or 7, fetch the name from KycLevel where id == 3
                                                $kycLevel = \App\Models\KycLevel::where('id', 3)->first();
                                            }

                                            // If we found a matching KycLevel
                                            if (isset($kycLevel)) {
                                                if (in_array($user->kyc, [1, 4, 7])) {
                                                    // Only show the KycLevel->name for kyc == 1, 4, or 7
                                                    $displayName = $kycLevel->name;
                                                } else {
                                                    // Get the KYCStatus enum name
                                                    $kycStatus = App\Enums\KYCStatus::from($user->kyc)->name;
                                                    $kycStatusFormatted = ucwords(str_replace('_', ' ', strtolower($kycStatus)));
                                                    // Show both KycLevel->name and KYCStatus for other values
                                                    $displayName = $kycLevel->name . ' - ' . $kycStatusFormatted;
                                                }
                                            } else {
                                                // Fallback to just showing the KYCStatus if no KycLevel is found
                                                $kycStatus = App\Enums\KYCStatus::from($user->kyc)->name;
                                                $displayName = ucwords(str_replace('_', ' ', strtolower($kycStatus)));
                                            }
                                        }
                                    @endphp
                                    {{ $displayName }}
                                </span>
                            </li>
                            <li class="flex justify-between text-xs text-slate-600 dark:text-slate-300">
                                <span>{{ __('IB Member:') }}</span> <!-- Added colon here -->
                                <span class="flex items-center gap-2">
                                    @if($user->ib_status == 'Unprocessed')
                                        {{ __('N/A') }}
                                    @else
                                        {{ ucfirst($user->ib_status)  }}
                                    @endif
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="2xl:col-span-9 lg:col-span-8 col-span-12">
                {{--profile settings--}}
                @include('frontend::user.setting.include.__profile')
            </div>
        </div>
        {{-- Modal for image crop--}}
        @include('frontend::user.setting.include.__avatar_cropper_modal')
    </div>

@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('global/css/cropper.css') }}">
    <style>
        [x-cloak] { display: none !important; }
    </style>
@endsection
@section('script')
    <script src="{{ asset('global/js/cropper.js') }}"></script>
    <script>
        function profileSettings() {
            return {
                showModal: false,
                isProcessing: false,
                cropper: null,
                
                init() {
                    // Initialize any additional setup if needed
                    if (window.renderLucideIcons) {
                        window.renderLucideIcons();
                    }
                },
                
                handleImageUpload(event) {
                    const files = event.target.files;
                    
                    if (files && files.length > 0) {
                        const file = files[0];
                        const reader = new FileReader();
                        
                        reader.onload = (e) => {
                            this.$refs.cropperImage.src = e.target.result;
                            this.showModal = true;
                            
                            // Initialize cropper after modal is shown
                            this.$nextTick(() => {
                                if (this.cropper) {
                                    this.cropper.destroy();
                                }
                                this.cropper = new Cropper(this.$refs.cropperImage, {
                                    aspectRatio: 1,
                                    viewMode: 0,
                                    responsive: true,
                                });
                            });
                        };
                        
                        reader.readAsDataURL(file);
                    }
                },
                
                closeModal() {
                    this.showModal = false;
                    if (this.cropper) {
                        this.cropper.destroy();
                        this.cropper = null;
                    }
                },
                
                async cropAndSave() {
                    if (!this.cropper) return;
                    
                    this.isProcessing = true;
                    
                    try {
                        const canvas = this.cropper.getCroppedCanvas({
                            width: 600,
                            height: 600,
                        });
                        
                        canvas.toBlob(async (blob) => {
                            const formData = new FormData();
                            formData.append('avatar', blob, 'avatar.jpg');
                            
                            try {
                                const response = await fetch('{{ route("user.setting.updateAvatar") }}', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: formData
                                });
                                
                                const data = await response.json();
                                
                                if (data.success) {
                                    notify().success('Profile picture updated successfully');
                                    this.closeModal();
                                    setTimeout(() => location.reload(), 1000);
                                } else {
                                    notify().warning('Upload failed');
                                }
                            } catch (error) {
                                console.error('Error:', error);
                                notify().warning('Something went wrong');
                            } finally {
                                this.isProcessing = false;
                            }
                        }, 'image/jpeg');
                        
                    } catch (error) {
                        console.error('Error:', error);
                        notify().warning('Something went wrong');
                        this.isProcessing = false;
                    }
                }
            };
        }
    </script>
@endsection
