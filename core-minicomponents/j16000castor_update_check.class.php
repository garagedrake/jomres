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

class j16000castor_update_check
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

		if (isset($componentArgs[ 'output_now' ])) {
			$output_now = $componentArgs[ 'output_now' ];
		} else {
			$output_now = true;
		}
		$this->retVals = '';

		$this_version = get_castor_current_version();
		$latest_version = get_latest_castor_version();

		if (empty($latest_version)) {
			$this->retVals = false;
		} else {
			$current_version_is_uptodate = check_castor_version();

			if (!$current_version_is_uptodate) {
				$output = array();
				$pageoutput = array();

				$output[ 'CASTOR_UPDATE_URL' ] = CASTOR_SITEPAGE_URL_ADMIN.'&task=updates';
				$output[ 'CASTOR_UPDATE_MESSAGE_TITLE' ] = jr_gettext('CASTOR_UPDATE_MESSAGE_TITLE', 'CASTOR_UPDATE_MESSAGE_TITLE', false);
				$output[ 'CASTOR_UPDATE_MESSAGE_MESSAGE' ] = jr_gettext('CASTOR_UPDATE_MESSAGE_MESSAGE', 'CASTOR_UPDATE_MESSAGE_MESSAGE', false);
				$output[ 'CASTOR_UPDATE_MESSAGE_LINK' ] = jr_gettext('CASTOR_UPDATE_MESSAGE_LINK', 'CASTOR_UPDATE_MESSAGE_LINK', false);

				$pageoutput[] = $output;
				$tmpl = new patTemplate();
				$tmpl->setRoot(CASTOR_TEMPLATEPATH_ADMINISTRATOR);
				$tmpl->addRows('pageoutput', $pageoutput);
				$tmpl->readTemplatesFromInput('castor_update_check.html');

				if ($output_now) {
					$tmpl->displayParsedTemplate();
				} else {
					$this->retVals = $tmpl->getParsedTemplate();
				}
				$this->retVals = true;
			} else {
				$this->retVals = false;
			}
		}
	}


	public function getRetVals()
	{
		return $this->retVals;
	}
}

