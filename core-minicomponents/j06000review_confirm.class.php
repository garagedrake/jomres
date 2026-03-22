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

class j06000review_confirm
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
		$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
		$jrConfig = $siteConfig->get();

		$rating_id = (int) $_GET[ 'rating_id' ];
		$state = (int) $_GET[ 'state' ];

		if ($jrConfig[ 'use_reviews' ] == '1' && $rating_id > 0) {
			$string = '';

			jr_import('castor_reviews');
			$Reviews = new castor_reviews();

			$property_uid = $Reviews->get_property_uid_for_rating_id($rating_id);
			
			if ($property_uid == 0) { // This definately isn't right. Just return without doing anything else.
				return;
			}
			
			$Reviews->property_uid = $property_uid;

			if ($Reviews->checkConfirmUniqueIp($rating_id) != 0) {
				echo '0';
				return;
			}

			$Reviews->save_confirmation($rating_id, $state);
			echo '1';
			return;
		}
	}

	public function getRetVals()
	{
		return $this->retVals;
	}
}

