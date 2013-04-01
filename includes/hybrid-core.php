<?php
/**
 * Hybrid Core Settings Intergration
 * Add settings in Hybrid Core Theme Settings if theme support "fx-login-customizer".
 *
 * @package fx_Login_Customizer
 * @subpackage includes
 * @since 0.1.0
 */


/* Add setttings in Hybrid Core Settings */
add_filter( 'fx_login_customizer_settings_config', 'fx_login_customizer_hybrid_core_settings' );


/**
 * Filter Settings Config to add in Hybrid Core Theme Settings
 * @since 0.1.0
 */
function fx_login_customizer_hybrid_core_settings( $config ){
	$config['option_group'] = sanitize_key( get_template() ) . '_theme_settings';
	$config['page'] = 'appearance_page_theme-settings';
	return $config;
}
