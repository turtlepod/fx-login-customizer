<?php
/**
 * Login Customizer Settings Meta Box
 * This metabox is added in f(x) - Utility Settings page.
 * @package fx_Login_Customizer
 * @subpackage includes
 * @since 0.1.0
 */

/* Add login customizer setting meta box */
add_action( 'add_meta_boxes', 'fx_login_customizer_add_meta_box' );

/**
 * Add meta box
 * @since 0.1.0
 */
function fx_login_customizer_add_meta_box() {

	add_meta_box(
		'fx-login-customizer-mb',
		__( 'Login Customizer', 'fx-login-customizer' ),
		'fx_login_customizer_meta_box',
		myfx_page(),
		'normal',
		'low'
	);
}

/**
 * Meta box Callback
 * @since 0.1.0
 */
function fx_login_customizer_meta_box(){ ?>

	<input type="text" class="login-input-hidden large-text" id="<?php echo myfx_id( 'login_logo' ); ?>" name="<?php echo myfx_name( 'login_logo' ); ?>" value="<?php echo myfx_get_option( 'login_logo' ); ?>" />
	<input type="text" class="login-input-hidden large-text" id="<?php echo myfx_id( 'login_bg' ); ?>" name="<?php echo myfx_name( 'login_bg' ); ?>" value="<?php echo myfx_get_option( 'login_bg' ); ?>" />

	<p>
		<a href="#" class="fx-login-logo-upload button button-primary"><?php _e( 'Upload Logo', 'fx-login-customizer' ); ?></a>
		<span class="fx-login-logo-remove-wrap">
			<?php if ( myfx_get_option( 'login_logo' ) ) { ?>
			<a href="#" style="margin-left:10px;" class="fx-login-logo-remove button button-secondary"><?php _e( 'Remove Logo', 'fx-login-customizer' ); ?></a>
			<?php } ?>
		</span>

		<a href="#" class="fx-login-bg-upload button button-primary"><?php _e( 'Upload Background', 'fx-login-customizer' ); ?></a>
		<span class="fx-login-bg-remove-wrap">
			<?php if ( myfx_get_option( 'login_bg' ) ) { ?>
			<a href="#" style="margin-left:10px;" class="fx-login-bg-remove button button-secondary"><?php _e( 'Remove Background', 'fx-login-customizer' ); ?></a>
			<?php } ?>
		</span>

		<span class="mb-login-bg">
			<input type="text" class="small-text" id="<?php echo myfx_id( 'login_bg_color' ); ?>" name="<?php echo myfx_name( 'login_bg_color' ); ?>" value="<?php echo myfx_get_option( 'login_bg_color' ); ?>" />
		</span>
		
	</p>

	<div id="login-preview-bg-wrap">

		<div id="login-preview" style="background-image:url(<?php echo myfx_get_option( 'login_bg' ); ?>)">
			<div class="login-logo-wrap">
			<?php if ( myfx_get_option( 'login_logo' ) ) { ?>
				<img class="login-logo" src="<?php echo esc_url( myfx_get_option( 'login_logo' ) ); ?>" alt="" />
			<?php }else{ ?>
				<img class="login-logo" src="<?php echo FX_LOGIN_CUSTOMIZER_URI . 'images/wordpress-logo.png' ?>" alt="" />
			<?php } ?>
			</div>
			<div class="login-form-wrap">
				<img src="<?php echo FX_LOGIN_CUSTOMIZER_URI . 'images/login-form.png' ?>" class="login-form">
			</div>
		</div>

	</div>
	<p class="howto"><?php _e( 'Login logo size is 274 &times; 63, background will be tiled.', 'fx-login-customizer' ); ?></p>
	<p>
		<input id="<?php echo myfx_id( 'login_logo_home_link' ); ?>" name="<?php echo myfx_name( 'login_logo_home_link' ); ?>" type="checkbox" value="1" <?php checked( myfx_get_option( 'login_logo_home_link' ), 1 ); ?>>
		<label for="<?php echo myfx_id( 'login_logo_home_link' ); ?>"><?php _e( 'Link logo to home page.', 'fx-login-customizer' ); ?></label>
	</p>

<?php }


/* Filter settings sanitize function */
add_filter( 'myfx_sanitize', 'fx_login_customizer_settings_sanitize' );

/**
 * Sanitize and validate settings input
 * @since 0.1.0
 */
function fx_login_customizer_settings_sanitize( $settings ){
	$settings['login_logo'] = esc_url( fx_login_customizer_validate_image( $settings['login_logo'] ) );
	$settings['login_bg'] = esc_url( fx_login_customizer_validate_image( $settings['login_bg'] ) );
	$settings['login_bg_color'] = fx_login_customizer_validate_color( $settings['login_bg_color'] );
	$settings['login_logo_home_link'] = ( isset( $settings['login_logo_home_link'] ) ? 1 : 0 );
	return $settings;
}

/**
 * Validate color
 * @since 0.1.0
 */
