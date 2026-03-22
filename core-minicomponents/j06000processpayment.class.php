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

class j06000processpayment
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
		
		$tmpBookingHandler = castor_singleton_abstract::getInstance('castor_temp_booking_handler');
		$property_uid = (int)$tmpBookingHandler->tmpbooking['property_uid'];
		$bookingdata = gettempBookingdata();
		
		request_log();

		if (!isset($bookingdata["cart_payment"]) || !$bookingdata["cart_payment"]) {
			if ($bookingdata[ 'ok_to_book' ] == false) {
				die("Naughty bot");
			}
		}

		$tag = set_booking_number();

		$thisJRUser = castor_singleton_abstract::getInstance('jr_user');
		$siteConfig		= castor_singleton_abstract::getInstance('castor_config_site_singleton');
		$jrConfig		  = $siteConfig->get();

		if ($thisJRUser->userIsManager && $jrConfig['development_production'] != 'development') {
			$plugin = castor_validate_gateway_plugin();
		} else { // Site is set to Development mode and we are allowing the manager to make payments. There's no need to validate the gateway
			$plugin = castorGetParam($_REQUEST, 'plugin', '');
		}


		$query = "SELECT `id` FROM #__castor_booking_data_archive WHERE `tag` = '".$tag."'";
		$result = doSelectSql($query);
		if (empty($result)) {
			$data = array('tmpbooking' => $tmpBookingHandler->tmpbooking, 'tmpguest' => $tmpBookingHandler->tmpguest);
			$data = str_replace("'", "''", serialize($data));

			$query = "INSERT INTO #__castor_booking_data_archive SET `data`='".$data."',`date`='".date('Y-m-d H:i:s')."', `tag` = '".$tag."'";
			doInsertSql($query, '');
		}

		$MiniComponents->triggerEvent('00599', array('bookingdata' => $tmpBookingHandler->tmpbooking)); // Optional

		// We'll let bookings of 0 value passed the gateway plugin handling as some users offer 100% discounts via coupons
		if ($bookingdata[ 'contract_total' ] == 0.00) {
			$plugin = 'NA';
		}

			$query = 'SELECT * FROM #__castor_pluginsettings WHERE (prid = '.(int) $property_uid." OR prid = 0)  AND `plugin` = '".(string) $plugin."' ";
			$gatewayDeets = doSelectSql($query);


			if (!empty($gatewayDeets)) {
				$test_mode = false;
				foreach ($gatewayDeets as $gw ) {
					if ($gw->setting == "test_mode" ) {
						$test_mode = (bool)$gw->value;
					}
				}

				if ( $test_mode != true && $thisJRUser->userIsManager ) {
					insertInternetBooking(get_showtime('castorsession'), $depositPaid = false, $confirmationPageRequired = true, $customTextForConfirmationForm = '');
				} else {
					$interrupted = intval(castorGetParam($_POST, 'interrupted', 0));
					$interruptOutgoingFile = false;

					if ($MiniComponents->eventFileLocate('00600', $plugin)) {
						$interruptOutgoingFile = 'j00600'.$plugin.'.class.php';
					}

					$outgoingFile = 'j00605'.$plugin.'.class.php';

					if ($interruptOutgoingFile && $interrupted == 0) {
						$MiniComponents->specificEvent('00600', $plugin, array('bookingdata' => $bookingdata, 'property_uid' => $property_uid));
					} //Interrupt outgoing
					else {
						$MiniComponents->specificEvent('00605', $plugin, array('bookingdata' => $bookingdata, 'property_uid' => $property_uid));
					} //outgoing
				}
			} else {
				insertInternetBooking(get_showtime('castorsession'), $depositPaid = false, $confirmationPageRequired = true, $customTextForConfirmationForm = '');
			}
	}


	public function getRetVals()
	{
		return null;
	}
}

