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

class j06002edit_resource
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

		$defaultProperty = getDefaultProperty();

		$mrConfig = getPropertySpecificSettings();

		$roomUid = (int) castorGetParam($_REQUEST, 'roomUid', 0);
		$clone = (int) castorGetParam($_REQUEST, 'clone', 0);

		$output = array();
		$pageoutput = array();
		$max_max_people = 100;

		$basic_property_details = castor_singleton_abstract::getInstance('basic_property_details');
		$basic_property_details->gather_data($defaultProperty);

		$basic_room_details = castor_singleton_abstract::getInstance('basic_room_details');

		$cancelText = jr_gettext('_CASTOR_COM_A_CANCEL', '_CASTOR_COM_A_CANCEL', false);
		$deleteText = jr_gettext('_CASTOR_COM_MR_ROOM_DELETE', '_CASTOR_COM_MR_ROOM_DELETE', false);
		$saveText = jr_gettext('_CASTOR_COM_MR_SAVE', '_CASTOR_COM_MR_SAVE', false);

		if ($mrConfig[ 'singleRoomProperty' ] == '0') { //MRPs
			$room_features_uid			= '';
			$room_name					= '';
			$room_number				= '';
			$room_floor					= '';
			$room_classes_uid			= false;
			$max_people					= '2';
			$max_adults					= '2';
			$max_children				= '0';
			$singleperson_suppliment	= 0;
			$room_tagline	 			= '';
			$room_description			= '';
			$surcharge					= '';

			if ($roomUid > 0 && $basic_room_details->get_room($roomUid)) {
				$room_classes_uid			= $basic_room_details->room['room_classes_uid'];
				$room_features_uid			= $basic_room_details->room['room_features_uid'];
				$room_name					= $basic_room_details->room['room_name'];
				$room_number				= $basic_room_details->room['room_number'];
				$room_floor					= $basic_room_details->room['room_floor'];
				$max_people					= $basic_room_details->room['max_people'];
				$max_adults					= $basic_room_details->room['max_adults'];
				$max_children				= $basic_room_details->room['max_children'];
				$singleperson_suppliment	= $basic_room_details->room['singleperson_suppliment'];
				$room_tagline	 			= $basic_room_details->room['tagline'];
				$room_description			= $basic_room_details->room['description'];
				$surcharge					= $basic_room_details->room['surcharge'];
			}

			//dropdown with room types assigned to this property type
			$classOptions = array();
			foreach ($basic_property_details->this_property_room_classes as $key => $roomClass) {
				if (!is_null($roomClass)) {
					$classOptions[] = castorHTML::makeOption($key, $roomClass[ 'abbv' ]);
				}
			}
			$output[ 'TYPEDROPDOWN' ] = castorHTML::selectList($classOptions, 'roomClasses', '', 'value', 'text', $room_classes_uid);

			//room features TODO: build a class for room features
			$ptype_id = $basic_property_details->ptype_id;

			if ($roomUid > 0) {
				$roomFeaturesArray = explode(',', $room_features_uid);
			} else {
				$roomFeaturesArray = array();
			}

			$featureListTxt = '';
			$query = "SELECT room_features_uid,feature_description,ptype_xref FROM #__castor_room_features WHERE property_uid = '".(int) $defaultProperty."' OR property_uid = '0' ORDER BY feature_description ";
			$roomFeaturesList = doSelectSql($query);
			foreach ($roomFeaturesList as $roomFeature) {
				$checked = '';
				if ($roomFeature->ptype_xref) {
					$ptype_xref = unserialize($roomFeature->ptype_xref);
					if (in_array($ptype_id, $ptype_xref)) {
						if (in_array(($roomFeature->room_features_uid), $roomFeaturesArray)) {
							$checked = 'checked';
						}
						$featureListTxt .= '<input type="checkbox" name="features_list[]" value="'.($roomFeature->room_features_uid).'" '.$checked.' >'.jr_gettext('_CASTOR_CUSTOMTEXT_ROOMFEATURE_DESCRIPTION'.(int) $roomFeature->room_features_uid, stripslashes($roomFeature->feature_description), false, false).'<br>';
					}
				} else {
					if (in_array(($roomFeature->room_features_uid), $roomFeaturesArray)) {
						$checked = 'checked';
					}
					$featureListTxt .= '<input type="checkbox" name="features_list[]" value="'.($roomFeature->room_features_uid).'" '.$checked.' >'.jr_gettext('_CASTOR_CUSTOMTEXT_ROOMFEATURE_DESCRIPTION'.(int) $roomFeature->room_features_uid, stripslashes($roomFeature->feature_description), false, false).'<br>';
				}
			}

			//$output[ 'MAXPEOPLE_DROPDOWN' ] = castorHTML::integerSelectList(1, $max_max_people, 1, 'max_people', '', $max_people);
			$output[ 'MAXADULTS_DROPDOWN' ] = castorHTML::integerSelectList(1, $max_max_people, 1, 'max_adults', '', $max_adults);
			$output[ 'MAXCHILDREN_DROPDOWN' ] = castorHTML::integerSelectList(0, $max_max_people, 1, 'max_children', '', $max_children);

			$output[ 'ROOMNAME' ]				= $room_name;
			$output[ 'ROOMNUMBER' ]				= $room_number;
			$output[ 'ROOMFLOOR' ]				= $room_floor;
			$output[ 'FEATURES' ]				= $featureListTxt;
			$output[ 'SUPPLIMENT' ]				= $singleperson_suppliment;
			$output[ 'ROOM_TAGLINE' ]	 		= $room_tagline;
			$output[ 'ROOM_DESCRIPTION' ]		= $room_description;
			$output[ 'SURCHARGE' ]				= $surcharge;

			$output[ 'IMAGE' ] = '<img src="'.getImageForProperty('room', $defaultProperty, (int) $roomUid).'" />';

			$output[ 'HTYPE' ] = jr_gettext('_CASTOR_COM_MR_VRCT_ROOM_HEADER_TYPE', '_CASTOR_COM_MR_VRCT_ROOM_HEADER_TYPE', false, false);
			$output[ 'HNAME' ] = jr_gettext('_CASTOR_COM_MR_VRCT_ROOM_HEADER_NAME', '_CASTOR_COM_MR_VRCT_ROOM_HEADER_NAME', false, false);
			$output[ 'HNUMBER' ] = jr_gettext('_CASTOR_COM_MR_VRCT_ROOM_HEADER_NUMBER', '_CASTOR_COM_MR_VRCT_ROOM_HEADER_NUMBER', false, false);
			$output[ 'HFLOOR' ] = jr_gettext('_CASTOR_COM_MR_VRCT_ROOM_HEADER_FLOOR', '_CASTOR_COM_MR_VRCT_ROOM_HEADER_FLOOR', false, false);
			$output[ 'HMAXPEOPLE' ] = jr_gettext('_CASTOR_COM_MR_VRCT_ROOM_HEADER_MAXPEOPLE', '_CASTOR_COM_MR_VRCT_ROOM_HEADER_MAXPEOPLE', false, false);
			$output[ 'HFEATURES' ] = jr_gettext('_CASTOR_COM_MR_VRCT_ROOM_HEADER_FEATURES', '_CASTOR_COM_MR_VRCT_ROOM_HEADER_FEATURES', false, false);
			$output[ 'HSUPPLIMENT' ] = jr_gettext('_CASTOR_COM_A_SUPPLIMENTS_SINGLEPERSON', '_CASTOR_COM_A_SUPPLIMENTS_SINGLEPERSON', false, false);
			$output[ 'SUPPLIMENT_DESC' ] = jr_gettext('_CASTOR_COM_SPS_EDITROOM_DESC', '_CASTOR_COM_SPS_EDITROOM_DESC', false, false);
			$output[ 'PAGETITLE' ] = jr_gettext('_CASTOR_COM_MR_EB_HROOM_DETAILS', '_CASTOR_COM_MR_EB_HROOM_DETAILS', false);
			$output[ 'HROOM_TAGLINE' ] = jr_gettext('_CASTOR_ROOM_TAGLINE', '_CASTOR_ROOM_TAGLINE', false);
			$output[ 'HROOM_DESCRIPTION' ] = jr_gettext('_CASTOR_ROOM_DESCRIPTION', '_CASTOR_ROOM_DESCRIPTION', false);
			$output[ '_CASTOR_SURCHARGE_TITLE' ] = jr_gettext('_CASTOR_SURCHARGE_TITLE', '_CASTOR_SURCHARGE_TITLE', false);
			$output[ '_CASTOR_SURCHARGE_DESC' ] = jr_gettext('_CASTOR_SURCHARGE_DESC', '_CASTOR_SURCHARGE_DESC', false);
			$output[ 'CASTOR_OCCUPANCY_LEVELS_MAX_ADULTS' ]		= jr_gettext('CASTOR_OCCUPANCY_LEVELS_MAX_ADULTS', 'CASTOR_OCCUPANCY_LEVELS_MAX_ADULTS', false);
			$output[ 'CASTOR_OCCUPANCY_LEVELS_MAX_CHILDREN' ]	= jr_gettext('CASTOR_OCCUPANCY_LEVELS_MAX_CHILDREN', 'CASTOR_OCCUPANCY_LEVELS_MAX_CHILDREN', false);

			if ($jrConfig[ 'allowHTMLeditor' ] == '1') {
				$width = '95%';
				$height = '250';
				$col = '20';
				$row = '3';

				$output[ 'ROOM_DESCRIPTION' ] = editorAreaText('room_description', $room_description, 'room_description', $width, $height, $col, $row);
			} else {
				castor_cmsspecific_addheaddata('javascript', CASTOR_NODE_MODULES_RELPATH.'simple-cmeditor/dist/', 'simplemde.min.js');
				castor_cmsspecific_addheaddata('css', CASTOR_NODE_MODULES_RELPATH.'simple-cmeditor/dist/', 'simplemde.min.css');
				
				$output['SIMPLEMDE_JAVASCRIPT'] = '
					<script type="text/javascript">
					castorJquery(document).ready(function () {
						var buttons =  ["bold", "italic", "heading", "strikethrough" , "|" , "unordered-list" , "ordered-list" , "clean-block" , "image" , "table" , "horizontal-rule" , "|", "preview" ];
						var simplemde = new SimpleMDE({ element: document.getElementById("room_description") ,toolbar: buttons, });
					});
					</script>';

				$output[ 'MARKDOWN_BUTTON' ] = $MiniComponents->specificEvent('06000', 'show_markdown_modal', array('output_now' => false));
				
				$output[ 'ROOM_DESCRIPTION' ] = '<textarea class="inputbox form-control" cols="70" rows="5" id="room_description" name="room_description">'.castor_remove_HTML($room_description, '').'</textarea>';
			}

			if ($clone > 0) {
				$output[ 'ROOMUID' ] = 0;
			} else {
				$output[ 'ROOMUID' ] = $roomUid;
			}

			$jrtbar = castor_singleton_abstract::getInstance('castor_toolbar');
			$jrtb = $jrtbar->startTable();
			$jrtb .= $jrtbar->toolbarItem('cancel', castorURL(CASTOR_SITEPAGE_URL.'&task=list_resources'), $cancelText);
			$jrtb .= $jrtbar->toolbarItem('save', '', $saveText, true, 'save_resource');
			$jrtb .= $jrtbar->endTable();
			$output[ 'CASTORTOOLBAR' ] = $jrtb;

			$pageoutput[] = $output;
			$tmpl = new patTemplate();
			$tmpl->setRoot(CASTOR_TEMPLATEPATH_BACKEND);
			$tmpl->readTemplatesFromInput('edit_resource.html');
			$tmpl->addRows('pageoutput', $pageoutput);
			$tmpl->displayParsedTemplate();
		} else { //SRPs
			$room_classes_uid = false;
			$max_people = '10';

			if ($roomUid > 0 && $basic_room_details->get_room($roomUid)) {
				$room_classes_uid = $basic_room_details->room['room_classes_uid'];
				$max_people = $basic_room_details->room['max_people'];
			}

			//dropdown with room types assigned to this property type
			$classOptions = array();
			foreach ($basic_property_details->this_property_room_classes as $key => $roomClass) {
				if (!is_null($roomClass)) {
					$classOptions[] = castorHTML::makeOption($key, $roomClass[ 'abbv' ]);
				}
			}
			$output[ 'DROPDOWNLIST' ] = castorHTML::selectList($classOptions, 'roomClasses', '', 'value', 'text', $room_classes_uid);

			$output[ 'MAXPEOPLE_DROPDOWN' ] = castorHTML::integerSelectList(1, $max_max_people, 1, 'max_people', '', $max_people);

			if ($clone > 0) {
				$output[ 'ROOMUID' ] = 0;
			} else {
				$output[ 'ROOMUID' ] = $roomUid;
			}

			$jrtbar = castor_singleton_abstract::getInstance('castor_toolbar');
			$jrtb = $jrtbar->startTable();
			$jrtb .= $jrtbar->toolbarItem('cancel', castorURL(CASTOR_SITEPAGE_URL.'&task=list_resources'), $cancelText);
			$jrtb .= $jrtbar->toolbarItem('save', '', $saveText, true, 'save_resource');
			$jrtb .= $jrtbar->endTable();
			$output[ 'CASTORTOOLBAR' ] = $jrtb;

			$output[ 'PAGETITLE' ] = jr_gettext('_CASTOR_COM_MR_EB_ROOM_CLASS_ABBV', '_CASTOR_COM_MR_EB_ROOM_CLASS_ABBV');
			$output[ '_CASTOR_COM_MR_VRCT_PROPERTY_TYPE_INFO' ] = jr_gettext('_CASTOR_COM_MR_EB_ROOM_CLASS_ABBV', '_CASTOR_COM_MR_EB_ROOM_CLASS_ABBV');
			$output[ '_CASTOR_COM_MR_VRCT_ROOM_HEADER_MAXPEOPLE' ] = jr_gettext('_CASTOR_COM_MR_VRCT_ROOM_HEADER_MAXPEOPLE', '_CASTOR_COM_MR_VRCT_ROOM_HEADER_MAXPEOPLE');

			$pageoutput[ ] = $output;
			$tmpl = new patTemplate();
			$tmpl->setRoot(CASTOR_TEMPLATEPATH_BACKEND);
			$tmpl->readTemplatesFromInput('edit_SRP_propertytype.html');
			$tmpl->addRows('pageoutput', $pageoutput);
			$tmpl->displayParsedTemplate();
		}
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

