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
	 * Builds the property comparison page from the list properties page
	 *
	 */

class j06000typography
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
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = false;
			$this->shortcode_data = array(
					'task' => 'compare',
					'arguments' => array(0 => array(
							'argument' => 'property_uids',
							'arg_info' => '_CASTOR_SHORTCODES_06000COMPARE_ARG_PROPERTY_UIDS',
							'arg_example' => '12,8,7',
							),
						),
					'info' => '_CASTOR_SHORTCODES_06000COMPARE',
				);

			return;
		}


		$output = array();

			$pageoutput[ ] = $output;
			$tmpl = new patTemplate();
			$tmpl->addRows('pageoutput', $pageoutput);
			$tmpl->setRoot(CASTOR_TEMPLATEPATH_FRONTEND);
			$tmpl->readTemplatesFromInput('typography.html');
			$tmpl->displayParsedTemplate();
	}

	public function getRetVals()
	{
		return null;
	}
}

