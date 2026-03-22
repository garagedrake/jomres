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

class j99998user_feedback
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
		
		if (get_showtime('no_html') == 1 || get_showtime('popup') == 1 || AJAXCALL) {
			return '';
		}

		$output = array();
		
		$castor_messaging = castor_singleton_abstract::getInstance('castor_messages');
		$messages = $castor_messaging->get_messages();
		
		$castor_user_feedback = castor_singleton_abstract::getInstance('castor_user_feedback');

		if (!empty($messages)) {
			foreach ($messages as $msg) {
				$castor_user_feedback->construct_message(array('message'=>$msg['message'], 'css_class'=>$msg['class']));
			}
		}

		//no need to run this if there are no feedback messages set
		if (empty($castor_user_feedback->user_feedback_messages)) {
			return;
		}

		$output[ 'MESSAGES' ] = $castor_user_feedback->get_messages();

		$pageoutput[ ] = $output;
		$tmpl = new patTemplate();
		$tmpl->addRows('pageoutput', $pageoutput);
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_FRONTEND);
		$tmpl->readTemplatesFromInput('user_feedback.html');
		$tmpl->displayParsedTemplate();
	}


	public function getRetVals()
	{
		return null;
	}
}

