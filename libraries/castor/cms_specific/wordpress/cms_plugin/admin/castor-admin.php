<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link	   https://www.castor.net
 * @since	  9.9.19
 *
 * @package	Castor
 * @subpackage Castor/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version.
 *
 * @package	Castor
 * @subpackage Castor/admin
 * @author	 Vince Wooll <support@castor.net>
 */
class Castor_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since	9.9.19
	 * @access   private
	 * @var	  string	$plugin_name	The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since	9.9.19
	 * @access   private
	 * @var	  string	$version	The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since	9.9.19
	 * @param	  string	$plugin_name	   The name of this plugin.
	 * @param	  string	$version	The version of this plugin.
	 */
	public function __construct($plugin_name, $version, $loader)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}
	
	/**
	 * Register the Castor admin area menu.
	 *
	 * @since	9.9.19
	 */
	public function register_castor_admin_menu()
	{
		
		add_menu_page('Castor admin', 'Castor', 'manage_options', 'castor/castor.php', '', '', 6);
	}
	
	/**
	 * Trigger Castor admin cpanel.
	 *
	 * @since	9.9.19
	 */
	public function admin_trigger_castor()
	{
	
		$wp_castor = WP_Castor::getInstance();
		
		if ($wp_castor->get_content() == '') {
			//check if we are on the castor admin page
			if (isset($_GET[ 'page' ]) && $_GET[ 'page' ] == 'castor/castor.php') {
				ob_start();

				trigger_castor();

				add_action('admin_enqueue_scripts', array($wp_castor, 'add_castor_js_css'), 9999);

				$wp_castor->set_content(ob_get_contents());

				ob_end_clean();
			}
		}

		return true;
	}
	
	
	/**
	 *
	 *
	 *
	 */

	public function castor_wp_ajax()
	{
	
		trigger_castor();

		die();  // Required for a proper Wordpress AJAX result
	}
}

