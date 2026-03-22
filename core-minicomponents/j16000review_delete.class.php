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

class j16000review_delete
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

		$rating_id = (int) $_GET[ 'rating_id' ];
		set_showtime('property_uid', (int)$_REQUEST['property_uid']);

		if ($rating_id > 0) {
			jr_import('castor_reviews');
			$Reviews = new castor_reviews();
			$Reviews->property_uid = (int)$_REQUEST['property_uid'];
			$result = $Reviews->delete_review($rating_id);
			if ($result) {
				echo '1';

				return;
			}
			echo '0';

			return;
		}
	}

	public function getRetVals()
	{
		return null;
	}
}

