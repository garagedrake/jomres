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

class j11040markers
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
		
		$this->ret_vals = array();
		
		$resource_type = castorGetParam($_REQUEST, 'resource_type', '');

		$castor_media_centre_images = castor_singleton_abstract::getInstance('castor_media_centre_images');
		$castor_media_centre_images->get_site_images($resource_type);
		
		if (isset($castor_media_centre_images->site_images [$resource_type])) {
			$this->ret_vals = $castor_media_centre_images->site_images [$resource_type];
		} else {
			$this->ret_vals = array();
		}
	}


	public function getRetVals()
	{
		return $this->ret_vals;
	}
}

