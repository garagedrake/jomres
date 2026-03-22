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

class j06000gdpr_download_pii
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
			$this->shortcode_data = array(
				'task' => 'gdpr_my_data',
				'info' => '_CASTOR_GDPR_MY_DATA',
				'arguments' => array()
				);

			return;
		}
		$thisJRUser = castor_singleton_abstract::getInstance('jr_user');
		
		if ($thisJRUser->id > 0) {
			jr_import('castor_gdpr_personal_information_collections');
			$castor_gdpr_personal_information_collections = new castor_gdpr_personal_information_collections();
			$castor_gdpr_personal_information_collections->set_id($thisJRUser->id);
			$pii = $castor_gdpr_personal_information_collections->collect_pii();
		} else {
			$pii = array();
		}

		$tmpBookingHandler = castor_singleton_abstract::getInstance('castor_temp_booking_handler');

		$pii['country'] = $tmpBookingHandler->tmpguest['country'];
		$pii['ip'] = $tmpBookingHandler->info['ip'];
		
		$pageoutput = array();
		$output = array();
		
		$output['PII'] = json_encode($pii);
		$output['_CASTOR_COM_MR_BACK'] = jr_gettext('_CASTOR_COM_MR_BACK', '_CASTOR_COM_MR_BACK', false);
		$output['_CASTOR_GDPR_DOWNLOAD_PROFILE_DATA_TEXT'] = jr_gettext('_CASTOR_GDPR_DOWNLOAD_PROFILE_DATA_TEXT', '_CASTOR_GDPR_DOWNLOAD_PROFILE_DATA_TEXT', false);
		
		
		$pageoutput[ ] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_FRONTEND);
		$tmpl->addRows('pageoutput', $pageoutput);
		$tmpl->readTemplatesFromInput('gdpr_my_data_download.html');
		$tmpl->displayParsedTemplate();
	}


	public function getRetVals()
	{
		return null;
	}
}

