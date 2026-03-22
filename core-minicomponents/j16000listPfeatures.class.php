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

class j16000listPfeatures
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
		
		$castor_property_features = castor_singleton_abstract::getInstance('castor_property_features');
		$castor_property_features->get_all_property_features();

		$castor_property_types = castor_singleton_abstract::getInstance('castor_property_types');
		$castor_property_types->get_all_property_types();

		$output[ 'PAGETITLE' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTYFEATURES_HEADER_LINK', '_CASTOR_COM_MR_VRCT_PROPERTYFEATURES_HEADER_LINK', false);
		$output[ 'HLINKTEXT' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTYFEATURES_ABBV', '_CASTOR_COM_MR_VRCT_PROPERTYFEATURES_ABBV', false);
		$output[ 'HPFEATURETITLE' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTYFEATURES_ABBV', '_CASTOR_COM_MR_VRCT_PROPERTYFEATURES_ABBV', false);
		$output[ 'HPFEATUREDESCRIPTION' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTYFEATURES_HEADER_DESC', '_CASTOR_COM_MR_VRCT_PROPERTYFEATURES_HEADER_DESC', false);
		$output[ 'HCASTOR_A_ICON' ] = jr_gettext('_CASTOR_A_ICON', '_CASTOR_A_ICON', false);
		$output[ 'HPROPERTY_TYPES' ] = jr_gettext('_CASTOR_FRONT_PTYPE', '_CASTOR_FRONT_PTYPE', false);
		$output[ 'HCATEGORY' ] = jr_gettext('_CASTOR_HCATEGORY', '_CASTOR_HCATEGORY', false);
		$output[ 'INCLUDEINFILTERS' ] = jr_gettext('INCLUDEINFILTERS', 'INCLUDEINFILTERS', false);


		foreach ($castor_property_features->property_features as $f) {
			$selected_ptype_rows = '';
			
			foreach ($f['ptype_xref'] as $ptype_id) {
				if (isset($castor_property_types->property_types[ $ptype_id ]['ptype'])) {
					$selected_ptype_rows .= $castor_property_types->property_types[ $ptype_id ]['ptype'].', ';
				}
			}
			
			$selected_ptype_rows = rtrim($selected_ptype_rows, ', ');

			$r[ 'PROPERTYFEATUREUID' ] = $f['id'];
			$r[ 'CHECKBOX' ] = '<input type="checkbox" id="cb'.count($rows).'" name="idarray[]" value="'.$f['id'].'" onClick="castor_isChecked(this.checked);">';
			$r[ 'PFEATURETITLE' ] = $f['abbv'];
			$r[ 'PFEATUREDESCRIPTION' ] = $f['desc'];
			$r[ 'PROPERTY_TYPES' ] = $selected_ptype_rows;
			$r[ 'IMAGE' ] = CASTOR_IMAGELOCATION_RELPATH . 'pfeatures/' . $f['image'];
			$r[ 'CATEGORY' ] = $f['cat_title'];

			if ($f['include_in_filters'] == "1") {
				$r[ 'INCLUDE_IN_FILTERS' ] = jr_gettext('_CASTOR_COM_MR_YES', '_CASTOR_COM_MR_YES', false);
			} else {
				$r[ 'INCLUDE_IN_FILTERS' ] = jr_gettext('_CASTOR_COM_MR_NO', '_CASTOR_COM_MR_NO', false);
			}
			
			$toolbar = castor_singleton_abstract::getInstance('castorItemToolbar');
			$toolbar->newToolbar();
			$toolbar->addItem('fa fa-pencil-square-o', 'btn btn-info', '', castorURL(CASTOR_SITEPAGE_URL_ADMIN.'&task=editPfeature&id='.$f['id']), jr_gettext('COMMON_EDIT', 'COMMON_EDIT', false));

			$r['EDITLINK'] = $toolbar->getToolbar();

			$rows[] = $r;
		}
		
		$output[ 'COUNTER' ] = count($rows);
		$output[ 'TOTALINLISTPLUSONE' ] = count($rows) + 1;

		$jrtbar = castor_singleton_abstract::getInstance('castor_toolbar');
		$jrtb = $jrtbar->startTable();
		$jrtb .= $jrtbar->toolbarItem('cancel', CASTOR_SITEPAGE_URL_ADMIN, '');
		$image = $jrtbar->makeImageValid(CASTOR_IMAGES_RELPATH.'castorimages/small/AddItem.png');
		$link = CASTOR_SITEPAGE_URL_ADMIN;
		$jrtb .= $jrtbar->customToolbarItem('editPfeature', $link, jr_gettext('_CASTOR_COM_MR_NEWTARIFF', '_CASTOR_COM_MR_NEWTARIFF', false), $submitOnClick = true, $submitTask = 'editPfeature', $image);
		$jrtb .= $jrtbar->spacer();
		$image = $jrtbar->makeImageValid(CASTOR_IMAGES_RELPATH.'castorimages/small/WasteBasket.png');
		$link = CASTOR_SITEPAGE_URL_ADMIN;
		$jrtb .= $jrtbar->customToolbarItem('deletePfeature', $link, jr_gettext('_CASTOR_COM_MR_ROOM_DELETE', '_CASTOR_COM_MR_ROOM_DELETE', false), $submitOnClick = true, $submitTask = 'deletePfeature', $image);
		$jrtb .= $jrtbar->endTable();
		$output[ 'CASTORTOOLBAR' ] = $jrtb;

		$pageoutput[] = $output;

		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_ADMINISTRATOR);
		$tmpl->readTemplatesFromInput('list_pfeatures.html');
		$tmpl->addRows('pageoutput', $pageoutput);
		$tmpl->addRows('rows', $rows);
		$tmpl->displayParsedTemplate();
	}


	public function getRetVals()
	{
		return null;
	}
}

