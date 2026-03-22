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

class j16000editGlobalroomTypes
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

		$room_classes_uid = intval(castorGetParam($_REQUEST, 'rmTypeUid', 0));
		$mrp_srp_flag = intval(castorGetParam($_REQUEST, 'mrp_srp_flag', 0));

		$output = array();
		$all_ptype_rows = array();

		$castor_property_types = castor_singleton_abstract::getInstance('castor_property_types');
		$castor_property_types->get_all_property_types();

		$castor_room_types = castor_singleton_abstract::getInstance('castor_room_types');
		$castor_room_types->get_room_type($room_classes_uid);

		$output[ 'ROOMCLASSUID' ] = $room_classes_uid;
		$output[ 'CLASSABBV' ] = stripslashes($castor_room_types->room_type['room_class_abbv']);

		$width="95%";
		$height="250";
		$col="20";
		$row="10";
		
		$output['CLASSDESC']=editorAreaText('room_class_desc', $castor_room_types->room_type['room_class_full_desc'], 'room_class_desc', $width, $height, $col, $row);
		
		if (!empty($castor_property_types->property_types)) {
			foreach ($castor_property_types->property_types as $ptype) {
				$r = array();
				$r[ 'propertytype_id' ] = $ptype['id'];
				$r[ 'propertytype_desc' ] = $ptype['ptype'];
				$r[ 'checked' ] = '';

				if (in_array($ptype['id'], $castor_room_types->room_type['ptype_xref'])) {
					$r[ 'checked' ] = ' checked ';
				}

				$all_ptype_rows[] = $r;
			}
		}

		$image = $castor_room_types->room_type['image'];

		//room type icons
		$images = $castor_room_types->get_all_room_type_images();
		
		$rows = array();
		
		foreach ($images as $i) {
			$i[ 'ISCHECKED' ] = '';
			
			if ($i[ 'IMAGE_FILENAME' ] == $image) {
				$i[ 'ISCHECKED' ] = 'checked';
			}
			
			$rows[] = $i;
		}

		$output[ 'PROPERTYFEATUREINFO' ] = jr_gettext('_CASTOR_A_GLOBALROOMTYPES_INFO', '_CASTOR_A_GLOBALROOMTYPES_INFO', false);
		$output[ 'HLINKTEXT' ] = jr_gettext('_CASTOR_COM_MR_VRCT_ROOMTYPES_LINKTEXT', '_CASTOR_COM_MR_VRCT_ROOMTYPES_LINKTEXT', false);
		$output[ 'HLINKTEXTCLONE' ] = jr_gettext('_CASTOR_COM_MR_LISTTARIFF_LINKTEXTCLONE', '_CASTOR_COM_MR_LISTTARIFF_LINKTEXTCLONE', false);
		$output[ 'HABBV' ] = jr_gettext('_CASTOR_SEARCH_RTYPES', '_CASTOR_SEARCH_RTYPES', false);
		$output[ 'HDESC' ] = jr_gettext('_CASTOR_COM_MR_EB_ROOM_CLASS_DESC', '_CASTOR_COM_MR_EB_ROOM_CLASS_DESC', false);
		$output[ 'PAGETITLE' ] = jr_gettext('_CASTOR_COM_MR_VRCT_ROOMTYPES_HEADER_LINK', '_CASTOR_COM_MR_VRCT_ROOMTYPES_HEADER_LINK', false);
		$output[ '_CASTOR_PROPERTY_TYPE_ASSIGNMENT' ] = jr_gettext('_CASTOR_PROPERTY_TYPE_ASSIGNMENT', '_CASTOR_PROPERTY_TYPE_ASSIGNMENT', false);
		$output[ '_CASTOR_IMAGE' ] = jr_gettext('_CASTOR_IMAGE', '_CASTOR_IMAGE', false);

		$jrtbar = castor_singleton_abstract::getInstance('castor_toolbar');
		$jrtb = $jrtbar->startTable();
		$image = $jrtbar->makeImageValid(CASTOR_IMAGES_RELPATH.'castorimages/small/Save.png');
		$link = CASTOR_SITEPAGE_URL_ADMIN;
		$jrtb .= $jrtbar->toolbarItem('cancel', CASTOR_SITEPAGE_URL_ADMIN.'&task=listGlobalroomTypes', '');
		$jrtb .= $jrtbar->customToolbarItem('saveGlobalRoomClass', $link, jr_gettext('_CASTOR_COM_MR_SAVE', '_CASTOR_COM_MR_SAVE', false), $submitOnClick = true, $submitTask = 'saveGlobalRoomClass', $image);
		$jrtb .= $jrtbar->endTable();
		$output[ 'CASTORTOOLBAR' ] = $jrtb;

		$pageoutput[ ] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_ADMINISTRATOR);
		$tmpl->readTemplatesFromInput('edit_room_type.html');
		$tmpl->addRows('pageoutput', $pageoutput);
		$tmpl->addRows('rows', $rows);
		$tmpl->addRows('all_ptype_rows', $all_ptype_rows);
		$tmpl->displayParsedTemplate();
	}


	public function getRetVals()
	{
		return null;
	}
}

