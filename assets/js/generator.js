(function ($) {
    $('#generate_zip_archive').after('<div class="loading"></div>');
    $('#generate_zip_archive').after('<div class="message"></div>');
    /**
     * AJAX triggered click event
     */
    $('#generate_zip_archive').click(function (e) {
        e.preventDefault();

        // Turn off form checks because we dont care if the form
        // is actually submitted in a postback type manner
        $(window).off('beforeunload');

        // put this object into easily accessed variable
        let workbutton = $(this);


        // Build array
        let data = {
            'action': 'generate_zip_archive',
            'plugin_label': $('[data-name="plugin_label"] input').val(),
            'plugin_namespace': $('[data-name="plugin_namespace"] input').val(),
            'plugin_file_slug': $('[data-name="plugin_file_slug"] input').val(),
            'plugin_version': $('[data-name="plugin_version"] input').val(),
            'plugin_url': $('[data-name="plugin_url"] input').val(),
            'plugin_description': $('[data-name="plugin_description"] textarea').val(),
            'plugin_author': $('[data-name="plugin_author"] input').val(),
            'plugin_author_url': $('[data-name="plugin_author_url"] input').val(),
        };

        // Send the call
        $.ajax({
            type: "post",
            dataType: "json",
            url: ajax_object.ajax_url,
            data: data,
            before: function () {
                workbutton.attr('disabled', 'disabled');
            },
            success: function (response) {
                workbutton.removeAttr('disabled');
                if (response.type == "success") {
                    // Force the download!
                    window.location.href = response.url;
                }
            }
        });

    });
})(jQuery);

