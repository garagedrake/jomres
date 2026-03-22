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
	 * Ajax script. dds properties to the shortlist, if the user is logged in, instead adds the property to the user's favourites list.
	 *
	 */

class j06000ajax_shortlist
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

		$property_uid = (int) castorGetParam($_GET, 'property_uid', 0);
		$show_label = (int) castorGetParam($_GET, 'show_label', 0);
		$result = '';

		$tmpBookingHandler = castor_singleton_abstract::getInstance('castor_temp_booking_handler');

		$thisJRUser = castor_singleton_abstract::getInstance('jr_user');

		$shortlist_items = array();
		if (isset($tmpBookingHandler->tmpsearch_data[ 'shortlist_items' ])) {
			$shortlist_items = $tmpBookingHandler->tmpsearch_data[ 'shortlist_items' ];
		}

		if (!in_array($property_uid, $shortlist_items)) {
			$shortlist_items[ ] = $property_uid;
			$tmpBookingHandler->tmpsearch_data[ 'shortlist_items' ] = $shortlist_items;

			$output = array();
			$pageoutput = array();
			$output['TEXT'] = jr_gettext('_CASTOR_REMOVEFROMSHORTLIST', '_CASTOR_REMOVEFROMSHORTLIST', false, false);
			$pageoutput[ ] = $output;

			$tmpl = new patTemplate();
			$tmpl->setRoot(CASTOR_TEMPLATEPATH_FRONTEND);
			if ($show_label == 1) {
				$tmpl->readTemplatesFromInput('shortlilst_added_text.html');
			} else {
				$tmpl->readTemplatesFromInput('shortlilst_added.html');
			}
			$tmpl->addRows('pageoutput', $pageoutput);
			$result = $tmpl->getParsedTemplate();

			if ($thisJRUser->userIsRegistered) {
				$query = "SELECT property_uid FROM #__jomcomp_mufavourites WHERE property_uid = '".(int) $property_uid."' AND `my_id` = '".(int) $thisJRUser->id."'";
				$propys = doSelectSql($query);

				if (empty($propys)) {
					$query = "INSERT INTO #__jomcomp_mufavourites (`my_id`,`property_uid`) VALUES ('".(int) $thisJRUser->id."','".(int) $property_uid."')";
					doInsertSql($query, '');
				}
			}
		} else { // Remove from shortlist
			$count = count($shortlist_items);
			if ($count > 0) {
				for ($i = 0; $i < $count; ++$i) {
					if (isset($shortlist_items[ $i ]) && $shortlist_items[ $i ] == $property_uid) {
						array_splice($shortlist_items, $i, 1);
					}
				}
			}
			$tmpBookingHandler->tmpsearch_data[ 'shortlist_items' ] = $shortlist_items;

			$output = array();
			$pageoutput = array();
			$output['TEXT'] = jr_gettext('_CASTOR_ADDTOSHORTLIST', '_CASTOR_ADDTOSHORTLIST', false, false);
			$pageoutput[ ] = $output;

			$tmpl = new patTemplate();
			$tmpl->setRoot(CASTOR_TEMPLATEPATH_FRONTEND);
			if ($show_label == 1) {
				$tmpl->readTemplatesFromInput('shortlist_removed_text.html');
			} else {
				$tmpl->readTemplatesFromInput('shortlist_removed.html');
			}
			$tmpl->addRows('pageoutput', $pageoutput);
			$result = $tmpl->getParsedTemplate();

			if ($thisJRUser->userIsRegistered) {
				$query = "SELECT property_uid FROM #__jomcomp_mufavourites WHERE property_uid = '".(int) $property_uid."' AND `my_id` = '".(int) $thisJRUser->id."'";
				$propys = doSelectSql($query);
				if (count($propys) == 1) {
					$query = "DELETE FROM #__jomcomp_mufavourites WHERE `my_id`='".(int) $thisJRUser->id."' AND `property_uid`='".(int) $property_uid."'";
					doInsertSql($query, '');
				}
			}
		}

		$webhook_notification						   		= new stdClass();
		$webhook_notification->webhook_event				= 'property_favourited';
		$webhook_notification->webhook_event_description	= 'Logs when a property is favourited';
		$webhook_notification->data					 		= new stdClass();
		$webhook_notification->data->property_uid	   		= $property_uid;
		add_webhook_notification($webhook_notification);

		echo $result;
	}


	public function getRetVals()
	{
		return null;
	}
}

