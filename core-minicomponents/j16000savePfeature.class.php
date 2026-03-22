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

class j16000savePfeature
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

		$castor_property_features = castor_singleton_abstract::getInstance('castor_property_features');
		
		$castor_property_features->id					= (int)castorGetParam($_POST, 'id', 0);
		$castor_property_features->abbv					= castorGetParam($_POST, 'feature_abbv', '');
		$castor_property_features->desc					= castorGetParam($_POST, 'feature_description', '');
		$castor_property_features->ptype_xref			= castorGetParam($_POST, 'ptype_ids', array());
		$castor_property_features->image				= castorGetParam($_POST, 'image', '');
		$castor_property_features->cat_id				= (int) castorGetParam($_POST, 'cat_id', 0);
		$castor_property_features->include_in_filters	= (int) castorGetParam($_POST, 'include_in_filters', 0);
		
		if ($castor_property_features->abbv != '') {
			if ($castor_property_features->id == 0) {
				$castor_property_features->commit_new_property_feature();
			} else {
				$castor_property_features->commit_update_property_feature();
			}
		} else {
			castorRedirect(castorURL(CASTOR_SITEPAGE_URL_ADMIN.'&task=editPfeature&id=' . $castor_property_features->id), '');
		}

		castorRedirect(castorURL(CASTOR_SITEPAGE_URL_ADMIN.'&task=listPfeatures'), jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTYFEATURES_SAVE_UPDATE', '_CASTOR_COM_MR_VRCT_PROPERTYFEATURES_SAVE_UPDATE', false));
	}


	public function getRetVals()
	{
		return null;
	}
}

