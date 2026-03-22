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
	 * Media centre uses this to find images that already exist for the rooms image resource
	 *
	 */

class j03383rooms
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

		$defaultProperty = getDefaultProperty();
		$resource_type = castorGetParam($_REQUEST, 'resource_type', '');
		$resource_id = castorGetParam($_REQUEST, 'resource_id', '0');

		$castor_media_centre_images = castor_singleton_abstract::getInstance('castor_media_centre_images');
		$castor_media_centre_images->get_images($defaultProperty);
		if (isset($castor_media_centre_images->images [$resource_type] [$resource_id])) {
			$this->ret_vals = $castor_media_centre_images->images [$resource_type] [$resource_id];
		} else {
			$this->ret_vals = array();
		}
	}


	/**
	 * @return array
	 */
	public function getRetVals()
	{
		return $this->ret_vals;
	}
}

