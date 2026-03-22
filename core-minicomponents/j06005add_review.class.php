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

class j06005add_review
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
			$property_uid = (int) $_GET[ 'property_uid' ];
		}
		
		$castor_gdpr_optin_consent = new castor_gdpr_optin_consent();
		if (!$castor_gdpr_optin_consent->user_consents_to_storage()&& !isset($_REQUEST['skip_consent_form'])) {
			echo $consent_form = $MiniComponents->specificEvent('06000', 'show_consent_form', array ('output_now' => false));
			return;
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
				$output[ '_CASTOR_REVIEWS' ] = jr_gettext('_CASTOR_REVIEWS', '_CASTOR_REVIEWS', false, false);
				$output[ '_CASTOR_REVIEWS_TITLE' ] = jr_gettext('_CASTOR_REVIEWS_TITLE', '_CASTOR_REVIEWS_TITLE', false, false);
				$output[ '_CASTOR_REVIEWS_REVIEWBODY' ] = jr_gettext('_CASTOR_REVIEWS_REVIEWBODY', '_CASTOR_REVIEWS_REVIEWBODY', false, false);

				$output[ '_CASTOR_REVIEWS_ADDREVIEW_ERROR_TITLE' ] = jr_gettext('_CASTOR_REVIEWS_ADDREVIEW_ERROR_TITLE', '_CASTOR_REVIEWS_ADDREVIEW_ERROR_TITLE', false, false);
				$output[ '_CASTOR_REVIEWS_ADDREVIEW_ERROR_DESCRIPTION' ] = jr_gettext('_CASTOR_REVIEWS_ADDREVIEW_ERROR_DESCRIPTION', '_CASTOR_REVIEWS_ADDREVIEW_ERROR_DESCRIPTION', false, false);
				$output[ '_CASTOR_REVIEWS_ADDREVIEW_ERROR_PROS' ] = jr_gettext('_CASTOR_REVIEWS_ADDREVIEW_ERROR_PROS', '_CASTOR_REVIEWS_ADDREVIEW_ERROR_PROS', false, false);
				$output[ '_CASTOR_REVIEWS_ADDREVIEW_ERROR_CONS' ] = jr_gettext('_CASTOR_REVIEWS_ADDREVIEW_ERROR_CONS', '_CASTOR_REVIEWS_ADDREVIEW_ERROR_CONS', false, false);
				$output[ '_CASTOR_REVIEWS_ADDREVIEW_ERROR_RATING_1' ] = jr_gettext('_CASTOR_REVIEWS_ADDREVIEW_ERROR_RATING_1', '_CASTOR_REVIEWS_ADDREVIEW_ERROR_RATING_1', false, false);
				$output[ '_CASTOR_REVIEWS_ADDREVIEW_ERROR_RATING_2' ] = jr_gettext('_CASTOR_REVIEWS_ADDREVIEW_ERROR_RATING_2', '_CASTOR_REVIEWS_ADDREVIEW_ERROR_RATING_2', false, false);
				$output[ '_CASTOR_REVIEWS_ADDREVIEW_ERROR_RATING_3' ] = jr_gettext('_CASTOR_REVIEWS_ADDREVIEW_ERROR_RATING_3', '_CASTOR_REVIEWS_ADDREVIEW_ERROR_RATING_3', false, false);
				$output[ '_CASTOR_REVIEWS_ADDREVIEW_ERROR_RATING_4' ] = jr_gettext('_CASTOR_REVIEWS_ADDREVIEW_ERROR_RATING_4', '_CASTOR_REVIEWS_ADDREVIEW_ERROR_RATING_4', false, false);
				$output[ '_CASTOR_REVIEWS_ADDREVIEW_ERROR_RATING_5' ] = jr_gettext('_CASTOR_REVIEWS_ADDREVIEW_ERROR_RATING_5', '_CASTOR_REVIEWS_ADDREVIEW_ERROR_RATING_5', false, false);
				$output[ '_CASTOR_REVIEWS_ADDREVIEW_ERROR_RATING_6' ] = jr_gettext('_CASTOR_REVIEWS_ADDREVIEW_ERROR_RATING_6', '_CASTOR_REVIEWS_ADDREVIEW_ERROR_RATING_6', false, false);

				$output[ '_CASTOR_REVIEWS_SUBMIT' ] = jr_gettext('_CASTOR_REVIEWS_SUBMIT', '_CASTOR_REVIEWS_SUBMIT', false, false);

				$output[ '_CASTOR_REVIEWS_ADDREVIEW_SUMMARY' ] = jr_gettext('_CASTOR_REVIEWS_ADDREVIEW_SUMMARY', '_CASTOR_REVIEWS_ADDREVIEW_SUMMARY', false, false);
				$output[ '_CASTOR_REVIEWS_ADDREVIEW_MOREDETAIL' ] = jr_gettext('_CASTOR_REVIEWS_ADDREVIEW_MOREDETAIL', '_CASTOR_REVIEWS_ADDREVIEW_MOREDETAIL', false, false);

				$output[ '_CASTOR_REVIEWS_RATING' ] = jr_gettext('_CASTOR_REVIEWS_RATING', '_CASTOR_REVIEWS_RATING', false, false);
				$output[ '_CASTOR_REVIEWS_RATING_1' ] = jr_gettext('_CASTOR_REVIEWS_RATING_1', '_CASTOR_REVIEWS_RATING_1');
				$output[ '_CASTOR_REVIEWS_RATING_2' ] = jr_gettext('_CASTOR_REVIEWS_RATING_2', '_CASTOR_REVIEWS_RATING_2');
				$output[ '_CASTOR_REVIEWS_RATING_3' ] = jr_gettext('_CASTOR_REVIEWS_RATING_3', '_CASTOR_REVIEWS_RATING_3');
				$output[ '_CASTOR_REVIEWS_RATING_4' ] = jr_gettext('_CASTOR_REVIEWS_RATING_4', '_CASTOR_REVIEWS_RATING_4');
				$output[ '_CASTOR_REVIEWS_RATING_5' ] = jr_gettext('_CASTOR_REVIEWS_RATING_5', '_CASTOR_REVIEWS_RATING_5');
				$output[ '_CASTOR_REVIEWS_RATING_6' ] = jr_gettext('_CASTOR_REVIEWS_RATING_6', '_CASTOR_REVIEWS_RATING_6');

				$output[ '_CASTOR_REVIEWS_PROS' ] = jr_gettext('_CASTOR_REVIEWS_PROS', '_CASTOR_REVIEWS_PROS', false, false);
				$output[ '_CASTOR_REVIEWS_CONS' ] = jr_gettext('_CASTOR_REVIEWS_CONS', '_CASTOR_REVIEWS_CONS', false, false);
				$output[ '_CASTOR_REVIEWS_ADD_REVIEW' ] = jr_gettext('_CASTOR_REVIEWS_ADD_REVIEW', '_CASTOR_REVIEWS_ADD_REVIEW', false, false);
				$output[ '_CASTOR_REVIEWS_COMPLETEALLFIELDS' ] = jr_gettext('_CASTOR_REVIEWS_COMPLETEALLFIELDS', '_CASTOR_REVIEWS_COMPLETEALLFIELDS', false, false);
				$output[ 'PROPERTY_DETAILS_URL' ] = get_property_details_url($property_uid);
				$output[ 'PROPERTY_UID' ] = $property_uid;
				$output[ '_CASTOR_COM_A_CANCEL' ] = jr_gettext('_CASTOR_COM_A_CANCEL', '_CASTOR_COM_A_CANCEL', false);
				
				$output[ 'CONTRACT_UID' ] = 0;
				if (isset($_GET[ 'contract_uid' ])) {
					$output[ 'CONTRACT_UID' ] = (int)$_GET[ 'contract_uid' ];
				}
				
				$yesno = array();
				$yesno[] = castorHTML::makeOption('0', jr_gettext("_CASTOR_COM_MR_NO", '_CASTOR_COM_MR_NO', false));
				$yesno[] = castorHTML::makeOption('1', jr_gettext("_CASTOR_COM_MR_YES", '_CASTOR_COM_MR_YES', false));
				$output['ANONYMISE_YESNO']		= castorHTML::selectList($yesno, 'anonymise', '', 'value', 'text', 0);
		
				$output[ '_CASTOR_REVIEWS_ANONYMISE' ] = jr_gettext('_CASTOR_REVIEWS_ANONYMISE', '_CASTOR_REVIEWS_ANONYMISE', false, false);
				$output[ '_CASTOR_REVIEWS_ANONYMISE_DESC' ] = jr_gettext('_CASTOR_REVIEWS_ANONYMISE_DESC', '_CASTOR_REVIEWS_ANONYMISE_DESC', false, false);
		
				$pageoutput[ ] = $output;
				$tmpl = new patTemplate();
				$tmpl->addRows('pageoutput', $pageoutput);
				$tmpl->setRoot(CASTOR_TEMPLATEPATH_FRONTEND);

				$tmpl->readTemplatesFromInput('add_review.html');
				$this->retVals = $tmpl->getParsedTemplate();
				echo $this->retVals;
			} else {
				echo jr_gettext('_CASTOR_REVIEWS_ALREADYREVIEWED', '_CASTOR_REVIEWS_ALREADYREVIEWED', false, false);
			}
		}
	}

	public function getRetVals()
	{
		return $this->retVals;
	}
}

