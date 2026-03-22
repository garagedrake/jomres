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

class j06001list_bookings
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

		$mrConfig = getPropertySpecificSettings();
		$defaultProperty = getDefaultProperty();
		
		$deposit_status = (int) castorGetParam($_POST, 'deposit_status', '2');
		$resident_status = (int) castorGetParam($_POST, 'resident_status', '2');
		$booking_status = (int) castorGetParam($_POST, 'booking_status', '2');
		$show_all = (int) castorGetParam($_POST, 'show_all', '0');
		$tag = (int) castorGetParam($_POST, 'tag', '0');
		$guest_uid = (int) castorGetParam($_REQUEST, 'guest_uid', '0');

		$startDate = castorGetParam($_POST, 'startDate', '');
		$endDate = castorGetParam($_POST, 'endDate', '');
		if ($startDate == '%' || $startDate == '') {
			$startDate = date('Y/m/d', strtotime('-5 years'));
		} else {
			$startDate = JSCalConvertInputDates($startDate);
		}
		if ($endDate == '%' || $endDate == '') {
			$endDate = date('Y/m/d', strtotime('+5 years'));
		} else {
			$endDate = JSCalConvertInputDates($endDate);
		}
		
		if (isset($componentArgs[ 'output_now' ])) {
			$output_now = $componentArgs[ 'output_now' ];
		} else {
			$output_now = true;
		}

		$output = array();
		$output[ 'PAGETITLE' ] = jr_gettext('_CASTOR_STATUS_BOOKINGS', '_CASTOR_STATUS_BOOKINGS', false);

		$output[ 'TEXT_PENDING' ] = $mrConfig[ 'wholeday_booking' ] == '1' ? jr_gettext('_CASTOR_COM_MR_VIEWBOOKINGS_PENDING_WHOLEDAY', '_CASTOR_COM_MR_VIEWBOOKINGS_PENDING_WHOLEDAY') : jr_gettext('_CASTOR_COM_MR_VIEWBOOKINGS_PENDING', '_CASTOR_COM_MR_VIEWBOOKINGS_PENDING');
		$output[ 'TEXT_ARRIVETODAY' ] = $mrConfig[ 'wholeday_booking' ] == '1' ? jr_gettext('_CASTOR_COM_MR_VIEWBOOKINGS_ARRIVETODAY_WHOLEDAY', '_CASTOR_COM_MR_VIEWBOOKINGS_ARRIVETODAY_WHOLEDAY') : jr_gettext('_CASTOR_COM_MR_VIEWBOOKINGS_ARRIVETODAY', '_CASTOR_COM_MR_VIEWBOOKINGS_ARRIVETODAY');
		$output[ 'TEXT_RESIDENT' ] = $mrConfig[ 'wholeday_booking' ] == '1' ? jr_gettext('_CASTOR_COM_MR_VIEWBOOKINGS_RESIDENT_WHOLEDAY', '_CASTOR_COM_MR_VIEWBOOKINGS_RESIDENT_WHOLEDAY') : jr_gettext('_CASTOR_COM_MR_VIEWBOOKINGS_RESIDENT', '_CASTOR_COM_MR_VIEWBOOKINGS_RESIDENT');
		$output[ 'TEXT_LATE' ] = $mrConfig[ 'wholeday_booking' ] == '1' ? jr_gettext('_CASTOR_COM_MR_VIEWBOOKINGS_LATE_WHOLEDAY', '_CASTOR_COM_MR_VIEWBOOKINGS_LATE_WHOLEDAY') : jr_gettext('_CASTOR_COM_MR_VIEWBOOKINGS_LATE', '_CASTOR_COM_MR_VIEWBOOKINGS_LATE');
		$output[ 'TEXT_DEPARTTODAY' ] = $mrConfig[ 'wholeday_booking' ] == '1' ? jr_gettext('_CASTOR_COM_MR_VIEWBOOKINGS_DEPARTTODAY_WHOLEDAY', '_CASTOR_COM_MR_VIEWBOOKINGS_DEPARTTODAY_WHOLEDAY') : jr_gettext('_CASTOR_COM_MR_VIEWBOOKINGS_DEPARTTODAY', '_CASTOR_COM_MR_VIEWBOOKINGS_DEPARTTODAY');
		$output[ 'TEXT_STILLHERE' ] = $mrConfig[ 'wholeday_booking' ] == '1' ? jr_gettext('_CASTOR_COM_MR_VIEWBOOKINGS_STILLHERE_WHOLEDAY', '_CASTOR_COM_MR_VIEWBOOKINGS_STILLHERE_WHOLEDAY') : jr_gettext('_CASTOR_COM_MR_VIEWBOOKINGS_STILLHERE', '_CASTOR_COM_MR_VIEWBOOKINGS_STILLHERE');
		$output[ 'TEXT_BOOKEDOUT' ] = jr_gettext('_CASTOR_STATUS_CHECKEDOUT', '_CASTOR_STATUS_CHECKEDOUT', false);
		$output[ 'TEXT_CANCELLED' ] = jr_gettext('_CASTOR_STATUS_CANCELLED', '_CASTOR_STATUS_CANCELLED', false);

		$output[ '_CASTOR_COM_MR_VIEWBOOKINGS_STATUS' ] = jr_gettext('_CASTOR_COM_MR_VIEWBOOKINGS_STATUS', '_CASTOR_COM_MR_VIEWBOOKINGS_STATUS', false);
		$output[ '_CASTOR_COM_MR_VIEWBOOKINGS_FIRSTNAME' ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_FIRSTNAME', '_CASTOR_COM_MR_DISPGUEST_FIRSTNAME', false);
		$output[ '_CASTOR_COM_MR_VIEWBOOKINGS_SURNAME' ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_SURNAME', '_CASTOR_COM_MR_DISPGUEST_SURNAME', false);
		$output[ '_CASTOR_COM_MR_VIEWBOOKINGS_EMAIL' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_EMAIL', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_EMAIL', false);
		$output[ '_CASTOR_COM_MR_EDITBOOKINGTITLE' ] = jr_gettext('_CASTOR_COM_MR_EDITBOOKINGTITLE', '_CASTOR_COM_MR_EDITBOOKINGTITLE', false);
		$output[ '_CASTOR_BOOKING_NUMBER' ] = jr_gettext('_CASTOR_BOOKING_NUMBER', '_CASTOR_BOOKING_NUMBER', true, false);
		$output[ 'HPROPERTY_NAME' ] = jr_gettext('_JRPORTAL_PROPERTIES_PROPERTYNAME', '_JRPORTAL_PROPERTIES_PROPERTYNAME', false, false);
		if ($mrConfig[ 'wholeday_booking' ] == '1') {
			$output[ 'ARRIVAL' ] = jr_gettext('_CASTOR_COM_MR_VIEWBOOKINGS_ARRIVAL_WHOLEDAY', '_CASTOR_COM_MR_VIEWBOOKINGS_ARRIVAL_WHOLEDAY', false);
			$output[ 'DEPARTURE' ] = jr_gettext('_CASTOR_COM_MR_VIEWBOOKINGS_DEPARTURE_WHOLEDAY', '_CASTOR_COM_MR_VIEWBOOKINGS_DEPARTURE_WHOLEDAY', false);
		} else {
			$output[ 'ARRIVAL' ] = jr_gettext('_CASTOR_COM_MR_VIEWBOOKINGS_ARRIVAL', '_CASTOR_COM_MR_VIEWBOOKINGS_ARRIVAL', false);
			if ($mrConfig[ 'showdepartureinput' ] == '1') {
				$output[ 'DEPARTURE' ] = jr_gettext('_CASTOR_COM_MR_VIEWBOOKINGS_DEPARTURE', '_CASTOR_COM_MR_VIEWBOOKINGS_DEPARTURE', false);
			} else {
				$output[ 'DEPARTURE' ] = '&nbsp;';
			}
		}
		$output[ 'HCONTRACT_TOTAL' ] = jr_gettext('_CASTOR_COM_MR_QUICKRES_STEP4_TOTALINVOICE', '_CASTOR_COM_MR_QUICKRES_STEP4_TOTALINVOICE', false);
		$output[ 'HDEPOSIT_REQUIRED' ] = jr_gettext('_CASTOR_COM_MR_EB_PAYM_DEPOSITREQUIRED', '_CASTOR_COM_MR_EB_PAYM_DEPOSITREQUIRED', false);
		$output[ 'HCONTRACT_UID' ] = 'Uid';
		$output[ 'HGUEST_LANDLINE' ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_LANDLINE', '_CASTOR_COM_MR_DISPGUEST_LANDLINE', false);
		$output[ 'HGUEST_MOBILE' ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_MOBILE', '_CASTOR_COM_MR_DISPGUEST_MOBILE', false);
		$output[ 'HINVOICE_UID' ] = jr_gettext('_JRPORTAL_LISTBOOKINGS_HEADER_INVOICE_ID', '_JRPORTAL_LISTBOOKINGS_HEADER_INVOICE_ID', false);
		$output[ 'HSPECIAL_REQS' ] = jr_gettext('_CASTOR_COM_MR_EB_ROOM_BOOKINGSPECIALREQ', '_CASTOR_COM_MR_EB_ROOM_BOOKINGSPECIALREQ', false);
		$output[ 'HTIMESTAMP' ] = jr_gettext('_CASTOR_HDATE_OF_BOOKING', '_CASTOR_HDATE_OF_BOOKING', false);
		$output[ 'HAPPROVED' ] = jr_gettext('_CASTOR_BOOKING_INQUIRY_HAPPROVAL', '_CASTOR_BOOKING_INQUIRY_HAPPROVAL', false);
		$output[ 'HLASTCHANGED' ] = jr_gettext('_CASTOR_HLASTCHANGED', '_CASTOR_HLASTCHANGED', false);
		$output[ 'HMADE_BY' ] = jr_gettext('BOOKING_MADE_BY', 'BOOKING_MADE_BY', false);
		$output[ 'HLEGEND' ] = jr_gettext('_CASTOR_HLEGEND', '_CASTOR_HLEGEND', false);
		$output[ '_CASTOR_REFERRER' ] = jr_gettext('_CASTOR_REFERRER', '_CASTOR_REFERRER', false);

		
		if (get_showtime('task') == 'list_bookings') {
			$output[ 'TOUR_DIV_ID' ] = 'tour_target_listall_bookings';
		}

		//buttons
		$output['HNEW_BOOKING'] = jr_gettext('_CASTOR_HNEW_BOOKING', '_CASTOR_HNEW_BOOKING', false);
		$output['NEW_BOOKING_URL'] = get_booking_url($defaultProperty);
		$output['HBLACK_BOOKINGS'] = jr_gettext('_CASTOR_FRONT_BLACKBOOKING', '_CASTOR_FRONT_BLACKBOOKING', false);
		$output['BLACK_BOOKINGS_URL'] = castorUrl(CASTOR_SITEPAGE_URL.'&task=list_black_bookings');
		$output['HSIMPLE_BOOKING'] = jr_gettext('_CASTOR_HQUICK_BOOKING', '_CASTOR_HQUICK_BOOKING', false);
		$output['SIMPLE_BOOKING_URL'] = castorUrl(CASTOR_SITEPAGE_URL.'&task=easy_blackbook');

		//filters
		$output['HFILTER'] = jr_gettext('_CASTOR_HFILTER', '_CASTOR_HFILTER', false);
		$output['HSTART'] = jr_gettext('_CASTOR_HFROM', '_CASTOR_HFROM', false);
		$output['HEND'] = jr_gettext('_CASTOR_HTO', '_CASTOR_HTO', false);
		$output['HDEPOSIT_STATUS'] = jr_gettext('_CASTOR_HSTATUS_DEPOSIT', '_CASTOR_HSTATUS_DEPOSIT', false);
		$output['HRESIDENT_STATUS'] = jr_gettext('_CASTOR_HSTATUS_GUEST', '_CASTOR_HSTATUS_GUEST', false);
		$output['HBOOKING_STATUS'] = jr_gettext('_CASTOR_HSTATUS_BOOKING', '_CASTOR_HSTATUS_BOOKING', false);
		$output['HSHOW_ALL'] = jr_gettext('_CASTOR_HSTATUS_SHOW_BOOKINGS_FOR', '_CASTOR_HSTATUS_SHOW_BOOKINGS_FOR', false);

		$output[ 'START' ] = generateDateInput('startDate', $startDate, false, true, true);
		$output[ 'END' ] = generateDateInput('endDate', $endDate, false, true, true);

		$options = array();
		$options[] = castorHTML::makeOption('2', jr_gettext('_CASTOR_STATUS_ANY', '_CASTOR_STATUS_ANY', false));
		$options[] = castorHTML::makeOption('1', jr_gettext('_CASTOR_STATUS_PAID', '_CASTOR_STATUS_PAID', false));
		$options[] = castorHTML::makeOption('0', jr_gettext('_CASTOR_STATUS_NOTPAID', '_CASTOR_STATUS_NOTPAID', false));
		$output['DEPOSIT_STATUS'] = castorHTML::selectList($options, 'deposit_status', '', 'value', 'text', $deposit_status);

		$options = array();
		$options[] = castorHTML::makeOption('2', jr_gettext('_CASTOR_STATUS_ANY', '_CASTOR_STATUS_ANY', false));
		$options[] = castorHTML::makeOption('3', jr_gettext('_CASTOR_COM_MR_VIEWBOOKINGS_PENDING', '_CASTOR_COM_MR_VIEWBOOKINGS_PENDING', false));
		$options[] = castorHTML::makeOption('1', jr_gettext('_CASTOR_COM_MR_VIEWBOOKINGS_RESIDENT', '_CASTOR_COM_MR_VIEWBOOKINGS_RESIDENT', false));
		$options[] = castorHTML::makeOption('0', jr_gettext('_CASTOR_STATUS_CHECKEDOUT', '_CASTOR_STATUS_CHECKEDOUT', false));
		$output['RESIDENT_STATUS'] = castorHTML::selectList($options, 'resident_status', '', 'value', 'text', $resident_status);

		$options = array();
		$options[] = castorHTML::makeOption('2', jr_gettext('_CASTOR_STATUS_ANY', '_CASTOR_STATUS_ANY', false));
		$options[] = castorHTML::makeOption('0', jr_gettext('_CASTOR_STATUS_ACTIVE', '_CASTOR_STATUS_ACTIVE', false));
		$options[] = castorHTML::makeOption('1', jr_gettext('_CASTOR_STATUS_CANCELLED', '_CASTOR_STATUS_CANCELLED', false));
		$output['BOOKING_STATUS'] = castorHTML::selectList($options, 'booking_status', '', 'value', 'text', $booking_status);

		$options = array();
		$options[] = castorHTML::makeOption('0', jr_gettext('_CASTOR_HSTATUS_CURRENT', '_CASTOR_HSTATUS_CURRENT', false));
		$options[] = castorHTML::makeOption('1', jr_gettext('_CASTOR_STATUS_ALL_PROPERTIES', '_CASTOR_STATUS_ALL_PROPERTIES', false));
		$output['SHOW_ALL'] = castorHTML::selectList($options, 'show_all', '', 'value', 'text', $show_all);

		$output['AJAX_URL'] = CASTOR_SITEPAGE_URL_AJAX.'&task=list_bookings_ajax&startDate='.$startDate.'&endDate='.$endDate.'&deposit_status='.$deposit_status.'&resident_status='.$resident_status.'&booking_status='.$booking_status.'&show_all='.$show_all.'&tag='.$tag.'&guest_uid='.$guest_uid;

		$thisJRUser = castor_singleton_abstract::getInstance('jr_user');

		$pageoutput[ ] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_BACKEND);
		$tmpl->readTemplatesFromInput('list_property_bookings.html');
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

		$output[ ] = jr_gettext('_CASTOR_COM_MR_EDITBOOKING_ADMIN_TITLE', '_CASTOR_COM_MR_EDITBOOKING_ADMIN_TITLE');

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

