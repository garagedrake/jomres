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
	 *
	 * @package Castor\Core\Classes
	 *
	 */
	#[AllowDynamicProperties]
class castor_syndicate_guests
{

	/**
	 *
	 *
	 *
	 */

	public function __construct()
	{
	}
	
	/**
	 *
	 *
	 *
	 */

	public function get_booking_stats_for_guest($email)
	{
		$response = '';

		$reply = new stdClass();
		$reply->stats = new stdClass();
		$reply->stats->bookings = jr_gettext('BOOKING_NOSHOW_UNKNOWN', 'BOOKING_NOSHOW_UNKNOWN', false);
		$reply->stats->noshows = jr_gettext('BOOKING_NOSHOW_UNKNOWN', 'BOOKING_NOSHOW_UNKNOWN', false);
		return $reply;


		// Not currently used
		$castor_check_support_key = castor_singleton_abstract::getInstance('castor_check_support_key');
		$castor_check_support_key->check_license_key();
		
		if (trim($castor_check_support_key->key_hash) != '') {
			if ($castor_check_support_key->key_valid) {
				try {
					$client = new GuzzleHttp\Client();

					$response = $client->request('POST', "https://app.castor.net/castor/api/syndication_guests/stats/", [
						'connect_timeout' => 5,
						'form_params' => [
							'api_url' => urlencode(get_showtime('live_site').'/'.CASTOR_ROOT_DIRECTORY.'/api/'),
							'data' => array (
								'license_key' => $castor_check_support_key->key_hash,
								'email' => $email
								)
							]
						]);
					
					$statusCode = $response->getStatusCode();
					$reasonPhrase = $response->getReasonPhrase();
					
					$reply = (string)$response->getBody()->getContents();
					$reply = json_decode($reply);
					if (isset($reply->data)) {
						return $reply->data;
					} else {
						return array();
					}
				} catch (GuzzleHttp\Exception\RequestException $e) {
					logging::log_message('Failed to get guest stats, received response '.$e->getMessage(), 'API', 'WARNING')  ;
				}
			}
		}
		
		$reply = new stdClass();
		$reply->stats = new stdClass();
		$reply->stats->bookings = jr_gettext('BOOKING_NOSHOW_UNKNOWN', 'BOOKING_NOSHOW_UNKNOWN', false);
		$reply->stats->noshows = jr_gettext('BOOKING_NOSHOW_UNKNOWN', 'BOOKING_NOSHOW_UNKNOWN', false);
		return $reply;
	}
}

