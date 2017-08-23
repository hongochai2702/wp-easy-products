jQuery(document).ready(function ($) {
    'use strict';

    /**
     * Manually upload file
     * Import from uploaded file
     */
    $('.js-demo-import-data').on('click', function (e) {
        e.preventDefault();
        // Reset response div content.
        $('.js-demo-ajax-response').empty();
        $('.list-file-upload').fadeOut(500, function () {
            $('.js-importer-progress').show();
            $('.js-demo-import-data').prop('disabled', true);
        });

        var $this = $(this);
        var $theme = $this.closest('form');

        // Prepare data from the AJAX call
        var data = new FormData(),
            $file_content = $('#demo-content-file-upload'),
            $file_widget = $('#demo-widget-file-upload'),
            $file_customize = $('#demo-customizer-file-upload'),
            $form_import = $('#tpfw_importer-upload-form');

        data.append('action', $form_import.find('input[name="action"]').val());
        data.append('security', $form_import.find('input[name="security"]').val());

        if ($file_content.length) {
            data.append('content_file', $file_content[0].files[0]);
        }
        if ($file_widget.length) {
            data.append('widget_file', $file_widget[0].files[0]);
        }
        if ($file_customize.length) {
            data.append('customizer_file', $file_customize[0].files[0]);
        }

        prepareFile(data, $theme);

    });

    /**
     * One-click Import data
     * Grid Layout import button click
     */
    $('.js-button-import').on('click', function (e) {
        e.preventDefault();
        var $this = $(this);
        var $theme = $this.closest('.theme');
        var theme_id = $theme.attr('tabindex');

        gridLayoutImport(theme_id, $theme);
    });


    /**
     * Prepare grid layout import data and execute the AJAX call
     *
     * @param int theme_id The selected import ID
     * @param obj $theme The jQuery selected item container object.
     */
    function gridLayoutImport(theme_id, $theme) {

        // Reset response div content
        $('.js-demo-ajax-response').empty();
        $('.js-demo-success .notice-success p').unwrap();
        $('.js-demo-success').hide();

        //Un active and add disabled for other theme
        $('.theme').removeClass('active').addClass('disabled').find('.theme-name span').hide();

        //Add current class
        $theme.addClass('current').removeClass('disabled');
        $theme.find('.js-importer-progress').show();

        // Prepare data for the AJAX call
        var data = new FormData();
        data.append('action', 'tpfw_importer');
        data.append('security', tpfw_importer.ajax_nonce);
        data.append('selected', theme_id);

        // Prepare file to import. Manual demo files upload
        prepareFile(data, $theme);
    }

    function prepareFile(data, $theme) {

        $.ajax({
            method: 'POST',
            url: tpfw_importer.ajax_url,
            data: data,
            contentType: false,
            processData: false,
            beforeSend: function () {

            }
        }).done(function (response) {
            if (response.success && response.success !== "undefined") {
                import_demo(response.data, $theme);
            } else {
                setTimeout(function () {
                    $('.js-importer-progress').hide();
                    $('.js-demo-ajax-response').append('<div class="notice notice-info"><p>' + response + '</p></div>');
                }, 1000);
            }
        }).fail(function (error) {
            $('.js-demo-ajax-response').append('<div class="notice notice-error is-dismissible"><p>Error: ' + error.statusText + ' (' + error.status + ')' + '</p></div>');
        });
    }

    function import_demo(data, $theme) {

        // Get checkbox placeholder image value
        var placeholder_img = $theme.find('.placeholder-image input[type="checkbox"]').is(':checked');

        if (typeof (EventSource) !== "undefined") {

            data.security = tpfw_importer.ajax_nonce;
            data.action = 'tpfw_import_demo';
            data.placeholder_img = placeholder_img;

            var url = ajaxurl + '?' + $.param(data);

            var eImport = new EventSource(url);

            var output = [];

            eImport.onmessage = function (e) {

                var data = JSON.parse(e.data),
                    $progress = $theme.find('.js-importer-progress');

                if (e.lastEventId == 'installing') {

                    $progress.find('.title').text(data.message);
                    $progress.find('.progress-bar').css('width', data.progress + '%');

                } else if (e.lastEventId == 'print') {

                    if (typeof data.message !== 'undefined') {
                        output.push(data.message);
                    }

                } else if (e.lastEventId == 'error') {

                    if (typeof data.message !== 'undefined') {
                        $('.js-demo-ajax-response').append('<div class="notice notice-error is-dismissible"><p>' + data.message + '</p></div>');
                    }

                } else if (e.lastEventId == 'done') {

                    setTimeout(function () {

                        $progress.hide();
                        $theme.removeClass('current').addClass('active');
                        $theme.find('.theme-name span').css('display', 'inline');
                        $theme.find('.js-button-import').text(tpfw_importer.reimport_text);
                        $('.theme').removeClass('disabled');
                        if (output.length) {
                            $('.js-demo-ajax-response').append('<div class="notice notice-info is-dismissible"><p>' + output.join(' ') + '</p></div>');
                        }

                        //$('.js-demo-success').find('p').unwrap('.notice');
                        $('.js-demo-success').find('p').wrap('<div class="notice notice-success"></div>').closest('.js-demo-success').show();

                        setTimeout(function () {
                            $progress.find('.progress-bar').css('width', '1%');

                        }, 500);
                    }, 1000);

                }

            }

            eImport.onerror = function (e) {
                console.log('Closed');
                eImport.close();
            }
        }
    }

});