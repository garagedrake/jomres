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

class j06000module_popup
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

		//add_gmaps_source();

		$property_uid = (int) castorGetParam($_REQUEST, 'id', 0);
		if ($property_uid == 0) {
			$property_uid = (int) castorGetParam($_REQUEST, 'property_uid', 0);
		}

		$mrConfig = getPropertySpecificSettings($property_uid);

		$result = '';
		$output = array();

		$current_property_details = castor_singleton_abstract::getInstance('basic_property_details');

		if ($property_uid > 0) {
			/*property_header($property_uid, false);*/


			$current_property_details->gather_data($property_uid);
			set_showtime('ptype_id', $current_property_details->ptype_id);

			$output[ 'PROPERTY_UID' ] = $property_uid;
			$output[ 'RANDOM_IDENTIFIER' ] = generateCastorRandomString(10);
			$output[ 'MOREINFORMATION' ] = jr_gettext('_CASTOR_COM_A_CLICKFORMOREINFORMATION', '_CASTOR_COM_A_CLICKFORMOREINFORMATION', $editable = false, true);
			$output[ 'MOREINFORMATIONLINK' ] = get_property_details_url($property_uid);

			//property description
			$output['PROPERTY_DESCRIPTION'] = $MiniComponents->specificEvent('06000', 'show_property_description', array('output_now' => false, 'property_uid' => $property_uid));

			//property features
			$output['FEATURES'] = $MiniComponents->specificEvent('06000', 'show_property_features', array('output_now' => false, 'property_uid' => $property_uid));

			//room types
			if ($mrConfig[ 'is_real_estate_listing' ] == 0) {
				$output['ROOM_TYPES'] = $MiniComponents->specificEvent('06000', 'show_property_room_types', array('output_now' => false, 'property_uid' => $property_uid));
			} else {
				$output['ROOM_TYPES'] = '';
			}

			//property prices from
			//$price_output				= get_property_price_for_display_in_lists( $property_uid );
			//$output[ 'PRICE_PRE_TEXT' ]  = $price_output[ 'PRE_TEXT' ];
			//$output[ 'PRICE_PRICE' ]	 = $price_output[ 'PRICE' ];
			//$output[ 'PRICE_POST_TEXT' ] = $price_output[ 'POST_TEXT' ];

			//calendar
			/*$this_task = get_showtime("task");
			set_showtime("task", "remoteavailability");
			$MiniComponents->specificEvent( '06000', 'remoteavailability',array("property_uid"=> $property_uid , "return_calendar" => true ) );
			$output[ 'CALENDAR' ] = $MiniComponents->miniComponentData[ '06000' ][ 'remoteavailability' ];
			set_showtime("task", $this_task);*/

			/*
			$castor_media_centre_images = castor_singleton_abstract::getInstance( 'castor_media_centre_images' );
			$output[ 'IMAGELARGE' ]  = $property_deets[ 'LIVESITE' ] ."/castor/assets/images/noimage.svg";
			$output[ 'IMAGEMEDIUM' ] = $property_deets[ 'LIVESITE' ] ."/castor/assets/images/noimage.svg";
			$output[ 'IMAGETHUMB' ]  = $property_deets[ 'LIVESITE' ] ."/castor/assets/images/noimage.svg";
			$castor_media_centre_images->get_images($propertys_uid, array('property'));
			if ($castor_media_centre_images->images['property'][0][0]['large'] != "")
				{
				$output[ 'IMAGELARGE' ]  = $castor_media_centre_images->images['property'][0][0]['large'];
				$output[ 'IMAGEMEDIUM' ] = $castor_media_centre_images->images['property'][0][0]['medium'];
				$output[ 'IMAGETHUMB' ]  = $castor_media_centre_images->images['property'][0][0]['small'];
				}
			*/

			$componentArgs = array('property_uid' => $property_uid, 'width' => '200', 'height' => '214');
			$MiniComponents->specificEvent('01050', 'x_geocoder', $componentArgs);
			$output[ 'MAP' ] = $MiniComponents->miniComponentData[ '01050' ][ 'x_geocoder' ];

			$pageoutput[] = $output;
			$tmpl = new patTemplate();
			$tmpl->setRoot(CASTOR_TEMPLATEPATH_FRONTEND);
			$tmpl->addRows('pageoutput', $pageoutput);
			$tmpl->readTemplatesFromInput('module_popup_contents.html');

			$result = $tmpl->getParsedTemplate();
		}
		echo $result;
	}


	public function getRetVals()
	{
		return null;
	}
}

