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

class j06005save_review
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

		if (!isset($_GET[ 'property_uid' ])) {
			$property_uid = get_showtime('property_uid');
		} else {
			$property_uid = (int) $_POST[ 'property_uid' ];
		}

		$rating_1 = (int) castorGetParam($_POST, 'rating_1', 0);
		$rating_2 = (int) castorGetParam($_POST, 'rating_2', 0);
		$rating_3 = (int) castorGetParam($_POST, 'rating_3', 0);
		$rating_4 = (int) castorGetParam($_POST, 'rating_4', 0);
		$rating_5 = (int) castorGetParam($_POST, 'rating_5', 0);
		$rating_6 = (int) castorGetParam($_POST, 'rating_6', 0);
		
		$anonymise = (bool) castorGetParam($_POST, 'anonymise', false);

		$review_title = castorGetParam($_POST, 'review_title', '');
		$review_description = castorGetParam($_POST, 'review_description', '');
		$pros = castorGetParam($_POST, 'pros', '');
		$cons = castorGetParam($_POST, 'cons', '');

		// We won't pass a message back, the only way the user will have got this far is if they've bypassed the javascript or don't have javascript enabled.
		// Either way, they've bypassed the methods we've built to guide them through the submission process. We can still take a valid submission, but we won't take invalid ones.
		if (($rating_1 < 1 || $rating_1 > 10) || ($rating_2 < 1 || $rating_2 > 10) || ($rating_3 < 1 || $rating_3 > 10) || ($rating_4 < 1 || $rating_4 > 10) || ($rating_5 < 1 || $rating_5 > 10) || ($rating_6 < 1 || $rating_6 > 10) || $review_title == '' || $review_description == '' || $pros == '') {
			castorRedirect(castorURL(CASTOR_SITEPAGE_URL.'&task=add_review&property_uid='.$property_uid), '');
		}

		$thisJRUser = castor_singleton_abstract::getInstance('jr_user');
		
		$query = "SELECT enc_firstname , enc_surname , cms_user_id FROM #__castor_guest_profile WHERE cms_user_id = ".(int)$thisJRUser->id;
		$guest_details = doSelectSql($query);
		
		jr_import('castor_encryption');
		$this->castor_encryption = new castor_encryption();
		if (!empty($guest_details)) {
			foreach ($guest_details as $guest) {
				$guest_names = $this->castor_encryption->decrypt($guest->enc_firstname)." ".$this->castor_encryption->decrypt($guest->enc_surname);
			}
		}
		
		if ($anonymise && trim($guest_names) != '') {  // Guest wants their name anonymised? If yes, and if the guest name isn't blank, we will do that, so for example Vince Wooll becomes VW
			$words = preg_split("/\s+/", $guest_names);
			$acronym = "";

			foreach ($words as $w) {
				$acronym .= $w[0];
			}
			$user_name = strtoupper($acronym);
		} elseif (trim($guest_names) != '') {  // Guest does not want their name anonymised
			$user_name = $guest_names;
		} else { // The guest name is not stored in the profiles table, we will fall back to the ANONYMOUS definition.
			$user_name = jr_gettext('ANONYMOUS', 'ANONYMOUS', false, false);
		}

		if ($jrConfig[ 'use_reviews' ] == '1' && $property_uid > 0) {
			$output = array();
			$pageoutput = array();
			$rows = array();

			jr_import('castor_reviews');
			$Reviews = new castor_reviews();
			$Reviews->property_uid = $property_uid;

			$this_user_can_review_this_property = $Reviews->this_user_can_review_this_property();

			if ($this_user_can_review_this_property) {
				$overall_rating = number_format(($rating_1 + $rating_2 + $rating_3 + $rating_4 + $rating_5 + $rating_6) / 6, 2);

				$rating_id = $Reviews->save_review($overall_rating, $review_title, $review_description, $pros, $cons, $user_name);
				$Reviews->save_rating_detail($property_uid, $rating_id, $rating_1, $rating_2, $rating_3, $rating_4, $rating_5, $rating_6);

				if ($jrConfig[ 'autopublish_reviews' ] == '1') {
					$thanks = jr_gettext('_CASTOR_REVIEWS_THANKS_FOR_REVIEW', '_CASTOR_REVIEWS_THANKS_FOR_REVIEW', false, false);
				} else {
					$thanks = jr_gettext('_CASTOR_REVIEWS_THANKS_FOR_REVIEW_MODERATED', '_CASTOR_REVIEWS_THANKS_FOR_REVIEW_MODERATED', false, false);
				}

				$saveMessage = $thanks;
				$castor_messaging = castor_singleton_abstract::getInstance('castor_messages');
				$castor_messaging->set_message($saveMessage);

				$property_name = getPropertyName($property_uid);
				$subject = jr_gettext('CASTOR_NEWREVIEW_SUBJECT', 'CASTOR_NEWREVIEW_SUBJECT', false).' '.$property_name;
				$message = jr_gettext('CASTOR_NEWREVIEW_MESSAGE', 'CASTOR_NEWREVIEW_MESSAGE', false).' '.$property_name.'  '.CASTOR_SITEPAGE_URL_ADMIN.'&task=view_property_reviews&property_uid='.(int) $property_uid.' <br/><br/>';
				sendAdminEmail($subject, $message);

				castorRedirect(get_property_details_url($property_uid), '');
			} else {
				echo jr_gettext('_CASTOR_REVIEWS_CANNOTREVIEW', '_CASTOR_REVIEWS_CANNOTREVIEW');
			}
		}
	}

	public function getRetVals()
	{
		return $this->retVals;
	}
}

