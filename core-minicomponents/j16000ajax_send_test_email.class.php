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

class j16000ajax_send_test_email
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

		if (trim($_REQUEST['test_email_address']) != '') {
			$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');

			$siteConfig->set_setting('alternate_smtp_use_settings', '1');

			$siteConfig->set_setting('default_from_address', castorGetParam($_REQUEST, 'default_from_address', ''));
			$siteConfig->set_setting('alternate_smtp_host', castorGetParam($_REQUEST, 'alternate_smtp_host', ''));
			$siteConfig->set_setting('alternate_smtp_port', castorGetParam($_REQUEST, 'alternate_smtp_port', ''));
			$siteConfig->set_setting('alternate_smtp_protocol', castorGetParam($_REQUEST, 'alternate_smtp_protocol', ''));
			$siteConfig->set_setting('alternate_smtp_username', castorGetParam($_REQUEST, 'alternate_smtp_username', ''));
			$siteConfig->set_setting('alternate_smtp_password', castorGetParam($_REQUEST, 'alternate_smtp_password', ''));

			$alternate_smtp_authentication = castorGetParam($_REQUEST, 'alternate_smtp_authentication', '');

			if ($alternate_smtp_authentication == 'true') {
				$siteConfig->set_setting('alternate_smtp_authentication', '1');
			} else {
				$siteConfig->set_setting('alternate_smtp_authentication', '0');
			}

			ob_start();

			$success = castorMailer(
				$siteConfig->get_setting('default_from_address'),
				'TEST EMAIL',
				castorGetParam($_REQUEST, 'test_email_address', ''),
				jr_gettext('_CASTOR_TEST_EMAIL_SUBJECT', '_CASTOR_TEST_EMAIL_SUBJECT', false),
				jr_gettext('_CASTOR_TEST_EMAIL_CONTENT', '_CASTOR_TEST_EMAIL_CONTENT', false),
				$mode = 1,
				array()
			);
			$contents = ob_get_contents();
			ob_end_clean();
			if ($success) {
				$response_array['status'] = true;
			} else {
				$response_array['status'] = false;
				$response_array['failure_message'] = $contents;
			}
		} else {
			$response_array['status'] = false;
			$response_array['failure_message'] = " The Email address to send the email to wasn't set.";
		}

		header('Content-type: application/json');
		echo json_encode($response_array);
	}


	public function getRetVals()
	{
		return null;
	}
}

