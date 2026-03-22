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

class j16000listGlobalroomTypes
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

		$castor_property_types = castor_singleton_abstract::getInstance('castor_property_types');
		$castor_property_types->get_all_property_types();

		$castor_room_types = castor_singleton_abstract::getInstance('castor_room_types');
		$castor_room_types->get_all_room_types(true);

		$output = array();
		$rows = array();

		$output[ 'PAGETITLE' ] = jr_gettext('_CASTOR_COM_MR_VRCT_ROOMTYPES_HEADER_LINK', '_CASTOR_COM_MR_VRCT_ROOMTYPES_HEADER_LINK', false);
		$output[ 'BACKLINK' ] = '<a href="javascript:submitbutton(\'cpanel\')">'.jr_gettext('_CASTOR_COM_MR_BACK', '_CASTOR_COM_MR_BACK', false).'</a>';
		$output[ 'HLINKTEXT' ] = jr_gettext('_CASTOR_COM_MR_VRCT_ROOMTYPES_HEADER_ABBV', '_CASTOR_COM_MR_VRCT_ROOMTYPES_HEADER_ABBV', false);
		$output[ 'HLINKTEXTCLONE' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTYFEATURES_HEADER_DESC', '_CASTOR_COM_MR_VRCT_PROPERTYFEATURES_HEADER_DESC', false);
		$output[ 'HRTTITLE' ] = jr_gettext('_CASTOR_COM_MR_VRCT_ROOMTYPES_HEADER_ABBV', '_CASTOR_COM_MR_VRCT_ROOMTYPES_HEADER_ABBV', false);
		$output[ 'HRTDESCRIPTION' ] = jr_gettext('_CASTOR_COM_MR_EB_ROOM_CLASS_DESC', '_CASTOR_COM_MR_EB_ROOM_CLASS_DESC', false);
		$output[ 'HCASTOR_A_ICON' ] = jr_gettext('_CASTOR_A_ICON', '_CASTOR_A_ICON', false);
		$output[ 'HPTYPE_ASSIGNMENT' ] = jr_gettext('_CASTOR_PROPERTY_TYPE_ASSIGNMENT', '_CASTOR_PROPERTY_TYPE_ASSIGNMENT', false);

		if (!empty($castor_room_types->room_types)) {
			foreach ($castor_room_types->room_types as $r) {
				$selected_ptype_rows = '';

				if (!empty($r['ptype_xref'])) {
					foreach ($r['ptype_xref'] as $k) {
						if (isset($castor_property_types->property_types[$k])) {
							$selected_ptype_rows .= $castor_property_types->property_types[$k]['ptype'].', ';
						}
					}
					
					$selected_ptype_rows = rtrim($selected_ptype_rows, ', ');
				}

				$r[ 'CHECKBOX' ] = '<input type="checkbox" id="cb'.count($rows).'" name="idarray[]" value="'.$r['room_classes_uid'].'" onClick="castor_isChecked(this.checked);">';
				$r[ 'RTTITLE' ] = $r['room_class_abbv'];
				$r[ 'RTDESCRIPTION' ] = $r['room_class_full_desc'];
				$r[ 'IMAGE' ] = CASTOR_IMAGELOCATION_RELPATH.'rmtypes/'.$r['image'];
				$r[ 'PROPERTY_TYPES' ] = $selected_ptype_rows;

				if (!using_bootstrap()) {
					$editIcon = '<img src="'.CASTOR_IMAGES_RELPATH.'castorimages/small/EditItem.png" border="0" />';
					$r[ 'EDITLINK' ] = '<a href="'.CASTOR_SITEPAGE_URL_ADMIN.'&task=editGlobalroomTypes&rmTypeUid='.$r['room_classes_uid'].'">'.$editIcon.'</a>';
				} else {
					$toolbar = castor_singleton_abstract::getInstance('castorItemToolbar');
					$toolbar->newToolbar();
					$toolbar->addItem('fa fa-pencil-square-o', 'btn btn-info', '', castorURL(CASTOR_SITEPAGE_URL_ADMIN.'&task=editGlobalroomTypes&rmTypeUid='.$r['room_classes_uid']), jr_gettext('COMMON_EDIT', 'COMMON_EDIT', false));

					$r[ 'EDITLINK' ] = $toolbar->getToolbar();
				}

				$rows[] = $r;
			}
		}

		$output[ 'COUNTER' ] = count($rows);
		$output[ 'TOTALINLISTPLUSONE' ] = count($rows) + 1;

		// Property type checks
		// We have a situation where a property type needs to be cross-referenced with room types, this check will ascertain if any property types are missing room types
		$output['PROPERTY_TYPE_WARNING'] = $castor_property_types->get_property_types_with_no_room_types_assigned();

		$jrtbar = castor_singleton_abstract::getInstance('castor_toolbar');
		$jrtb = $jrtbar->startTable();
		$jrtb .= $jrtbar->toolbarItem('cancel', CASTOR_SITEPAGE_URL_ADMIN, '');
		$image = $jrtbar->makeImageValid(CASTOR_IMAGES_RELPATH.'castorimages/small/AddItem.png');
		$link = CASTOR_SITEPAGE_URL_ADMIN;
		$jrtb .= $jrtbar->customToolbarItem('editGlobalroomTypes', $link, jr_gettext('_CASTOR_COM_MR_NEWTARIFF', '_CASTOR_COM_MR_NEWTARIFF', false), $submitOnClick = true, $submitTask = 'editGlobalroomTypes', $image);
		$image = $jrtbar->makeImageValid(CASTOR_IMAGES_RELPATH.'castorimages/small/WasteBasket.png');
		$link = CASTOR_SITEPAGE_URL_ADMIN;
		$jrtb .= $jrtbar->customToolbarItem('deleteGlobalroomTypes', $link, jr_gettext('_CASTOR_COM_MR_ROOM_DELETE', '_CASTOR_COM_MR_ROOM_DELETE', false), $submitOnClick = true, $submitTask = 'deleteGlobalroomTypes', $image);
		$jrtb .= $jrtbar->endTable();

		$output[ 'CASTORTOOLBAR' ] = $jrtb;

		$pageoutput[] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_ADMINISTRATOR);
		$tmpl->readTemplatesFromInput('list_room_types.html');
		$tmpl->addRows('pageoutput', $pageoutput);
		$tmpl->addRows('rows', $rows);
		$tmpl->displayParsedTemplate();
	}


	public function getRetVals()
	{
		return null;
	}
}

