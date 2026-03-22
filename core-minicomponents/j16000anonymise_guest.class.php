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

class j16000anonymise_guest
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
	 
	public function __construct()
	{
		// Must be in all minicomponents. Minicomponents with templates that can contain editable text should run $this->template_touch() else just return
		$MiniComponents = castor_getSingleton('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = false;

			return;
		}

		$guest_id = (int)castorGetParam($_REQUEST, 'guest_id', 0);
		$property_uid = (int)castorGetParam($_REQUEST, 'property_uid', 0);
		
		jr_import('castor_gdpr_personal_information_collections');
		$castor_gdpr_personal_information_collections = new castor_gdpr_personal_information_collections();
		$castor_gdpr_personal_information_collections->redact_non_registered_guest_pii($guest_id, $property_uid);
		
		castorRedirect(castorURL(CASTOR_SITEPAGE_URL_ADMIN . "&task=list_guests"), jr_gettext("_CASTOR_GDPR_RTBF_GUEST_REDACTED", '_CASTOR_GDPR_RTBF_GUEST_REDACTED', false));
	}


	public function getRetVals()
	{
		return null;
	}
}

