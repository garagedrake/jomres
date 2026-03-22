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

class j06002save_email
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
		$MiniComponents = castor_getSingleton('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = false;

			return;
		}

		$email_subject = castorGetParam($_POST, 'email_subject', '');
		$email_text = castorGetParam($_POST, 'email_text', '');
		$email_type = castorGetParam($_POST, 'email_type', '');

		if ($email_type == '' || $email_text == '' || $email_subject == '') {
			if ($email_type == '') {
				echo '<p class="alert alert-error">Error: Email type not valid.<p>';
			}
			if ($email_text == '') {
				echo '<p class="alert alert-error">Error: Email text can`t be blank.<p>';
			}
			if ($email_subject == '') {
				echo '<p class="alert alert-error">Error: Email subject can`t be blank.<p>';
			}
		} else {
			updateCustomText('_EMAIL_TEXT_'.$email_type, $email_text, true);
			updateCustomText('_EMAIL_SUBJECT_'.$email_type, $email_subject, true);

			castorRedirect(castorURL(CASTOR_SITEPAGE_URL.'&task=list_emails'), '');
		}
	}


	public function getRetVals()
	{
		return null;
	}
}

