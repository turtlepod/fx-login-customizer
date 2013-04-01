<?php
/**
 * Login Customizer Settings Meta Box
 * This metabox is added in f(x) - Utility Settings page.
 * @package fx_Login_Customizer
 * @subpackage includes
 * @since 0.1.0
 */

/* Register settings field */
add_action( 'admin_init' , 'fx_login_customizer_register_settings' );

/**
 * Register Field in General Settings Page
 * @since 0.1.0
 */
function fx_login_customizer_register_settings() {

	/* Get settings config */
	$config = fx_login_customizer_settings_config();

	/* Check capability */
	if ( current_user_can( $config['capability'] ) ) {

		/* Register settings */
		register_setting( $config['option_group'], $config['option_name'], 'fx_login_customizer_sanitize' );
	}
}


/**
 * Sanitize and validate settings input
 * @since 0.1.0
 */
function fx_login_customizer_sanitize( $settings ){
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

/**
 * Get Option helper function
 * @since 0.1.0
 */
function fx_login_customizer_get_option( $option = '' ) {

	/* Bail early if no option defined */
	if ( !$option ) return false;

	/* Get settings config */
	$config = fx_login_customizer_settings_config();

	/* Defaults */
	$default = fx_login_customizer_defaults();

	/* Get database and sanitize it */
	$get_option = get_option( $config['option_name'], $default );

	/* Get default if data not yet set */
	if ( !isset( $get_option[ $option ] ) && isset( $default[ $option ] ) )
		return $default[ $option ];

	/* False if it's empty or not array */
	if ( !is_array( $get_option ) || empty( $get_option[$option] ) )
		return false;

	/* If it's array return it */
	if ( is_array( $get_option[$option] ) )
		return $get_option[$option];

	/* if not array sanitize it */
	else
		return wp_kses_stripslashes( $get_option[$option] );
}

/**
 * Default value.
 * @since 0.1.0
 */
function fx_login_customizer_defaults(){
	$settings = array();
	$settings['login_logo'] = '';
	$settings['login_bg'] = '';
	$settings['login_bg_color'] = '#fbfbfb';
	$settings['login_logo_home_link'] = 0;
	return $settings;
}


/* Add login customizer setting meta box */
add_action( 'add_meta_boxes', 'fx_login_customizer_add_meta_box' );

/**
 * Add meta box
 * @since 0.1.0
 */
function fx_login_customizer_add_meta_box() {

	/* Get settings config */
	$config = fx_login_customizer_settings_config();

	/* Check capability */
	if ( current_user_can( $config['capability'] ) ) {
 
		/* Add settings metabox */
		add_meta_box(
			'fx-login-customizer-mb',
			__( 'Login Customizer', 'fx-login-customizer' ),
			'fx_login_customizer_meta_box',
			$config['page'],
			'normal',
			'low'
		);
	}
}

/**
 * Meta box Callback
 * @since 0.1.0
 */
function fx_login_customizer_meta_box(){

	/* get config */
	$config = fx_login_customizer_settings_config();
?>

	<input type="text" class="login-input-hidden fx_login_customizer-login_logo" id="<?php echo $config['option_name']; ?>-login_logo" name="<?php echo $config['option_name']; ?>[login_logo]" value="<?php echo fx_login_customizer_get_option( 'login_logo' ); ?>">

	<input type="text" class="login-input-hidden fx_login_customizer-login_bg" id="<?php echo $config['option_name']; ?>-login_bg" name="<?php echo $config['option_name']; ?>[login_bg]" value="<?php echo fx_login_customizer_get_option( 'login_bg' ); ?>">

	<p>
		<a href="#" class="fx-login-logo-upload button button-primary"><?php _e( 'Upload Logo', 'fx-login-customizer' ); ?></a>
		<span class="fx-login-logo-remove-wrap">
			<?php if ( fx_login_customizer_get_option( 'login_logo' ) ) { ?>
			<a href="#" style="margin-left:10px;" class="fx-login-logo-remove button button-secondary"><?php _e( 'Remove Logo', 'fx-login-customizer' ); ?></a>
			<?php } ?>
		</span>

		<a href="#" class="fx-login-bg-upload button button-primary"><?php _e( 'Upload Background', 'fx-login-customizer' ); ?></a>
		<span class="fx-login-bg-remove-wrap">
			<?php if ( fx_login_customizer_get_option( 'login_bg' ) ) { ?>
			<a href="#" style="margin-left:10px;" class="fx-login-bg-remove button button-secondary"><?php _e( 'Remove Background', 'fx-login-customizer' ); ?></a>
			<?php } ?>
		</span>

		<span class="mb-login-bg">
			<input type="text" class="small-text fx_login_customizer-login_bg_color" id="<?php echo $config['option_name']; ?>-login_bg_color" name="<?php echo $config['option_name']; ?>[login_bg_color]" value="<?php echo fx_login_customizer_get_option( 'login_bg_color' ); ?>">
		</span>
		
	</p>

	<div id="login-preview-bg-wrap">
		<?php
		/* background image inline style */
		$bg_style = '';
		if ( fx_login_customizer_get_option( 'login_bg' ) ){
			$bg_style = ' style="background-image: url(' . esc_url( fx_login_customizer_get_option( 'login_bg' ) ) . ');"';
		}
		?>
		<div id="login-preview"<?php echo $bg_style; ?>>
			<div class="login-logo-wrap">
			<?php if ( fx_login_customizer_get_option( 'login_logo' ) ) { ?>
				<img class="login-logo" src="<?php echo esc_url( fx_login_customizer_get_option( 'login_logo' ) ); ?>" alt="" />
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
		<input id="<?php echo $config['option_name']; ?>-login_logo_home_link" name="<?php echo $config['option_name']; ?>[login_logo_home_link]" type="checkbox" value="1" <?php checked( fx_login_customizer_get_option( 'login_logo_home_link' ), 1 ); ?>>
		<label for="<?php echo $config['option_name']; ?>-login_logo_home_link"><?php _e( 'Link logo to home page.', 'fx-login-customizer' ); ?></label>
	</p>

<?php }


/* Load needed scripts for settings page */
add_action( 'admin_enqueue_scripts', 'fx_login_customizer_enqueue' );

/**
 * Enqueue Scripts and Styles needed for settings page
 * @since 0.1.0
 */
function fx_login_customizer_enqueue( $hook_suffix ){

	/* Get settings config */
	$config = fx_login_customizer_settings_config();

	/* Minify suffix for debug */
	$min = '';
	$min = '.min';

	/* only load in f(x) Utility Settings page */
	if ( $hook_suffix == $config['page'] ){

		/* Background color picker */
		wp_enqueue_script( 'fx-login-bg', FX_LOGIN_CUSTOMIZER_URI . "js/login-bg-color{$min}.js", array( 'jquery', 'wp-color-picker' ), FX_LOGIN_CUSTOMIZER_VERSION, true );

		/* Enqueue WordPress media uploader script */
		wp_enqueue_media();

		/* Enqueue logo uploader script */
		wp_enqueue_script( 'fx-login-logo-upload', FX_LOGIN_CUSTOMIZER_URI . "js/login-logo-upload{$min}.js", array( 'jquery' ), FX_LOGIN_CUSTOMIZER_VERSION, true );

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
		wp_enqueue_script( 'fx-login-bg-upload', FX_LOGIN_CUSTOMIZER_URI . "js/login-bg-upload{$min}.js", array( 'jquery' ), FX_LOGIN_CUSTOMIZER_VERSION, true );

		/* Localize logo uploader script */
		wp_localize_script( 'fx-login-bg-upload', 'fx_login_bg',
			array(
				'title'  => __( 'Upload Login Background', 'site-login' ),
				'button' => __( 'Insert Login Background', 'site-login' ),
				'remove' => __( 'Remove Background', 'site-login' ),
			)
		);

		/* Enqueue CSS for Settings Page */
		wp_enqueue_style( 'fx-login-customizer', FX_LOGIN_CUSTOMIZER_URI . "css/login-customizer{$min}.css", array( 'thickbox', 'wp-color-picker' ), FX_LOGIN_CUSTOMIZER_VERSION );
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

	/* Get data */
	$logo = fx_login_customizer_get_option( 'login_logo' );
	$bg = fx_login_customizer_get_option( 'login_bg' );
	$color = fx_login_customizer_get_option( 'login_bg_color' );

	/* Open Sesame */
	$css = '';
	if ( $logo || $bg || $color != '#fbfbfb' ){
		$css .= '<style id="fx-login-customizer" type="text/css">';
		$css .= ( $logo ? 'h1 a {background-image:url(' . $logo . ') !important;}' : '' );
		$css .= ( $bg ? 'body.login{background-image:url(' . $bg . ') !important;}' : '' );
		$css .= ( $color ? 'body.login{background-color:' . $color . ' !important}' : '' );
		$css .= '</style>';
	}

	/* Close sesame */
	echo $css;
}

/* Login Logo URL */
add_filter( 'login_headerurl', 'fx_login_customizer_login_logo_url' );

/**
 * Login logo URL
 * @since 0.1.0
 */
function fx_login_customizer_login_logo_url( $url ) { 
	if ( fx_login_customizer_get_option( 'login_logo_home_link' ) )
		return home_url();
	else
		return $url;
}
