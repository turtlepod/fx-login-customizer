/**
 * f(x) Login Background Uploader
 */
// Open Sesame
jQuery(document).ready(function($){

	// Prepare the variable that holds our custom media manager.
	var fx_login_bg_media_frame;

	// Bind to our click event in order to open up the new media experience.
	$(document.body).on('click', '.fx-login-bg-upload', function(loginbgevent){

		// Prevent the default action from occuring.
		loginbgevent.preventDefault();

		// If the frame already exists, re-open it.
		if ( fx_login_bg_media_frame ) {
			fx_login_bg_media_frame.open();
			return;
		}

		// If media frame doesn't exist, create it with some options.
		fx_login_bg_media_frame = wp.media.frames.fx_login_bg_media_frame = wp.media({

			// custom class for the iframe
			className: 'media-frame myfx-media-frame',

			// frame workflows to chose from: 'select' or 'post'.
			frame: 'select',

			// only upload one file
			multiple: false,

			// title of iframe, use localize
			title: fx_login_bg.title,

			// limit to image upload
			library: {
				type: 'image'
			},

			// button text, use localize
			button: {
				text:  fx_login_bg.button
			}
		});

		// Event binding on select image.
		fx_login_bg_media_frame.on('select', function(){

			// Grab our attachment selection and construct a JSON representation of the model.
			var media_attachment = fx_login_bg_media_frame.state().get('selection').first().toJSON();

			// Send the attachment URL to our custom input field.
			$('input:text.fx_login_customizer-login_bg').val(media_attachment.url);

			// Send the attachment URL to our preview container.
			$("#login-preview").css("background-image", "url(" + media_attachment.url + ")" );

			// Add remove button
			$('.fx-login-bg-remove-wrap').empty().append('<a href="#" style="margin-left:10px;" class="fx-login-bg-remove button button-secondary">' + fx_login_bg.remove + '</a>');
		});

		// Now that everything has been set, let's open up the frame.
		fx_login_bg_media_frame.open();
	});

	// On remove button click event
	$(document.body).on('click', '.fx-login-bg-remove', function(){

		// Empty/delete input field
		$('input:text.fx_login_customizer-login_bg').val('');

		// Hide remove button
		$('.fx-login-bg-remove').delay(100).fadeOut(300);

		// No background image
		$("#login-preview").css("background-image", "none" );
	});
});