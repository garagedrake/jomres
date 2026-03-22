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

class j16000editPfeatureCategory
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
		
		$output = array();
		$pageoutput = array();
		
		$castor_property_features_categories = castor_singleton_abstract::getInstance('castor_property_features_categories');
		
		$id = (int)castorGetParam($_REQUEST, 'id', 0);

		if ($id > 0) {
			$castor_property_features_categories->get_property_features_category($id);
		}
		
		$output[ 'ID' ] = $castor_property_features_categories->id;
		$output[ 'TITLE' ] = $castor_property_features_categories->title;

		$output[ 'PAGETITLE' ] = jr_gettext('_CASTOR_PROPERTYFEATURES_HCATEGORIES_HEDIT', '_CASTOR_PROPERTYFEATURES_HCATEGORIES_HEDIT', false);
		$output[ 'HTITLE' ] = jr_gettext('_JRPORTAL_CRATE_TITLE', '_JRPORTAL_CRATE_TITLE', false);

		$jrtbar = castor_singleton_abstract::getInstance('castor_toolbar');
		$jrtb = $jrtbar->startTable();
		$image = $jrtbar->makeImageValid(CASTOR_IMAGES_RELPATH.'castorimages/small/Save.png');
		$link = CASTOR_SITEPAGE_URL_ADMIN;

		$jrtb .= $jrtbar->toolbarItem('cancel', CASTOR_SITEPAGE_URL_ADMIN.'&task=listPfeaturesCategories', '');
		$jrtb .= $jrtbar->customToolbarItem('savePfeatureCategory', $link, jr_gettext('_CASTOR_COM_MR_SAVE', '_CASTOR_COM_MR_SAVE', false), $submitOnClick = true, $submitTask = 'savePfeatureCategory', $image);
		$jrtb .= $jrtbar->endTable();
		$output[ 'CASTORTOOLBAR' ] = $jrtb;

		$pageoutput[ ] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_ADMINISTRATOR);
		$tmpl->readTemplatesFromInput('edit_property_feature_category.html');
		$tmpl->addRows('pageoutput', $pageoutput);
		$tmpl->displayParsedTemplate();
	}


	public function getRetVals()
	{
		return null;
	}
}

