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

class j16000rest_api_connectivity_test
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
	 
	function __construct()
	{
		// Must be in all minicomponents. Minicomponents with templates that can contain editable text should run $this->template_touch() else just return
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = false;
			return;
		}

		if (strstr(CASTORPATH_BASE, '/wp-content/plugins/castor/castor/')) { // We are buried deep in the wp plugins dir and the API needs to be in public_html/castor
			echo "<p class='alert alert-danger'>Sorry, your installation of Castor cannot currently support use of the REST API because the Castor files are stored in ".CASTORPATH_BASE." <br/><br/>If you want to use the REST API please move the files in this directory into the ".CASTORCONFIG_ABSOLUTE_PATH."/castor/ directory instead, then empty the ".CASTORCONFIG_ABSOLUTE_PATH."/castor/temp directory<br/><br/>Do NOT move the files in the directory above, you need the files in /plugins/castor to remain in place, only move the files in /plugins/castor/castor/</p>";
			return;
		}

			$output	 = array ();
		$pageoutput = array ();

		jr_import('castor_call_api');
		$castor_call_api = new castor_call_api('system');
		try {
			$response = $castor_call_api->send_request("GET", "core/report/");
		} catch (Exception $e) {
		   // throw new Exception('Error: invalid response from local API, received '.$e->getMessage() );
		}


		$api_keys = $castor_call_api->init_manager();

		$output['API_CLIENT'] = $api_keys['client_id'];
		$output['API_SECRET'] = $api_keys['client_secret'];

		$output[ '_OAUTH_APPS' ]		    = jr_gettext('_OAUTH_APPS', '_OAUTH_APPS', false);
		$output[ '_OAUTH_APIKEY' ]		= jr_gettext('_OAUTH_APIKEY', '_OAUTH_APIKEY', false);
		$output[ '_OAUTH_SECRET' ]		= jr_gettext('_OAUTH_SECRET', '_OAUTH_SECRET', false);

		$output['URL'] = $castor_call_api->server."core/report/";
		
		if (is_object($response)) {
			$output['RESPONSE'] = json_encode($response, JSON_PRETTY_PRINT);
		} else {
			$output['RESPONSE'] = $response;
		}
		
		
		if (isset($response->meta->code) && $response->meta->code == 200) {
			$test_passed = true;
			$output[ 'TEST_RESULT' ]		= jr_gettext('_CASTOR_REST_API_CONNECTIVITY_TEST_PASSED', '_CASTOR_REST_API_CONNECTIVITY_TEST_PASSED', false);
			$output[ 'STATUS' ]				= "success";
		} else {
			$test_passed = false;
			$output[ 'TEST_RESULT' ]		= jr_gettext('_CASTOR_REST_API_CONNECTIVITY_TEST_FAILED', '_CASTOR_REST_API_CONNECTIVITY_TEST_FAILED', false);
			$output[ 'STATUS' ]				= "danger";
		}
		
/*		$client = new GuzzleHttp\Client();

		$response = $client->request('POST', "https://app.castor.net/castor/api/get_sites/confirm/", [
			'form_params' => [

				'api_url' => urlencode(get_showtime('live_site').'/'.CASTOR_ROOT_DIRECTORY.'/api/')
				]
			]);

		$body				= json_decode((string)$response->getBody());

		if ($body->meta->code == "200" && $body->data->response == true ) {

			$output[ '_CASTOR_REST_API_SYNDICATION_TEST' ] = jr_gettext('_CASTOR_REST_API_CONNECTIVITY_TEST_SYNDICATION_NETWORK_CONFIRMATION_PASSED', '_CASTOR_REST_API_CONNECTIVITY_TEST_SYNDICATION_NETWORK_CONFIRMATION_PASSED', false);
			$output[ 'SYNDICATION_STATUS' ]				= "success";
		} else {
			$output[ '_CASTOR_REST_API_SYNDICATION_TEST' ] = jr_gettext('_CASTOR_REST_API_CONNECTIVITY_TEST_SYNDICATION_NETWORK_CONFIRMATION_FAILED', '_CASTOR_REST_API_CONNECTIVITY_TEST_SYNDICATION_NETWORK_CONFIRMATION_FAILED', false);
			$output[ 'SYNDICATION_STATUS' ]				= "danger";
			$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
			$siteConfig->update_setting('appServerRegister',0);
		}*/
		
		
		$output[ '_CASTOR_REST_API_CONNECTIVITY_TEST_SYNDICATION_NETWORK_CONFIRMATION_TITLE' ] = jr_gettext('_CASTOR_REST_API_CONNECTIVITY_TEST_SYNDICATION_NETWORK_CONFIRMATION_TITLE', '_CASTOR_REST_API_CONNECTIVITY_TEST_SYNDICATION_NETWORK_CONFIRMATION_TITLE', false);
		$output[ '_CASTOR_REST_API_CONNECTIVITY_TEST_SYNDICATION_NETWORK_CONFIRMATION_INTRO' ] = jr_gettext('_CASTOR_REST_API_CONNECTIVITY_TEST_SYNDICATION_NETWORK_CONFIRMATION_INTRO', '_CASTOR_REST_API_CONNECTIVITY_TEST_SYNDICATION_NETWORK_CONFIRMATION_INTRO', false);
		$output[ '_CASTOR_REST_API_CONNECTIVITY_TEST' ] = jr_gettext('_CASTOR_REST_API_CONNECTIVITY_TEST', '_CASTOR_REST_API_CONNECTIVITY_TEST', false);
		$output[ '_CASTOR_REST_API_CONNECTIVITY_TEST_INFO' ]		= jr_gettext('_CASTOR_REST_API_CONNECTIVITY_TEST_INFO', '_CASTOR_REST_API_CONNECTIVITY_TEST_INFO', false);
		$output[ '_CASTOR_REST_API_CONNECTIVITY_TEST_CALLED' ]		= jr_gettext('_CASTOR_REST_API_CONNECTIVITY_TEST_CALLED', '_CASTOR_REST_API_CONNECTIVITY_TEST_CALLED', false);
		$output[ '_CASTOR_REST_API_CONNECTIVITY_TEST_RESPONSE' ]	= jr_gettext('_CASTOR_REST_API_CONNECTIVITY_TEST_RESPONSE', '_CASTOR_REST_API_CONNECTIVITY_TEST_RESPONSE', false);
		
		$jrtbar = castor_singleton_abstract::getInstance('castor_toolbar');
		$jrtb  = $jrtbar->startTable();
		$jrtb .= $jrtbar->toolbarItem('cancel', CASTOR_SITEPAGE_URL_ADMIN, jr_gettext("COMMON_CANCEL", 'COMMON_CANCEL', false));
		$jrtb .= $jrtbar->endTable();
		$output['CASTORTOOLBAR']=$jrtb;

		$pageoutput[] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_ADMINISTRATOR);
		$tmpl->readTemplatesFromInput('rest_api_connectivity_test.html');
		$tmpl->addRows('pageoutput', $pageoutput);
		$tmpl->displayParsedTemplate();
	}

  

	function getRetVals()
	{
		return null;
	}
}

