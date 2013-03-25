/**
 * f(x) Login Logo Uploader
 */
// Open Sesame
jQuery(document).ready(function($){

	// Prepare the variable that holds our custom media manager.
	var fx_login_logo_media_frame;

	// Bind to our click event in order to open up the new media experience.
	$(document.body).on('click.myfxOpenMediaManager', '.fx-login-logo-upload', function(e){

		// Prevent the default action from occuring.
		e.preventDefault();

		// If the frame already exists, re-open it.
		if ( fx_login_logo_media_frame ) {
			fx_login_logo_media_frame.open();
			return;
		}

		// If media frame doesn't exist, create it with some options.
		fx_login_logo_media_frame = wp.media.frames.fx_login_logo_media_frame = wp.media({

			// custom class for the iframe
			className: 'media-frame myfx-media-frame',

			// frame workflows to chose from: 'select' or 'post'.
			frame: 'select',

			// only upload one file
			multiple: false,

			// title of iframe, use localize
			title: fx_login_logo.title,

			// limit to image upload
			library: {
				type: 'image'
			},

			// button text, use localize
			button: {
				text:  fx_login_logo.button
			}
		});

		// Event binding on select image.
		fx_login_logo_media_frame.on('select', function(){

			// Grab our attachment selection and construct a JSON representation of the model.
			var media_attachment = fx_login_logo_media_frame.state().get('selection').first().toJSON();

			// Send the attachment URL to our custom input field.
			$('#myfx-login_logo').val(media_attachment.url);

			// Send the attachment URL to our preview container.
			$('.login-logo-wrap').empty().append('<img class="login-logo" src="' + media_attachment.url + '">' );

			// Add remove button
			$('.fx-login-logo-remove-wrap').empty().append('<a href="#" style="margin-left:10px;" class="fx-login-logo-remove button button-secondary">' + fx_login_logo.remove + '</a>');
		});

		// Now that everything has been set, let's open up the frame.
		fx_login_logo_media_frame.open();
	});

	// On remove button click event
	$(document.body).on('click', '.fx-login-logo-remove', function(e){

		// Empty/delete input field
		$('#myfx-login_logo').val('');

		// Hide remove button
		$('.fx-login-logo-remove').delay(100).fadeOut(300);

		// Switch back to WordPress logo
		$('.login-logo-wrap').empty().append('<img class="login-logo" src="' + fx_login_logo.wplogo + '">' );
	});
});

