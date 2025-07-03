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
                <form action="{{ route('admin.user.mail-send') }}" method="post" class="space-y-5">
                    @csrf
                    
                    <!-- Recipient Selection Section -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="input-area">
                            <label for="users" class="form-label">{{ __('Users') }}</label>
                            <select name="users[]" id="users" class="form-control" multiple>
                                <option value="all">{{ __('All Users') }}</option>
                            </select>
                            <small class="text-muted">{{ __('Start typing to search users') }}</small>
                        </div>
                        <!-- IB Groups Dropdown -->
                        <div class="input-area">
                            <label for="ib_groups" class="form-label">{{ __('IB Groups') }}</label>
                            <select name="ib_groups[]" id="ib_groups" class="form-control select2" multiple>
                                <option value="all">{{ __('All IB Groups') }}</option>
                                @foreach($ibGroups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">{{ __('Select one or multiple IB groups') }}</small>
                        </div>
                        
                        <!-- Account Type (Forex Schema) Dropdown -->
                        <div class="input-area">
                            <label for="account_types" class="form-label">{{ __('Account Types') }}</label>
                            <select name="account_types[]" id="account_types" class="form-control select2" multiple>
                                <option value="all">{{ __('All Account Types') }}</option>
                                @foreach($forexSchemas as $schema)
                                    <option value="{{ $schema->id }}">{{ $schema->title }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">{{ __('Select one or multiple account types') }}</small>
                        </div>
                    </div>
                    
                    <!-- Email Content Section -->
                    <div class="input-area">
                        <label for="subject" class="form-label">{{ __('Subject:') }}</label>
                        <input type="text" name="subject" class="form-control mb-0" required/>
                    </div>
                    
                    <div class="input-area">
                        <label for="message" class="form-label">{{ __('Email Details') }}</label>
                        <textarea id="summernote" class="summernote form-control mb-0" rows="7"></textarea>
                        <input type="hidden" name="message" id="message">
                    </div>

                    <div class="action-btns text-right">
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
        const allUsersOption = {
        id: 'all',
        text: 'All Users',
        email: '',
        avatar: ''
    };

    $('#users').select2({
        placeholder: 'Search user by name or email',
        minimumInputLength: 1,
        ajax: {
            url: '{{ route("admin.user.search") }}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return { q: params.term };
            },
            processResults: function (data) {
                // Prepend "All Users" as the first option
                return {
                    results: [allUsersOption, ...data.results]
                };
            },
            cache: true
        },
        templateResult: function (user) {
            if (user.loading) return user.text;
            if (user.id === 'all') {
                return $(`<span><strong>${user.text}</strong></span>`);
            }
            const avatar = user.avatar ? `<img src="${user.avatar}" class="w-5 h-5 rounded-full mr-2 inline-block align-middle">` : '';
            return $(`<span>${avatar}${user.text} <small class="text-muted">(${user.email})</small></span>`);
        },
        templateSelection: function (user) {
            if (!user || typeof user !== 'object') return user;
            if (user.id === 'all') return 'All Users';
            const name = user.text || '';
            const email = user.email || '';
            return email ? `${name} (${email})` : name;
        }
    });
        
        // Initialize other Select2 dropdowns
        // $('.select2').select2({
        //     placeholder: "Select options",
        //     allowClear: true
        // });
        
        // Initialize Summernote
        $('.summernote').summernote({
            height: 300,
            callbacks: {
                onChange: function(contents, $editable) {
                    // Update hidden input with sanitized content
                    $('#message').val(contents);
                }
            }
        });
        
        // Handle form submission
        $('form').on('submit', function(e) {
            // Ensure the hidden message field has the latest content
            $('#message').val($('#summernote').summernote('code'));
        });
    });
</script>
@endsection