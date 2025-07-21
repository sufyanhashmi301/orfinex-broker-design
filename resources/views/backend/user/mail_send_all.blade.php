@extends('backend.layouts.app')
@section('title')
    {{ __('Send Email to All') }}
@endsection
@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('Send Email to All') }}</h4>
            </div>
            <div class="card-body p-6">
                <form action="{{ route('admin.user.mail-send') }}" method="post">
                    @csrf
                    <div class="space-y-5">
                        <!-- Recipient Selection Section -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="input-area">
                                <label for="users" class="form-label">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Select the users to send the email to">
                                        {{ __('Users') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <select name="users[]" id="users" class="form-control" multiple>
                                    @if(old('users'))
                                        @foreach(old('users') as $userId)
                                            @php $user = App\Models\User::find($userId); @endphp
                                            @if($user)
                                                <option value="{{ $user->id }}" selected>
                                                    {{ $user->first_name }} {{ $user->last_name }} ({{ $user->email }})
                                                </option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                @error('users')
                                    <span class="text-danger text-xs">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">{{ __('Start typing to search users') }}</small>
                            </div>

                            <!-- IB Groups Dropdown -->
                            <div class="input-area">
                                <label for="ib_groups" class="form-label">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Select the IB groups to send the email to">
                                        {{ __('IB Groups') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <select name="ib_groups[]" id="ib_groups" class="form-control select2" multiple>
                                    <option value="all" @if(old('ib_groups') && in_array('all', old('ib_groups'))) selected @endif>{{ __('All IB Groups') }}</option>
                                    @foreach($ibGroups as $group)
                                        <option value="{{ $group->id }}" @if(old('ib_groups') && in_array($group->id, old('ib_groups'))) selected @endif>
                                            {{ $group->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('ib_groups')
                                    <span class="text-danger text-xs">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">{{ __('Select one or multiple IB groups') }}</small>
                            </div>
                            
                            <!-- Account Type (Forex Schema) Dropdown -->
                            <div class="input-area">
                                <label for="account_types" class="form-label">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Select the account types to send the email to">
                                        {{ __('Account Types') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <select name="account_types[]" id="account_types" class="form-control select2" multiple>
                                    <option value="all" @if(old('account_types') && in_array('all', old('account_types'))) selected @endif>{{ __('All Account Types') }}</option>
                                    @foreach($forexSchemas as $schema)
                                        <option value="{{ $schema->id }}" @if(old('account_types') && in_array($schema->id, old('account_types'))) selected @endif>
                                            {{ $schema->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('account_types')
                                    <span class="text-danger text-xs">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">{{ __('Select one or multiple account types') }}</small>
                            </div>
                        </div>
                        
                        <!-- Email Content Section -->
                        <div class="input-area">
                            <label for="subject" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enter the subject of the email">
                                    {{ __('Subject') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input type="text" name="subject" class="form-control mb-0" value="{{ old('subject') }}" />
                            @error('subject')
                                <span class="text-danger text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="input-area">
                            <label for="message" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enter the details of the email">
                                    {{ __('Email Details') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <textarea id="summernote" class="summernote form-control mb-0" rows="7">{{ old('message') }}</textarea>
                            <input type="hidden" name="message" id="message" value="{{ old('message') }}">
                            @error('message')
                                <span class="text-danger text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="action-btns text-right mt-10">
                        <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:send"></iconify-icon>
                            {{ __('Send Email') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        // Initialize Select2 for users
        $('#users').select2({
            placeholder: 'Search user by name or email',
            minimumInputLength: 0, // allow triggering without typing
            ajax: {
                url: '{{ route("admin.user.search") }}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term || ''
                    };
                },
                processResults: function (data, params) {
                    let results = [];

                    // If no search term, only show "All Users"
                    if (!params.term || params.term.trim() === '') {
                        results.push({
                            id: 'all',
                            text: 'All Users',
                            email: '',
                            avatar: ''
                        });
                    } else {
                        results = data.results;
                    }

                    return {
                        results: results
                    };
                },
                cache: true
            },
            templateResult: function (user) {
                if (user.loading) return user.text;

                if (user.id === 'all') {
                    return $('<span><i class="fas fa-users mr-2"></i> All Users</span>');
                }

                return $(`<span>${user.text} <small class="text-muted">(${user.email})</small></span>`);
            },
            templateSelection: function (user) {
                if (user.id === 'all') {
                    return 'All Users';
                }

                if (!user || typeof user !== 'object') return '';

                const name = user.text || '';
                const email = user.email || '';

                return email ? `${name} (${email})` : name;
            }
        });

        // Initialize Select2 for other multi-select fields
        $('#ib_groups, #account_types').select2();

        // Initialize Summernote
        $('.summernote').summernote({
            height: 300,
            callbacks: {
                onChange: function(contents, $editable) {
                    $('#message').val(contents);
                },
                onInit: function() {
                    // Set initial content if there's old input
                    @if(old('message'))
                        $('.summernote').summernote('code', '{!! old('message') !!}');
                    @endif
                }
            }
        });

        // Ensure hidden message input is updated on form submit
        $('form').on('submit', function(e) {
            $('#message').val($('#summernote').summernote('code'));
        });
    });
</script>
@endsection