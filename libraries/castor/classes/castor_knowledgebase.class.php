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
	
	/**
	 *
	 * @package Castor\Core\Classes
	 *
	 */
	#[AllowDynamicProperties]
class castor_knowledgebase
{

	/**
	 *
	 *
	 *
	 */

	public function __construct()
	{
		$castor_language = castor_singleton_abstract::getInstance('castor_language');
		$castor_language->get_language('faq');

		$this->admin_faq = false;
		$this->manager_faq = false;
		$this->guest_faq = false;
	}
	
	/**
	 *
	 *
	 *
	 */

	// Get admin faq
	public function get_admin_faq()
	{
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');

		if (!$this->admin_faq) {
			$this->admin_faq = array();

			$MiniComponents->triggerEvent('07070');
		}

		return $this->build_faq($this->admin_faq);
	}
	
	/**
	 *
	 *
	 *
	 */

	// Get manager faq
	public function get_manager_faq()
	{
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');

		if (!$this->manager_faq) {
			$this->manager_faq = array();

			$MiniComponents->triggerEvent('07060');
		}

		return $this->build_faq($this->manager_faq);
	}
	
	/**
	 *
	 *
	 *
	 */

	// Get guest faq
	public function get_guest_faq()
	{
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');

		if (!$this->guest_faq) {
			$this->guest_faq = array();

			$MiniComponents->triggerEvent('07080');
		}

		return $this->build_faq($this->guest_faq);
	}
	
	/**
	 *
	 *
	 *
	 */

	public function build_faq($faq = array())
	{
		if (empty($faq)) {
			return false;
		}

		$output = array();
		$pageoutput = array();
		$category_rows = array();
		$counter = 1;

		foreach ($faq as $category => $qa) {
			$output = array();
			$pageoutput = array();
			$rows = array();

			$output['CATEGORY'] = jr_gettext($category, $category, false);

			foreach ($qa as $question_set) {
				$r = array();

				$r['COUNTER'] = $counter;
				$r['QUESTION'] = $question_set['question'];
				$r['ANSWER'] = $question_set['answer'];

				$rows[] = $r;
				++$counter;
			}

			$pageoutput[ ] = $output;
			$tmpl = new patTemplate();

			if (castor_cmsspecific_areweinadminarea()) {
				$tmpl->setRoot(CASTOR_TEMPLATEPATH_ADMINISTRATOR);
			} else {
				$tmpl->setRoot(CASTOR_TEMPLATEPATH_FRONTEND);
			}

			$tmpl->addRows('pageoutput', $pageoutput);
			$tmpl->addRows('rows', $rows);
			$tmpl->readTemplatesFromInput('faq_questions.html');

			$category_rows[]['CATEGORY'] = $tmpl->getParsedTemplate();
		}

		$output = array();
		$pageoutput = array();

		$output['_CASTOR_FAQ'] = jr_gettext('_CASTOR_FAQ', '_CASTOR_FAQ', false);

		$pageoutput[ ] = $output;
		$tmpl = new patTemplate();

		if (castor_cmsspecific_areweinadminarea()) {
			$tmpl->setRoot(CASTOR_TEMPLATEPATH_ADMINISTRATOR);
		} else {
			$tmpl->setRoot(CASTOR_TEMPLATEPATH_FRONTEND);
		}

		$tmpl->addRows('pageoutput', $pageoutput);
		$tmpl->addRows('questions', $category_rows);
		$tmpl->readTemplatesFromInput('faq_pane.html');

		return $tmpl->getParsedTemplate();
	}
}

