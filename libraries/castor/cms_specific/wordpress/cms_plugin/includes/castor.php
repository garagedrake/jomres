<?php
	/**
	 * A class definition that includes attributes and functions used across both the
	 * public-facing side of the site and the admin area.
	 *
	 * @package Castor\Core\CMS_Specific
	 *
	 * @author	 Vince Wooll <support@castor.net>
	 */
class WP_Castor
{

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * Castor.
	 *
	 * @since	9.9.19
	 * @access   protected
	 * @var	  Castor_Loader	$loader	Maintains and registers all Castor hooks.
	 */
	protected $loader;

	/**
	 * The Castor plugin unique identifier.
	 *
	 * @since	9.9.19
	 * @access   protected
	 * @var	  string	$plugin_name	The string used to uniquely identify the Castor plugin.
	 */
	protected $plugin_name;

	/**
	 * The current Castor version.
	 *
	 * @since	9.9.19
	 * @access   protected
	 * @var	  string	$version	The current Castor version.
	 */
	protected $version;

	/**
	 * The Castor instance.
	 *
	 * @since	9.9.19
	 * @access   protected
	 * @var	  object	$configInstance	The Castor instance.
	 */
	private static $configInstance;

	/**
	 * The Castor javascript.
	 *
	 * @since	9.9.19
	 * @access   private
	 * @var	  array	$js	The Castor javascript.
	 */
	private $js;

	/**
	 * The Castor css.
	 *
	 * @since	9.9.19
	 * @access   private
	 * @var	  array	$css	The Castor css.
	 */
	private $css;

	/**
	 * The Castor custom meta.
	 *
	 * @since	9.9.19
	 * @access   private
	 * @var	  array	$custom_meta	The Castor custom meta.
	 */
	private $custom_meta;

	/**
	 * The Castor output.
	 *
	 * @since	9.9.19
	 * @access   private
	 * @var	  string	$content	The Castor output.
	 */
	private $content;

	/**
	 * The Castor page meta title.
	 *
	 * @since	9.9.19
	 * @access   private
	 * @var	  string	$meta_title	The Castor page meta title.
	 */
	private $meta_title;

	/**
	 * Define the Castor core functionality.
	 *
	 * Set the Castor name and the Castor version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since	9.9.19
	 */
	public function __construct()
	{

		if (defined('CASTOR_WP_PLUGIN_VERSION')) {
			$this->version = CASTOR_WP_PLUGIN_VERSION;
		} else {
			$this->version = '0';
		}

		$this->plugin_name = 'castor';
		$this->js = array();
		$this->css = array();
		$this->custom_meta = array();
		$this->content = '';
		$this->meta_title = '';

		$this->load_dependencies();
		$this->define_common_hooks();
		$this->define_public_hooks();

		if (is_admin()) {
			$this->define_admin_hooks();
		}
	}

	/**
	 * Get Castor instance.
	 *
	 * Description.
	 *
	 * @since	9.9.19
	 */
	public static function getInstance()
	{

		if (!self::$configInstance) {
			self::$configInstance = new self();
		}

		return self::$configInstance;
	}

