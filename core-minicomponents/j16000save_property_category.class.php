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

class j16000save_property_category
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

		$castor_property_categories = castor_singleton_abstract::getInstance('castor_property_categories');
		
		$castor_property_categories->id = (int)castorGetParam($_POST, 'id', 0);
		$castor_property_categories->title = castorGetParam($_POST, 'title', '');
		$castor_property_categories->description = castorGetParam($_POST, 'description', '');
		
		if ($castor_property_categories->title != '') {
			if ($castor_property_categories->id > 0) {
				$castor_property_categories->commit_update_property_category();
			} else {
				$castor_property_categories->commit_new_property_category();
			}
		} else {
			castorRedirect(castorURL(CASTOR_SITEPAGE_URL_ADMIN.'&task=edit_property_category'), 'Please enter a category title');
		}

		castorRedirect(castorURL(CASTOR_SITEPAGE_URL_ADMIN.'&task=list_property_categories'), '');
	}


	public function getRetVals()
	{
		return null;
	}
}

