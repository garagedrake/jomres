<?php

/**
 * The Castor public-facing functionality.
 *
 * Defines the plugin name, version.
 *
 * @package Castor\Core\CMS_Specific
 *
 * @author	 Vince Wooll <support@castor.net>
 */


// If this file is called directly, abort.
if (! defined('WPINC')) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/castor-activator.php
 */
function activate_castor()
{
	require_once plugin_dir_path(__FILE__) . 'castor-activator.php';
	Castor_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/castor-deactivator.php
 */
function deactivate_castor()
{
	require_once plugin_dir_path(__FILE__) . 'castor-deactivator.php';
	Castor_Deactivator::deactivate();
}

/**
 * Begins Castor execution.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since	9.9.19
 */
function run_castor()
{

	$wp_castor = WP_Castor::getInstance();
	$wp_castor->run();

	if (isset($_GET['page']) && $_GET['page'] == 'castor/castor.php') {
		echo $wp_castor->get_content();
	}
}

/**
 * Check if Castor is installed and updated.
 *
 * Checks if Castor is installed and updated. If it`s not,
 * install or update it.
 *
 * @since	9.9.19
 */
function castor_is_installed_and_updated()
{

	global $wpdb;

	$castor_wp_plugin_version = get_option('castor_wp_plugin_version', '0');

	if (version_compare($castor_wp_plugin_version, CASTOR_WP_PLUGIN_VERSION, '!=')) {
		$result = $wpdb->query(
			"SELECT `table_name` FROM information_schema.tables WHERE 
			`table_schema` = '".$wpdb->dbname."'
			AND (`table_name` LIKE '".$wpdb->prefix."castor_%' 
			OR `table_name` LIKE '".$wpdb->prefix."jomcomp_%' 
			OR `table_name` LIKE '".$wpdb->prefix."castorportal_%') "
		);

		if (empty($result)) {
			if (! run_castor_installer('install')) {
				return false;
			}
		} else {
			if (! run_castor_installer('update')) {
				return false;
			}
		}
	}
	
	return true;
}

/**
 * Trigger Castor.
 *
 * Triggers Castor frontend or admin
 *
 * @since	9.9.19
 */
function trigger_castor()
{
	
	if (isset($_REQUEST[ 'jr_wp_source' ])) {
		if ($_REQUEST[ 'jr_wp_source' ] == 'admin') {
			jr_wp_trigger_admin();
		} else {
			jr_wp_trigger_frontend();
		}
	} else {
		if (strpos($_SERVER[ 'SCRIPT_FILENAME' ], 'wp-admin') > 0) {
			jr_wp_trigger_admin();
		} else {
			jr_wp_trigger_frontend();
		}
	}
}

/**
 * Trigger Castor frontend.
 *
 * Triggers Castor frontend
 *
 * @since	9.9.19
 */
function jr_wp_trigger_frontend()
{
	
	require_once ABSPATH . CASTOR_ROOT_DIRECTORY . '/castor.php';
	
	// If it's an ajax call, we need to die when Castor has done it's stuff
	if (isset($_REQUEST[ 'jrajax' ]) && (int) $_REQUEST[ 'jrajax' ] == 1) {
		die();
	}
}

/**
 * Trigger Castor admin.
 *
 * Triggers Castor admin and installs or update Castor if needed
 *
 * @since	9.9.19
 */
function jr_wp_trigger_admin()
{
	
	global $current_user;
	
	$user_roles = $current_user->roles;
	$user_role = array_shift($user_roles);
	$role = trim($user_role);
	
	if ($role == 'administrator' && castor_is_installed_and_updated()) {
		require_once ABSPATH . CASTOR_ROOT_DIRECTORY . '/admin.php';
	}
	
	// If it's an ajax call, we need to die when Castor has done it's stuff
	if (isset($_REQUEST[ 'jrajax' ]) && (int) $_REQUEST[ 'jrajax' ] == 1) {
		die();
	}
}


/**
 * Show error messages.
 *
 * Utility function to display error messages
 *
 * @since	9.9.19
 */
function castor_notice($notice)
{
	printf(
		'<div class="notice notice-error is-dismissible"><p><strong>%s</strong></p></div>',
		esc_html($notice)
	);
}
/**
 * Create a shortcode for the WP login form that can be used (ultimately) by Castor function castor_parse_modules()
 *
 * This is a fallback for the Castor messaging system's call to generate the WP login form. If no other shortcode has been configured then Castor can fall back to this form. It's not beautiful, but it is functional. Ideally the site manager would set their own shortcode for their preferred login form.
 *
 * @since	10.5.4
 */
function castor_login_form_shortcode( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'redirect' => ''
	), $atts ) );

	if (!is_user_logged_in()) {
		if($redirect) {
			$redirect_url = $redirect;
		} else {
			$redirect_url = get_permalink();
		}
		$form = wp_login_form(array('echo' => false, 'redirect' => $redirect_url ));
	}
	return $form;
	}
	add_shortcode('default_wordpress_loginform', 'castor_login_form_shortcode');


	add_filter( 'run_wptexturize', '__return_false' , PHP_INT_MAX );
