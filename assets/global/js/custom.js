function validateNumber(e) {
    "use strict";
    const pattern = /^[0-9]$/;
    return pattern.test(e.key)
}

function validateDouble($value) {
    "use strict";
    return $value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');
}

function isWhatPercentOf(numA, numB) {
    "use strict";
    return (numA / numB) * 100;
}

function calPercentage(num, percentage) {
    "use strict";
    const result = num * (percentage / 100);
    return parseFloat(result.toFixed(2));
}

function imagePreview() {
    "use strict";
    $('input[type="file"]').each(function () {
        // Refs
        var $file = $(this),
            $label = $file.next('label'),
            $labelText = $label.find('span'),
            labelDefault = $labelText.text();

        // When a new file is selected
        $file.on('change', function (event) {
            var fileName = $file.val().split('\\').pop(),
                tmppath = URL.createObjectURL(event.target.files[0]);
            //Check successfully selection
            if (fileName) {
                $label
                    .addClass('file-ok')
                    .css('background-image', 'url(' + tmppath + ')');
                $labelText.text(fileName);
            } else {
                $label.removeClass('file-ok');
                $labelText.text(labelDefault);
            }
        });

        $('.remove-img').removeAttr('hidden');
    });
}

function imagePreviewAdd(title) {
    "use strict";
    var base_url = window.location.origin;

    var previewImage = $("#image-old");
    previewImage.css({
        'background-image': 'url(' + base_url + '/assets/' + title + ')'
    });
    previewImage.addClass("file-ok");
}


function tNotify(type, message) {
    new Notify({
        status: type,
        title: type,
        text: message,
        effect: 'slide',
        speed: 300,
        customClass: '',
        customIcon: getIcon(type),
        showIcon: true,
        showCloseButton: true,
        autoclose: true,
        autotimeout: 9000,
        gap: 20,
        distance: 20,
        type: 1,
        position: 'right top',
        customWrapper: '',
    })

}

function imageRemoveWithRoute(targetCode=null,route = null,token) {
    $('.remove-img').on('click', function () {

        var target = $(this).data('des');
        $(this).attr('hidden', true);

        $("input[name='" + target + "']").val(null);
        if (null != route){
            $.ajax({
                type: "POST",
                url: route,
                data: {
                    _token: token,
                    target_code: targetCode,
                    field_name: target,
                    type:'img-remove'
                },
                success: function () {
                    imagePreviewRemove('Update Image');
                    tNotify('success', 'Image Removed Successfully');
                }
            });
        }
        imagePreviewRemove(target,'Update Image');
    });
}


function imagePreviewRemove(target,title) {

    var image = $("#"+target)
    image.removeAttr("style");
    image.removeClass("file-ok");
    image.children("span").html(title);

}
function getIcon(type) {
    let icon;
    switch (type) {
        case 'success':
            icon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check"><polyline points="20 6 9 17 4 12"/></svg>';
            break;
        case 'info':
            icon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-megaphone"><path d="m3 11 18-5v12L3 14v-3z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>';
            break;
        case 'warning':
            icon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-alert-triangle"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" x2="12" y1="9" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/></svg>';
            break;
        case 'error':
            icon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-server-crash"><path d="M6 10H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2h-2"/><path d="M6 14H4a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2h-2"/><path d="M6 6h.01"/><path d="M6 18h.01"/><path d="m13 6-4 6h6l-4 6"/></svg>';
            break;
        default:
            icon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check"><polyline points="20 6 9 17 4 12"/></svg>';
            break;
    }
    return icon;
}

function sumArrayValues(arr) {
    let sum = 0;
    for (let i = 0; i < arr.length; i++) {
        sum += arr[i];
    }
    return sum;
}

function copyRef(idName) {
    /* Get the text field */
    var copyApi = document.getElementById(idName);
    /* Select the text field */
    copyApi.select();
    copyApi.setSelectionRange(0, 999999999); /* For mobile devices */
    /* Copy the text inside the text field */
    document.execCommand('copy');
    $('#copy').text($('#copied').val())
}

