<?php
/**
 * Functions
**/

/**
 * Hex Color Sanitization Helper Function
 * This function added to sanitize color in front end, 
 * because wp sanitize_hex_color() only available in customize screen.
 * This is duplicate for sanitize_hex_color() wp-includes/class-wp-customize-manager.php
 * @since 1.0.1
 */
function fx_login_customizer_sanitize_hex_color( $color ){

	/* return empty if empty. */
	if ( '' === $color ){
		return '';
	}

	/* 3 or 6 hex digits, or the empty string. */
	if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ){
		return $color;
	}

	return null;
}


/**
 * Hex Color Sanitization Helper Function
 * This function added to sanitize color in front end,
 * because wp sanitize_hex_color_no_hash() only available in customize screen.
 * This is duplicate for sanitize_hex_color_no_hash() wp-includes/class-wp-customize-manager.php
 * @since 1.0.1
 */
function fx_login_customizer_sanitize_hex_color_no_hash( $color ){

	/* remove hashtag. */
	$color = ltrim( $color, '#' );

	/* return empty if empty. */
	if ( '' === $color ){
		return '';
	}

	/* check using sanitize hex colot. */
	return tamatebako_sanitize_hex_color( '#' . $color ) ? $color : null;
}

/* === FILE === */

/**
 * Sanitize File Type
 * Check if data is an certain file type.
 * example in checking image file:
 * $image_url = tamatebako_sanitize_filetype( $url, 'image' );
 * will return empty if not valid.
 * file type: "application", "audio", "video", "image", "text", etc.
 * @link https://en.wikipedia.org/wiki/Internet_media_type#List_of_common_media_types
 */
function fx_login_customizer_sanitize_file_type( $input, $type = 'image' ){

	/* check file type of input. */
	$filetype = wp_check_filetype( $input );
	$mime_type = $filetype['type'];

	/* if only one type defined. */
	if( is_string( $type ) ){

		/* only file allowed */
		if ( strpos( $mime_type, $type ) !== false ){
			return $input;
		}
	}

	/* multiple file type in array. */
	elseif( is_array( $type ) ){

		/* loop foreach file type allowed. */
		foreach( $type as $single_type ){
			if ( strpos( $mime_type, $single_type ) !== false ){
				return $input;
			}
		}
	}
	return '';
}