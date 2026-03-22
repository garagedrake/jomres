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

class j06000faq
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
		} else {
			$output_now = true;
		}

		$kb = castor_singleton_abstract::getInstance('castor_knowledgebase');

		$thisJRUser = castor_singleton_abstract::getInstance('jr_user');

		if ($thisJRUser->userIsManager) {
			if ($output_now) {
				echo $kb->get_manager_faq();
			} else {
				$this->retVals = $kb->get_manager_faq();
			}
		} else {
			if ($output_now) {
				echo $kb->get_guest_faq();
			} else {
				$this->retVals = $kb->get_guest_faq();
			}
		}
	}

	public function getRetVals()
	{
		return $this->retVals;
	}
}