function checkPassword(password,type,button) {
    var isValid = true;
    // Check for minimum length
    if (lengthCheck = password.length >= 8) {
        $('#length-check-'+type).css('color', 'green');
    }else{
        $('#length-check-'+type).css('color', '#ef476f');
        isValid = false;
    }


    // Check for at least one uppercase letter
    if (lettersCheck = /[A-Z]/.test(password) && /[a-z]/.test(password)) {
        $('#letters-check-'+type).css('color', 'green');

    }else{
        $('#letters-check-'+type).css('color', '#ef476f');
        isValid = false;
    }

    // Check for at least one number
    if (numberCheck = /\d/.test(password)) {
        $('#number-check-'+type).css('color', 'green');
    }else{
        $('#number-check-'+type).css('color', '#ef476f');
        isValid = false;
    }

    // Check for at least one special character
    if (specialCheck = /[!@#$%^&*(),-.?":{}|<>]/.test(password)) {
        $('#special-check-'+type).css('color', 'green');
    }else{
        $('#special-check-'+type).css('color', '#ef476f');
        isValid = false;
    }
    console.log(isValid,'isValid');
    if (isValid) {
        $('#'+button).prop('disabled', false);
    } else {
        $('#'+button).prop('disabled', true);
    }

}

imagePreview();

function submit_form(formData,btn,url,appendId=null,modalId=null,datatable=null){
    // debugger;
    $.ajax({
        url : url,
        type: 'POST', data: formData, processData: false, contentType: false,
        beforeSend: function () {
            $("#sppiner-loader").show();
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success : function(res) {
            console.log(res);
            if(res.success){
                tNotify('success', res.success);
                if(res.reload) {
                    setTimeout(function(){ location.reload(); }, 900);
                }
                if(res.redirect) {
                    setTimeout(function(){ window.location.replace(res.redirect); }, 900);
                }
                if (res.modal) {
                    $('#'+modalId).modal('toggle');
                    // NioApp.Form.errors(res, true);
                    // btn.prop('disabled', false);
                }
            }
            else if(res.append){
                $('#'+appendId).html(res.append);
                // NioApp.Toast(res.error, 'warning');
                // setTimeout(function(){ location.reload(); }, 900);

                $(".form-select").select2({
                    matcher: matchCustom,
                    templateResult: formatState,
                    templateSelection: formatState
                });

                function stringMatch(term, candidate) {
                    return candidate && candidate.toLowerCase().indexOf(term.toLowerCase()) >= 0;
                }
                function matchCustom(params, data) {
                    // If there are no search terms, return all of the data
                    if ($.trim(params.term) === '') {
                        return data;
                    }
                    // Do not display the item if there is no 'text' property
                    if (typeof data.text === 'undefined') {
                        return null;
                    }
                    // Match text of option
                    if (stringMatch(params.term, data.text)) {
                        return data;
                    }
                    // Match attribute "data-foo" of option
                    if (stringMatch(params.term, $(data.element).attr('data-des'))) {
                        return data;
                    }
                    // Return `null` if the term should not be displayed
                    return null;
                }
                function formatState(opt) {
                    if (!opt.id) {
                        return opt.text.toUpperCase();
                    }

                    var optimage = $(opt.element).attr('data-image');
                    var optdes = $(opt.element).attr('data-des');
                    // console.log(optimage)
                    if (!optimage) {
                        return opt.text.toUpperCase();
                    } else {
                        var $opt = $(
                            '<div class="coin-item coin-btc"><div class="coin-icon"><img src="' + optimage + '" class="mr-2" width="40px" /></div><div class="coin-info"><span class="coin-name">' + opt.text.toUpperCase() + '</span><ul class="kanban-item-meta-list">' + optdes + '</ul></div></div>'
                        );
                        return $opt;
                    }
                }
            }
            else if(res.error){
                // NioApp.Toast(res.error, 'warning');
                // tNotify('warning', res.message);
                tNotify('warning', res.error);
                // setTimeout(function(){ location.reload(); }, 900);
            }
            else if (res.errors) {
                NioApp.Form.errors(res, true);
                tNotify('warning', res.message);
                btn.prop('disabled', false);
            }
        },
        complete: function (data) {
            // Hide image container
            $("#sppiner-loader").hide();
        },
        error: function(data) {
            btn.prop('disabled', false);
            // console.log(data.responseJSON.message,'data.message')
            tNotify('warning', data.responseJSON.message);
            // var msg = __("Sorry something went wrong! Please reload the page and try again.")
            // tNotify('warning', msg);
            // NioApp.Toast("Sorry something went wrong! Please reload the page and try again.", 'warning');
        }
    })
}
