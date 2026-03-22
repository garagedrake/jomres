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

class j06002edit_room_type
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
		// Must be in all minicomponents. Minicomponents with templates that can contain editable text should run $this->template_touch() else just return
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = true;

			return;
		}
		
		$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
		$jrConfig = $siteConfig->get();
		if ($jrConfig[ 'frontend_room_type_editing_allowed' ] == 0) {
			return;
		}
		
		$property_uid = getDefaultProperty();

		$room_classes_uid = intval(castorGetParam($_REQUEST, 'room_classes_uid', 0));

		$output = array();

		$castor_room_types = castor_singleton_abstract::getInstance('castor_room_types');
		$castor_room_types->get_all_room_types();
		
		if ($room_classes_uid > 0) {
			$castor_room_types->validate_manager_access_to_room_type($room_classes_uid);
		}

		$room_class_ids = array();

		$castor_room_types->get_room_type($room_classes_uid);

		$output[ 'ROOMCLASSUID' ] = $room_classes_uid;
		if (isset($castor_room_types->property_specific_room_type[$property_uid][$room_classes_uid])) {
			$output[ 'CLASSABBV' ] = jr_gettext('_CASTOR_CUSTOMTEXT_ROOMTYPES_ABBV'.(int)$room_classes_uid, stripslashes($castor_room_types->property_specific_room_type[$property_uid][$room_classes_uid]['room_class_abbv']), false, false);
			$image = $castor_room_types->property_specific_room_type[$property_uid][$room_classes_uid]['image'];
			$output[ 'CLASSDESC' ] =jr_gettext('_CASTOR_CUSTOMTEXT_ROOMTYPES_DESC'.(int)$room_classes_uid, $castor_room_types->property_specific_room_type[$property_uid][$room_classes_uid]['room_class_full_desc'], false, false);
		} else {
			$output[ 'CLASSABBV' ] = '';
			$output[ 'CLASSDESC' ] = '';
			$image = '';
		}
			
		if ($jrConfig[ 'allowHTMLeditor' ] == '1') {
			$width = '95%';
			$height = '250';
			$col = '20';
			$row = '3';
			
			$output[ 'SIMPLEMDE_JAVASCRIPT' ] = '';
			$output[ 'MARKDOWN_BUTTON' ] = '';
			$output[ 'CLASSDESC' ] = editorAreaText('room_class_desc', $output[ 'CLASSDESC' ], 'room_class_desc', $width, $height, $col, $row);
		} else {
			castor_cmsspecific_addheaddata('javascript', CASTOR_NODE_MODULES_RELPATH.'simple-cmeditor/dist/', 'simplemde.min.js');
			castor_cmsspecific_addheaddata('css', CASTOR_NODE_MODULES_RELPATH.'simple-cmeditor/dist/', 'simplemde.min.css');
			
			$output[ 'SIMPLEMDE_JAVASCRIPT' ] = '
				<script type="text/javascript">
				castorJquery(document).ready(function () {
					var buttons =  ["bold", "italic", "heading", "strikethrough" , "|" , "unordered-list" , "ordered-list" , "clean-block" , "image" , "table" , "horizontal-rule" , "|", "preview" ];
					var simplemde = new SimpleMDE({ element: document.getElementById("room_description") ,toolbar: buttons, });
				});
				</script>';
			$output[ 'MARKDOWN_BUTTON' ] = $MiniComponents->specificEvent('06000', 'show_markdown_modal', array('output_now' => false));
			
			$output[ 'CLASSDESC' ] = '<textarea class="inputbox form-control" cols="70" rows="5" id="room_class_desc" name="room_class_desc">'.$output[ 'CLASSDESC' ].'</textarea>';
		}
			

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
		$link = CASTOR_SITEPAGE_URL;
		$jrtb .= $jrtbar->toolbarItem('cancel', CASTOR_SITEPAGE_URL.'&task=list_room_types', '');
		$jrtb .= $jrtbar->customToolbarItem('save_room_type', $link, jr_gettext('_CASTOR_COM_MR_SAVE', '_CASTOR_COM_MR_SAVE', false), $submitOnClick = true, $submitTask = 'save_room_type', $image);
		$jrtb .= $jrtbar->endTable();
		$output[ 'CASTORTOOLBAR' ] = $jrtb;

		$pageoutput[ ] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_BACKEND);
		$tmpl->readTemplatesFromInput('edit_room_type.html');
		$tmpl->addRows('pageoutput', $pageoutput);
		$tmpl->addRows('rows', $rows);
		$tmpl->displayParsedTemplate();
	}

	public function touch_template_language()
	{
		$output = array();

		$output[ ] = jr_gettext('_CASTOR_FRONT_MR_MENU_ADMIN_PROPERTYADMIN', '_CASTOR_FRONT_MR_MENU_ADMIN_PROPERTYADMIN');
		$output[ ] = jr_gettext('_CASTOR_COM_A_CANCEL', '_CASTOR_COM_A_CANCEL');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_ROOM_DELETE', '_CASTOR_COM_MR_ROOM_DELETE');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_SAVE', '_CASTOR_COM_MR_SAVE');
		$output[ ] = jr_gettext('_CASTOR_UPLOAD_IMAGE', '_CASTOR_UPLOAD_IMAGE');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_VRCT_TAB_ROOM', '_CASTOR_COM_MR_VRCT_TAB_ROOM');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_VRCT_ROOM_HEADER_TYPE', '_CASTOR_COM_MR_VRCT_ROOM_HEADER_TYPE');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_VRCT_ROOM_HEADER_NAME', '_CASTOR_COM_MR_VRCT_ROOM_HEADER_NAME');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_VRCT_ROOM_HEADER_NUMBER', '_CASTOR_COM_MR_VRCT_ROOM_HEADER_NUMBER');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_VRCT_ROOM_HEADER_FLOOR', '_CASTOR_COM_MR_VRCT_ROOM_HEADER_FLOOR');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_VRCT_ROOM_HEADER_MAXPEOPLE', '_CASTOR_COM_MR_VRCT_ROOM_HEADER_MAXPEOPLE');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_VRCT_ROOM_HEADER_FEATURES', '_CASTOR_COM_MR_VRCT_ROOM_HEADER_FEATURES');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_NO', '_CASTOR_COM_MR_NO');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_YES', '_CASTOR_COM_MR_YES');

		foreach ($output as $o) {
			echo $o;
			echo '<br/>';
		}
	}


	public function getRetVals()
	{
		return null;
	}
}

