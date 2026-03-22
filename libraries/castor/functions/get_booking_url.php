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



/**
 * @package Castor\Core\Functions
 *
 *  The purpose of this function is to allow us to override the dobooking link programatically. In this case it will simply return the dobooking link, but making it a simple function allows us to override it if needed
 *
 * Types:
*  sef: sef url
* nosef: no sef url
* sefsafe: sef url not passed through castorURL function
* ajax: ajax safe url
 */
	if (!function_exists('get_booking_url')) {
		function get_booking_url($property_uid = 0, $type = 'sef', $params = '')
		{
			$castor_access_control = castor_singleton_abstract::getInstance('castor_access_control');

			if (!$castor_access_control->this_user_can('dobooking')) {
				return false;
			}

			$mrConfig = getPropertySpecificSettings($property_uid);

			if (isset($mrConfig[ 'externalBookingFormUrl' ]) && $mrConfig[ 'externalBookingFormUrl' ] != '') {
				$url = filter_var($mrConfig[ 'externalBookingFormUrl' ], FILTER_SANITIZE_URL);
				$url = str_replace("&#38;#61;", "=", $url);
			} else {
				switch ($type) {
					case 'sef':
						$url = castorURL(CASTOR_SITEPAGE_URL.'&task=dobooking&selectedProperty='.$property_uid.$params);
						break;
					case 'nosef':
						$url = castorURL(CASTOR_SITEPAGE_URL_NOSEF.'&task=dobooking&selectedProperty='.$property_uid.$params);
						break;
					case 'sefsafe':
						$url = CASTOR_SITEPAGE_URL.'&task=dobooking&selectedProperty='.$property_uid.$params;
						break;
					case 'ajax':
						$url = CASTOR_SITEPAGE_URL_AJAX.'&task=dobooking&selectedProperty='.$property_uid.$params;
						break;
					default:
						$url = castorURL(CASTOR_SITEPAGE_URL.'&task=dobooking&selectedProperty='.$property_uid.$params);
						break;
				}
			}

			return $url;
		}
	}


