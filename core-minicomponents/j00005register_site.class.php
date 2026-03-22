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
	 * Attempts to update the App Server (<a href="https://www.castor.net/manual/developers-guide-2/387-castor-syndication-network">Castor Syndicate Network</a>) with this installation's existence.
	 *
	 */

class j00005register_site
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
	
		if (get_showtime("task") == "view_log_file" ||
		get_showtime("task") == "list_error_logs" ||
		AJAXCALL
		) {
			return;
		}

		return;

	// reports the server's existence to the Castor app server
	
		$app_server = "https://app.castor.net/castor/api/register_site/";
	
		$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
		$jrConfig = $siteConfig->get();

		if (!isset($jrConfig['useSyndication'])) {
			$jrConfig['useSyndication'] = 0;
		}

		if ($jrConfig['useSyndication'] == 0) {
			return;
		}

		if (!isset($jrConfig['appServerRegister'])) {
			$tries = 0;
		} else {
			$tries = $jrConfig['appServerRegister'];
		}
	
		if ($tries >= 6) { //It aint happening, let's give up
			return;
		}
	
	
		try {
			$client = new GuzzleHttp\Client();

			$response = $client->request('POST', $app_server, [
			//'debug' => true,
			'form_params' => [
				'api_url' => urlencode(get_showtime('live_site').'/'.CASTOR_ROOT_DIRECTORY.'/api/')
				]
			]);

			$code				= $response->getStatusCode();
			$body				= (string)$response->getBody();

		
		
			if ($code == 200) {
				logging::log_message('Updated app.castor.net ', 'API', 'DEBUG');
				$tries = 10;
			} else {
				logging::log_message('Failed to update app.castor.net Received response code '.$code, 'API', 'WARNING');
				$tries++;
			}
		} catch (\Exception $e) {
			logging::log_message('Failed to update app.castor.net Received response '.$e->getMessage()." with message ".$body, 'API', 'WARNING', $body)  ;
			$tries++;
		}
		
		$siteConfig->update_setting('appServerRegister', $tries);
	}
	
	

	public function getRetVals()
	{
		return null;
	}
}

