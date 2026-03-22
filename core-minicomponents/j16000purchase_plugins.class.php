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

class j16000purchase_plugins
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

		$key_validation = castor_singleton_abstract::getInstance('castor_check_support_key');
		$key_validation->check_license_key(true); //only needed if we want to force a recheck
		$key_validation->remove_plugin_licenses_file();

		$items = castorGetParam($_REQUEST, 'items', '');
		$total = (float) castorGetParam($_REQUEST, 'total', 0);
		$username = castorGetParam($_REQUEST, 'username', '');
		$password = castorGetParam($_REQUEST, 'password', '');
		if ($username == '' || $password == '') {
			$output[ 'MESSAGE' ] = "Sorry, you didn't enter your username and/or your password.";
			$template = 'purchase_failure.html';
		} else {
			$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
			$jrConfig = $siteConfig->get();

			saveSiteConfig(array('license_server_username' => $username, 'license_server_password' => $password));

			$request = 'request=create_invoice&username='.$username.'&password='.$password.'&items='.$items;
			$response = query_shop($request);
			if ($response->success) {
				if ($total == 0) {
					$output[ 'MESSAGE' ] = 'Thank you for your request, you will be able to install the plugin(s) you requested through the plugin manager.';
				} else {
					$output[ 'MESSAGE' ] = 'Thank you for your purchase, a link to the invoice has been created and emailed to you. When the invoice has been paid you will be able to use the Castor Plugin Manager to install the plugin(s).';
				}
				$template = 'purchase_success.html';
			} else {
				$output[ 'MESSAGE' ] = 'Sorry, there was a problem creating the invoice, please double check your License Server username and password are correct.';
				$template = 'purchase_failure.html';
			}
		}

		$pageoutput[ ] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_ADMINISTRATOR);
		$tmpl->addRows('pageoutput', $pageoutput);

		$tmpl->readTemplatesFromInput($template);
		$tmpl->displayParsedTemplate();
	}


	public function getRetVals()
	{
		return null;
	}
}

