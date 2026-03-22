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

class j06000gdpr_forget_me_now
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
		jr_import('castor_gdpr_personal_information_collections');
		$castor_gdpr_personal_information_collections = new castor_gdpr_personal_information_collections();
		$castor_gdpr_personal_information_collections->set_id($thisJRUser->id);
		$result = $castor_gdpr_personal_information_collections->can_redact_this_cms_user();
		if ($result['can_redact'] == false) {
			echo $result['reason'];
			return;
		}
		$castor_gdpr_personal_information_collections->redact_pii();
		castorRedirect(castorURL(CASTOR_SITEPAGE_URL.'&task=edit_my_account'), jr_gettext('_CASTOR_GDPR_MY_RTBF_FORGET_ME_FORGOTTEN', '_CASTOR_GDPR_MY_RTBF_FORGET_ME_FORGOTTEN', false));
	}


	public function getRetVals()
	{
		return null;
	}
}

