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

class j06000gdpr_my_data
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
	 
	public function __construct($componentArgs)
	{
		// Must be in all minicomponents. Minicomponents with templates that can contain editable text should run $this->template_touch() else just return
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = false;
			$this->shortcode_data = array(
				'task' => 'gdpr_my_data',
				'info' => '_CASTOR_GDPR_MY_DATA',
				'arguments' => array()
				);

			return;
		}
		$thisJRUser = castor_singleton_abstract::getInstance('jr_user');
		jr_import('castor_gdpr_personal_information_collections');
		
		if (!$thisJRUser->userIsRegistered) {
			$castor_gdpr_optin_consent = new castor_gdpr_optin_consent();
			$opted_in = $castor_gdpr_optin_consent->user_consents_to_storage();
			
			if ($opted_in) {
					$result = array( "can_redact" => false , "reason" => jr_gettext('_CASTOR_GDPR_MY_RTBF_NOTREGISTERED_OPTEDIN', '_CASTOR_GDPR_MY_RTBF_NOTREGISTERED_OPTEDIN', false) );
			} else {
				$result = array( "can_redact" => false , "reason" => jr_gettext('_CASTOR_GDPR_MY_RTBF_NOTREGISTERED_OPTEDOUT', '_CASTOR_GDPR_MY_RTBF_NOTREGISTERED_OPTEDOUT', false) );
			}
		} else {
				$castor_gdpr_personal_information_collections = new castor_gdpr_personal_information_collections();
				$castor_gdpr_personal_information_collections->set_id($thisJRUser->id);
				$result = $castor_gdpr_personal_information_collections->can_redact_this_cms_user();
		}
		
		$pageoutput = array();
		
		if ($result['can_redact'] == true) {
			$output = array (
				"MESSAGE" =>$result['response']['main'] ,
				"NOTE" =>$result['response']['note'] ,
				"_CASTOR_GDPR_MY_RTBF_FORGET_ME" => jr_gettext('_CASTOR_GDPR_MY_RTBF_FORGET_ME', '_CASTOR_GDPR_MY_RTBF_FORGET_ME', false) ,
				"_CASTOR_GDPR_MY_RTBF_FORGET_ME_WARNING" => jr_gettext('_CASTOR_GDPR_MY_RTBF_FORGET_ME_WARNING', '_CASTOR_GDPR_MY_RTBF_FORGET_ME_WARNING', false)
			);
			
			$pageoutput[ ] = $output;
			$tmpl = new patTemplate();
			$tmpl->setRoot(CASTOR_TEMPLATEPATH_FRONTEND);
			$tmpl->addRows('pageoutput', $pageoutput);
			$tmpl->readTemplatesFromInput('gdpr_my_data_can_redact.html');
			$message = $tmpl->getParsedTemplate();
		} else {
			$output = array (
				"MESSAGE" =>$result['reason']);
			$pageoutput[ ] = $output;
			$tmpl = new patTemplate();
			$tmpl->setRoot(CASTOR_TEMPLATEPATH_FRONTEND);
			$tmpl->addRows('pageoutput', $pageoutput);
			$tmpl->readTemplatesFromInput('gdpr_my_data_cannot_redact.html');
			$message = $tmpl->getParsedTemplate();
		}
		
		
		$output = array();
		$pageoutput = array();
		
		$output['_CASTOR_GDPR_MY_DATA'] = jr_gettext('_CASTOR_GDPR_MY_DATA', '_CASTOR_GDPR_MY_DATA', false);
		$output['_CASTOR_GDPR_MY_DATA_PRIVACY_NOTICE'] = jr_gettext('_CASTOR_GDPR_MY_DATA_PRIVACY_NOTICE', '_CASTOR_GDPR_MY_DATA_PRIVACY_NOTICE', false);
		$output['_CASTOR_GDPR_MY_DATA_LEAD'] = jr_gettext('_CASTOR_GDPR_MY_DATA_LEAD', '_CASTOR_GDPR_MY_DATA_LEAD', false);
		$output['_CASTOR_GDPR_MY_DATA_INTRO'] = jr_gettext('_CASTOR_GDPR_MY_DATA_INTRO', '_CASTOR_GDPR_MY_DATA_INTRO', false);
		$output['_CASTOR_GDPR_MY_DATA_DOWNLOAD_TEXT'] = jr_gettext('_CASTOR_GDPR_MY_DATA_DOWNLOAD_TEXT', '_CASTOR_GDPR_MY_DATA_DOWNLOAD_TEXT', false);
		$output['_CASTOR_GDPR_MY_DATA_DOWNLOAD_BUTTON'] = jr_gettext('_CASTOR_GDPR_MY_DATA_DOWNLOAD_BUTTON', '_CASTOR_GDPR_MY_DATA_DOWNLOAD_BUTTON', false);
		$output['_CASTOR_GDPR_MY_RTBF_LEAD'] = jr_gettext('_CASTOR_GDPR_MY_RTBF_LEAD', '_CASTOR_GDPR_MY_RTBF_LEAD', false);
		$output['_CASTOR_GDPR_MY_RTBF_INTRO'] = jr_gettext('_CASTOR_GDPR_MY_RTBF_INTRO', '_CASTOR_GDPR_MY_RTBF_INTRO', false);
		$output['MESSAGE'] = $message;

		
		$pageoutput[ ] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_FRONTEND);
		$tmpl->addRows('pageoutput', $pageoutput);
		$tmpl->readTemplatesFromInput('gdpr_my_data.html');
		$tmpl->displayParsedTemplate();
	}


	public function getRetVals()
	{
		return null;
	}
}

