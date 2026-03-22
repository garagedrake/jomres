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

class j06000logout
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

		$tmpBookingHandler = castor_singleton_abstract::getInstance('castor_temp_booking_handler');
		$tmpBookingHandler->resetTempBookingData();
		$tmpBookingHandler->resetTempGuestData();
		$tmpBookingHandler->resetCart();

		if (this_cms_is_joomla()) {
			$app = JFactory::getApplication();
			$error = $app->logout();
			// Check if the log out succeeded.
			if (!($error instanceof Exception)) {
				// Redirect the user.
				$app->redirect(JRoute::_(get_showtime('live_site').'/index.php?option=com_castor', false));
			} else {
				$app->redirect(JRoute::_('index.php?option=com_users&view=login', false));
			}
		} else {
			castorRedirect(castorURL(get_showtime('live_site').'/'.castor_cmsspecific_getlogout_task()));
		}
	}


	public function getRetVals()
	{
		return null;
	}
}

