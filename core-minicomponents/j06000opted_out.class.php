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

class j06000opted_out
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
		
		$output = array();
		if (!isset($_REQUEST['url_already_forwarded']) && isset($_REQUEST['jr_redirect_url'])) {
			$output['RETURN_URL'] = jr_base64url_encode($_REQUEST['jr_redirect_url']);
		} else {
			$output['RETURN_URL'] = $_REQUEST['jr_redirect_url'];
		}
		
		$output['_CASTOR_GDPR_NOCONSENT_INTRO']						= jr_gettext('_CASTOR_GDPR_NOCONSENT_INTRO', '_CASTOR_GDPR_NOCONSENT_INTRO', false);
		$output['_CASTOR_GDPR_NOCONSENT_DIDNOTCONSENT']				= jr_gettext('_CASTOR_GDPR_NOCONSENT_DIDNOTCONSENT', '_CASTOR_GDPR_NOCONSENT_DIDNOTCONSENT', false);
		$output['_CASTOR_GDPR_NOCONSENT_DIDNOTCONSENT_LINK_TEXT']	= jr_gettext('_CASTOR_GDPR_NOCONSENT_DIDNOTCONSENT_LINK_TEXT', '_CASTOR_GDPR_NOCONSENT_DIDNOTCONSENT_LINK_TEXT', false);
		$output['_CASTOR_GDPR_NOCONSENT_DIDNOTCONSENT_LINK_CONTINUE']	= jr_gettext('_CASTOR_GDPR_NOCONSENT_DIDNOTCONSENT_LINK_CONTINUE', '_CASTOR_GDPR_NOCONSENT_DIDNOTCONSENT_LINK_CONTINUE', false);
		

		$pageoutput[ ] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_FRONTEND);
		$tmpl->readTemplatesFromInput('opted_out.html');
		$tmpl->addRows('pageoutput', $pageoutput);
		$tmpl->displayParsedTemplate();
	}

/**
 * Must be included in every mini-component.
 #
 * Returns any settings that the mini-component wants to send back to the calling script. In addition to being returned to the calling script they are put into an array in the mcHandler object as eg. $mcHandler->miniComponentData[$ePoint][$eName]
 */

	public function getRetVals()
	{
		return null;
	}
}

