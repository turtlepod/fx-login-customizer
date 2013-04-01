<?php
/**
 * Plugin Name: f(x) Login Customizer
 * Plugin URI: http://shellcreeper.com/
 * Description: WordPress login customizer.
 * Version: 0.1.0
 * Author: David Chandra Purnama
 * Author URI: http://shellcreeper.com/
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume 
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without 
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package fx_Login_Customizer
 * @version 0.1.0
 * @author David Chandra Purnama <david@shellcreeper.com>
 * @copyright Copyright (c) 2013, David Chandra Purnama
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */


/* Constants
------------------------------------------ */

/* Set the constant path to the plugin directory URI. */
define( 'FX_LOGIN_CUSTOMIZER_VERSION', '0.1.0' );

/* Set the constant path to the plugin path. */
define( 'FX_LOGIN_CUSTOMIZER_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );

/* Set the constant path to the plugin directory URI. */
define( 'FX_LOGIN_CUSTOMIZER_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );


/* Settings Config
------------------------------------------ */

/**
 * Settings Config.
 * @since 0.1.0
 */
function fx_login_customizer_settings_config(){
	$config = array();
	$config['option_name'] = 'fx_login_customizer';
	$config['option_group'] = 'myfx';
	$config['page'] = 'tools_page_myfx';
	$config['capability'] = 'edit_theme_options';
	return apply_filters( 'fx_login_customizer_settings_config', $config );
}


/* Plugins Loaded
------------------------------------------ */

/* Load plugins file */
add_action( 'plugins_loaded', 'fx_login_customizer_plugins_loaded' );

/**
 * Load plugins file
 * @since 0.1.0
 */
function fx_login_customizer_plugins_loaded(){

	/* Language */
	load_plugin_textdomain( 'fx-login-customizer', false, basename( dirname( __FILE__ ) ) . '/languages' );

	/* Load functions and settings */
	require_once( FX_LOGIN_CUSTOMIZER_PATH . 'includes/fx-settings.php' );

	/* Hybrid core settings */
	add_action( 'after_setup_theme', 'fx_login_customizer_hybrid_core_settings_setup', 15 );

	/* Hook updater to init */
	add_action( 'init', 'fx_login_customizer_updater_init' );
}


/* Hybrid Core Compat
------------------------------------------ */

/**
 * Hybrid Core Settings Compat
 * @since 0.1.0
 */
function fx_login_customizer_hybrid_core_settings_setup(){

	/* only in supported theme */
	if ( current_theme_supports( 'hybrid-core-theme-settings' ) && current_theme_supports( 'fx-login-customizer' ) ){
		require_once( FX_LOGIN_CUSTOMIZER_PATH . 'includes/hybrid-core.php' );
	}
}


/* Updater
------------------------------------------ */

/**
 * Load and Activate Plugin Updater Class.
 * @since 0.1.0
 */
function fx_login_customizer_updater_init() {

	/* Load Plugin Updater */
	require_once( FX_LOGIN_CUSTOMIZER_PATH . 'includes/updater.php' );

	/* Updater Config */
	$config = array(
		'base'		=> plugin_basename( __FILE__ ), //required
		'repo_uri'	=> 'http://repo.shellcreeper.com/',
		'repo_slug'	=> 'fx-login-customizer',
	);

	/* Load Updater Class */
	new FX_Login_Customizer_Plugin_Updater( $config );
}