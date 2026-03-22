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

class j06000show_property_calendar
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
	 
	public function __construct($componentArgs = null)
	{
		// Must be in all minicomponents. Minicomponents with templates that can contain editable text should run $this->template_touch() else just return
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = false;
			$this->shortcode_data = array(
				'task' => 'show_property_calendar',
				'info' => '_CASTOR_SHORTCODES_06000SHOW_PROPERTY_CALENDAR',
				'arguments' => array(
					array(
						'argument' => 'property_uid',
						'arg_info' => '_CASTOR_SHORTCODES_06000SHOW_PROPERTY_CALENDAR_ARG_PROPERTY_UID',
						'arg_example' => '1',
						),
					array(
						'argument' => 'months_to_show',
						'arg_info' => '_CASTOR_SHORTCODES_06000SHOW_PROPERTY_CALENDAR_ARG_MONTHS_TO_SHOW',
						'arg_example' => '4',
						),
					array(
						'argument' => 'show_just_month',
						'arg_info' => '_CASTOR_SHORTCODES_06000SHOW_PROPERTY_CALENDAR_ARG_SHOW_JUST_MONTH',
						'arg_example' => '1',
						),
					),
				);

			return;
		}

		$this->retVals = '';

		$property_uid = (int) castorGetParam($_REQUEST, 'property_uid', '');
		if (isset($componentArgs ['property_uid'])) {
			$property_uid = (int) $componentArgs ['property_uid'];
		}

		$mrConfig = getPropertySpecificSettings($property_uid);

		if (!isset($componentArgs['output_now'])) {
			$componentArgs['output_now'] = true;
		}

		if (!user_can_view_this_property($property_uid)) {
			return;
		}

		if (!isset($_REQUEST['months_to_show'])) {
			$_REQUEST['months_to_show'] = 24;
		}

		if (!isset($_REQUEST['show_just_month'])) {
			$_REQUEST['show_just_month'] = false;
		}

		castor_set_page_title( $property_uid ,  jr_gettext('_CASTOR_FRONT_AVAILABILITY', '_CASTOR_FRONT_AVAILABILITY', false) );

		if ($mrConfig[ 'is_real_estate_listing' ] == 0) {
			if ($mrConfig[ 'singleRoomProperty' ] == 1) {
				$result = $MiniComponents->specificEvent('06000', 'srp_calendar', array('output_now' => $componentArgs['output_now'], 'property_uid' => $property_uid));
			} else {
				$result = $MiniComponents->specificEvent('06000', 'mrp_calendar', array('output_now' => $componentArgs['output_now'], 'property_uid' => $property_uid));
			}
		}

		if ($componentArgs['output_now'] == false) {
			$this->retVals = $result;
		}
	}

	public function getRetVals()
	{
		return $this->retVals;
	}
}

