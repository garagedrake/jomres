<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @link	   https://www.castor.net
 * @since	  9.9.19
 *
 * @package Castor\Core\CMS_Specific
 */



// If uninstall not called from WordPress, then exit.
if (! defined('ABSPATH') || ! defined('WP_UNINSTALL_PLUGIN')) {
	die;
}

//check if user is admin
if (! current_user_can('activate_plugins')) {
	return;
}

//check admin referrer. This breaks ajax plugin uninstall
//check_admin_referer( 'bulk-plugins' );

//uninstall Castor
if (! defined('_CASTOR_INITCHECK')) {
	define('_CASTOR_INITCHECK', 1);
}

if (file_exists(ABSPATH . 'castor_root.php')) {
	require_once ABSPATH . 'castor_root.php';
} else {
	if (!defined('CASTOR_ROOT_DIRECTORY')) {
		define('CASTOR_ROOT_DIRECTORY', 'castor');
	}
}

// Important: Check if the file is the one
// that was registered during the uninstall hook.
if (WP_UNINSTALL_PLUGIN != 'castor/castor.php') {
	return;
}

if (! file_exists(ABSPATH . CASTOR_ROOT_DIRECTORY . '/integration.php')) {
	return;
}

if (! file_exists(ABSPATH . CASTOR_ROOT_DIRECTORY . '/libraries/castor/classes/castor_install.class.php')) {
	return;
}

try {
	require_once ABSPATH . CASTOR_ROOT_DIRECTORY . '/libraries/castor/classes/castor_install.class.php';

	$castor_install = new castor_install('uninstall');

	delete_option('castor_wp_plugin_version');
} catch (Exception $e) {
	die('Something went wrong when uninstalling Castor.');
	
	return false;
}

