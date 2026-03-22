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

class j06000editingmode
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
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = false;
			return;
		}

		$this->retVals = '';

		if (isset($componentArgs[ 'output_now' ])) {
			$output_now = $componentArgs[ 'output_now' ];
		} elseif (isset($_REQUEST['output_now'])) {
			$output_now = (bool)$_REQUEST['output_now'];
		} else {
			$output_now = true;
		}

		$pageoutput = array();
		$output = array();

		$output['CASTOR_EDITING_MODE_HELP_TITLE'] = jr_gettext('CASTOR_EDITING_MODE_HELP_TITLE', 'CASTOR_EDITING_MODE_HELP_TITLE', false);
		$output['CASTOR_EDITING_MODE_HELP_LEAD'] = jr_gettext('CASTOR_EDITING_MODE_HELP_LEAD', 'CASTOR_EDITING_MODE_HELP_LEAD', false);
		$output['CASTOR_EDITING_MODE_HELP_INFO_1'] = jr_gettext('CASTOR_EDITING_MODE_HELP_INFO_1', 'CASTOR_EDITING_MODE_HELP_INFO_1', false);
		$output['CASTOR_EDITING_MODE_HELP_INFO_2'] = jr_gettext('CASTOR_EDITING_MODE_HELP_INFO_2', 'CASTOR_EDITING_MODE_HELP_INFO_2', false);
		$output['CASTOR_EDITING_MODE_HELP_STEPS_1_TITLE'] = jr_gettext('CASTOR_EDITING_MODE_HELP_STEPS_1_TITLE', 'CASTOR_EDITING_MODE_HELP_STEPS_1_TITLE', false);
		$output['CASTOR_EDITING_MODE_HELP_STEPS_1_TEXT'] = jr_gettext('CASTOR_EDITING_MODE_HELP_STEPS_1_TEXT', 'CASTOR_EDITING_MODE_HELP_STEPS_1_TEXT', false);
		$output['CASTOR_EDITING_MODE_HELP_STEPS_2_TITLE'] = jr_gettext('CASTOR_EDITING_MODE_HELP_STEPS_2_TITLE', 'CASTOR_EDITING_MODE_HELP_STEPS_2_TITLE', false);
		$output['CASTOR_EDITING_MODE_HELP_STEPS_2_TEXT'] = jr_gettext('CASTOR_EDITING_MODE_HELP_STEPS_2_TEXT', 'CASTOR_EDITING_MODE_HELP_STEPS_2_TEXT', false);
		$output['CASTOR_EDITING_MODE_HELP_STEPS_3_TITLE'] = jr_gettext('CASTOR_EDITING_MODE_HELP_STEPS_3_TITLE', 'CASTOR_EDITING_MODE_HELP_STEPS_3_TITLE', false);
		$output['CASTOR_EDITING_MODE_HELP_STEPS_3_TEXT'] = jr_gettext('CASTOR_EDITING_MODE_HELP_STEPS_3_TEXT', 'CASTOR_EDITING_MODE_HELP_STEPS_3_TEXT', false);
		$output['CASTOR_EDITING_MODE_HELP_STEPS_4_TITLE'] = jr_gettext('CASTOR_EDITING_MODE_HELP_STEPS_4_TITLE', 'CASTOR_EDITING_MODE_HELP_STEPS_4_TITLE', false);
		$output['CASTOR_EDITING_MODE_HELP_STEPS_4_TEXT'] = jr_gettext('CASTOR_EDITING_MODE_HELP_STEPS_4_TEXT', 'CASTOR_EDITING_MODE_HELP_STEPS_4_TEXT', false);
		$output['CASTOR_EDITING_MODE_HELP_STEPS_5_TITLE'] = jr_gettext('CASTOR_EDITING_MODE_HELP_STEPS_5_TITLE', 'CASTOR_EDITING_MODE_HELP_STEPS_5_TITLE', false);
		$output['CASTOR_EDITING_MODE_HELP_STEPS_5_TEXT'] = jr_gettext('CASTOR_EDITING_MODE_HELP_STEPS_5_TEXT', 'CASTOR_EDITING_MODE_HELP_STEPS_5_TEXT', false);


		$pageoutput[ ] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_BACKEND);
		$tmpl->readTemplatesFromInput('editing_mode_help.html');
		$tmpl->addRows('pageoutput', $pageoutput);
		$editing_mode_help_output = $tmpl->getParsedTemplate();


		if ($output_now) {
			echo $editing_mode_help_output;
		} else {
			$this->retVals = $editing_mode_help_output;
		}
	}

	public function getRetVals()
	{
		return $this->retVals;
	}
}

