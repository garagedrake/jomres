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

class j16000get_region_dropdown_for_country_code
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
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = false;

			return;
		}
		$countryCode = castorGetParam($_REQUEST, 'country', '');
		$currentRegion = castorGetParam($_REQUEST, 'region', '');
		$input_name = castorGetParam($_REQUEST, 'input_name', '');

		if ($input_name == '') {
			$input_name = 'region';
		}
		$dropdown = setupRegions($countryCode, $currentRegion, false, $input_name);

		// Might need this for required fields, not sure yet. Will leave it in but commented for now (15/07/2013)
/* 		if ($dropdown == '');
			{
			$dropdown = '<input type="hidden" id="'.$input_name.'" name="'.$input_name.'" value="">';
			} */
		echo $dropdown;
	}


	public function getRetVals()
	{
		return null;
	}
}

