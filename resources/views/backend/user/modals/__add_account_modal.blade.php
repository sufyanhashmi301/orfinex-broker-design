<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="add-account" tabindex="-1" aria-labelledby="add-accountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
            <div class="flex items-center justify-between p-5">
                <h3 class="text-xl font-medium dark:text-white capitalize" id="add-accountLabel">
                    {{ __('Add Account') }}
                </h3>
                <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                            dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="#000000" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                                11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="modal-body p-6 pt-0">
                <form action="{{ route('admin.account.add_manually') }}" method="post" class="space-y-5" id="add-manual-account-form">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <div class="input-area">
                        <label for="" class="form-label">Select Account Type</label>
                        <select class="form-control w-100" name="account_type_id" id="select-account-type">
                            <option value="" disabled selected>Select Account Type</option>
                            @foreach($all_account_types as $account_type)
                                <option value="{{$account_type->id}}">{{ $account_type->title }}</option>
                            @endforeach
                        </select>

                    </div> 
                    <div class="input-area" id="phases-container" style="display: none">
                        <label for="" class="form-label">Select Phase</label>
                        <select class="form-control w-100" name="phase_id" id="" required style="text-transform: capitalize">
                            
                        </select>

                    </div>

                    <div class="input-area" id="rules-container" style="display: none">
                        <label for="" class="form-label">Allocated Funds</label>
                        <div class="grid md:grid-cols-2 grid-cols-1 gap-5 funds-options">
                            
                        </div>
                    </div>

                    <div class="input-area text-right">
                        <button type="submit" class="btn btn-dark inline-flex items-center justify-center add-account" disabled>
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:list-plus"></iconify-icon>
                            {{ __('Add Account') }}
                        </button>
                        <style>
                            button:disabled {
                                opacity: 0.6;
                            }
                        </style>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

@push('single-script')
    <script>

        let ruleOptionHtml = (id, funds, is_checked = false) => {
            return `<div class="success-radio">
                        <label class="flex items-center cursor-pointer p-3 rounded border dark:border-slate-700">
                            <input type="radio" class="hidden" name="rule_id" value="${id}" ${is_checked == true ? 'checked' : ''}>
                            <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>

                            <span class="dark:text-white">$${funds}</span>

                        </label>
                    </div>`
        }

        $('#select-account-type').on('change', function () {

            $('#phases-container').hide()
            $('#rules-container').hide()

            $.ajax({
                url: "{{ route('admin.account_type.info') }}", // Laravel named route
                type: "GET",
                data: {
                    'id': $(this).val()
                },
                success: function (data) {
                    if(data == 'false') {
                        alert('Unknown error occured.')
                        return false
                    }

                    $('.add-account').removeAttr('disabled')
                    $('#phases-container').show()
                    $('#rules-container').show()

                    let phases = data.phases
                    let rules = data.rules

                    // Phases
                    let phases_options = '<option value="" disabled selected>Select Phase</option>'
                    for(let i=0; i < phases.length; i++) {
                        phases_options += `<option value="${phases[i]['id']}">${phases[i]['type'].replace('_', ' ')} (Step ${phases[i]['phase_step']})</option>`
                    }
                    $('#phases-container').find('select').html(phases_options)

                    // Rules
                    let rules_options = ''
                    for(let i=0; i < rules.length; i++) {
                        if(i == 0) {
                            rules_options += ruleOptionHtml(rules[i]['id'], rules[i]['allotted_funds'], true)
                        } else {
                            rules_options += ruleOptionHtml(rules[i]['id'], rules[i]['allotted_funds'])
                        }
                    }
                    $('#rules-container').find('.funds-options').html(rules_options);

                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText); // Handle error response
                }
            });

            $('#add-manual-account-form').on('submit', function() {
                $('.add-account').attr('disabled', true)
            })
        });
    </script>
@endpush
