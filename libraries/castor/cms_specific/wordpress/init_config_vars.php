<?php
	/**
	 * Core file.
	 *
	 * @author Vince Wooll <sales@castor.net>
	 *
	 *  @version Castor 10.7.2
	 *
	 * @copyright	2005-2023 Vince Wooll
	 * Castor is currently available for use in all personal or commercial projects under both MIT and GPL2 licenses. This means that you can choose the license that best suits your project, and use it accordingly
	 **/

// ################################################################
	defined('_CASTOR_INITCHECK') or die('Direct Access to this file is not allowed.');
// ################################################################

	if (!defined('WPINC')) {
		require_once CASTORCONFIG_ABSOLUTE_PATH.JRDS.'wp-config.php';
	}




	$castorConfig_live_site = get_site_url('siteurl');
	if (defined('API_STARTED')) {
		$castorConfig_live_site = str_replace('/castor/api', '', $castorConfig_live_site);
	}

	$castorConfig_lang = 'en-GB';
	$castorConfig_lang_shortcode = 'en';
	if (!defined('AUTO_UPGRADE')) {
		$castorConfig_lang = str_replace('_', '-', get_locale());

		//get lang short code
		//TODO: this is unreliable at this point, for example for pt-BR and pt-PT, because the language code is always pt.
		//later in the code in cms_specific_urls.php the correct shortcode will be set
		$castorConfig_lang_shortcode = substr($castorConfig_lang, 0, 2);
	}

	include(CASTORCONFIG_ABSOLUTE_PATH.CASTOR_ROOT_DIRECTORY.JRDS.'configuration.php');

	if ($jrConfig['bootstrap_version'] == 0 && AJAXCALL === false && !is_admin()) {

		function castor_load_css_js_sin_bootstrap() {
			// JS
			wp_enqueue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js', array('jquery') , null , false );
			wp_enqueue_style('bootstrap_css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css' );

			wp_enqueue_style(
				'font-awesome-6','https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css',array(),'6.2.0');
		}

		add_action('wp_enqueue_scripts', 'castor_load_css_js_sin_bootstrap');
	}

	add_filter('wp_editor_settings', function ($settings) {
		$settings['media_buttons']=false;
		return $settings;
	});

	$showtime = castor_getSingleton('showtime');

	$showtime->error_reporting = 0;

	$showtime->lang = $castorConfig_lang;
	$showtime->lang_shortcode = $castorConfig_lang_shortcode;
	$showtime->live_site = $castorConfig_live_site;
	$showtime->offline = false;

	global $wpdb; //wp global
	$showtime->db       = $wpdb->dbname;
	$showtime->user     = $wpdb->dbuser;
	$showtime->password = $wpdb->dbpassword;
	$showtime->host     = $wpdb->dbhost;
	$showtime->secret   = AUTH_SALT;
	$showtime->dbprefix = $wpdb->prefix;

	$showtime->sitename = get_option('blogname');
	$showtime->mailer   = 'mail';
	$showtime->mailfrom = get_option('admin_email');
	$showtime->fromname = get_option('blogname');
	$showtime->smtpuser = get_option('mailserver_login');
	$showtime->smtppass = get_option('mailserver_pass');
	$showtime->smtphost = get_option('mailserver_url');
	$showtime->smtpport = get_option('mailserver_port');
	$showtime->smtpauth = 0;
	$showtime->smtpsecure = '';
//$showtime->gzip = get_option('gzipcompression');
	$showtime->gzip = '0'; //this is not used in wp

	if (get_option('permalink_structure') != '') {
		$showtime->sef = '1';  // Sef urls are enabled.
	} else {
		$showtime->sef = '0';
	}

