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

class j07080faq_guest_questions
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

		$kb = castor_singleton_abstract::getInstance('castor_knowledgebase');

		//dummy faq
		$kb->guest_faq['_CASTOR_FAQ_GUEST_CATEGORY_SOMETHING'][] = array(
			'question' => jr_gettext('_CASTOR_FAQ_GUEST_QUESTION_SOMEQUESTION', '_CASTOR_FAQ_GUEST_QUESTION_SOMEQUESTION', false),
			'answer' => jr_gettext('_CASTOR_FAQ_GUEST_ANSWER_SOMEANSWER', '_CASTOR_FAQ_GUEST_ANSWER_SOMEANSWER', false),
			);
	}


	public function getRetVals()
	{
		return null;
	}
}

