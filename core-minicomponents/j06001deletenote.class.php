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

class j06001deletenote
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
		$thisJRUser = castor_singleton_abstract::getInstance('jr_user');
		if (!$thisJRUser->userIsManager) {
			return;
		}
		$note_id = castorGetParam($_REQUEST, 'note_id', 0);
		$contract_uid = castorGetParam($_REQUEST, 'contract_uid', 0);
		if ($note_id == 0 || $contract_uid == 0) {
			echo 'Error with note id or contract id';

			return;
		}
		$defaultProperty = getDefaultProperty();
		$auditMessage = jr_gettext('_JOMCOMP_BOOKINGNOTES_AUDITMESSAGE_DELETE', '_JOMCOMP_BOOKINGNOTES_AUDITMESSAGE_DELETE', false, false);
		$query = "DELETE FROM #__jomcomp_notes WHERE `id`='".(int) $note_id."' AND `property_uid`='".(int) $defaultProperty."' LIMIT 1";
		if (doInsertSql($query, $auditMessage)) {
			$webhook_notification						   = new stdClass();
			$webhook_notification->webhook_event			= 'booking_note_deleted';
			$webhook_notification->webhook_event_description = 'Logs when booking notes are deleted.';
			$webhook_notification->data					 = new stdClass();
			$webhook_notification->data->contract_uid	   = $contract_uid;
			$webhook_notification->data->property_uid	   = $defaultProperty;
			$webhook_notification->data->note_id			= $note_id;
			add_webhook_notification($webhook_notification);
			
			castorRedirect(castorURL(CASTOR_SITEPAGE_URL."&task=edit_booking&contract_uid=$contract_uid"), '');
		} else {
			echo 'Error deleting note';
		}
	}


	public function getRetVals()
	{
		return null;
	}
}

