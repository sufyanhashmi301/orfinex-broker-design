<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).ready(function() {
        showData();
    });
    $('.phase-btn').on('click', function () {
        console.log('phase')
        var phase = $(this).data('phase');
        updatePhaseData(phase);

        $(this).addClass('active').siblings().removeClass('active');
    });

    $('.leverage-btn').on('click', function () {
        console.log('leverage-btn')
        var leverage = $(this).data('leverage');
        updateLeverageData(leverage);
        $(this).addClass('active').siblings().removeClass('active');

        $('#sub-type').val($('.leverage-btn.active').data('leverage'));
        // $('#sub-type').val(leverage);
        showData();
    });

    $('body').on('click', '.pricing-tab-switcher', function() {
        var tabSwitcher = $(this);
        tabSwitcher.toggleClass("active");

        $('#plans-tabs .nav-link').toggleClass('active');
        $('#plans-tab-content .tab-pane').toggleClass('show active');
        var tab1 = $('#challenge-tab-pane');
        var tab2 = $('#direct-tab-pane');

        if (tab1.hasClass('show active')) {
            $('#main-type').val('challenge');
            $('#sub-type').val($('.challenge-btn.active').data('challenge'));

        } else {
            $('#main-type').val('direct');
            $('#sub-type').val($('.leverage-btn.active').data('leverage'));
        }

        showData();
    });

    $('#direct-tab').on('click', function(){
        $('.pricing-tab-switcher').addClass('active');
        // console.log('ss')
        $('#main-type').val('direct');
        $('#sub-type').val($('.leverage-btn.active').data('leverage'));

        // $('#sub-type').val($('.challenge-btn.active').data('direct'));

        showData();
    });

    $('#challenge-tab').on('click', function(){
        $('.pricing-tab-switcher').removeClass('active');
        $('#main-type').val('challenge');
        $('#sub-type').val($('.challenge-btn.active').data('challenge'));
        showData();
    });


    $('#step-challenge__2').on('click', function() {
        $('#phaseButtons').show();
        $('#sub-type').val('two_step_challenge');
        showData();

    });

    $('#step-challenge__1').on('click', function() {
        $('#phaseButtons').hide();
        $('#sub-type').val('single_step_challenge');
        showData();
    });

    // Initialize the challenge data based on the default selected challenge
    updateChallangeData("two_step_challenge");

    $('.challenge-btn').on('click', function () {
        var challenge = $(this).data('challenge');

        updateChallangeData(challenge);

        $(this).addClass('active').siblings().removeClass('active');
    });

    function updateChallangeData(challenge) {

        $('.challenge-data').each(function () {
            var dataChallange = $(this).data('challenge');

            if (dataChallange === challenge) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    };


    // Initialize the phase data based on the default selected phase
    updatePhaseData(1);

    function updatePhaseData(phase) {

        $('.phase-data').each(function () {
            var dataPhase = $(this).data('phase');
            if (dataPhase === phase) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
        $('#stage').val(phase);
        showData();
    }

    // Initialize the leverage data based on the default selected leverage
    updateLeverageData("{{\App\Enums\FundedSchemeSubTypes::LEVERAGE_1}}");

    function updateLeverageData(leverage) {
        $('.leverage-data').each(function () {
            var dataLeverage = $(this).data('leverage');

            if (dataLeverage === leverage) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    $('body').on('click', '.payment-plans-row__container', function() {
        $(this).children('.plan-details').slideToggle();
    });

    $('body').ready(function() {
        $('.planSlide').hide();
        $('.planSlide.current').show();
    });

    $('body').on('click', '#next', function() {
        $('.planSlide.current').removeClass('current').hide().next().show().addClass('current');

        if ($('.planSlide.current').hasClass('last')) {
            $('#next').css('display', 'none');
        }
        $('#prev').css('display', 'inline-flex');
    });

    $('body').on('click', '#prev', function() {
        $('.planSlide.current').removeClass('current').hide().prev().show().addClass('current');

        if ($('.planSlide.current').hasClass('first')) {
            $('#prev').css('display', 'none');
        }
        $('#next').css('display', 'inline-flex');
    });

    function submit_form_invest(formData,btn,url,appendId=null,modalId=null){
        // debugger;
        $.ajax({
            url : url,
            type: 'POST', data: formData, processData: false, contentType: false,
            beforeSend: function () {
                $("#sppiner-loader").show();
            },
            success : function(res) {
                console.log(res);
                if(res.success){
                    NioApp.Toast(res.success, 'success');
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

                    $('#'+appendId).find('.planSlide').hide();
                    $('#'+appendId).find('.planSlide.current').show();

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
                    NioApp.Toast(res.error, 'warning');
                    setTimeout(function(){ location.reload(); }, 900);
                }
                else if (res.errors) {
                    NioApp.Form.errors(res, true);
                    btn.prop('disabled', false);
                }
            },
            complete: function (data) {
                // Hide image container
                $("#sppiner-loader").hide();
            },
            error: function(data) {
                btn.prop('disabled', false);
                NioApp.Toast("Sorry something went wrong! Please reload the page and try again.", 'warning');
            }
        })
    }


    function showData() {
        $('#append-data').text('');
        var type = $('#main-type').val();
        // console.log(type,'type fu');
        var btn =null;
        let formData = new FormData();
        formData.append('type', $('#main-type').val());
        formData.append('sub_type', $('#sub-type').val());
        formData.append('stage', $('#stage').val());
        {{--var url = "{{route('user.pricing.show.data')}}";--}}
        var url =  $('#show-data-url').val();
        submit_form_invest(formData, btn, url, 'append-data');
    }


</script>
