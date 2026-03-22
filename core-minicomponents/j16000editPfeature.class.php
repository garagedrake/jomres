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

class j16000editPfeature
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
		$rows = array();
		$pageoutput = array();
		$all_ptype_rows = array();
		
		$castor_property_features = castor_singleton_abstract::getInstance('castor_property_features');
		
		$id = (int)castorGetParam($_REQUEST, 'id', 0);
 
		if ($id > 0) {
			$castor_property_features->get_property_feature($id);
		}
		
		$castor_property_features_categories = castor_singleton_abstract::getInstance('castor_property_features_categories');
		$castor_property_features_categories->get_all_property_features_categories();
		
		$castor_property_types = castor_singleton_abstract::getInstance('castor_property_types');
		$castor_property_types->get_all_property_types();
		
		$output[ 'HPROPERTY_TYPE' ] = jr_gettext('_CASTOR_FRONT_PTYPE', '_CASTOR_FRONT_PTYPE', false);
		
		$output[ 'PROPERTYFEATUREUID' ] = $id;
		$output[ 'FEATURE_ABBV' ] = $castor_property_features->abbv;
		$output[ 'FEATURE_DESCRIPTION' ] = $castor_property_features->desc;
		
		$image = $castor_property_features->image;
		$cat_id = $castor_property_features->cat_id;

		//property types list
		foreach ($castor_property_types->property_types as $p) {
			$r = array();
			
			$r[ 'propertytype_id' ] = $p['id'];
			$r[ 'propertytype_desc' ] = $p['ptype'];
			$r[ 'checked' ] = '';
			
			if (in_array($p['id'], $castor_property_features->ptype_xref)) {
				$r[ 'checked' ] = ' checked ';
			}
			
			$all_ptype_rows[] = $r;
		}

		//feature images
		$images = $castor_property_features->get_all_property_features_images();

		foreach ($images as $i) {
			$i[ 'ISCHECKED' ] = '';
			
			if ($i[ 'IMAGE_FILENAME' ] == $image) {
				$i[ 'ISCHECKED' ] = 'checked';
			}
			
			$rows[] = $i;
		}
	
		$output[ 'HLINKTEXT' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTYFEATURES_HEADER_LINK', '_CASTOR_COM_MR_VRCT_PROPERTYFEATURES_HEADER_LINK', false);
		$output[ 'HFEATUREABBV' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTYFEATURES_ABBV', '_CASTOR_COM_MR_VRCT_PROPERTYFEATURES_ABBV', false);
		$output[ 'HFEATUREDESCRIPTION' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTYFEATURES_HEADER_DESC', '_CASTOR_COM_MR_VRCT_PROPERTYFEATURES_HEADER_DESC', false);
		$output[ 'PAGETITLE' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTYFEATURES_HEADER_LINK', '_CASTOR_COM_MR_VRCT_PROPERTYFEATURES_HEADER_LINK', false);
		$output[ '_CASTOR_PROPERTY_TYPE_ASSIGNMENT' ] = jr_gettext('_CASTOR_PROPERTY_TYPE_ASSIGNMENT', '_CASTOR_PROPERTY_TYPE_ASSIGNMENT', false);
		$output[ 'HIMAGE' ] = jr_gettext('_CASTOR_A_ICON', '_CASTOR_A_ICON', false);

		$options = array();
		$options[] = castorHTML::makeOption(0, '');
		foreach ($castor_property_features_categories->property_features_categories as $c) {
			$options[] = castorHTML::makeOption($c['id'], $c['title']);
		}
		$output[ 'CATEGORY' ] = castorHTML::selectList($options, 'cat_id', '', 'value', 'text', $cat_id);
		$output[ 'HCATEGORY' ] = jr_gettext('_CASTOR_HCATEGORY', '_CASTOR_HCATEGORY');
		
		$output[ 'INCLUDEINFILTERS' ] = jr_gettext('INCLUDEINFILTERS', 'INCLUDEINFILTERS', false);
		$output[ 'INCLUDEINFILTERS_DESC' ] = jr_gettext('INCLUDEINFILTERS_DESC', 'INCLUDEINFILTERS_DESC', false);
		
		$yesno = array();
		$yesno[ ] = castorHTML::makeOption('0', jr_gettext('_CASTOR_COM_MR_NO', '_CASTOR_COM_MR_NO', false));
		$yesno[ ] = castorHTML::makeOption('1', jr_gettext('_CASTOR_COM_MR_YES', '_CASTOR_COM_MR_YES', false));

		$output[ 'FILTERS' ] = castorHTML::selectList($yesno, 'include_in_filters', '', 'value', 'text', $castor_property_features->include_in_filters);
		
		$jrtbar = castor_singleton_abstract::getInstance('castor_toolbar');
		$jrtb = $jrtbar->startTable();
		$image = $jrtbar->makeImageValid(CASTOR_IMAGES_RELPATH.'castorimages/small/Save.png');
		$link = CASTOR_SITEPAGE_URL_ADMIN;

		$jrtb .= $jrtbar->toolbarItem('cancel', CASTOR_SITEPAGE_URL_ADMIN.'&task=listPfeatures', '');
		$jrtb .= $jrtbar->customToolbarItem('savePfeature', $link, jr_gettext('_CASTOR_COM_MR_SAVE', '_CASTOR_COM_MR_SAVE', false), $submitOnClick = true, $submitTask = 'savePfeature', $image);
		$jrtb .= $jrtbar->endTable();
		$output[ 'CASTORTOOLBAR' ] = $jrtb;

		$pageoutput = array();
		$pageoutput[ ] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_ADMINISTRATOR);
		$tmpl->readTemplatesFromInput('edit_property_feature.html');
		$tmpl->addRows('pageoutput', $pageoutput);
		$tmpl->addRows('all_ptype_rows', $all_ptype_rows);
		$tmpl->addRows('rows', $rows);
		$tmpl->displayParsedTemplate();
	}


	public function getRetVals()
	{
		return null;
	}
}

