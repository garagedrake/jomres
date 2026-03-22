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

class j11020property_types
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
			$this->template_touchable = false;

			return;
		}
		$thisJRUser = castor_singleton_abstract::getInstance('jr_user');
		if (!$thisJRUser->userIsManager) {
			return;
		}

		$this->ret_vals = '';

		$castor_property_types = castor_singleton_abstract::getInstance('castor_property_types');
		$castor_property_types->get_all_property_types();

		if (!empty($castor_property_types->property_types)) {
			$resource_options = array();
			foreach ($castor_property_types->property_types as $ptype) {
				$resource_options[ ] = castorHTML::makeOption($ptype['id'], $ptype['ptype']);
			}
			$use_bootstrap_radios = false;
			$dropdown = castorHTML::selectList($resource_options, 'resource_id', ' autocomplete="off" ', 'value', 'text', '', $use_bootstrap_radios);
		}
		$this->ret_vals = $dropdown;
	}


	public function getRetVals()
	{
		return $this->ret_vals;
	}
}

