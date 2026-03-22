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

class j06001save_review_reply
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
			$this->template_touchable = false;

			return;
		}

		$property_uid = getDefaultProperty();
		$rating_id = intval(castorGetParam($_POST, 'rating_id', 0));
		$review_reply = getEscaped(castorGetParam($_REQUEST, 'review_reply', ''));

		$thisJRUser = castor_singleton_abstract::getInstance('jr_user');
		
		jr_import('castor_reviews');
		$Reviews = new castor_reviews();
		$Reviews->property_uid = $property_uid;
		$itemReviews = $Reviews->showReviews($property_uid);
		
		if (isset($itemReviews['fields'][$rating_id])) {
			$result = $Reviews->save_review_reply($thisJRUser->id, $review_reply, $rating_id);
			if ($result) {
				castorRedirect(castorURL(CASTOR_SITEPAGE_URL.'&task=show_property_reviews&property_uid='.$property_uid), jr_gettext('_CASTOR_REVIEWS_REPLY_SAVED', '_CASTOR_REVIEWS_REPLY_SAVED'));
			}
		}
	}

/**
 * Must be included in every mini-component.
 #
 * Returns any settings that the mini-component wants to send back to the calling script. In addition to being returned to the calling script they are put into an array in the mcHandler object as eg. $mcHandler->miniComponentData[$ePoint][$eName]
 */

	public function getRetVals()
	{
		return null;
	}
}

