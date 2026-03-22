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

class j06000show_property_room_type
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
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = false;
/*			$this->shortcode_data = array(
				'task' => 'show_property_room_type',
				'info' => '_CASTOR_SHORTCODES_06000SHOW_PROPERTY_ROOM_TYPE',
				'arguments' => array(0 => array(
						'argument' => 'property_uid',
						'arg_info' => '_CASTOR_SHORTCODES_06000SHOW_PROPERTY_ROOM_TYPES_ARG_PROPERTY_UID',
						'arg_example' => '1',
						),
						1 => array(
						'argument' => 'room_classes_uid',
						'arg_info' => '_CASTOR_SHORTCODES_06000SHOW_PROPERTY_ROOM_TYPE_ARG_ROOM_TYPE_ID',
						'arg_example' => '3',
						)
					),
				);*/

			return;
		}
		$this->retVals = '';

		if (isset($componentArgs[ 'property_uid' ])) {
			$property_uid = (int)$componentArgs[ 'property_uid' ];
		} else {
			$property_uid = (int)castorGetParam($_REQUEST, 'property_uid', 0);
		}
		
		if (isset($componentArgs[ 'room_classes_uid' ])) {
			$room_classes_uid = (int)$componentArgs[ 'room_classes_uid' ];
		} else {
			$room_classes_uid = (int)castorGetParam($_REQUEST, 'room_classes_uid', 0);
		}
		
		if (isset($componentArgs[ 'output_now' ])) {
			$output_now = $componentArgs[ 'output_now' ];
		} elseif (isset($_REQUEST[ 'output_now' ])) {
			$output_now = (bool) castorGetParam($_REQUEST, 'output_now', 1);
		} else {
			$output_now = true;
		}
		
		$castor_room_types = castor_singleton_abstract::getInstance('castor_room_types');
		$castor_room_types->get_all_room_types();
		
		$castor_room_types->get_room_type($room_classes_uid);
		
		$output = array();
		$pageoutput = array();
		
		if (isset($castor_room_types->property_specific_room_type[$property_uid][$room_classes_uid]['room_class_abbv'])) {
			jr_import('castor_markdown');
			$castor_markdown = new castor_markdown();


			$output[ 'ROOM_CLASS_ABBV' ] = jr_gettext('_CASTOR_CUSTOMTEXT_ROOMTYPES_ABBV'.(int) $room_classes_uid, $castor_room_types->property_specific_room_type[$property_uid][$room_classes_uid]['room_class_abbv']);
			$output[ 'ROOM_CLASS_FULL_DESC' ] = $castor_markdown->get_markdown(jr_gettext('_CASTOR_CUSTOMTEXT_ROOMTYPES_DESC'.(int) $room_classes_uid, $castor_room_types->property_specific_room_type[$property_uid][$room_classes_uid]['room_class_full_desc']));
		} else {
			$output[ 'ROOM_CLASS_ABBV' ] = jr_gettext('_CASTOR_CUSTOMTEXT_ROOMTYPES_ABBV'.(int) $room_classes_uid, $castor_room_types->room_types[$room_classes_uid]['room_class_abbv']);
			$output[ 'ROOM_CLASS_FULL_DESC' ] = jr_gettext('_CASTOR_CUSTOMTEXT_ROOMTYPES_DESC'.(int) $room_classes_uid, $castor_room_types->room_types[$room_classes_uid]['room_class_full_desc']);
		}

		$output['ROOMS'] = $MiniComponents->specificEvent('06000', 'show_property_rooms', array('output_now' => false, 'property_uid' => $property_uid, 'room_classes_uid' => $room_classes_uid ));
		
		$resource_type = 'room_types';
		$resource_id = $room_classes_uid;
		
		$castor_media_centre_images = castor_singleton_abstract::getInstance('castor_media_centre_images');
		$castor_media_centre_images->get_images($property_uid);
		if (isset($castor_media_centre_images->images [$resource_type] [$resource_id])) {
			$images = $castor_media_centre_images->images [$resource_type] [$resource_id];
			$slideshow = $MiniComponents->specificEvent('01060', 'slideshow', array('images' => $images ));
			$output['SLIDESHOW'] = $slideshow['slideshow'];
		} else {
			$output['SLIDESHOW'] = '';
		}
		
		

		
		$pageoutput[] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_FRONTEND);
		$tmpl->addRows('pageoutput', $pageoutput);

		$tmpl->readTemplatesFromInput('show_property_room_type.html');
		$template = $tmpl->getParsedTemplate();
		
		if ($output_now) {
			echo $template;
		} else {
			$this->retVals = $template;
		}
	}

	public function getRetVals()
	{
		return $this->retVals;
	}
}