function fx_login_customizer_validate_color( $input ){

	/* hex */
	$hex = trim( $input );

	/* Strip recognized prefixes. */
	if ( 0 === strpos( $hex, '#' ) )
		$hex = substr( $hex, 1 );
	elseif ( 0 === strpos( $hex, '%23' ) )
		$hex = substr( $hex, 3 );

	/* Regex match. */
	if ( 0 === preg_match( '/^[0-9a-fA-F]{6}$/', $hex ) )
		$output = '';
	else
		$output = trim( $input );

	return $output;
}

/**
 * Validate image file
 * @since 0.1.0
 */
function fx_login_customizer_validate_image( $input ){

	/* check file type */
	$filetype = wp_check_filetype( $input );
	$mime_type = $filetype['type'];

	/* only images allowed */
	if ( strpos( $mime_type, 'image' ) !== false )
		$output = $input;
	else
		$output = '';
	return $output;
}

/* Add default value to settings */
add_filter( 'myfx_defaults', 'fx_login_customizer_settings_defaults' );

/**
 * Default value for favicon settings
 * @since 0.1.0
 */
function fx_login_customizer_settings_defaults( $settings ){
	$settings['login_logo'] = '';
	$settings['login_bg'] = '';
	$settings['login_bg_color'] = '#fbfbfb';
	$settings['login_logo_home_link'] = 0;
	return $settings;
}

/* Load needed scripts for settings page */
add_action( 'admin_enqueue_scripts', 'fx_login_customizer_enqueue' );

/**
 * Enqueue Scripts and Styles needed for settings page
 * @since 0.1.0
 */
function fx_login_customizer_enqueue( $hook_suffix ){

	/* only load in f(x) Utility Settings page */
	if ( $hook_suffix == myfx_page() ){

		/* Background color picker */
		wp_enqueue_script( 'fx-login-bg', FX_LOGIN_CUSTOMIZER_URI . 'js/login-bg-color.min.js', array( 'jquery', 'wp-color-picker' ), FX_LOGIN_CUSTOMIZER_VERSION, true );

		/* Enqueue WordPress media uploader script */
		wp_enqueue_media();

		/* Enqueue logo uploader script */
		wp_enqueue_script( 'fx-login-logo-upload', FX_LOGIN_CUSTOMIZER_URI . 'js/login-logo-upload.min.js', array( 'jquery' ), FX_LOGIN_CUSTOMIZER_VERSION, true );

		/* Localize logo uploader script */
		wp_localize_script( 'fx-login-logo-upload', 'fx_login_logo',
			array(
				'title'  => __( 'Upload Login Logo', 'site-login' ),
				'button' => __( 'Insert Login Logo', 'site-login' ),
				'remove' => __( 'Remove Logo', 'site-login' ),
				'wplogo' => FX_LOGIN_CUSTOMIZER_URI . 'images/wordpress-logo.png',
			)
		);

		/* Enqueue background uploader script */
		wp_enqueue_script( 'fx-login-bg-upload', FX_LOGIN_CUSTOMIZER_URI . 'js/login-bg-upload.min.js', array( 'jquery' ), FX_LOGIN_CUSTOMIZER_VERSION, true );

		/* Localize logo uploader script */
		wp_localize_script( 'fx-login-bg-upload', 'fx_login_bg',
			array(
				'title'  => __( 'Upload Login Background', 'site-login' ),
				'button' => __( 'Insert Login Background', 'site-login' ),
				'remove' => __( 'Remove Background', 'site-login' ),
			)
		);

		/* Enqueue CSS for Settings Page */
		wp_enqueue_style( 'fx-login-customizer', FX_LOGIN_CUSTOMIZER_URI . 'css/login-customizer.min.css', array( 'thickbox', 'wp-color-picker' ), FX_LOGIN_CUSTOMIZER_VERSION );
	}
}

/* Functions
================================ */

/* Login CSS */
add_action( 'login_head', 'fx_login_customizer_print_style' );

/**
 * Login CSS
 * @since 0.1.0
 */
function fx_login_customizer_print_style() {
	$logo = myfx_get_option( 'login_logo' );
	$bg = myfx_get_option( 'login_bg' );
	$color = myfx_get_option( 'login_bg_color' );

	$css = '';
	if ( $logo || $bg || $color != '#fbfbfb' ){
		$css .= '<style id="fx-login-customizer" type="text/css">';
		$css .= ( $logo ? 'h1 a {background-image:url(' . $logo . ') !important;}' : '' );
		$css .= ( $bg ? 'body.login{background-image:url(' . $bg . ') !important;}' : '' );
		$css .= ( $color ? 'body.login{background-color:' . $color . ' !important}' : '' );
		$css .= '</style>';
	}
	echo $css;
}

/* Login Logo URL */
add_filter( 'login_headerurl', 'fx_login_customizer_login_logo_url' );

/**
 * Login logo URL
 * @since 0.1.0
 */
function fx_login_customizer_login_logo_url( $url ) { 
	if ( myfx_get_option( 'login_logo_home_link' ) )
		return home_url();
	else
		return $url;
}
