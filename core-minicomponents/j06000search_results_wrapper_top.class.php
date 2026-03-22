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

class j06000search_results_wrapper_top
{

	/**
	 *
	 * Constructor
	 *
	 * This minicomponent + wrapper_bottom and it's corresponding templates are holding scripts which don't do anything in castor Core. They're intended to provide a mechanism for plugins to provide their own functionality to the search results page (e.g. sidebar widgets, columns, things like that)
	 *
	 *
	 *
	 */
	 
	public function __construct()
	{
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = false;

			return;
		}

        if (AJAXCALL){
            return;
        }

        echo simple_template_output(CASTOR_TEMPLATEPATH_FRONTEND, 'search_results_wrapper_top.html', '' );
	}


	public function getRetVals()
	{
		return null;
	}
}

