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

class j06000listproperties
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
		$MiniComponents = castor_getSingleton('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = false;

			return;
		}
		
		$tmpBookingHandler = castor_singleton_abstract::getInstance('castor_temp_booking_handler');
		
		$componentArgs = array();
		
		$MiniComponents->triggerEvent('01004', $componentArgs); // optional
		$MiniComponents->triggerEvent('01005', $componentArgs); // optional
		$MiniComponents->triggerEvent('01006', $componentArgs); // optional
		$MiniComponents->triggerEvent('01007', $componentArgs); // optional

		if (isset($tmpBookingHandler->tmpsearch_data[ 'ajax_list_search_results' ]) && !empty($tmpBookingHandler->tmpsearch_data[ 'ajax_list_search_results' ])) {
			$componentArgs[ 'propertys_uid' ] = $tmpBookingHandler->tmpsearch_data[ 'ajax_list_search_results' ];
		} else {
			$componentArgs[ 'propertys_uid' ] = get_showtime('published_properties_in_system');
		}
		
		$MiniComponents->triggerEvent('01010', $componentArgs); // listPropertys
	}


	public function getRetVals()
	{
		return null;
	}
}

