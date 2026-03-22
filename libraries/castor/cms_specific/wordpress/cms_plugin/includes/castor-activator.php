<?php

/**
 * Fired during plugin activation
 *
 * @link	   https://www.castor.net
 * @since	  9.9.19
 *
 * @package Castor\Core\CMS_Specific
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since	  9.9.19
 *
 * @author	 Vince Wooll <support@castor.net>
 */
class Castor_Activator
{

	/**
	 * Performs Castor installation/update.
	 *
	 * Download Castor, unzip files, move files to /castor dir and execute the Castor installer.
	 *
	 * @since	9.9.19
	 */
	public static function activate()
	{
		if (version_compare(PHP_VERSION, '7.4', '<')) {
			exit(sprintf('Castor requires PHP 7.4 or higher. You’re still on %s.', PHP_VERSION));
		}

		if (!is_dir(ABSPATH.'castor')) {
			if (!mkdir(ABSPATH.'castor')) {
				exit(sprintf('Unable to create the directory '.ABSPATH.'castor'.' automatically. <br/> 
					Please FTP into your site and create it, then activate the plugin.'));
			}
		}
		self::maybe_install_or_update_castor();
		self::maybe_add_default_castor_page();
	}
	
	/**
	 * Install or update Castor.
	 *
	 * This installs or updates Castor on Castor WP plugin activation.
	 *
	 * @since	9.9.19
	 */
	private static function maybe_install_or_update_castor()
	{
		
		return castor_is_installed_and_updated();
	}
	
	/**
	 * Creates the Castor default Page.
	 *
	 * Default Castor Page includes the Castor shortcode formatted like [castor:xx-XX].
	 * xx-XX is the language code, for example: en-GB.
	 *
	 * @since	9.9.19
	 */
	private static function maybe_add_default_castor_page()
	{
		
		global $wpdb;

		$currentBlogLang = str_replace('_', '-', get_locale());
		$keyword = '[castor:' . $currentBlogLang . ']';

		$result = $wpdb->get_results("SELECT `ID` FROM {$wpdb->posts} WHERE LOWER( `post_content` ) LIKE '%" . strtolower($keyword) . "%' AND `post_status` = 'publish' AND `post_type` = 'page' LIMIT 1", OBJECT);

		if (empty($result)) {
			$postarr = array(
				'comment_status' => 'closed',
				'ping_status' => 'closed',
				'post_title' => 'Bookings',
				'post_content' => $keyword,
				'post_status' => 'publish',
				'post_type' => 'page',
				);

			wp_insert_post($postarr);
		}
		
		return true;
	}
}

