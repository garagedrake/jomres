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

//

/**
 * @package Castor\Core\Functions
 *
 * The purpose of this function is to allow us to override the booking number programatically.
 *
 */
	if (!function_exists('set_booking_number')) {
		function set_booking_number()
		{
			$tmpBookingHandler = castor_singleton_abstract::getInstance('castor_temp_booking_handler');
			if (!isset($tmpBookingHandler->tmpbooking[ 'booking_number' ]) || trim($tmpBookingHandler->tmpbooking[ 'booking_number' ]) == '' || $tmpBookingHandler->tmpbooking[ 'booking_number' ] == 0) {
				$keeplooking = true;
				while ($keeplooking) :
					$cartnumber = mt_rand(10000000, 99999999);
					$query = "SELECT `contract_uid` FROM #__castor_contracts WHERE `tag` = '".$cartnumber."' LIMIT 1";
					$bklist = doSelectSql($query);
					if (empty($bklist)) {
						$keeplooking = false;
					}
				endwhile;
				$tmpBookingHandler->tmpbooking[ 'booking_number' ] = $cartnumber;
			} else {
				$cartnumber = $tmpBookingHandler->tmpbooking[ 'booking_number' ];
			}

			return $cartnumber;
		}
	}


