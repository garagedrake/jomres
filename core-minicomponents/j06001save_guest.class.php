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

class j06001save_guest
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
		$id = (int)castorGetParam($_REQUEST, 'id', 0);
		$defaultProperty = getDefaultProperty();
		
		jr_import('jrportal_guests');
		$jrportal_guests = new jrportal_guests();
		$jrportal_guests->id = $id;
		$jrportal_guests->property_uid = $defaultProperty;
		
		if ($id > 0) {
			$jrportal_guests->get_guest(); // if we don't get_guest then the mos_id ( cms_id) will get reset when the guest is saved
		}
		
		
		$jrportal_guests->firstname = castorGetParam($_REQUEST, 'firstname', '');
		$jrportal_guests->surname = castorGetParam($_REQUEST, 'surname', '');
		$jrportal_guests->house = castorGetParam($_REQUEST, 'house', '');
		$jrportal_guests->street = castorGetParam($_REQUEST, 'street', '');
		$jrportal_guests->town = castorGetParam($_REQUEST, 'town', '');
		$jrportal_guests->region = castorGetParam($_REQUEST, 'region', '');
		$jrportal_guests->country = castorGetParam($_REQUEST, 'guest_country', '');
		$jrportal_guests->postcode = castorGetParam($_REQUEST, 'postcode', '');
		$jrportal_guests->tel_landline = castorGetParam($_REQUEST, 'landline', '');
		$jrportal_guests->tel_mobile = castorGetParam($_REQUEST, 'mobile', '');
		$jrportal_guests->email = castorGetParam($_REQUEST, 'email', '');
		$jrportal_guests->vat_number = castorGetParam($_REQUEST, 'vat_number', '');
		$jrportal_guests->discount = (int) castorGetParam($_REQUEST, 'discount', 0);
		$jrportal_guests->blacklisted = (int) castorGetParam($_REQUEST, 'blacklisted', 0);

		if ($id > 0) {
			$jrportal_guests->commit_update_guest();
		} else {
			$jrportal_guests->commit_new_guest();
		}
			
		castorRedirect(castorURL(CASTOR_SITEPAGE_URL."&task=list_guests"), 'Guest saved');
	}


	public function getRetVals()
	{
		return null;
	}
}

