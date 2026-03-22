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

class j06005list_invoices
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
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = false;

			return;
		}
		
		$this->retVals = '';

		$thisJRUser = castor_singleton_abstract::getInstance('jr_user');

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

		//status values are as follows
		//0 unpaid
		//1 paid
		//2 cancelled
		//3 pending
		//4 any
		if (isset($_REQUEST['invoice_status'])) {
			$invoice_status = (int) castorGetParam($_REQUEST, 'invoice_status', '4');
		} else {
			$invoice_status = (int) castorGetParam($_POST, 'invoice_status', '4');
		}
		$invoice_type = (int) castorGetParam($_POST, 'invoice_type', '0');
		$guest_id = (int) castorGetParam($_REQUEST, 'guest_id', '0');
		$show_all = (int) castorGetParam($_POST, 'show_all', '0');
		
		if (isset($componentArgs[ 'output_now' ])) {
			$output_now = $componentArgs[ 'output_now' ];
		} else {
			$output_now = true;
		}

		$output = array();
		$pageoutput = array();

		$output[ 'PAGETITLE' ] = jr_gettext('_JRPORTAL_INVOICES_TITLE', '_JRPORTAL_INVOICES_TITLE', false);
		$output[ 'HBOOKINGNO' ] = jr_gettext('_CASTOR_BOOKING_NUMBER', '_CASTOR_BOOKING_NUMBER', false);
		$output[ 'HPROPERTY_NAME' ] = jr_gettext('_JRPORTAL_PROPERTIES_PROPERTYNAME', '_JRPORTAL_PROPERTIES_PROPERTYNAME', false);
		$output[ 'HFIRSTNAME' ] = jr_gettext('_CASTOR_COM_MR_VIEWBOOKINGS_SURNAME', '_CASTOR_COM_MR_VIEWBOOKINGS_SURNAME', false);
		$output[ 'HSURNAME' ] = jr_gettext('_CASTOR_FRONT_MR_DISPGUEST_SURNAME', '_CASTOR_FRONT_MR_DISPGUEST_SURNAME', false);
		$output[ 'HSTATUS' ] = jr_gettext('_JRPORTAL_INVOICES_STATUS', '_JRPORTAL_INVOICES_STATUS', false);
		$output[ 'HRAISED' ] = jr_gettext('_JRPORTAL_INVOICES_RAISED', '_JRPORTAL_INVOICES_RAISED', false);
		$output[ 'HDUE' ] = jr_gettext('_JRPORTAL_INVOICES_DUE', '_JRPORTAL_INVOICES_DUE', false);
		$output[ 'HPAID' ] = jr_gettext('_JRPORTAL_INVOICES_STATUS_PAID', '_JRPORTAL_INVOICES_STATUS_PAID', false);
		$output[ 'HINITTOTAL' ] = jr_gettext('_JRPORTAL_INVOICES_INITTOTAL', '_JRPORTAL_INVOICES_INITTOTAL', false);
		$output[ 'HGRAND_TOTAL' ] = jr_gettext('_CASTOR_COM_INVOICE_LETTER_GRANDTOTAL', '_CASTOR_COM_INVOICE_LETTER_GRANDTOTAL', false);
		$output[ 'HLINEITEMS' ] = jr_gettext('_JRPORTAL_INVOICES_LINEITEMS', '_JRPORTAL_INVOICES_LINEITEMS', false);
		$output[ 'HEDITLINK' ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_EDITDETAILS', '_CASTOR_COM_MR_DISPGUEST_EDITDETAILS', false);
		$output[ 'HLEGEND' ] = jr_gettext('_CASTOR_HLEGEND', '_CASTOR_HLEGEND', false);
		$output[ 'HUNPAID' ] = jr_gettext('_JRPORTAL_INVOICES_STATUS_UNPAID', '_JRPORTAL_INVOICES_STATUS_UNPAID', false);
		$output[ 'HPAID' ] = jr_gettext('_JRPORTAL_INVOICES_STATUS_PAID', '_JRPORTAL_INVOICES_STATUS_PAID', false);
		$output[ 'HCANCELLED' ] = jr_gettext('_JRPORTAL_INVOICES_STATUS_CANCELLED', '_JRPORTAL_INVOICES_STATUS_CANCELLED', false);
		$output[ 'HPENDING' ] = jr_gettext('_JRPORTAL_INVOICES_STATUS_PENDING', '_JRPORTAL_INVOICES_STATUS_PENDING', false);
		$output[ '_JRPORTAL_INVOICES_LINEITEMS' ] = jr_gettext('_JRPORTAL_INVOICES_LINEITEMS', '_JRPORTAL_INVOICES_LINEITEMS', false);
		$output[ 'HPAYNOW' ] = jr_gettext('_JRPORTAL_INVOICES_PAYNOW', '_JRPORTAL_INVOICES_PAYNOW', false);
		$output[ '_CASTOR_INVOICE_NUMBER' ] = jr_gettext('_CASTOR_INVOICE_NUMBER', '_CASTOR_INVOICE_NUMBER', false);

		if (!using_bootstrap()) {
			$output[ 'TASK_FILTER_ANY' ] = '<a href="'.CASTOR_SITEPAGE_URL.'&task=list_invoices">'.jr_gettext('_CASTOR_FRONT_ROOMSMOKING_EITHER', '_CASTOR_FRONT_ROOMSMOKING_EITHER', false).'</a>';
			$output[ 'TASK_FILTER_UNPAID' ] = '<a href="'.CASTOR_SITEPAGE_URL.'&task=list_invoices&invoice_status=0">'.$output[ 'HUNPAID' ].'</a>';
			$output[ 'TASK_FILTER_PAID' ] = '<a href="'.CASTOR_SITEPAGE_URL.'&task=list_invoices&invoice_status=1">'.$output[ 'HPAID' ].'</a>';
			$output[ 'TASK_FILTER_CANCELLED' ] = '<a href="'.CASTOR_SITEPAGE_URL.'&task=list_invoices&invoice_status=2">'.$output[ 'HCANCELLED' ].'</a>';
			$output[ 'TASK_FILTER_PENDING' ] = '<a href="'.CASTOR_SITEPAGE_URL.'&task=list_invoices&invoice_status=3">'.$output[ 'HPENDING' ].'</a>';
		}

		//filters
		$output['HFILTER'] = jr_gettext('_CASTOR_HFILTER', '_CASTOR_HFILTER', false);
		$output['HINVOICE_STATUS'] = jr_gettext('_CASTOR_HSTATUS_INVOICE', '_CASTOR_HSTATUS_INVOICE', false);
		$output['HINVOICE_TYPE'] = jr_gettext('_CASTOR_HSTATUS_INVOICE_TYPE', '_CASTOR_HSTATUS_INVOICE_TYPE', false);
		$output['HSTART'] = jr_gettext('_CASTOR_HFROM', '_CASTOR_HFROM', false);
		$output['HEND'] = jr_gettext('_CASTOR_HTO', '_CASTOR_HTO', false);

		$output[ 'START' ] = generateDateInput('startDate', $startDate, false, true, true);
		$output[ 'END' ] = generateDateInput('endDate', $endDate, false, true, true);

		$options = array();
		$options[] = castorHTML::makeOption('0', jr_gettext('_CASTOR_FRONT_ROOMSMOKING_EITHER', '_CASTOR_FRONT_ROOMSMOKING_EITHER', false));
		$options[] = castorHTML::makeOption('1', jr_gettext('_CASTOR_STATUS_BOOKINGS', '_CASTOR_STATUS_BOOKINGS', false));
		if ($thisJRUser->accesslevel > 50) { //higher than receptionist
			$options[] = castorHTML::makeOption('2', jr_gettext('_CASTOR_STATUS_COMMISSIONS', '_CASTOR_STATUS_COMMISSIONS', false));
		}
		if ($thisJRUser->userIsRegistered && $thisJRUser->accesslevel != 50) { //user is registered but other than receptionist
			$options[] = castorHTML::makeOption('3', jr_gettext('_CASTOR_STATUS_SUBSCRIPTIONS', '_CASTOR_STATUS_SUBSCRIPTIONS', false));
		}
		$output['INVOICE_TYPE'] = castorHTML::selectList($options, 'invoice_type', '', 'value', 'text', $invoice_type);

		$options = array();
		$options[] = castorHTML::makeOption('4', jr_gettext('_CASTOR_FRONT_ROOMSMOKING_EITHER', '_CASTOR_FRONT_ROOMSMOKING_EITHER', false));
		$options[] = castorHTML::makeOption('0', $output[ 'HUNPAID' ]);
		$options[] = castorHTML::makeOption('1', $output[ 'HPAID' ]);
		$options[] = castorHTML::makeOption('2', $output[ 'HCANCELLED' ]);
		$options[] = castorHTML::makeOption('3', $output[ 'HPENDING' ]);
		$output['INVOICE_STATUS'] = castorHTML::selectList($options, 'invoice_status', '', 'value', 'text', $invoice_status);

		if ($thisJRUser->userIsManager || $thisJRUser->superPropertyManager) {
			$output[ 'HSHOW_ALL' ] = jr_gettext('_CASTOR_HSTATUS_SHOW_INVOICES_FOR', '_CASTOR_HSTATUS_SHOW_INVOICES_FOR', false);
			$options = array();
			$options[] = castorHTML::makeOption('0', jr_gettext('_CASTOR_HSTATUS_CURRENT', '_CASTOR_HSTATUS_CURRENT', false));
			$options[] = castorHTML::makeOption('1', jr_gettext('_CASTOR_STATUS_ALL_PROPERTIES', '_CASTOR_STATUS_ALL_PROPERTIES', false));
			$output['SHOW_ALL'] = castorHTML::selectList($options, 'show_all', '', 'value', 'text', $show_all);
		}

		$output['GUEST_ID'] = $guest_id;

		$output['AJAX_URL'] = CASTOR_SITEPAGE_URL_AJAX.'&task=list_invoices_ajax&startDate='.$startDate.'&endDate='.$endDate.'&invoice_type='.$invoice_type.'&invoice_status='.$invoice_status.'&show_all='.$show_all.'&guest_id='.$guest_id;

		$pageoutput[] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_FRONTEND);
		$tmpl->readTemplatesFromInput('frontend_list_invoices.html');
		$tmpl->addRows('pageoutput', $pageoutput);
		if ($output_now) {
			$tmpl->displayParsedTemplate();
		} else {
			$this->retVals = $tmpl->getParsedTemplate();
		}
	}


	public function getRetVals()
	{
		return $this->retVals;
	}
}

