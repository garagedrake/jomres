<?php
	/**
	 * Core file.
	 *
	 * @author Vince Wooll <sales@castor.net>
	 *
	  *  @version Castor 10.7.2
	 *
	 * @copyright	2005-2023 Vince Wooll
	 * Castor (tm) PHP, CSS & Javascript files are released under both MIT and GPL2 licenses. This means that you can choose the license that best suits your project, and use it accordingly
	 **/

// ################################################################
	defined('_CASTOR_INITCHECK') or die('');
// ################################################################
	#[AllowDynamicProperties]
	/**
	 * @package Castor\Core\Minicomponents
	 *
	 * The script makes sure that the castor_bootstrap_version is set before any other scripts are called, due to changes how J4 calls web assets we need to prioritise that variable
	 *
	 */

class j00004a_set_bootstrap_version_js
{
	/**
	 *
	 * Constructor
	 *
	 * Main functionality of the Minicomponent
	 *
	 *
	 *
	 */

	public function __construct($componentArgs)
	{
		// Must be in all minicomponents. Minicomponents with templates that can contain editable text should run $this->template_touch() else just return
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = false;

			return;
		}

		if (AJAXCALL == '1') {
			return;
		}

		$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
		$jrConfig = $siteConfig->get();
		$ls = castorGetDomain();

		$template_dir = find_plugin_template_directory();
		if (castor_cmsspecific_areweinadminarea()) {
			if (_CASTOR_DETECTED_CMS != 'joomla4') {
				$template_dir = 'bootstrap';
			} else {
				$template_dir = 'bootstrap5';
			}
		}

		$misc_url_defs = '
			var castor_template_version = "'.$template_dir.'";
			';

		if (!isset($_SERVER[ "SERVER_PORT" ])) { // CLI not isset variable fix
			$SERVER_PORT = '80';
		} else {
			$SERVER_PORT = $_SERVER[ "SERVER_PORT" ];
		}

		if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $SERVER_PORT == 443) {// We need to include some javascript which could normally be echo'd into the page, but due to the fact that it might be included by Castor proper, as well as plugins, we'll instead create it's own .js file, and use the host CMS to insert it into the head.
			$temp_file = $ls.'_ssl_'.get_showtime('lang');
		} else {
			$temp_file = $ls.'_'.get_showtime('lang');
		}

		if (castor_cmsspecific_areweinadminarea()) {
			$temp_file .= '_bootstrap_version_misc_url_defs_admin.js';
		} else {
			$temp_file .= '_bootstrap_version_misc_url_defs.js';
		}
		if (!file_exists(CASTOR_TEMP_ABSPATH.$temp_file)) {
			$result = file_put_contents(CASTOR_TEMP_ABSPATH.$temp_file, $misc_url_defs);
			if (!$result) {
				throw new Exception('Tried to write  '.CASTOR_TEMP_ABSPATH.$temp_file.' but was not succcessful');
			}
		}

		castor_cmsspecific_addheaddata('javascript', CASTOR_ROOT_DIRECTORY.'/temp/', $temp_file);
	}


	public function getRetVals()
	{
		return null;
	}
}

