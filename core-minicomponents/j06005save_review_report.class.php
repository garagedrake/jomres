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

class j06005save_review_report
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

		$rating_id = (int) $_REQUEST[ 'rating_id' ];
		$report = castorGetParam($_REQUEST, 'report', '');

		if ($jrConfig[ 'use_reviews' ] == '1' && $rating_id > 0 && $report != '') {
			jr_import('castor_reviews');
			$Reviews = new castor_reviews();
			$Reviews->save_review_report($rating_id, $report);
			$property_uid = $Reviews->get_property_uid_for_rating_id($rating_id);

			$property_name = getPropertyName($property_uid);
			$subject = jr_gettext('CASTOR_NEWREPORT_SUBJECT', 'CASTOR_NEWREPORT_SUBJECT', false).' '.$property_name;
			$message = jr_gettext('CASTOR_NEWREPORT_MESSAGE', 'CASTOR_NEWREPORT_MESSAGE', false).' '.$property_name.'  '.CASTOR_SITEPAGE_URL_ADMIN.'&task=view_property_reviews&property_uid='.(int) $property_uid.'  <br/><br/>';
			sendAdminEmail($subject, $message);

			castorRedirect(get_property_details_url($property_uid), '');
			exit;
		}
	}

	public function getRetVals()
	{
		return $this->retVals;
	}
}

