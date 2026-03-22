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
	 *
	 */

class j99999animation_library_init
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
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = false;

			return;
		}


		if (AJAXCALL) {
			return;
		}

		if (defined('API_STARTED')) {
			return;
		}

		if (castor_cmsspecific_areweinadminarea()) {
			return;
		}


		if (!defined('AOS_INITIALISED')) {
			$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
			$jrConfig = $siteConfig->get();

			if (!isset($jrConfig['animation_library_enabled'])) {
				$jrConfig['animation_library_enabled'] = 1;
			}

			if ($jrConfig['animation_library_enabled'] != 1 ) {
				return;
			}

			castor_cmsspecific_addheaddata('css', 'https://unpkg.com/aos@2.3.1/dist/','aos.css');
			castor_cmsspecific_addheaddata('javascript', 'https://unpkg.com/aos@2.3.1/dist/', 'aos.js');
			castor_cmsspecific_addheaddata('javascript', CASTOR_JS_RELPATH, 'AOS.js');
			define('AOS_INITIALISED',1);
		}
	}

	public function getRetVals()
	{
		return null;
	}
}

