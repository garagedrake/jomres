<?php
/**
 * Core file
 *
 * @author Vince Wooll <sales@castor.net>
 *  @version Castor 10.7.2
 * @package Castor
 * @copyright	2005-2016 Vince Wooll
 * Castor (tm) PHP, CSS & Javascript files are released under both MIT and GPL2 licenses. This means that you can choose the license that best suits your project, and use it accordingly.
 **/

// ################################################################
defined('_CASTOR_INITCHECK') or die('');
// ################################################################
	#[AllowDynamicProperties]
class j06002publishcustomertype
{
	function __construct()
	{
		// Must be in all minicomponents. Minicomponents with templates that can contain editable text should run $this->template_touch() else just return
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = false;
			return;
		}
		
		$defaultProperty = (int)getDefaultProperty();
		
		$id = (int)castorGetParam($_REQUEST, 'id', 0);
		
		jr_import('jrportal_guest_types');
		$jrportal_guest_types = new jrportal_guest_types();
		$jrportal_guest_types->id = $id;
		$jrportal_guest_types->property_uid	= $defaultProperty;
		
		$jrportal_guest_types->publish_guest_type();
		
		castorRedirect(castorURL(CASTOR_SITEPAGE_URL."&task=listcustomertypes"), '');
	}

	// This must be included in every Event/Mini-component
	function getRetVals()
	{
		return null;
	}
}

