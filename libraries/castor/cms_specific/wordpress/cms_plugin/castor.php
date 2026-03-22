<?php

	/**
	 * The plugin bootstrap file
	 *
	 * This file is read by WordPress to generate the plugin information in the plugin
	 * admin area. This file also includes all of the dependencies used by the plugin,
	 * registers the activation and deactivation functions, and defines a function
	 * that starts the plugin.
	 *
	 * @link			  https://www.castor.net
	 * @since			 9.9.19
	 * @package Castor\Core\CMS_Specific
	 *
	 * @wordpress-plugin
	 * Plugin Name:	   Castor
	 * Plugin URI:		https://www.castor.net
	 * Description:	   The complete online booking and property management solution for WordPress.
	 * Version:		   10.7.2
	 * Author:			Vince Wooll <support@castor.net>
	 * Author URI:		https://www.castor.net
	 * License:		   GPL-2.0+
	 * License URI:	   http://www.gnu.org/licenses/gpl-2.0.txt
	 * Text Domain:	   castor
	 * Domain Path:	   /languages
	 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
	die;
}

	/**
	 * Castor plugin version.
	 */
if (! defined('CASTOR_WP_PLUGIN_VERSION')) {
	define('CASTOR_WP_PLUGIN_VERSION', '10.7.2');
}

	/**
	 * Castor plugin base path.
	 */
if (! defined('CASTOR_WP_PLUGIN_PATH')) {
	define('CASTOR_WP_PLUGIN_PATH', plugin_dir_path(__FILE__));
}

	/**
	 * Castor init check.
	 */
if (! defined('_CASTOR_INITCHECK')) {
	define('_CASTOR_INITCHECK', 1);
}

	/**
	 * Castor admin init check.
	 */
if (is_admin() && ! defined('_CASTOR_INITCHECK_ADMIN')) {
	define('_CASTOR_INITCHECK_ADMIN', 1);
}

	/**
	 * Set path definitions.
	 *
	 *
	 * @since	9.23.6
	 */
if (!function_exists('define_castor_sub_dir_in_plugins_dir_as_root')) {
	function define_castor_sub_dir_in_plugins_dir_as_root()
	{
		$jr_root = str_replace(ABSPATH, '', CASTOR_WP_PLUGIN_PATH);
		define('CASTOR_ROOT_DIRECTORY', $jr_root.DIRECTORY_SEPARATOR.'castor');
		define('CASTORPATH_BASE', CASTOR_WP_PLUGIN_PATH.'castor'.DIRECTORY_SEPARATOR);
		define('JRDS', DIRECTORY_SEPARATOR);
	}
}

	/**
	 * Set path definitions.
	 *
	 *
	 * @since	10.3.1
	 */
if (!function_exists('define_castor_off_root_dir_as_root')) {
	function define_castor_off_root_dir_as_root()
	{
		define('CASTOR_ROOT_DIRECTORY', 'castor');
		define('CASTORPATH_BASE', ABSPATH.CASTOR_ROOT_DIRECTORY.DIRECTORY_SEPARATOR);
		define('JRDS', DIRECTORY_SEPARATOR);
	}
}


	/**
	 * Runs Castor installation or update routine.
	 *
	 * Donwloads Castor, unzips and runs the castor install or update
	 *
	 * @since	9.9.19
	 */
