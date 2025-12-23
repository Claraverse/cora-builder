jQuery(document).ready(function($) {
    // Media Uploader Popup
    $(document).on('click', '.cora-media-upload-btn', function(e) {
        e.preventDefault();
        const $btn = $(this);
        const $input = $btn.prev('.cora-media-input');
        
        const frame = wp.media({
            title: 'Select or Upload Media',
            button: { text: 'Use this file' },
            multiple: false
        });

        frame.on('select', function() {
            const attachment = frame.state().get('selection').first().toJSON();
            $input.val(attachment.url);
        });

        frame.open();
    });

    // Toggle Label for True/False
    $(document).on('change', '.cora-switch input[type="checkbox"]', function() {
        const $label = $(this).next('.switch-label');
        if($(this).is(':checked')) {
            $label.text('Enabled');
        } else {
            $label.text('Disabled');
        }
    });
});