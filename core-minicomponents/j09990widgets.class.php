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

class j09990widgets
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
		
		$property_uid = getDefaultProperty();
		
		if ($property_uid == 0) {
			return;
		}
		
		$mrConfig = getPropertySpecificSettings($property_uid);

		$thisJRUser = castor_singleton_abstract::getInstance('jr_user');
		
		$castor_widgets = castor_singleton_abstract::getInstance('castor_widgets');

		//register core widgets
		if ($thisJRUser->accesslevel >= 50) {
			if ($mrConfig[ 'is_real_estate_listing' ] != '1' && !get_showtime('is_jintour_property')) {
				$castor_widgets->register_widget('06001', 'dashboard', jr_gettext('_CASTOR_TIMELINE', '_CASTOR_TIMELINE', false), true);
				$castor_widgets->register_widget('06001', 'weekly_occupancy_percentages', jr_gettext('_CASTOR_OVERALL_ROOMS_BOOKED', '_CASTOR_OVERALL_ROOMS_BOOKED', false), true);
			}

			$castor_widgets->register_widget('06001', 'listyourproperties', jr_gettext('_JRPORTAL_CPANEL_LISTPROPERTIES', '_JRPORTAL_CPANEL_LISTPROPERTIES', false));
			
			if ($mrConfig[ 'is_real_estate_listing' ] != '1') {
				$castor_widgets->register_widget('06001', 'list_bookings', jr_gettext('_CASTOR_FRONT_MR_MENU_ADMIN_LISTBOOKINGS', '_CASTOR_FRONT_MR_MENU_ADMIN_LISTBOOKINGS', false));
				$castor_widgets->register_widget('06001', 'list_guests', jr_gettext('_CASTOR_HLIST_GUESTS_MENU', '_CASTOR_HLIST_GUESTS_MENU', false));
				$castor_widgets->register_widget('06005', 'list_invoices', jr_gettext('_CASTOR_HLIST_INVOICES_MENU', '_CASTOR_HLIST_INVOICES_MENU', false));
			}
		}
		
		if ($thisJRUser->accesslevel >= 70) {
			if ($mrConfig[ 'is_real_estate_listing' ] != '1') {
				$castor_widgets->register_widget('06002', 'chart_bookings', jr_gettext('_CASTOR_CHART_BOOKINGS_DESC', '_CASTOR_CHART_BOOKINGS_DESC', false));
			}
		}
	}


	public function getRetVals()
	{
		return null;
	}
}