	/**
	 * Load the required Castor dependencies.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Castor_Loader. Orchestrates the hooks of the plugin.
	 * - Castor_i18n. Defines internationalization functionality.
	 * - Castor_Admin. Defines all hooks for the admin area.
	 * - Castor_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since	9.9.19
	 * @access   private
	 */
	private function load_dependencies()
	{

		/**
		 * The class responsible for orchestrating the Castor actions and filters.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/castor-loader.php';

		/**
		 * The class responsible for defining all Castor actions that occur in the admin area.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/castor-admin.php';

		/**
		 * The class responsible for defining all Castor actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/castor-public.php';

		$this->loader = new Castor_Loader();
	}

	/**
	 * Define the Castor locale for internationalization.
	 *
	 * Uses the Castor_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since	9.9.19
	 * @access   private
	 */
	private function set_locale()
	{

		$plugin_i18n = new Castor_i18n();

		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	/**
	 * Register all of the common hooks related to the Castor admin area and public-facing functionality.
	 *
	 * @since	9.9.19
	 * @access   private
	 */
	private function define_common_hooks()
	{

		$this->loader->add_action('wp_login', $this, 'castor_wp_end_session');
		$this->loader->add_action('wp_logout', $this, 'castor_wp_end_session');
		$this->loader->add_action('wp_head', $this, 'castor_add_custom_meta');
	}

	/**
	 * Register all of the hooks related to the Castor admin area functionality.
	 *
	 * @since	9.9.19
	 * @access   private
	 */
	private function define_admin_hooks()
	{

		$castor_admin = new Castor_Admin($this->get_plugin_name(), $this->get_version(), $this->get_loader());

		$this->loader->add_action('admin_menu', $castor_admin, 'register_castor_admin_menu');

		if (isset($_REQUEST[ 'page' ]) && $_REQUEST[ 'page' ] == 'castor/castor.php') {
			if (! defined('_CASTOR_INITCHECK_ADMIN')) {
				define('_CASTOR_INITCHECK_ADMIN', 1);
			}

			$this->loader->add_action('init', $castor_admin, 'admin_trigger_castor', 1);
			$this->loader->add_action('wp_ajax_' . sanitize_text_field($_REQUEST[ 'page' ]), $castor_admin, 'castor_wp_ajax');
		}

		$this->loader->add_action('wp_ajax_castor_ajax', $castor_admin, 'castor_wp_ajax');
		$this->loader->add_action('wp_ajax_nopriv_castor_ajax', $castor_admin, 'castor_wp_ajax');
	}

	/**
	 * Register all of the hooks related to the Castor public-facing functionality.
	 *
	 * @since	9.9.19
	 * @access   private
	 */
	private function define_public_hooks()
	{

		$castor_public = new Castor_Public($this->get_plugin_name(), $this->get_version(), $this->get_loader());

		$this->loader->add_action('wp', $castor_public, 'frontend_trigger_castor', 1);

		$this->loader->add_filter('the_content', $castor_public, 'asamodule_search_results');
		$this->loader->add_filter('wp_title', $castor_public, 'set_castor_meta_title');
		$this->loader->add_filter('redirect_canonical', $castor_public, 'payments_redirect_canonical', 10, 2);

		//if &popup=1 is in $_REQUEST we'll disable all widgets, but leave the keys intact so that you don't get the "please activate a widget" message
		if (isset($_REQUEST['popup']) && (int)$_REQUEST['popup'] == 1) {
			$this->loader->add_filter('sidebars_widgets', $castor_public, 'disable_all_widgets');
		}

		//fullscreen view
		if (isset($_GET['tmpl']) && $_GET['tmpl'] == 'castor') {
			$this->loader->add_filter('template_include', $castor_public, 'castor_fullscreen_view');
		}
	}

	/**
	 * Register all scripts and styles related to the Castor andmin and public-facing functionality.
	 *
	 * @since	9.9.19
	 * @access   public
	 */
	public function add_castor_js_css()
	{
		$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
		$jrConfig = $siteConfig->get();
		$arr = array('jquery' , 'bootstrap');
		if ($jrConfig["bootstrap_version"] == 0) {
			$arr = array('jquery');
		}

		if (! empty($this->js)) {
			foreach ($this->js as $js_filename => $js) {
				if (is_admin()) {
					if (strpos($js['0'], 'castor.js')) {
						wp_register_script($js_filename, $js['0'], array('jquery' ), $js['1']);
					} else {
						wp_register_script($js_filename, $js['0'], array('jquery' ), $js['1'], true);
					}
				} else {
					if (strpos($js['0'], 'castor.js')) {
						wp_register_script($js_filename, $js['0'], $arr, $js['1'], false);
					} else {
						wp_register_script($js_filename, $js['0'], $arr, $js['1'], false);
					}
				}


				wp_enqueue_script($js_filename);
			}

			$this->js = array();
		}

		if (! empty($this->css)) {
			foreach ($this->css as $css_filename => $css) {
				wp_register_style( $css_filename, $css['0']);
				wp_enqueue_style($css_filename);
			}

			$this->css = array();
		}
	}

	/**
	 * Clear the Castor session data.
	 *
	 * @since	9.9.19
	 * @access   private
	 */
	public function castor_wp_end_session()
	{

		$_SESSION['castor_wp_session'] = array();
	}

	/**
	 * Echoes the Castor custom meta data.
	 *
	 * @since	9.9.19
	 * @access   private
	 */
	public function castor_add_custom_meta()
	{

		if (empty($this->custom_meta)) {
			return true;
		}

		echo PHP_EOL;

		foreach ($this->custom_meta as $meta) {
			echo $meta . PHP_EOL;
		}

		return true;
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since	9.9.19
	 */
	public function run()
	{

		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since	 9.9.19
	 * @return	string	The name of the plugin.
	 */
	public function get_plugin_name()
	{

		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since	 9.9.19
	 * @return	Castor_Loader	Orchestrates the hooks of the plugin.
	 */
	public function get_loader()
	{

		return $this->loader;
	}

	/**
	 * Retrieve the Castor version number.
	 *
	 * @since	 9.9.19
	 * @return	string	The Castor version number.
	 */
	public function get_version()
	{

		return $this->version;
	}

	/**
	 * Retrieve the Castor js.
	 *
	 * @since	 9.9.19
	 * @return	array	The Castor js.
	 */
	public function get_js()
	{

		return $this->js;
	}

	/**
	 * Retrieve the Castor css.
	 *
	 * @since	 9.9.19
	 * @return	array	The Castor css.
	 */
	public function get_css()
	{

		return $this->css;
	}

	/**
	 * Retrieve the Castor custom meta.
	 *
	 * @since	 9.9.19
	 * @return	array	The Castor custom meta.
	 */
	public function get_custom_meta()
	{

		return $this->custom_meta;
	}

	/**
	 * Retrieve the Castor output.
	 *
	 * @since	 9.9.19
	 * @return	string	The Castor output.
	 */
	public function get_content()
	{

		return $this->content;
	}

	/**
	 * Retrieve the Castor page metta title.
	 *
	 * @since	 9.9.19
	 * @return	array	The Castor page meta title.
	 */
	public function get_meta_title()
	{

		return $this->meta_title;
	}

	/**
	 * Retrieve the Castor output.
	 *
	 * @since	 9.9.19
	 * @return	bool	true.
	 */
	public function set_content($content)
	{

		$this->content = $content;

		return true;
	}

	/**
	 * Adds javascript files to $js array.
	 *
	 * @since	 9.9.19
	 * @return	bool	true.
	 */
	public function add_js($filename, $js, $version)
	{

		$this->js[ $filename ] = array( $js, $version );

		return true;
	}

	/**
	 * Adds javascript files to $js array.
	 *
	 * @since	 9.9.19
	 * @return	bool	true.
	 */
	public function add_css($filename, $css, $version)
	{

		$this->css[ $filename ] = array( $css, $version );

		return true;
	}

	/**
	 * Adds custom meta data cu $custom_meta array.
	 *
	 * @since	 9.9.19
	 * @return	bool	true.
	 */
	public function add_custom_meta($meta)
	{

		$this->custom_meta[] = $meta;

		return true;
	}

	/**
	 * Sets the Castor page meta title.
	 *
	 * @since	 9.9.19
	 * @return	bool	true.
	 */
	public function set_meta_title($title)
	{

		$this->meta_title = trim($title);

		return true;
	}
}

