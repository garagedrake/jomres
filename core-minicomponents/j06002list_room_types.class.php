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

class j06002list_room_types
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

			return;
		}

		$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
		$jrConfig = $siteConfig->get();
		if ($jrConfig[ 'frontend_room_type_editing_allowed' ] == 0) {
			return;
		}
		
		$property_uid = getDefaultProperty();
		
		$castor_room_types = castor_singleton_abstract::getInstance('castor_room_types');
		$castor_room_types->get_all_room_types();

		$output = array();
		$rows = array();

		$output[ 'PAGETITLE' ] = jr_gettext('_CASTOR_PROPERTY_ROOM_TYPES_EDIT', '_CASTOR_PROPERTY_ROOM_TYPES_EDIT', false);

		$output[ 'HLINKTEXT' ] = jr_gettext('_CASTOR_COM_MR_VRCT_ROOMTYPES_HEADER_ABBV', '_CASTOR_COM_MR_VRCT_ROOMTYPES_HEADER_ABBV', false);
		$output[ 'HRTTITLE' ] = jr_gettext('_CASTOR_COM_MR_VRCT_ROOMTYPES_HEADER_ABBV', '_CASTOR_COM_MR_VRCT_ROOMTYPES_HEADER_ABBV', false);
		$output[ '_CASTOR_PROPERTY_ROOM_TYPES_EDIT_LEAD' ] = jr_gettext('_CASTOR_PROPERTY_ROOM_TYPES_EDIT_LEAD', '_CASTOR_PROPERTY_ROOM_TYPES_EDIT_LEAD', false);

		if (!empty($castor_room_types->property_specific_room_types[$property_uid])) {
			foreach ($castor_room_types->property_specific_room_types[$property_uid] as $r) {
				$r[ 'RTTITLE' ] = jr_gettext('_CASTOR_CUSTOMTEXT_ROOMTYPES_ABBV'.$r['room_classes_uid'], stripslashes($r['room_class_abbv']), true) ;
				$r[ 'RTDESCRIPTION' ] = $r['room_class_full_desc'];
				$r[ 'IMAGE' ] = CASTOR_IMAGELOCATION_RELPATH.'rmtypes/'.$r['image'];


				if (!using_bootstrap()) {
					$editIcon = '<img src="'.CASTOR_IMAGES_RELPATH.'castorimages/small/EditItem.png" border="0" />';
					$r[ 'EDITLINK' ] = '<a href="'.CASTOR_SITEPAGE_URL.'&task=edit_room_type&room_classes_uid='.$r['room_classes_uid'].'">'.$editIcon.'</a>';
				} else {
					$toolbar = castor_singleton_abstract::getInstance('castorItemToolbar');
					$toolbar->newToolbar();
					$toolbar->addItem('fa fa-pencil-square-o', 'btn btn-info', '', castorURL(CASTOR_SITEPAGE_URL.'&task=edit_room_type&room_classes_uid='.$r['room_classes_uid']), jr_gettext('COMMON_EDIT', 'COMMON_EDIT', false));
					$toolbar->addSecondaryItem('fa fa-trash-o', '', '', castorURL(CASTOR_SITEPAGE_URL.'&task=delete_room_type&room_classes_uid='.$r['room_classes_uid']), jr_gettext('COMMON_DELETE', 'COMMON_DELETE', false));
					
					$r[ 'EDITLINK' ] = $toolbar->getToolbar();
				}

				$rows[] = $r;
			}
		}

		if (!using_bootstrap()) {
			$jrtbar = castor_singleton_abstract::getInstance('castor_toolbar');
			$jrtb = $jrtbar->startTable();
			$text = jr_gettext('_CASTOR_PROPERTY_ROOM_TYPES_NEW', '_CASTOR_PROPERTY_ROOM_TYPES_NEW', false, true);
			$link = CASTOR_SITEPAGE_URL.'&task=edit_room_type';
			$targetTask = 'edit_room_type';
			$image = CASTOR_IMAGES_RELPATH.'castorimages/'.$jrtbar->imageSize.'/guestAdd.png';
			$jrtb .= $jrtbar->customToolbarItem($targetTask, $link, $text, $submitOnClick = false, $submitTask = '', $image);
			$jrtb .= $jrtbar->endTable();
			$output[ 'CASTORTOOLBAR' ] = $jrtb;
		} else {
			$output[ 'NEW_ROOM_TYPE_URL' ] = castorUrl(CASTOR_SITEPAGE_URL.'&task=edit_room_type');
			$output[ '_CASTOR_PROPERTY_ROOM_TYPES_NEW' ] = jr_gettext('_CASTOR_PROPERTY_ROOM_TYPES_NEW', '_CASTOR_PROPERTY_ROOM_TYPES_NEW', false, true);
		}

		$pageoutput[] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_BACKEND);
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

