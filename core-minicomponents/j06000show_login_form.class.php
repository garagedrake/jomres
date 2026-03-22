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

class j06000show_login_form
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
				'task' => 'show_login_form',
				'info' => '_CASTOR_SHORTCODE_LOGIN_FORM'
				);

			return;
		}
		$this->retVals = '';

		if (isset($componentArgs[ 'output_now' ])) {
			$output_now = $componentArgs[ 'output_now' ];
		} else {
			$output_now = true;
		}

		
		$output = array();
		$pageoutput = array();
		
		$reasonoutput = array();
		$reasonoutput [0]['LOGIN_REASON'] = '';
		if (isset($componentArgs[ 'login_reason' ])) {
			$reasonoutput [0]['LOGIN_REASON'] = $componentArgs[ 'login_reason' ];
		}
		
		$output['_CASTOR_LOGIN_USERNAME'] = jr_gettext('_CASTOR_LOGIN_USERNAME', '_CASTOR_LOGIN_USERNAME', false);
		$output['_CASTOR_LOGIN_PASSWORD'] = jr_gettext('_CASTOR_LOGIN_PASSWORD', '_CASTOR_LOGIN_PASSWORD', false);
		$output['_CASTOR_CUSTOMCODE_CASTORMAINMENU_LOGIN'] = jr_gettext('_CASTOR_CUSTOMCODE_CASTORMAINMENU_LOGIN', '_CASTOR_CUSTOMCODE_CASTORMAINMENU_LOGIN', false);
		$output['RETURN_URL'] = getCurrentUrl(true);
		
		$output['_CASTOR_LOGIN_RESET_MESSAGE'] = jr_gettext('_CASTOR_LOGIN_RESET_MESSAGE', '_CASTOR_LOGIN_RESET_MESSAGE', false);
		$output['_CASTOR_LOGIN_RESET_BUTTON'] = jr_gettext('_CASTOR_LOGIN_RESET_BUTTON', '_CASTOR_LOGIN_RESET_BUTTON', false);
		
		if (!this_cms_is_wordpress()) {
			$output['RESET_URL'] = get_showtime('live_site').'/index.php?option=com_users&task=reset&view=reset&lang='.get_showtime('lang_shortcode');
		} else {
			$output['RESET_URL'] = wp_lostpassword_url();
		}
		
		$pageoutput[] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_FRONTEND);
		$tmpl->addRows('pageoutput', $pageoutput);
		$tmpl->addRows('reasonoutput', $reasonoutput);
		$tmpl->readTemplatesFromInput('login_form.html');
		$result = $tmpl->getParsedTemplate();

		if ($output_now) {
			echo $result;
		} else {
			$this->retVals = $result;
		}
	}


	public function getRetVals()
	{
		return $this->retVals;
	}
}

