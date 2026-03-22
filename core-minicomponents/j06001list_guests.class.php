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

class j06001list_guests
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
			$this->template_touchable = true;

			return;
		}
		
		$this->retVals = '';

		if (isset($componentArgs[ 'output_now' ])) {
			$output_now = $componentArgs[ 'output_now' ];
		} else {
			$output_now = true;
		}

		$historic = (int) castorGetParam($_POST, 'historic', '2');
		$guest_id = (int) castorGetParam($_POST, 'guest_id', '0');
		$show_all = (int) castorGetParam($_POST, 'show_all', '0');

		$output[ 'PAGETITLE' ] = jr_gettext('_CASTOR_HLIST_GUESTS', '_CASTOR_HLIST_GUESTS', false);
		$output[ 'HTOWN' ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_TOWN', '_CASTOR_COM_MR_DISPGUEST_TOWN', false);
		$output[ 'HEDITLINK' ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_EDITDETAILS', '_CASTOR_COM_MR_DISPGUEST_EDITDETAILS', false);
		$output[ 'HINVOICELINK' ] = jr_gettext('_CASTOR_MANAGER_SHOWINVOICES', '_CASTOR_MANAGER_SHOWINVOICES', false);
		$output[ 'HFIRSTNAME' ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_FIRSTNAME', '_CASTOR_COM_MR_DISPGUEST_FIRSTNAME', false);
		$output[ 'HSURNAME' ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_SURNAME', '_CASTOR_COM_MR_DISPGUEST_SURNAME', false);
		$output[ 'HHOUSE' ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_HOUSE', '_CASTOR_COM_MR_DISPGUEST_HOUSE', false);
		$output[ 'HSTREET' ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_STREET', '_CASTOR_COM_MR_DISPGUEST_STREET', false);
		$output[ 'HTOWN' ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_TOWN', '_CASTOR_COM_MR_DISPGUEST_TOWN', false);
		$output[ 'HREGION' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_REGION', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_REGION', false);
		$output[ 'HPOSTCODE' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_POSTCODE', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_POSTCODE', false);
		$output[ 'HCOUNTRY' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_COUNTRY', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_COUNTRY', false);
		$output[ 'HLANDLINE' ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_LANDLINE', '_CASTOR_COM_MR_DISPGUEST_LANDLINE', false);
		$output[ 'HMOBILE' ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_MOBILE', '_CASTOR_COM_MR_DISPGUEST_MOBILE', false);
		$output[ 'HEMAIL' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_EMAIL', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_EMAIL', false);
		$output[ 'HVAT' ] = jr_gettext('_CASTOR_COM_YOURBUSINESS_VATNO', '_CASTOR_COM_YOURBUSINESS_VATNO', false);
		$output[ 'HPERSONAL_DISCOUNT' ] = jr_gettext('_CASTOR_PERSONAL_DISCOUNT', '_CASTOR_PERSONAL_DISCOUNT', false);
		$output[ 'HPROPERTY_NAME' ] = jr_gettext('_JRPORTAL_PROPERTIES_PROPERTYNAME', '_JRPORTAL_PROPERTIES_PROPERTYNAME', false, false);

		if (!using_bootstrap()) {
			$jrtbar = castor_singleton_abstract::getInstance('castor_toolbar');
			$jrtb = $jrtbar->startTable();
			$text = jr_gettext('_CASTOR_COM_MR_NEWGUEST', '_CASTOR_COM_MR_NEWGUEST', false, true);
			$link = CASTOR_SITEPAGE_URL.'&task=edit_guest';
			$targetTask = 'editGuest';
			$image = CASTOR_IMAGES_RELPATH.'castorimages/'.$jrtbar->imageSize.'/guestAdd.png';
			$jrtb .= $jrtbar->customToolbarItem($targetTask, $link, $text, $submitOnClick = false, $submitTask = '', $image);
			$jrtb .= $jrtbar->endTable();
			$output[ 'CASTORTOOLBAR' ] = $jrtb;
		} else {
			$output[ 'NEW_GUEST_URL' ] = castorUrl(CASTOR_SITEPAGE_URL.'&task=edit_guest');
			$output[ 'HNEW_GUEST' ] = jr_gettext('_CASTOR_COM_MR_NEWGUEST', '_CASTOR_COM_MR_NEWGUEST', false, true);
		}

		//filters
		$output['HFILTER'] = jr_gettext('_CASTOR_HFILTER', '_CASTOR_HFILTER', false);
		$output['HGUEST_STATUS'] = jr_gettext('_CASTOR_HSTATUS_GUEST', '_CASTOR_HSTATUS_GUEST', false);
		$output['HSHOW_ALL'] = jr_gettext('_CASTOR_HSTATUS_SHOW_GUESTS_FOR', '_CASTOR_HSTATUS_SHOW_GUESTS_FOR', false);

		$options = array();
		$options[] = castorHTML::makeOption('2', jr_gettext('_CASTOR_STATUS_ANY', '_CASTOR_STATUS_ANY', false));
		$options[] = castorHTML::makeOption('0', jr_gettext('_CASTOR_STATUS_GUEST_BOOKINGS_ACTIVE', '_CASTOR_STATUS_GUEST_BOOKINGS_ACTIVE', false));
		$options[] = castorHTML::makeOption('1', jr_gettext('_CASTOR_STATUS_GUEST_BOOKINGS_PAST', '_CASTOR_STATUS_GUEST_BOOKINGS_PAST', false));
		$output['GUEST_STATUS'] = castorHTML::selectList($options, 'historic', '', 'value', 'text', $historic);

		$options = array();
		$options[] = castorHTML::makeOption('0', jr_gettext('_CASTOR_HSTATUS_CURRENT', '_CASTOR_HSTATUS_CURRENT', false));
		$options[] = castorHTML::makeOption('1', jr_gettext('_CASTOR_STATUS_ALL_PROPERTIES', '_CASTOR_STATUS_ALL_PROPERTIES', false));
		$output['SHOW_ALL'] = castorHTML::selectList($options, 'show_all', '', 'value', 'text', $show_all);

		$output['AJAX_URL'] = CASTOR_SITEPAGE_URL_AJAX.'&task=listguests_ajax&historic='.$historic.'&guest_id='.$guest_id.'&show_all='.$show_all;

		$pageoutput[ ] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_BACKEND);
		$tmpl->readTemplatesFromInput('list_guests.html');
		$tmpl->addRows('pageoutput', $pageoutput);
		if ($output_now) {
			$tmpl->displayParsedTemplate();
		} else {
			$this->retVals = $tmpl->getParsedTemplate();
		}
	}

	public function touch_template_language()
	{
		$output = array();

		$output[ ] = jr_gettext('_CASTOR_FRONT_MR_MENU_ADMIN_GUESTADMIN', '_CASTOR_FRONT_MR_MENU_ADMIN_GUESTADMIN');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_TOWN', '_CASTOR_COM_MR_DISPGUEST_TOWN');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_EDITDETAILS', '_CASTOR_COM_MR_DISPGUEST_EDITDETAILS');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_FIRSTNAME', '_CASTOR_COM_MR_DISPGUEST_FIRSTNAME');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_SURNAME', '_CASTOR_COM_MR_DISPGUEST_SURNAME');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_HOUSE', '_CASTOR_COM_MR_DISPGUEST_HOUSE');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_STREET', '_CASTOR_COM_MR_DISPGUEST_STREET');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_TOWN', '_CASTOR_COM_MR_DISPGUEST_TOWN');

		foreach ($output as $o) {
			echo $o;
			echo '<br/>';
		}
	}


	public function getRetVals()
	{
		return $this->retVals;
	}
}

