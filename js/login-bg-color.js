/**
 * Login Background Color Picker
 */
jQuery(document).ready(function ($) {
	'use strict';
	// Initialize color picker
	if (typeof jQuery.wp === 'object' && typeof jQuery.wp.wpColorPicker === 'function') {
		$('input:text#myfx-login_bg_color').wpColorPicker({
			defaultColor: "#fbfbfb",
			clear: false
		});
	}

	// Get color var from color picker
	var loginbg = $('.wp-color-result').css("background-color");

	// Display color
	$("#login-preview").css("background-color", loginbg );

	// on mousemove display !
	$(".mb-login-bg .iris-picker-inner").mousemove(function () {
		var loginbg = $('.wp-color-result').css("background-color");
		$("#login-preview").css("background-color", loginbg );
	});

	// on default
	$(document.body).on('click', '.mb-login-bg .wp-picker-default', function(){
		$("#login-preview").css("background-color", "#fbfbfb" );
	});
});