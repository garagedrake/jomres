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

class j06005mulistbookings
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
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = true;

			return;
		}
		$thisJRUser = castor_singleton_abstract::getInstance('jr_user');
		$mrConfig = getPropertySpecificSettings();
		$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
		$jrConfig = $siteConfig->get();
		
		$query = "SELECT contract_uid FROM #__castor_reviews_ratings WHERE user_id = ".(int)$thisJRUser->id;
		$reviewed_contracts_data = doSelectSql($query);
		$reviewed_contracts = array();
		if (!empty($reviewed_contracts_data)) {
			foreach ($reviewed_contracts_data as $review) {
				$reviewed_contracts[]=$review->contract_uid;
			}
		}
		
		if ($thisJRUser->userIsRegistered) {
			$pageoutput = array();
			$output = array();
			$rows = array();
			$r = array();
			$allGuestUids = array();

			if (!$thisJRUser->is_partner) {
				$query = "SELECT guests_uid FROM #__castor_guests WHERE mos_userid = '".(int) $thisJRUser->id."' ";
			} else {
				$query = "SELECT guests_uid FROM #__castor_guests WHERE partner_id = '".(int) $thisJRUser->id."' ";
			}

			$guests_uids = doSelectSql($query);
			// Because a new record is made in the guests table for each new property the guest registers in, we need to find all the guest uids for this user
			if (!empty($guests_uids)) {
				foreach ($guests_uids as $g) {
					$allGuestUids[ ] = $g->guests_uid;
				}

				$query = 'SELECT * FROM #__castor_contracts WHERE guest_uid IN ('.castor_implode($allGuestUids).') AND cancelled = 0 ';
				$contracts = doSelectSql($query);
			} else {
				$contracts = array();
			}

			//if ( count( $contracts ) > 0 ) //we`ll just display an empty table if there are no bookings.
				//{
				$output[ 'HARRIVAL' ] = jr_gettext('_CASTOR_COM_MR_VIEWBOOKINGS_ARRIVAL', '_CASTOR_COM_MR_VIEWBOOKINGS_ARRIVAL', $editable = false, $isLink = false);
			$output[ 'HDEPARTURE' ] = jr_gettext('_CASTOR_COM_MR_VIEWBOOKINGS_DEPARTURE', '_CASTOR_COM_MR_VIEWBOOKINGS_DEPARTURE', $editable = false, $isLink = false);
			$output[ 'HTOTAL' ] = jr_gettext('_CASTOR_AJAXFORM_BILLING_TOTAL', '_CASTOR_AJAXFORM_BILLING_TOTAL', $editable = false, $isLink = false);
			$output[ 'HEXTRAS' ] = jr_gettext('_CASTOR_AJAXFORM_BILLING_EXTRAS', '_CASTOR_AJAXFORM_BILLING_EXTRAS', $editable = false, $isLink = false);
			$output[ 'HPNAME' ] = jr_gettext('_CASTOR_COM_MR_QUICKRES_STEP2_PROPERTYNAME', '_CASTOR_COM_MR_QUICKRES_STEP2_PROPERTYNAME', $editable = false, $isLink = false);

			$output[ 'HMOREINFO' ] = jr_gettext('_CASTOR_COM_A_CLICKFORMOREINFORMATION', '_CASTOR_COM_A_CLICKFORMOREINFORMATION', $editable = false, $isLink = false);
			if (isset($_REQUEST['unreviewed'])) {
				$output[ 'TITLE' ] = jr_gettext('BOOKINGS_AWAITING_REVIEWS', 'BOOKINGS_AWAITING_REVIEWS', $editable = false, $isLink = false);
			} else {
				$output[ 'TITLE' ] = jr_gettext('_JOMCOMP_MYUSER_MYBOOKINGS', '_JOMCOMP_MYUSER_MYBOOKINGS', $editable = false, $isLink = false);
			}

			if (!empty($contracts)) {
				$basic_property_details = castor_singleton_abstract::getInstance('basic_property_details');
				$castor_media_centre_images = castor_singleton_abstract::getInstance('castor_media_centre_images');

				$counter = 0;
				foreach ($contracts as $c) {
					if ($c->property_uid > 0) {
						$castor_media_centre_images->get_images($c->property_uid, array('property'));

						$basic_property_details->gather_data($c->property_uid);

						$r[ 'PROPERTYNAME' ] = getPropertyName($c->property_uid);

						$r[ 'ARRIVAL' ] = outputDate($c->arrival);
						$r[ 'DEPARTURE' ] = outputDate($c->departure);
						$r[ 'LASTCHANGED' ] = $c->timestamp;
						$r[ 'EXTRASVALUE' ] = output_price($c->extrasvalue);
						$r[ 'CONTRACT_TOTAL' ] = output_price($c->contract_total);
						$r[ 'IMAGE' ] = $castor_media_centre_images->images ['property'][0][0]['small'];
						$r[ 'VIEWLINK' ] = CASTOR_SITEPAGE_URL.'&task=muviewbooking&contract_uid='.$c->contract_uid;
						$r[ 'VIEWLINK_TEXT' ] = jr_gettext('_JOMCOMP_MYUSER_VIEWBOOKING', '_JOMCOMP_MYUSER_VIEWBOOKING', $editable = false, $isLink = true);
						$r[ 'PROPERTYDETAILSLINK' ] = get_property_details_url($c->property_uid);
						
						$r['REVIEW_BUTTON'] = '';
						if ((int) $jrConfig[ 'use_reviews' ] == 1) {
							$o = array();
							$p = array();
							
							if (!in_array($c->contract_uid, $reviewed_contracts)) {
								$o[ 'REVIEWLINK' ] = CASTOR_SITEPAGE_URL.'&task=add_review&property_uid='.$c->property_uid.'&contract_uid='.$c->contract_uid;
								$o[ 'REVIEWLINK_TEXT' ] = jr_gettext('_CASTOR_REVIEWS_ADD_REVIEW', '_CASTOR_REVIEWS_ADD_REVIEW', $editable = false, $isLink = true);
							} else {
								$o[ 'REVIEWLINK' ] = CASTOR_SITEPAGE_URL.'&task=show_property_reviewed_contracts&property_uid='.$c->property_uid;
								$o[ 'REVIEWLINK_TEXT' ] = jr_gettext('_CASTOR_REVIEWS_CLICKTOSHOW', '_CASTOR_REVIEWS_CLICKTOSHOW', $editable = false, $isLink = true);
							}
							$p[]=$o;
							$tmpl = new patTemplate();
							$tmpl->setRoot(CASTOR_TEMPLATEPATH_FRONTEND);
							$tmpl->addRows('pageoutput', $p);
							$tmpl->readTemplatesFromInput('review_button.html');
							$r['REVIEW_BUTTON'] =$tmpl->getParsedTemplate();
						}

						if (isset($_REQUEST['unreviewed']) && !in_array($c->contract_uid, $reviewed_contracts)) {
							$rows[ ] = $r;
						} elseif (!isset($_REQUEST['unreviewed'])) {
							$rows[ ] = $r;
						}
					}
				}
			}

			$pageoutput[ ] = $output;
			$tmpl = new patTemplate();
			$tmpl->setRoot(CASTOR_TEMPLATEPATH_FRONTEND);
			$tmpl->addRows('pageoutput', $pageoutput);
			$tmpl->addRows('rows', $rows);
			$tmpl->readTemplatesFromInput('list_bookings.html');
			$tmpl->displayParsedTemplate();
		}
			//else
				//{
				//echo jr_gettext( '_JOMCOMP_MYUSER_VIEWBOOKINGS_NONE', _JOMCOMP_MYUSER_VIEWBOOKINGS_NONE, false, false );
				//}
			//}
	}

	public function touch_template_language()
	{
		$output = array();

		$output[ 'HARRIVAL' ] = jr_gettext('_CASTOR_COM_MR_VIEWBOOKINGS_ARRIVAL', '_CASTOR_COM_MR_VIEWBOOKINGS_ARRIVAL');
		$output[ 'HDEPARTURE' ] = jr_gettext('_CASTOR_COM_MR_VIEWBOOKINGS_DEPARTURE', '_CASTOR_COM_MR_VIEWBOOKINGS_DEPARTURE');
		$output[ 'HTOTAL' ] = jr_gettext('_CASTOR_AJAXFORM_BILLING_TOTAL', '_CASTOR_AJAXFORM_BILLING_TOTAL');
		$output[ 'HEXTRAS' ] = jr_gettext('_CASTOR_AJAXFORM_BILLING_EXTRAS', '_CASTOR_AJAXFORM_BILLING_EXTRAS');
		$output[ 'HPNAME' ] = jr_gettext('_CASTOR_COM_MR_QUICKRES_STEP2_PROPERTYNAME', '_CASTOR_COM_MR_QUICKRES_STEP2_PROPERTYNAME');
		$output[ 'HMOREINFO' ] = jr_gettext('_CASTOR_COM_A_CLICKFORMOREINFORMATION', '_CASTOR_COM_A_CLICKFORMOREINFORMATION');
		$output[ 'TITLE' ] = jr_gettext('_JOMCOMP_MYUSER_MYBOOKINGS', '_JOMCOMP_MYUSER_MYBOOKINGS');

		foreach ($output as $o) {
			echo $o;
			echo '<br/>';
		}
	}


	public function getRetVals()
	{
		return null;
	}
}

