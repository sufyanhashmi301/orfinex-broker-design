@extends('frontend::user.setting.index')
@section('title')
    {{ __('Withdraw Accounts') }}
@endsection
@section('settings-content')
    @if(count($accounts) == 0)
        <div class="max-w-xl text-center py-10 mx-auto space-y-5">
            <div class="w-20 h-20 bg-danger text-white rounded-full inline-flex items-center justify-center">
                <iconify-icon icon="fa6-solid:box-open" class="text-5xl"></iconify-icon>
            </div>
            <h4 class="text-3xl text-slate-900 dark:text-white">
                {{ __("You're almost ready to withdraw!") }}
            </h4>
            <p class="text-slate-600 dark:text-slate-100">{{ __('To make a withdraw, please add a withdraw account from your profile (withdraw accounts).') }}</p>
            <a href="{{ route('user.withdraw.account.create') }}" class="btn md:btn-sm btn-dark loaderBtn inline-flex items-center justify-center">
                {{ __('Add Withdraw Account') }}
            </a>
        </div>
    @else
        @if($withdrawAccountApproval)
            <div class="py-[18px] px-6 font-normal font-Inter text-sm rounded-md bg-warning-500 bg-opacity-[14%] mb-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <iconify-icon icon="lucide:info" class="text-xl"></iconify-icon>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium">
                            {{ __('Manual Approval Required') }}
                        </h3>
                        <div class="mt-2 text-sm">
                            <p>{{ __('New withdraw accounts require admin approval before they can be used. Pending accounts will be reviewed and you will be notified of the result.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">@yield('title')</h4>
                <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                    <a href="{{ route('user.withdraw.account.create') }}" class="btn loaderBtn inline-flex justify-center btn-primary btn-sm">
                        <span class="flex items-center">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="bi:plus"></iconify-icon>
                            <span>{{ __('Add New') }}</span>
                        </span>
                    </a>
                </div>
            </div>
            <div class="card-body relative px-6 pt-3">
                <div class="overflow-x-auto -mx-6 dashcode-data-table">
                    <span class=" col-span-8  hidden"></span>
                    <span class="  col-span-4 hidden"></span>
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden ">
                            <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700" id="dataTable">
                                <thead>
                                    <tr>
                                        <th scope="col" class="table-th">{{ __('Account') }}</th>
                                        <th scope="col" class="table-th">{{ __('Status') }}</th>
                                        <th scope="col" class="table-th">{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                    @foreach($accounts as $account)
                                    <tr>
                                        <td class="table-td">
                                            <div class="flex items-center">
                                                <div class="flex-none">
                                                    <div class="w-10 h-10 lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center mr-2">
                                                        <iconify-icon icon="lucide:backpack"></iconify-icon>
                                                    </div>
                                                </div>
                                                <div class="flex-1 text-start">
                                                    <h4 class="text-sm font-medium text-slate-600 dark:text-white whitespace-nowrap">
                                                        {{ $account->method_name }}
                                                    </h4>
                                                    <div class="text-xs font-normal text-slate-600 dark:text-slate-200">
                                                        {{ __('Account') }}: {{ $account->method->currency }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="table-td">
                                            @if($account->status === 'pending')
                                                <span class="badge badge-warning capitalize">{{ __('Pending') }}</span>
                                            @elseif($account->status === 'approved')
                                                <span class="badge badge-success capitalize">{{ __('Approved') }}</span>
                                            @elseif($account->status === 'rejected')
                                                <span class="badge badge-danger capitalize">{{ __('Rejected') }}</span>
                                            @else
                                                <span class="badge badge-secondary capitalize">{{ __('Unknown') }}</span>
                                            @endif
                                        </td>
                                        <td class="table-td">
                                            <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                                <button type="button"
                                                        class="action-btn view-account-btn"
                                                        data-account-id="{{ the_hash($account->id) }}"
                                                        data-account-name="{{ $account->method_name }}">
                                                    <iconify-icon icon="lucide:eye"></iconify-icon>
                                                </button>
                                                <button type="button"
                                                        class="action-btn delete-account-btn"
                                                        data-account-id="{{ the_hash($account->id) }}"
                                                        data-account-name="{{ $account->method_name }}">
                                                    <iconify-icon icon="lucide:trash-2"></iconify-icon>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
         id="deleteAccountModal"
         tabindex="-1"
         aria-labelledby="deleteAccountModalLabel"
         aria-hidden="true">
        <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
            <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                <div class="modal-body p-6 py-10 text-center">
                    <div class="space-y-3">
                        <div class="info-icon h-16 w-16 rounded-full inline-flex items-center justify-center bg-danger text-danger bg-opacity-30">
                            <iconify-icon class="text-4xl" icon="lucide:alert-triangle"></iconify-icon>
                        </div>
                        <div class="title">
                            <h4 class="text-2xl font-medium dark:text-white capitalize">
                                {{ __('Are you sure?') }}
                            </h4>
                        </div>
                        <p>
                            {{ __('You want to delete') }} <strong class="account-name"></strong> {{ __('Withdraw Account?') }}
                        </p>
                    </div>
                    <div class="action-btns mt-10">
                        <button type="button" id="confirm-delete-btn" class="btn btn-dark inline-flex items-center justify-center mr-2">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                            {{ __('Confirm') }}
                        </button>
                        <button type="button" class="btn btn-danger inline-flex items-center justify-center" data-bs-dismiss="modal" aria-label="Close">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                            {{ __('Cancel') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Account Details Modal -->
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
         id="viewAccountModal"
         tabindex="-1"
         aria-labelledby="viewAccountModalLabel"
         aria-hidden="true">
        <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
            <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                <div class="modal-header flex flex-shrink-0 items-center justify-between p-4 border-b border-slate-200 dark:border-slate-700 rounded-t-md">
                    <h5 class="text-xl font-medium leading-normal text-slate-800 dark:text-white" id="viewAccountModalLabel">
                        {{ __('Account Details') }}
                    </h5>
                    <button type="button" class="btn-close box-content w-4 h-4 p-1 text-slate-500 bg-transparent border-0 rounded-none opacity-50 focus:shadow-none focus:opacity-100 focus:outline-none outline-none" data-bs-dismiss="modal" aria-label="Close">
                        <iconify-icon icon="lucide:x"></iconify-icon>
                    </button>
                </div>
                <div class="modal-body p-6">
                    <div id="account-details-content">
                        <!-- Account details will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle view account button clicks
    document.querySelectorAll('.view-account-btn').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            const accountId = this.getAttribute('data-account-id');
            const accountName = this.getAttribute('data-account-name');

            // Show loading state
            const contentDiv = document.getElementById('account-details-content');
            contentDiv.innerHTML = `
                <div class="flex items-center justify-center py-8">
                    <iconify-icon class="spining-icon text-2xl" icon="svg-spinners:180-ring"></iconify-icon>
                    <span class="ml-2">{{ __("Loading account details...") }}</span>
                </div>
            `;

            // Show modal
            const modal = document.getElementById('viewAccountModal');
            modal.style.display = 'block';
            modal.classList.remove('hidden');
            modal.classList.add('show');

            // Fetch account details
            fetch(`{{ route('user.withdraw.account.show', ['id' => ':id']) }}`.replace(':id', accountId), {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    contentDiv.innerHTML = data.html;
                } else {
                    contentDiv.innerHTML = `
                        <div class="text-center py-8">
                            <iconify-icon icon="lucide:alert-circle" class="text-4xl text-red-500 mb-4"></iconify-icon>
                            <p class="text-red-600">{{ __("Error loading account details") }}</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                contentDiv.innerHTML = `
                    <div class="text-center py-8">
                        <iconify-icon icon="lucide:alert-circle" class="text-4xl text-red-500 mb-4"></iconify-icon>
                        <p class="text-red-600">{{ __("Error loading account details") }}</p>
                    </div>
                `;
            });
        });
    });

    // Handle delete account button clicks
    document.querySelectorAll('.delete-account-btn').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            const accountId = this.getAttribute('data-account-id');
            const accountName = this.getAttribute('data-account-name');

            // Show modal
            const modal = document.getElementById('deleteAccountModal');
            if (modal) {
                modal.style.display = 'block';
                modal.classList.remove('hidden');
                modal.classList.add('show');
                
                // Set account name
                const accountNameElement = modal.querySelector('.account-name');
                if (accountNameElement) {
                    accountNameElement.textContent = accountName;
                }
                
                // Store account ID for deletion
                modal.setAttribute('data-account-id', accountId);
            }
        });
    });

    // Handle confirm delete button
    document.getElementById('confirm-delete-btn').addEventListener('click', function() {
        const modal = document.getElementById('deleteAccountModal');
        const accountId = modal.getAttribute('data-account-id');
        
        // Show loading state
        const submitBtn = this;
        const originalContent = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <iconify-icon class="spining-icon text-xl ltr:mr-2 rtl:ml-2" icon="svg-spinners:180-ring"></iconify-icon>
            {{ __("Deleting...") }}
        `;

        // Create form data for DELETE request
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('_method', 'DELETE');

        // Send DELETE request
        fetch(`{{ route('user.withdraw.account.index') }}/${accountId}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            // Hide modal first
            modal.style.display = 'none';
            modal.classList.add('hidden');
            modal.classList.remove('show');

            // Show success notification
            if (typeof tNotify === 'function') {
                tNotify('success', '{{ __("Withdraw account deleted successfully.") }}');
            }

            // Reload page to refresh the list
            setTimeout(function() {
                window.location.reload();
            }, 1500);
        })
        .catch(error => {
            // Reset button state
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalContent;

            // Show error notification
            let errorMessage = '{{ __("Error deleting withdraw account") }}';
            if (typeof tNotify === 'function') {
                tNotify('error', errorMessage);
            }

            // Hide modal on error
            modal.style.display = 'none';
            modal.classList.add('hidden');
            modal.classList.remove('show');
        });
    });

    // Handle modal close buttons
    document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(function(button) {
        button.addEventListener('click', function() {
            const deleteModal = document.getElementById('deleteAccountModal');
            const viewModal = document.getElementById('viewAccountModal');
            
            if (deleteModal) {
                deleteModal.style.display = 'none';
                deleteModal.classList.add('hidden');
                deleteModal.classList.remove('show');
            }
            
            if (viewModal) {
                viewModal.style.display = 'none';
                viewModal.classList.add('hidden');
                viewModal.classList.remove('show');
            }
        });
    });

    // Handle clicking outside modals to close
    document.getElementById('deleteAccountModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.style.display = 'none';
            this.classList.add('hidden');
            this.classList.remove('show');
        }
    });

    document.getElementById('viewAccountModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.style.display = 'none';
            this.classList.add('hidden');
            this.classList.remove('show');
        }
    });
});
</script>
