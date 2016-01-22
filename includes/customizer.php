<?php
/**
 * Customizer
 * @since 1.0.0
**/

/* Add sub menu page */
add_action( 'admin_menu', 'fx_login_customizer_admin_menu' );

/**
 * Login Customizer Admin Menu
 * @since 1.0.0
 */
function fx_login_customizer_admin_menu(){

	/* Add Sub Menu Page */
	add_submenu_page(
		'themes.php',
		__( 'Customize Login', 'fx-login-customizer' ),
		__( 'Customize Login', 'fx-login-customizer' ),
		'manage_options',
		add_query_arg(
			array(
				'url' => urlencode( wp_login_url() ),
			),
			'customize.php' 
		)
	);
}


/* Register customizer */
add_action( 'customize_register', 'fx_login_customizer_register' );

/**
 * Register Customizer Setting
 * @since 1.0.0
 */
function fx_login_customizer_register( $wp_customize ){

	/* Add Section: Logo */
	$wp_customize->add_section( 'fx_login_customizer',
		array(
			'title'         => __( 'Customize Login Page', 'fx-login-customizer' ),
		)
	);

	/* Add Setting: Logo */
	$wp_customize->add_setting( 'fx_login_customizer[logo]',
		array(
			'type'          => 'option',
			'capability'    => 'manage_options',
		)
	);

	/* Add Control: Logo */
	if ( class_exists( 'WP_Customize_Cropped_Image_Control' ) ){ /* WP 4.3 */
		$wp_customize->add_control( new WP_Customize_Cropped_Image_Control( $wp_customize, 'fx_login_logo',
			array(
				'section'       => 'fx_login_customizer',
				'settings'      => 'fx_login_customizer[logo]',
				'description'   => 'Image width is 280px. Image height is 80px.',
				'width'         => 280,
				'height'        => 80,
				'label'         => __( 'Login Logo', 'fx-login-customizer' ),
			)
		));
	}

}

/* Hide other controls/settings when modifying login logo */
add_action( 'customize_controls_print_styles', 'fx_login_customizer_hide_controls' );

/**
 * Hide other customizer control/setting when modify login page
 * @since 1.0.0
 */
function fx_login_customizer_hide_controls(){ ?>

<?php if( isset( $_GET["url"] ) && ( urlencode( wp_login_url() ) == urlencode( $_GET["url"] ) ) ){ ?>
	<style id="fx-login-customizer-hide-control" type="text/css">
		.accordion-section:not(#accordion-section-fx_login_customizer){
			display: none !important;
		}
	</style>
<?php } else { ?>
	<style id="fx-login-customizer-hide-control" type="text/css">
		#accordion-section-fx_login_customizer{
			display: none !important;
		}
	</style>
<?php }
}


/* Add CSS to Login Head */
add_action( 'login_head', 'fx_login_customizer_login_head' );

/**
 * Login Head CSS
 * @since 1.0.0
 */
function fx_login_customizer_login_head(){
	$css = '';
	$option = get_option( 'fx_login_customizer' );
	if( isset( $option['logo'] ) && $option['logo'] ){
		$image = wp_get_attachment_image_src( $option['logo'], 'full' );
		if( isset( $image[0] ) ){
			$css .= ".login h1 a{ background-image:url({$image[0]});width:280px;height:80px;background-size:280px 80px; }";
		}
	}
?>
<style id="fx-login-customizer-css" type="text/css"><?php echo $css; ?></style>
<?php
}


/* Linked title to home page */
add_filter( 'login_headerurl', 'fx_login_customizer_login_logo_url' );

/**
 * Linked Login Logo to Home Page
 * @since 1.0.0
 */
function fx_login_customizer_login_logo_url( $url ){
	return esc_url( home_url() );
}

/* Change login logo title */
add_filter( 'login_headertitle', 'fx_login_customizer_login_logo_title' );

/**
 * Linked Login Logo to Home Page
 * @since 1.0.0
 */
function fx_login_customizer_login_logo_title( $title ){
	return esc_attr( get_bloginfo( 'name' ) );
}
