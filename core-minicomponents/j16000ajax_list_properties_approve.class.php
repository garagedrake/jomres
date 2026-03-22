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

class j16000ajax_list_properties_approve
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
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = false;

			return;
		}

		$property_uid = (int) castorGetParam($_REQUEST, 'property_uid', 0);
		$approved = (int) castorGetParam($_REQUEST, 'approved', 0);
		
		$castor_properties = castor_singleton_abstract::getInstance('castor_properties');
		$castor_properties->propertys_uid = $property_uid;
		$castor_properties->setApproved($approved);

		if ($approved == 0) {
			$castor_properties->setPublished(0);
		}

		$current_property_details = castor_singleton_abstract::getInstance('basic_property_details');
		$current_property_details->gather_data($property_uid);

		$castorConfig_mailfrom = get_showtime('mailfrom');
		$castorConfig_fromname = get_showtime('fromname');

		$link = get_property_details_url($property_uid, 'nosef');

		switch ($approved) {
			case 1:
				if (!castorMailer($castorConfig_mailfrom, $castorConfig_fromname, $current_property_details->property_email, jr_gettext('_CASTOR_APPROVALS_MANAGER_EMAIL_SUBJECT', '_CASTOR_APPROVALS_MANAGER_EMAIL_SUBJECT', false), jr_gettext('_CASTOR_APPROVALS_MANAGER_EMAIL_CONTENT', '_CASTOR_APPROVALS_MANAGER_EMAIL_CONTENT', false).$link, $mode = 1)) {
					error_logging('Failure in sending approval email to hotel. Target address: '.$current_property_details->property_email.' Subject '.jr_gettext('_CASTOR_APPROVALS_MANAGER_EMAIL_SUBJECT', '_CASTOR_APPROVALS_MANAGER_EMAIL_SUBJECT', false));
				}
				break;
			case 0:
				if (!castorMailer($castorConfig_mailfrom, $castorConfig_fromname, $current_property_details->property_email, jr_gettext('_CASTOR_APPROVALS_MANAGER_EMAIL_SUBJECT_UNAPPROVED', '_CASTOR_APPROVALS_MANAGER_EMAIL_SUBJECT_UNAPPROVED', false), jr_gettext('_CASTOR_APPROVALS_MANAGER_EMAIL_CONTENT_UNAPPROVED', '_CASTOR_APPROVALS_MANAGER_EMAIL_CONTENT_UNAPPROVED', false).$link, $mode = 1)) {
					error_logging('Failure in sending unapproval email to hotel. Target address: '.$current_property_details->property_email.' Subject '.jr_gettext('_CASTOR_APPROVALS_MANAGER_EMAIL_SUBJECT_UNAPPROVED', '_CASTOR_APPROVALS_MANAGER_EMAIL_SUBJECT_UNAPPROVED', false));
				}
				break;
			default:
				break;
		}

		echo 'Approval status changed to '.$approved;
		exit;
	}


	public function getRetVals()
	{
		return null;
	}
}

