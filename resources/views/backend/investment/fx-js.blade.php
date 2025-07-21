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

                    tippy(".shift-Away", {
                        placement: "top",
                        animation: "shift-away"
                    });

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
        $('#account-info__login').text($(this).data('login'));
        $('#account-info-account_name').text($(this).data('account_name'));
        $('#account-info-server').text($(this).data('server'));
        $('#account-schema-title').text($(this).data('schema_title'));
        $('#account-type').text($(this).data('account_type'));
        $('#account-info-leverage').text($(this).data('leverage'));
        $('#account-info-balance').text($(this).data('balance'));
        $('#account-info-free-margin').text($(this).data('free-margin'));
        $('#account-info-equity').text($(this).data('equity'));
    });


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
            url: "{{ route('admin.forex.update.account') }}",
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

    $('body').on('click', '.copyBtn', function () {
        var targetSelector = $(this).data('target');
        var $input = $('#' + targetSelector);

        var $tempInput = $('<input>').val($input.text()).appendTo('body');
        $tempInput.select();

        document.execCommand('copy');
        $tempInput.remove();

        var $button = $(this);
        $button.addClass('text-success');

        setTimeout(function() {
            $button.removeClass('text-success');
        }, 2000);
    });

    // Change Account Type
// When clicking "Update Account Type" button, populate modal with user data
function view_forex_schema_modal(formData, btn, url) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (res) {
            console.log('Server Response:', res); // Debug the response
            if (res) {
                btn.prop('disabled', false);
                $('#view_forex_schema_form').html(res);
                $('.select2').select2();

                tippy(".shift-Away", {
                    placement: "top",
                    animation: "shift-away"
                });

            } else if (res.error) {
                // Ensure res.error is a string
                const errorMessage = Array.isArray(res.error) ? res.error.join(', ') : res.error;
                tNotify('warning', errorMessage);
                btn.prop('disabled', false);
                setTimeout(function () {
                    location.reload();
                }, 900);
            } else if (res.errors) {
                btn.prop('disabled', false);
                NioApp.Form.errors(res, true);
            }
        },
        error: function (xhr) {
            console.error('AJAX Error:', xhr.responseText);
            tNotify('warning', 'An error occurred. Please try again.');
            btn.prop('disabled', false);
        }
    });
}

$('body').on('click', '.dropdown-update-type', function () {
    var btn = $(this);
    btn.prop('disabled', true);
    
    // Set the login value into your modal's element.
    $('#update-forex-schema-modal-login').text($(this).data('login'));
    
    // Toggle the modal to show.
    $('#changeForexSchema').modal('toggle');
    
    // Prepare FormData with necessary values.
    let formData = new FormData();
    formData.append('login', $(this).data('login'));
    // Optionally, if you pass an id or other data, include it:
    formData.append('id', $(this).data('id'));
    
    // Get the URL for AJAX from data attribute.
    var url = $(this).data('action');
    console.log(url);
    view_forex_schema_modal(formData, btn, url);

});

$('body').on('click', '#submit-forex-schema', function () {
    var forexSchemaId = $('#forex-schema-id').val();
    if (forexSchemaId) {
        var btn = $(this);
        btn.prop('disabled', true);
        let formData = new FormData();
        formData.append('login', $('#update-forex-schema-modal-login-id').val());
        formData.append('forex_schema_id', forexSchemaId);
        update_user_info(formData, btn);
    }
});

$('body').on('click', '.dropdown-update-account-type', function () {
        // Set the login value in the modal
        $('#update-account-type-modal-login').text($(this).data('login'));
        $('#update-account-type-modal-login').val($(this).data('login'));

        // Set the selected value in the dropdown
        $('#account-type').val($(this).data('account_type'));
    });

$('body').on('click', '#submit-account-type', function () {
    var accountType = $('#account-type').val();
    if (accountType) {
        var btn = $(this);
        btn.prop('disabled', true);
        let formData = new FormData();
        formData.append('login', $('#update-account-type-modal-login').val());
        formData.append('account_type', accountType);  // Updated field
        update_user_info(formData, btn);
    }
});
</script>
