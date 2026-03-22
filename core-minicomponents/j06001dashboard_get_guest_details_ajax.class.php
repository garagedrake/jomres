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

class j06001dashboard_get_guest_details_ajax
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

		jr_import('castor_encryption');
		$castor_encryption = new castor_encryption();

		$property_uid = castorGetParam($_GET, 'property_uid', 0);
		if ($property_uid == 0) {
			$property_uid = getDefaultProperty();
		}

		$thisJRUser = castor_singleton_abstract::getInstance('jr_user');
		if (!in_array($property_uid, $thisJRUser->authorisedProperties)) {
			return;
		}

		$existing_id = (int) castorGetParam($_GET, 'existing_id', 0);

		$thisJRUser = castor_singleton_abstract::getInstance('jr_user');

		$guestDeets = array();

		if ($existing_id == 0) {
			echo json_encode($guestDeets);
			exit;
		}

		$query = 'SELECT 
						`guests_uid` AS existing_id,
						`mos_userid`,
						`enc_surname`,
						`enc_firstname`,
						`enc_house`,
						`enc_street`,
						`enc_town`,
						`enc_county`,
						`enc_country`,
						`enc_postcode`,
						`enc_tel_landline`,
						`enc_tel_mobile`,
						`enc_email`
					FROM #__castor_guests 
					WHERE `property_uid` IN (' .castor_implode($thisJRUser->authorisedProperties).') 
						AND `guests_uid` = '.(int) $existing_id.'  
					LIMIT 1 ';
		$guestDeets = doSelectSql($query, 2);

		foreach ($guestDeets as $key => $val) {
			if (substr($key, 0, 4) == "enc_") {
				$newkey = substr($key, 4);
				$guestDeets[$newkey] = $castor_encryption->decrypt($val);
				unset($guestDeets[$key]);
			}
		}

		echo json_encode($guestDeets);
		exit;
	}


	public function getRetVals()
	{
		return null;
	}
}

