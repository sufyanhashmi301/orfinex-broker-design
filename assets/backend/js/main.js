// iDevs Admin
(function ($) {

    'use strict';

    // Counter For Dashboard Card
    $('.count').counterUp({
        delay: 10,
        time: 2000
    });


    // Image Preview
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
                $('.'+ $file.attr('name')).removeAttr('hidden');
            } else {
                $label.removeClass('file-ok');
                $labelText.text(labelDefault);
            }
        });

    });


    // Custom Toaster
    $('.toast__close').on('click', function (e) {
        e.preventDefault();
        var parent = $(this).parent('.site-toaster');
        parent.fadeOut("slow", function () {
            $(this).remove();
        });
    });


    //Text Editor
    // $(document).ready(function () {
    //     $('.summernote').summernote({
    //         toolbar: [
    //             ['style', ['style']],
    //             ['font', ['bold', 'underline', 'clear']],
    //             ['color', ['color']],
    //             ['para', ['ul', 'ol', 'paragraph']],
    //             ['insert', ['link', 'picture',]],
    //             ['view', ['help']],
    //         ],
    //         styleTags: [
    //             'p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'
    //         ],
    //         placeholder: 'Write Your Message',
    //         tabsize: 2,
    //         height: 220,
    //     });
    //     $('.note-editable').css('font-weight', '400');
    //
    // });

})(jQuery);
