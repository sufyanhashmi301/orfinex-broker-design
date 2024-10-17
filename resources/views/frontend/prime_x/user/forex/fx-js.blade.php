<script type="text/javascript">

    function view_modal_form(formData, btn, url) {
        // debugger;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: url,
            type: 'POST', data: formData, processData: false, contentType: false,
            success: function (res) {
                if (res) {
                    // console.log(res);
                    btn.prop('disabled', false);
                    $('#view_leverage_form').html(res);
                    // NioApp.Toast(res.success, 'success');
                    // if(res.reload) {
                    //     setTimeout(function(){ location.reload(); }, 900);
                    // }
                } else if (res.error) {
                    tNotify('warning', res.error);
                    btn.prop('disabled', false);
                    setTimeout(function () {
                        location.reload();
                    }, 900);
                } else if (res.errors) {
                    btn.prop('disabled', false);
                    NioApp.Form.errors(res, true);

                }
            },
            error: function (data) {
                btn.prop('disabled', false);
                tNotify('warning', data.responseJSON.message);
            }
        })
    }


{{--    function getGroupTypeInfo(formData, btn, url, appendId = null, appendId2 = null) {--}}
{{--        // console.log(appendId,'appendId')--}}
{{--        // debugger;--}}
{{--        $.ajax({--}}
{{--            url: url,--}}
{{--            type: 'POST', data: formData, processData: false, contentType: false,--}}
{{--            beforeSend: function () {--}}
{{--                $("#sppiner-loader").show();--}}
{{--            },--}}
{{--            success: function (res) {--}}
{{--                if (res.append) {--}}
{{--                    // console.log(appendId,'appendId')--}}
{{--                    // console.log(res.append,'res.append')--}}
{{--                    $('#' + appendId).html(res.append);--}}
{{--                    $('#' + appendId2).html(res.append2);--}}
{{--                    // NioApp.Toast(res.error, 'warning');--}}
{{--                    // setTimeout(function(){ location.reload(); }, 900);--}}

{{--                    $(".form-select").select2({--}}
{{--                        matcher: matchCustom,--}}
{{--                        templateResult: formatState,--}}
{{--                        templateSelection: formatState--}}
{{--                    });--}}

{{--                    function stringMatch(term, candidate) {--}}
{{--                        return candidate && candidate.toLowerCase().indexOf(term.toLowerCase()) >= 0;--}}
{{--                    }--}}

{{--                    function matchCustom(params, data) {--}}
{{--                        // If there are no search terms, return all of the data--}}
{{--                        if ($.trim(params.term) === '') {--}}
{{--                            return data;--}}
{{--                        }--}}
{{--                        // Do not display the item if there is no 'text' property--}}
{{--                        if (typeof data.text === 'undefined') {--}}
{{--                            return null;--}}
{{--                        }--}}
{{--                        // Match text of option--}}
{{--                        if (stringMatch(params.term, data.text)) {--}}
{{--                            return data;--}}
{{--                        }--}}
{{--                        // Match attribute "data-foo" of option--}}
{{--                        if (stringMatch(params.term, $(data.element).attr('data-des'))) {--}}
{{--                            return data;--}}
{{--                        }--}}
{{--                        // Return `null` if the term should not be displayed--}}
{{--                        return null;--}}
{{--                    }--}}

{{--                    function formatState(opt) {--}}
{{--                        if (!opt.id) {--}}
{{--                            return opt.text.toUpperCase();--}}
{{--                        }--}}

{{--                        var optimage = $(opt.element).attr('data-image');--}}
{{--                        var optdes = $(opt.element).attr('data-des');--}}
{{--                        // console.log(optimage)--}}
{{--                        if (!optimage) {--}}
{{--                            return opt.text.toUpperCase();--}}
{{--                        } else {--}}
{{--                            var $opt = $(--}}
{{--                                '<div class="coin-item coin-btc"><div class="coin-icon"><img src="' + optimage + '" class="mr-2" width="40px" /></div><div class="coin-info"><span class="coin-name">' + opt.text.toUpperCase() + '</span><ul class="kanban-item-meta-list">' + optdes + '</ul></div></div>'--}}
{{--                            );--}}
{{--                            return $opt;--}}
{{--                        }--}}
{{--                    }--}}
{{--                } else if (res.error) {--}}
{{--                    NioApp.Toast(res.error, 'warning');--}}
{{--                    setTimeout(function () {--}}
{{--                        location.reload();--}}
{{--                    }, 900);--}}
{{--                } else if (res.errors) {--}}
{{--                    NioApp.Form.errors(res, true);--}}
{{--                    btn.prop('disabled', false);--}}
{{--                }--}}
{{--            },--}}
{{--            complete: function (data) {--}}
{{--                // Hide image container--}}
{{--                $("#sppiner-loader").hide();--}}
{{--            },--}}
{{--            error: function (data) {--}}
{{--                btn.prop('disabled', false);--}}
{{--                NioApp.Toast("Sorry something went wrong! Please reload the page and try again.", 'warning');--}}
{{--            }--}}
{{--        })--}}
{{--    }--}}

    $('body').on('click', '.dropdown-update-leverage', function () {
        var btn = $(this);
        btn.prop('disabled', true);
        $('#update-leverage-modal-login').text($(this).data('login'));
        $('#changeLeverage').modal('toggle');
        // let form = document.querySelector('#sent-form');
        let formData = new FormData();
        formData.append('id', $(this).data('id'));
        formData.append('login', $(this).data('login'));
        var url = $(this).data('action');
        console.log(url);
        view_modal_form(formData, btn, url);
    });

    //depoist demo account
    $('body').on('click', '.dropdown-deposit-demo-account', function () {
        $('.deposit-demo-account-login').text($(this).data('login'));
        $('.deposit-demo-account-login').val($(this).data('login'));
    });
    $('body').on('click', '#deposit-demo-account-submit', function () {
        var amount = $('#demo-amount').val();
        if (amount) {
            var btn = $(this);
            btn.prop('disabled', true);
            let formData = new FormData();
            formData.append('target_id', $('#deposit-demo-account-login').val());
            formData.append('amount', $('#demo-amount').val());
            var url = $('#deposit-demo-form').attr('action');
            deposit_demo(url,formData, btn);
        }
    });

    function deposit_demo(url, formData, btn) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // Convert formData to FormData object if it's not already
        if (!(formData instanceof FormData)) {
            formData = new FormData(formData);
        }

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false, // Don't process the data
            contentType: false, // Don't set contentType
            success: function (res) {
                // console.log(res, 'res');
                if (res.success) {
                    tNotify('success', res.success);
                    if (res.reload) {
                        setTimeout(function () {
                            location.reload();
                        }, 900);
                    }
                } else if (res.error) {
                    tNotify('warning', res.error);
                    btn.prop('disabled', false);
                } else if (res.errors) {
                    tNotify('warning', res.message);
                    btn.prop('disabled', false);
                }
            },
            error: function (xhr, status, error) {
                // Handle error
                console.log(xhr.responseText,'error');

                var errorMessage = "Sorry, something went wrong! Please try again.";

                // Check if there are specific errors provided
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    // Extract error messages
                    var errorMessages = Object.values(xhr.responseJSON.errors).flat();
                    errorMessage = errorMessages.join('<br>');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    // Use the general error message if no specific errors found
                    errorMessage = xhr.responseJSON.message;
                }

                tNotify('warning', errorMessage);
                btn.prop('disabled', false);
            }
        });
    }
    {{--    $('body').on('change', '.account-group', function () {--}}
{{--        var obj = $(this);--}}
{{--        var type = obj.data('type');--}}
{{--        var appendId = 'account-leverage-' + type--}}
{{--        var appendId2 = 'group-currency-' + type--}}
{{--        if ($('#account-group-islamic-' + type).prop("checked")) {--}}
{{--            var islamic = 1;--}}
{{--        } else {--}}
{{--            var islamic = 0;--}}
{{--        }--}}
{{--        if (obj.val() == 'default_option') {--}}
{{--            $('#' + appendId).html('');--}}
{{--        } else {--}}
{{--            let formData = new FormData();--}}
{{--            formData.append('type', type);--}}
{{--            formData.append('id', $('#group-type-id-' + type).val());--}}
{{--            formData.append('islamic', islamic);--}}
{{--            var url = "{{route('user.forex-trading.get.group.leverages')}}"--}}
{{--            getGroupTypeInfo(formData, obj, url, appendId, appendId2);--}}
{{--        }--}}
{{--    });--}}
{{--    //trade modal--}}
{{--    $('body').on('click', '#trade_from_forex', function () {--}}
{{--        $('#login').text($(this).data('login'));--}}
{{--        $('#server').text($(this).data('server'));--}}
{{--    });--}}

{{--    $(document).ready(function () {--}}
{{--        $('body').on('click', '#other_options_btn', function () {--}}
{{--            $("#other_options").slideToggle();--}}
{{--        });--}}
{{--        $('body').on('click', '.deposit-btn', function () {--}}
{{--            $('#login-id').val($(this).data('login'));--}}
{{--            $('#deposit-user-id').val($(this).data('user_id'));--}}
{{--        });--}}
{{--        $('body').on('click', '.deposit-btn-demo', function () {--}}

{{--            $('#login-id-demo').val($(this).data('login'));--}}
{{--            $('#deposit-user-id-demo').val($(this).data('user_id'));--}}
{{--            // $('#login-id-demo').val($(this).data('login'));--}}
{{--        });--}}
{{--    });--}}
{{--    //update user withdraw--}}
{{--    function get_account_balance(login) {--}}
{{--        // debugger;--}}
{{--        var url = "{{ route('get.forex-trading.account') }}";--}}
{{--        $.ajax({--}}
{{--            url: url,--}}
{{--            type: 'get',--}}
{{--            data: {login: login},--}}
{{--            success: function (res) {--}}
{{--                if (res) {--}}
{{--                    $('#withdraw-from-account').val(res.account);--}}
{{--                    $('#withdraw-amount').val(res.amount);--}}
{{--                    $('#withdraw-login-id').val(login);--}}
{{--                } else if (res.error) {--}}
{{--                    NioApp.Toast(res.error, 'warning');--}}
{{--                    setTimeout(function () {--}}
{{--                        location.reload();--}}
{{--                    }, 900);--}}
{{--                } else if (res.errors) {--}}
{{--                    NioApp.Form.errors(res, true);--}}
{{--                }--}}
{{--            },--}}
{{--            error: function (data) {--}}
{{--                NioApp.Toast("{{ __('Sorry, something went wrong! Please reload the page and try again.') }}", 'warning');--}}
{{--            }--}}
{{--        })--}}
{{--    }--}}

{{--    $('body').on('click', '#withdraw_from_forex', function () {--}}
{{--        var login = $('#withdraw_from_forex').data('login');--}}
{{--        if (login) {--}}
{{--            get_account_balance(login);--}}
{{--        }--}}
{{--    });--}}
{{--    $('body').on('click', '#submit-withdraw', function () {--}}
{{--        $('#submit-withdraw').prop('disabled', true);--}}
{{--        let formData = new FormData();--}}
{{--        formData.append('login', $('#withdraw-login-id').val());--}}
{{--        formData.append('amount', $('#withdraw-amount').val());--}}
{{--        submit_withdraw(formData);--}}
{{--    });--}}

{{--    function submit_withdraw(formData) {--}}
{{--        // debugger;--}}
{{--        $.ajax({--}}
{{--            url: "{{ route('user.forex-trading.withdraw') }}",--}}
{{--            type: 'POST', data: formData, processData: false, contentType: false,--}}
{{--            success: function (res) {--}}
{{--                if (res.success) {--}}
{{--                    NioApp.Toast(res.success, 'success');--}}
{{--                    if (res.reload) {--}}
{{--                        setTimeout(function () {--}}
{{--                            location.reload();--}}
{{--                        }, 900);--}}
{{--                    }--}}
{{--                } else if (res.error) {--}}
{{--                    NioApp.Toast(res.error, 'warning');--}}
{{--                    setTimeout(function () {--}}
{{--                        location.reload();--}}
{{--                    }, 900);--}}
{{--                } else if (res.errors) {--}}
{{--                    NioApp.Form.errors(res, true);--}}
{{--                    $('#submit-withdraw').prop('disabled', false);--}}
{{--                }--}}
{{--            },--}}
{{--            error: function (data) {--}}
{{--                $('#submit-withdraw').prop('disabled', false);--}}
{{--                NioApp.Toast("{{ __('Sorry, something went wrong! Please reload the page and try again.') }}", 'warning');--}}
{{--            }--}}
{{--        })--}}
{{--    }--}}

    //account archive & reactive
    $('body').on('click', '.archive-login', function () {
        $('.update-archive-login').val($(this).data('login'));
    });
    $('body').on('click', '.dropdown-update-archive', function () {
        // $('#archiveAccount').modal('show');
        var btn = $(this);
        btn.prop('disabled', true);
        var login = $('.update-archive-login').val();
        let formData = new FormData();
        formData.append('login', login);
        formData.append('archive', true);
        update_user_info(formData, btn);
        // }
    });
    $('body').on('click', '.reactivate-account', function () {
        var btn = $(this);
        btn.prop('disabled', true);
        var login = $('.update-archive-login').val();
        let formData = new FormData();
        formData.append('login', login);
        formData.append('reactive', true);
        update_user_info(formData, btn);
        // }
    });

    //account info in modal
    $('body').on('click', '.dropdown-account-info', function () {
        $('#account-info-login').text($(this).data('login'));
        $('#account-info-account_name').text($(this).data('account_name'));
        $('#account-info-server').text($(this).data('server'));
        $('#account-schema-title').text($(this).data('schema_title'));
        $('#account-type').text($(this).data('account_type'));
        $('#account-info-leverage').text($(this).data('leverage'));
        $('#account-info-balance').text($(this).data('balance'));
        $('#account-info-free-margin').text($(this).data('free-margin'));
        $('#account-info-equity').text($(this).data('equity'));
    });

    //update user leverage
    // $('body').on('click', '.dropdown-update-leverage', function () {
    //     $('#update-leverage-modal-login').text($(this).data('login'));
    //     $('#update-leverage-modal-login-id').val($(this).data('login'));
    //     var leverages = $(this).data('leverage');
    // });

    $('body').on('click', '#submit-leverage', function () {
        var leverage = $('#update-leverage-modal-leverage').val();
        if (leverage) {
            var btn = $(this);
            btn.prop('disabled', true);
            let formData = new FormData();
            formData.append('login', $('#update-leverage-modal-login-id').val());
            formData.append('leverage', leverage);
            update_user_info(formData, btn);
        }
    });

    //update user nickname
    $('body').on('click', '.dropdown-update-name', function () {
        $('.update-name-modal-login').text($(this).data('login'));
        $('.update-name-modal-login').val($(this).data('login'));
        $('#update-name-modal-name').val($(this).data('account_name'));
    });
    $('body').on('click', '#submit-name', function () {
        var name = $('#update-name-modal-name').val();
        if (name) {
            var btn = $(this);
            btn.prop('disabled', true);
            let formData = new FormData();
            formData.append('login', $('#update-name-modal-login').val());
            formData.append('name', name);
            update_user_info(formData, btn);
        }
    });
    //update user password
    $('body').on('click', '.dropdown-update-password', function () {
        $('.update-password-modal-login').text($(this).data('login'));
        $('.update-password-modal-login').val($(this).data('login'));
    });
    $('body').on('click', '#submit-password', function () {
        var main_pass = $('#update-main-password').val();
        if (main_pass) {
            var btn = $(this);
            btn.prop('disabled', true);
            let formData = new FormData();
            formData.append('login', $('#update-password-modal-login').val());
            formData.append('main_password', main_pass);
            update_user_info(formData, btn);
        }
    });
    $('#update-invest-password').on('input', function () {
        var password = $(this).val();
        checkPassword(password,'invest','submit-investor-password');

    });
    $('#update-main-password').on('input', function () {
        var password = $(this).val();
        checkPassword(password,'main','submit-password');

    });
    $('body').on('click', '#submit-investor-password', function () {
        var invest_pass = $('#update-invest-password').val();
        if (invest_pass) {
            var btn = $(this);
            btn.prop('disabled', true);
            let formData = new FormData();
            formData.append('login', $('#update-investor-password-modal-login').val());
            formData.append('invest_password', invest_pass);
            update_user_info(formData, btn);
        }
    });

    function update_user_info(formData, btn) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // debugger;
        $.ajax({
            url: "{{ route('user.forex.update.account') }}",
            type: 'POST', data: formData, processData: false, contentType: false,
            success: function (res) {
                console.log(res,'res')
                if (res.success) {
                     tNotify('success', res.success);
                    // NioApp.Toast(res.success, 'success');
                    if (res.reload) {
                        setTimeout(function () {
                            location.reload();
                        }, 900);
                    }
                } else if (res.error) {
                    tNotify('warning', res.error);
                    // NioApp.Toast(res.error, 'warning');
                    // setTimeout(function () {
                    //     location.reload();
                    // }, 900);
                } else if (res.errors) {
                    tNotify('warning', res.message);
                    // NioApp.Form.errors(res, true);
                    btn.prop('disabled', false);
                }
            },
            error: function (data) {
                // console.log(data,'data')
                btn.prop('disabled', false);
                tNotify('warning', data.responseJSON.message);
                // NioApp.Form.errors(res, true);
                btn.prop('disabled', false);
                {{--tNotify('warning', "{{ __('Sorry, something went wrong! Please reload the page and try again.') }}");--}}
{{--                NioApp.Toast("{{ __('Sorry, something went wrong! Please reload the page and try again.') }}", 'warning');--}}
            }
        })
    }
</script>