if (!function_exists('run_castor_installer')) {
	function run_castor_installer($method = 'install')
	{

		@ignore_user_abort(true);
		@set_time_limit(0);

		require_once(ABSPATH . 'wp-admin/includes/file.php');

		WP_Filesystem();

		global $wp_filesystem;

		//get the latest castor version download url
		$url = 'http://updates.castor.net/getlatest.php?includebeta=true';
		$nightly_url = 'http://updates.castor.net/nightly/';

		$nightly = false;

		if (WP_DEBUG) {
			$nightly = true;
		} elseif (file_exists(ABSPATH . CASTOR_ROOT_DIRECTORY . '/configuration.php')) {
			include ABSPATH . CASTOR_ROOT_DIRECTORY . '/configuration.php';

			if ($jrConfig['development_production'] == 'development') {
				$nightly = true;
			}
		}

		//download castor core
		$response = wp_remote_get($url);

		if (strlen($response['body']) == 0) {
			castor_notice('There was an error getting the latest Castor version number.');

			return false;
		}

		//set source and target
		$source = get_temp_dir() . 'castor.zip';
		$target = ABSPATH . CASTOR_ROOT_DIRECTORY;

		//check if /castor dir is writable
		if (!wp_is_writable($target)) {
			castor_notice('Castor dir ' . $target . ' can`t be created or it`s not writable. Using FTP, create the directory manually then re-run the installer, many times this will solve the problem.');

			return false;
		}

		//download Castor
		$options = array(
			'timeout' => 300,
			'stream' => true,
			'filename' => $source
		);

		if (!$nightly) {
			$response = wp_remote_get($response['body'], $options);
		} else {
			$response = wp_remote_get($nightly_url, $options);
		}

		if (is_wp_error($response)) {
			castor_notice('There was an error downloading castor.zip.');

			return false;
		}

		//unzip castor files
		$unzipfile = unzip_file($source, $target);

		if (is_wp_error($unzipfile)) {
			castor_notice('There was an error unzipping the Castor files. Tried to unzip from '.$source.' to '.$target.'');

			return false;
		}

		//delete downloaded zip
		if (file_exists($source)) {
			unlink($source);
		}

		//install Castor
		try {
			require_once ABSPATH . CASTOR_ROOT_DIRECTORY . '/libraries/castor/classes/castor_install.class.php';

			$castor_install = new castor_install($method);

			$messages = $castor_install->getMessages();

			//if there are no installation errors, update castor_wp_plugin_version in db
			if (empty($messages)) {
				update_option('castor_wp_plugin_version', CASTOR_WP_PLUGIN_VERSION);
			}
		} catch (Exception $e) {
			$messages = $castor_install->getMessages();

			foreach ($messages as $m) {
				castor_notice($m);
			}

			castor_notice($e->getMessage());

			return false;
		}

		return true;
	}
}

	/**
	 * Castor root directory.
	 */
	if (! defined('CASTOR_ROOT_DIRECTORY')) {
		if (is_dir(ABSPATH.'castor')) {
			define_castor_off_root_dir_as_root();
			if (!file_exists(ABSPATH.'castor'.DIRECTORY_SEPARATOR.'configuration.php'))  {
				run_castor_installer();
			}
		} else {
			if (file_exists(ABSPATH . 'castor_root.php')) {
				require_once ABSPATH . 'castor_root.php';
			} elseif (file_exists(ABSPATH.'castor'.DIRECTORY_SEPARATOR.'configuration.php')) {
				define_castor_off_root_dir_as_root();
			} else {
				if (file_exists(CASTOR_WP_PLUGIN_PATH.DIRECTORY_SEPARATOR.'castor'.DIRECTORY_SEPARATOR.'castor.php')) {
					define_castor_sub_dir_in_plugins_dir_as_root();
				} else {
					if (!is_dir(CASTOR_WP_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'castor' . DIRECTORY_SEPARATOR)) { // Let's see if we can install Castor in the castor subdir of wp-content, instead of it's traditional location off root
						if (mkdir(CASTOR_WP_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'castor' . DIRECTORY_SEPARATOR)) {
							define_castor_sub_dir_in_plugins_dir_as_root();
							run_castor_installer();
						}
					} else { // fallback for updated installations
						define('CASTOR_ROOT_DIRECTORY', 'castor');
					}
				}
			}
		}
	}

	/**
	 * The Castor wp plugin functions.
	 */
	require_once plugin_dir_path(__FILE__) . 'includes/functions.php';

	/**
	 * Register the activate and deactivate callbacks.
	 */
	register_activation_hook(__FILE__, 'activate_castor');
	register_deactivation_hook(__FILE__, 'deactivate_castor');

	/**
	 * The core plugin class that is used to define internationalization,
	 * admin-specific hooks, and public-facing site hooks.
	 */
	require_once plugin_dir_path(__FILE__) . 'includes/castor.php';

	/**
	 * Begins Castor execution.
	 *
	 * Since everything within the plugin is registered via hooks,
	 * then kicking off the plugin from this point in the file does
	 * not affect the page life cycle.
	 *
	 * @since	9.9.19
	 */
	run_castor();

