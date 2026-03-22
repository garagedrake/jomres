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

class j06001amendBooking
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
			$this->template_touchable = true;

			return;
		}
		$thisJRUser = castor_singleton_abstract::getInstance('jr_user');
		if (!$thisJRUser->userIsManager) {
			return;
		}

		$defaultProperty = getDefaultProperty();
		$contract_uid = intval(castorGetParam($_REQUEST, 'contractUid', 0));

		if (!can_modify_this_booking((int) $contract_uid)) {
			return;
		}

		if ($contract_uid > 0) {
			$tmpArray = array();

			$basic_property_details = castor_singleton_abstract::getInstance('basic_property_details');

			$basic_property_details->gather_data_multi($thisJRUser->authorisedProperties);
			$current_property_property_type = $basic_property_details->multi_query_result[$defaultProperty]['ptype_id'];
			
			foreach ($thisJRUser->authorisedProperties as $p) {
				if ($basic_property_details->multi_query_result[$p]['ptype_id'] == $current_property_property_type) {
					$obj = new stdClass();
					$obj->propertys_uid = $p;
					$obj->property_name =$basic_property_details->multi_query_result[$p]['property_name'];
					$tmpArray[ ] = $obj;
				}
			}
			$propertysList = $tmpArray;
			if (count($propertysList) == 1) {
				castorRedirect(get_booking_url($defaultProperty, '', '&amend=1&contractuid='.$contract_uid), '');
			}
			
			$counter = 0;
			foreach ($propertysList as $property) {
				if ($counter == 0) {
					$thisProperty = $property->propertys_uid;
				}
				++$counter;
				$pname = $property->property_name;
				$propertyOptions[ ] = castorHTML::makeOption($property->propertys_uid, stripslashes($pname));
			}

			$propertyDropdown = castorHTML::selectList($propertyOptions, 'selectedProperty', '', 'value', 'text', $defaultProperty);

			$output[ 'PROPERTYDROPDOWN' ] = ''.$propertyDropdown.'';

			$cancelText = jr_gettext('_CASTOR_COM_A_CANCEL', '_CASTOR_COM_A_CANCEL', false);
			$jrtbar = castor_singleton_abstract::getInstance('castor_toolbar');
			$jrtb = $jrtbar->startTable();
			$jrtb .= $jrtbar->toolbarItem('cancel', castorURL(CASTOR_SITEPAGE_URL.'&task=edit_booking&contract_uid='.$contract_uid), $cancelText);
			$jrtb .= $jrtbar->endTable();
			$output[ 'CASTORTOOLBAR' ] = $jrtb;

			$output[ 'PAGETITLE' ] = jr_gettext('_JOMCOMP_AMEND', '_JOMCOMP_AMEND');
			$output[ 'SELECTPROPERTY' ] = jr_gettext('_JOMCOMP_AMEND_SELECTPROPERTY', '_JOMCOMP_AMEND_SELECTPROPERTY');
			$output[ 'CONTRACTUID' ] = $contract_uid;
			$output[ '_CASTOR_CONFIRMATION_AMEND' ] = jr_gettext('_CASTOR_CONFIRMATION_AMEND', '_CASTOR_CONFIRMATION_AMEND');
			
			$output[ 'BOOKING_FORM_URL' ] = get_booking_url($defaultProperty, 'nosef');
			
			
			$pageoutput[ ] = $output;
			$tmpl = new patTemplate();

			$tmpl->setRoot(CASTOR_TEMPLATEPATH_BACKEND);
			$tmpl->readTemplatesFromInput('amend_booking.html');
			$tmpl->addRows('pageoutput', $pageoutput);
			$tmpl->displayParsedTemplate();
		} else {
			echo 'Incorrect contract uid';
		}
	}

	public function touch_template_language()
	{
		$output = array();

		$output[ ] = jr_gettext('_JOMCOMP_AMEND', '_JOMCOMP_AMEND');
		$output[ ] = jr_gettext('_JOMCOMP_AMEND_SELECTPROPERTY', '_JOMCOMP_AMEND_SELECTPROPERTY');
		$output[ ] = jr_gettext('_CASTOR_COM_A_CANCEL', '_CASTOR_COM_A_CANCEL');

		foreach ($output as $o) {
			echo $o;
			echo '<br/>';
		}
	}

	/**
	 * Must be included in every mini-component.
	 #
	 * Returns any settings that the mini-component wants to send back to the calling script. In addition to being returned to the calling script they are put into an array in the mcHandler object as eg. $mcHandler->miniComponentData[$ePoint][$eName]
	 */
	public function getRetVals()
	{
		return null;
	}
}

