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

class j06000cron_syndication_get_syndicate_domains
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

		return;

		$query = "SELECT id , domain FROM #__castor_syndication_domains ";
		$result = doSelectSql($query);
		
		$existing_domains = array();
		if (!empty($result)) {
			foreach ($result as $r) {
				$existing_domains[$r->id] = $r->domain;
			}
		}
		
		try {
			$client = new GuzzleHttp\Client();
			$response = $client->request('GET', "https://app.castor.net/castor/api/get_sites/", ['connect_timeout' => 10 ]);

			$body				= json_decode((string)$response->getBody());
					
			if (!empty($body->data->sites->sites)) {
				foreach ($body->data->sites->sites as $site) {
					$domain = parse_url($site->api_url);
					if ($domain) {
						$now = date("Y-m-d H:i:s");
						$new_site_domain					= $domain['host'];
						$new_site_api_url					= $domain['scheme']."://".$domain['host'].filter_var($domain['path'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
						$new_site_datetime_added			= $now;
						$new_site_datetime_last_checked		= $now;
						
						if (!in_array($new_site_domain, $existing_domains)) {
							try {
								$query = "
									INSERT INTO #__castor_syndication_domains SET
										`domain` = '".$new_site_domain."',
										`api_url` = '".$new_site_api_url."',
										`date_added` =  '".$new_site_datetime_added."',
										`last_checked` =  '".$new_site_datetime_last_checked."'
								";
								doInsertSql($query);
							} catch (Exception $e) {
								logging::log_message("Tried to insert domain ".$new_site_domain." but failed ", 'Syndication', 'WARNING');
							}
						} else {
							logging::log_message("Domain ".$domain['host']." already in domains table ", 'Syndication', 'DEBUGGING');
						}
					}
				}
			}
		} catch (GuzzleHttp\Exception\RequestException $e) {
			//  If the user reports problems, the REST API connectivity check should give us some clues
		}
	}


	public function getRetVals()
	{
		return null;
	}
}

