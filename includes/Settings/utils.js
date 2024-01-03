jQuery(document).ready(function ($) {

    //Initiate Color Picker
    $('.wp-color-picker-field').wpColorPicker();

    $('.media-preview').each(function () {
        if ($(this).attr('src')) {
        } else {
            $(this).siblings('.media-clear').addClass('hidden');
        }
    });

    $('.media-browse').on('click', function (event) {

        event.preventDefault();

        let self = $(this);

        // Create the media frame.
        let file_frame = wp.media.frames.file_frame = wp.media({
            title: self.data('uploader_title'),
            button: {
                text: self.data('uploader_button_text'),
            },
            multiple: false
        });

        file_frame.on('select', function () {
            let attachment = file_frame.state().get('selection').first().toJSON();

            self.siblings('.media-url').attr('value', attachment.url);
            self.siblings('.media-preview').attr('src', attachment.url);
            self.siblings('.media-clear').removeClass('hidden');
        });

        // Finally, open the modal
        file_frame.open();
    });

    $('.media-clear').on('click', function (event) {

        event.preventDefault();

        let self = $(this);

        self.siblings('.media-url').attr('value', '');
        self.siblings('.media-preview').attr('src', '');
        self.addClass('hidden');
    });
});