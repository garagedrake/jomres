<?php
/**
* Castor CMS Agnostic Plugin
* @author Woollyinwales IT <sales@castor.net>
* @version Castor 9 
* @package Castor
* @copyright	2005-2022 Woollyinwales IT
* Castor (tm) PHP files are released under both MIT and GPL2 licenses. This means that you can choose the license that best suits your project.
**/

// ################################################################
defined( '_CASTOR_INITCHECK' ) or die( 'Direct Access to this file is not allowed.' );
// ################################################################
	#[AllowDynamicProperties]

class j00605stripe_standard {
	function __construct($componentArgs)
		{
		// Must be in all minicomponents. Minicomponents with templates that can contain editable text should run $this->template_touch() else just return
		$MiniComponents =castor_getSingleton('mcHandler');
		if ($MiniComponents->template_touch)
			{
			$this->template_touchable=false; return;
			}
			$tmpBookingHandler = castor_singleton_abstract::getInstance( 'castor_temp_booking_handler' );
			castorRedirect( castorURL(CASTOR_SITEPAGE_URL."&task=stripe_standard_redirect&jsid=".$tmpBookingHandler->castorsession) ,"" );
		}

	function getRetVals() {
		return null;
		}
	}


