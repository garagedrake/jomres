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
	 * Shows the GDPR consent form if required. If config disables the form from being shown then consent is automatically assumed, optin records are saved and we move on.
	 *
	 */

class j00060show_gdpr_consent_form
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
			return;
		}

		$castor_gdpr_optin_consent = new castor_gdpr_optin_consent();
		$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
		$jrConfig = $siteConfig->get();
	
		if (!isset($_COOKIE['castor_gdpr_consent_form_processed']) && !AJAXCALL && get_showtime("task") != "show_consent_form") {
			if ($jrConfig[ 'enable_gdpr_compliant_fucntionality' ] == "1") {
				$consent_form = $MiniComponents->specificEvent('06000', 'show_consent_form', array ('output_now' => false));
				$output = array ("CONSENT_FORM" => $consent_form );
				$output['_CASTOR_GDPR_CONSENT_TRIGGER_FORM'] = jr_gettext('_CASTOR_GDPR_CONSENT_TRIGGER_FORM', '_CASTOR_GDPR_CONSENT_TRIGGER_FORM', false);

				$pageoutput[] = $output;
				$tmpl = new patTemplate();
				$tmpl->addRows('pageoutput', $pageoutput);
				$tmpl->setRoot(CASTOR_TEMPLATEPATH_FRONTEND);
				$tmpl->readTemplatesFromInput('consent_form_wrapper.html');
				echo $tmpl->getParsedTemplate();
			} else {
				$thisJRUser = castor_singleton_abstract::getInstance('jr_user');
				$castor_gdpr_optin_consent->optedin = true;
				$castor_gdpr_optin_consent->set_user_id($thisJRUser->id);
				$castor_gdpr_optin_consent->save_record();
			}
		}
	}



	public function getRetVals()
	{
		return null;
	}
}

