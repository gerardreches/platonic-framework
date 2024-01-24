jQuery(document).ready(function ($) {

    let mediaFrame = null;

    $('fieldset.media-field').each(function () {
        let mediaFieldset = $(this);

        if (mediaFieldset.find('.media-url').val()) {
            mediaFieldset.find('.button-clear-media').removeClass('hidden');
        }

        mediaFieldset.find('.button-clear-media').on('click', function (event) {
            event.preventDefault();

            mediaFieldset.find('.media-url').val('');
            mediaFieldset.find('.media-preview').attr('src', '');
            $(this).addClass('hidden');
        });

        mediaFieldset.find('.button-add-media').on('click', function (event) {
            event.preventDefault();

            if (!mediaFrame) {
                mediaFrame = wp.media().on('select', function () {
                    let attachment = mediaFrame.state().get('selection').first().toJSON();

                    mediaFieldset.find('.media-url').val(attachment.url);
                    mediaFieldset.find('.media-preview').removeClass('hidden');
                    mediaFieldset.find('.media-preview').attr('src', attachment.url);
                    mediaFieldset.find('.button-clear-media').removeClass('hidden');
                });
            }
            mediaFrame.open();
        });
    });
});