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

	class j06002list_resources
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
				$this->template_touchable = false;

				return;
			}

			$defaultProperty = getDefaultProperty();
			$mrConfig = getPropertySpecificSettings($defaultProperty);
			$output = array();


			// If a room/property type is changed we can end up with duplicated but incorrect occupancy levels, so we'll recalculate them here and then redirect to the edit resource page instead of listing resources as there're only ever one
			if ($mrConfig[ 'singleRoomProperty' ] == '1') {
				jr_import('jrportal_rooms');
				$jrportal_rooms = new jrportal_rooms();


				$current_property_details = castor_singleton_abstract::getInstance('basic_property_details');
				$current_property_details->gather_data_multi(array( $defaultProperty ));

				$basic_room_details = castor_singleton_abstract::getInstance('basic_room_details');
				$basic_room_details->get_all_rooms($defaultProperty);
				$first_key = array_key_first($basic_room_details->rooms);
				if (!isset($basic_room_details->rooms[$first_key]['room_classes_uid']) || empty($basic_room_details->rooms) ) { // Somehow, when changing the property's type, the property's room wasn't created, so we'll create a new one here
					$jrportal_rooms = new jrportal_rooms();
					$first_room_type_id = array_key_first($current_property_details->this_property_room_classes);

					$jrportal_rooms->propertys_uid				= $defaultProperty;
					$jrportal_rooms->room_uid					= 0;
					$jrportal_rooms->room_classes_uid			= $first_room_type_id;
					$jrportal_rooms->max_people					= 2;
					$jrportal_rooms->max_adults					= 2;
					$jrportal_rooms->max_children				= 0;
					$jrportal_rooms->commit_new_room();

					$basic_room_details->get_all_rooms($defaultProperty);
					$first_key = array_key_first($basic_room_details->rooms);
				}
				$the_correct_room_type_id = $basic_room_details->rooms[$first_key]['room_classes_uid'];

				jr_import('castor_occupancy_levels');
				$castor_occupancy_levels = new castor_occupancy_levels($defaultProperty);
				foreach ($castor_occupancy_levels->occupancy_levels as $key => $val) {
					if ($key != $the_correct_room_type_id) {
						unset($castor_occupancy_levels->occupancy_levels[$key]);
					} else {
						$rooms_max_adults		= 0;
						foreach ($current_property_details->multi_query_result[$defaultProperty][ 'rooms_max_adults' ] as $rooms ) {
							$rooms_max_adults = $rooms_max_adults + array_sum($rooms);
						}
						$rooms_max_children		= 0;
						foreach ($current_property_details->multi_query_result[$defaultProperty][ 'rooms_max_children' ] as $rooms ) {
							$rooms_max_children = $rooms_max_children + array_sum($rooms);
						}
						$rooms_max_occupancy	= $rooms_max_adults + $rooms_max_children;

						$castor_occupancy_levels->set_occupancy_level( $the_correct_room_type_id, $rooms_max_adults, $rooms_max_children, $rooms_max_occupancy );
						$room_uid =  $basic_room_details->rooms[$first_key]["room_uid"];
					}
				}

				$castor_occupancy_levels->save_occupancy_levels($the_correct_room_type_id);
				castorRedirect(castorURL(CASTOR_SITEPAGE_URL.'&task=edit_resource&roomUid='.$room_uid), '');
			}

			$castor_media_centre_images = castor_singleton_abstract::getInstance('castor_media_centre_images');
			$castor_media_centre_images->get_images($defaultProperty, array('rooms'));

			$current_property_details = castor_singleton_abstract::getInstance('basic_property_details');
			$current_property_details->gather_data($defaultProperty);

			$basic_room_details = castor_singleton_abstract::getInstance('basic_room_details');
			$basic_room_details->get_all_rooms($defaultProperty);

			$toolbar = castor_singleton_abstract::getInstance('castorItemToolbar');

			$number_of_rooms = count($basic_room_details->rooms);

			$output['CASTORTOOLBAR'] = '';
			if ($mrConfig[ 'singleRoomProperty' ] == '1' && $number_of_rooms < 1) {
				$jrtbar = castor_singleton_abstract::getInstance('castor_toolbar');
				$jrtb = $jrtbar->startTable();
				$jrtb .= $jrtbar->toolbarItem('new', castorURL(CASTOR_SITEPAGE_URL.'&task=edit_resource'), '');
				$jrtb .= $jrtbar->endTable();
				$output['CASTORTOOLBAR'] = $jrtb;
			}
			if ($mrConfig[ 'singleRoomProperty' ] == '0') {
				$jrtbar = castor_singleton_abstract::getInstance('castor_toolbar');
				$jrtb = $jrtbar->startTable();
				$jrtb .= $jrtbar->toolbarItem('new', castorURL(CASTOR_SITEPAGE_URL.'&task=edit_resource'), '');
				$jrtb .= $jrtbar->toolbarItem('new', castorURL(CASTOR_SITEPAGE_URL.'&task=create_multiple_resources'), jr_gettext('_CASTOR_MULTIPLE_RESOURCES_TITLE', '_CASTOR_MULTIPLE_RESOURCES_TITLE', false));
				$jrtb .= $jrtbar->endTable();
				$output['CASTORTOOLBAR'] = $jrtb;
			}

			$roomRowInfo = array();

			foreach ($basic_room_details->rooms as $room) {
				$r = array();

				$r[ 'ROOM_UID' ] = $room['room_uid'];

				$toolbar->newToolbar();
				$toolbar->addItem('icon-edit', 'btn btn-info', '', castorURL(CASTOR_SITEPAGE_URL.'&task=edit_resource'.'&roomUid='.$room['room_uid']), jr_gettext('COMMON_EDIT', 'COMMON_EDIT', false));
				if (($mrConfig[ 'singleRoomProperty' ] == '1' && $number_of_rooms < 1) || $mrConfig[ 'singleRoomProperty' ] == '0') {
					$toolbar->addSecondaryItem('icon-copy', '', '', castorURL(CASTOR_SITEPAGE_URL.'&task=edit_resource'.'&roomUid='.$room['room_uid'].'&clone=1'), jr_gettext('_CASTOR_COM_MR_LISTTARIFF_LINKTEXTCLONE', '_CASTOR_COM_MR_LISTTARIFF_LINKTEXTCLONE', false));
				}
				if ($mrConfig[ 'singleRoomProperty' ] == '0') {
					$toolbar->addSecondaryItem('icon-trash', '', '', castorURL(CASTOR_SITEPAGE_URL.'&task=delete_resource'.'&roomUid='.$room['room_uid']), jr_gettext('COMMON_DELETE', 'COMMON_DELETE', false));
				}
				$r['BUTTONS'] = $toolbar->getToolbar();

				if ((int) $room['room_classes_uid'] > 0 && isset($current_property_details->room_types[ $room['room_classes_uid'] ]['abbv'])) {
					$r[ 'ROOM_TYPE' ] = $current_property_details->room_types[ $room['room_classes_uid'] ]['abbv'];
				} else {
					$r[ 'ROOM_TYPE' ] = '';
				}

				$r[ 'ROOM_NAME' ] = $room['room_name'];
				$r[ 'ROOM_NUMBER' ] = $room['room_number'];
				$r[ 'ROOM_FLOOR' ] = $room['room_floor'];
				$r[ 'MAX_PEOPLE' ] = $room['max_people'];

				$r[ 'ROOM_IMAGE' ] = $castor_media_centre_images->images['rooms'][ $room['room_uid'] ][0]['small'];

				//room features TODO: build a class for room features
				$roomFeaturesArray = explode(',', $room['room_features_uid']);

				$n = count($roomFeaturesArray);

				$r[ 'ROOM_FEATURES' ] = '<ul>';
				foreach ($basic_room_details->all_room_features as $feature) {
					for ($i = 0; $i < $n; ++$i) {
						if (isset($roomFeaturesArray[ $i ]) && $roomFeaturesArray[ $i ] != '' && $roomFeaturesArray[ $i ] == $feature['room_features_uid']) {
							$r[ 'ROOM_FEATURES' ] .= '<li>'.$feature['feature_description'].'</li>
						';
						}
					}
				}
				$r[ 'ROOM_FEATURES' ] .= '</ul>';

				$roomRowInfo[] = $r;
			}

			$output[ 'HROOM_TYPE' ] = jr_gettext('_CASTOR_HRESOURCE_TYPE', '_CASTOR_HRESOURCE_TYPE', false);
			$output[ 'HROOM_NAME' ] = jr_gettext('_CASTOR_COM_MR_VRCT_ROOM_HEADER_NAME', '_CASTOR_COM_MR_VRCT_ROOM_HEADER_NAME', false);
			$output[ 'HROOM_NUMBER' ] = jr_gettext('_CASTOR_COM_MR_VRCT_ROOM_HEADER_NUMBER', '_CASTOR_COM_MR_VRCT_ROOM_HEADER_NUMBER', false);
			$output[ 'HROOM_FLOOR' ] = jr_gettext('_CASTOR_COM_MR_VRCT_ROOM_HEADER_FLOOR', '_CASTOR_COM_MR_VRCT_ROOM_HEADER_FLOOR', false);
			$output[ 'HROOM_MAXPEOPLE' ] = jr_gettext('_CASTOR_COM_MR_VRCT_ROOM_HEADER_MAXPEOPLE', '_CASTOR_COM_MR_VRCT_ROOM_HEADER_MAXPEOPLE', false);
			$output[ 'HROOM_IMAGE' ] = jr_gettext('_CASTOR_COM_A_BOOKINGFORM_SHOWROOMIMAGE', '_CASTOR_COM_A_BOOKINGFORM_SHOWROOMIMAGE', false);
			$output[ 'HROOM_FEATURES' ] = jr_gettext('_CASTOR_HRESOURCE_FEATURES', '_CASTOR_HRESOURCE_FEATURES', false);

			$pageoutput = array();

			if ($mrConfig[ 'singleRoomProperty' ] == '0') {
				$output[ 'PAGETITLE' ] = jr_gettext('_CASTOR_COM_MR_VRCT_TAB_ROOM', '_CASTOR_COM_MR_VRCT_TAB_ROOM', false);

				$pageoutput[] = $output;
				$tmpl = new patTemplate();
				$tmpl->setRoot(CASTOR_TEMPLATEPATH_BACKEND);
				$tmpl->readTemplatesFromInput('list_resources.html');
				$tmpl->addRows('rows', $roomRowInfo);
				$tmpl->addRows('pageoutput', $pageoutput);
				$tmpl->displayParsedTemplate();
			} else {
				$output[ 'PAGETITLE' ] = jr_gettext('_CASTOR_COM_MR_EB_ROOM_CLASS_ABBV', '_CASTOR_COM_MR_EB_ROOM_CLASS_ABBV', false);

				$pageoutput[] = $output;
				$tmpl = new patTemplate();
				$tmpl->setRoot(CASTOR_TEMPLATEPATH_BACKEND);
				$tmpl->readTemplatesFromInput('list_resources_srp.html');
				$tmpl->addRows('rows', $roomRowInfo);
				$tmpl->addRows('pageoutput', $pageoutput);
				$tmpl->displayParsedTemplate();
			}
		}

		public function getRetVals()
		{
			return null;
		}
	}

