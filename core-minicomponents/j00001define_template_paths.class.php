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
	 * Obsolete, as template overrides are better understood by users, but left in situ for historic users.
	 *
	 */

class j00001define_template_paths
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

		$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
		$jrConfig = $siteConfig->get();
		if (!isset($jrConfig[ 'bootstrap_version' ]) || $jrConfig[ 'bootstrap_version' ] == 0) {
			$jrConfig[ 'bootstrap_version' ] = '5';
		}

		if (!defined('CASTOR_TEMPLATEPATH_FRONTEND')) {
			if (!using_bootstrap()) {
				define('CASTOR_TEMPLATEPATH_FRONTEND', CASTORPATH_BASE.JRDS.'assets'.JRDS.'templates'.JRDS.'jquery_ui'.JRDS.'frontend');
			} else {
				define('CASTOR_TEMPLATEPATH_FRONTEND', CASTORPATH_BASE.JRDS.'assets'.JRDS.'templates'.JRDS.'bootstrap'.$jrConfig[ 'bootstrap_version' ].JRDS.'frontend');
			}
		}

		if (!defined('CASTOR_TEMPLATEPATH_BACKEND')) {
			if (!using_bootstrap()) {
				define('CASTOR_TEMPLATEPATH_BACKEND', CASTORPATH_BASE.JRDS.'assets'.JRDS.'templates'.JRDS.'jquery_ui'.JRDS.'backend');
			} else {
				define('CASTOR_TEMPLATEPATH_BACKEND', CASTORPATH_BASE.JRDS.'assets'.JRDS.'templates'.JRDS.'bootstrap'.$jrConfig[ 'bootstrap_version' ].JRDS.'backend');
			}
		}

		if (!defined('CASTOR_TEMPLATEPATH_ADMINISTRATOR')) {
			if (_CASTOR_DETECTED_CMS == 'joomla3' || this_cms_is_wordpress()) {
				define('CASTOR_TEMPLATEPATH_ADMINISTRATOR', CASTORPATH_BASE.JRDS.'assets'.JRDS.'templates'.JRDS.'bootstrap'.JRDS.'administrator');
			} elseif (_CASTOR_DETECTED_CMS == 'joomla4') {
				define('CASTOR_TEMPLATEPATH_ADMINISTRATOR', CASTORPATH_BASE.JRDS.'assets'.JRDS.'templates'.JRDS.'bootstrap5'.JRDS.'administrator');
			} else {
				define('CASTOR_TEMPLATEPATH_ADMINISTRATOR', CASTORPATH_BASE.JRDS.'assets'.JRDS.'templates'.JRDS.'bootstrap'.$jrConfig[ 'bootstrap_version' ].JRDS.'administrator');
			}
		}
	}


	public function getRetVals()
	{
		return null;
	}
}

