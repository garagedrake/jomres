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
	 * Builds the property comparison page from the list properties page
	 *
	 */

	class j06000compare
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
				$this->shortcode_data = array(
					'task' => 'compare',
					'arguments' => array(0 => array(
						'argument' => 'property_uids',
						'arg_info' => '_CASTOR_SHORTCODES_06000COMPARE_ARG_PROPERTY_UIDS',
						'arg_example' => '12,8,7',
					),
					),
					'info' => '_CASTOR_SHORTCODES_06000COMPARE',
				);

				return;
			}

			//add_gmaps_source();
			$property_uids = castorGetParam($_REQUEST, 'property_uids', '');

			// Clean them sukkas up
			if ($property_uids != '') {
				$bang = explode(',', $property_uids);
				$tmp = array();
				foreach ($bang as $p) {
					if ((int) $p > 0) {
						$tmp[ ] = (int) $p;
					}
				}
				$property_uids = $tmp;
			} else {
				$property_uids = array();
			}

			$output = array();
			$output[ '_CASTOR_RETURN_TO_RESULTS' ] = jr_gettext('_CASTOR_RETURN_TO_RESULTS', '_CASTOR_RETURN_TO_RESULTS', false, false);
			$output[ 'RETURN_TO_RESULTS_LINK' ] = castorURL(CASTOR_SITEPAGE_URL.'&task=search');

			$output[ '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_TOWN' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_TOWN', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_TOWN', false, false);
			$output[ '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_COUNTRY' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_COUNTRY', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_COUNTRY', false, false);
			$output[ '_CASTOR_FRONT_PTYPE' ] = jr_gettext('_CASTOR_FRONT_PTYPE', '_CASTOR_FRONT_PTYPE', false, false);
			$output[ '_CASTOR_SORTORDER_STARS' ] = jr_gettext('_CASTOR_SORTORDER_STARS', '_CASTOR_SORTORDER_STARS', false, false);
			$output[ '_CASTOR_TARIFFSFROM' ] = jr_gettext('_CASTOR_TARIFFSFROM', '_CASTOR_TARIFFSFROM', false, false);
			$output[ '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_MAPPINGLINK' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_MAPPINGLINK', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_MAPPINGLINK', false, false);

			if (!empty($property_uids)) {
				$tick = CASTOR_IMAGES_RELPATH.'castorimages/small/Tick.png';
				$cross = CASTOR_IMAGES_RELPATH.'castorimages/small/Cancel.png';

				$current_property_details = castor_singleton_abstract::getInstance('basic_property_details');
				$current_property_details->gather_data_multi($property_uids);

				$castor_property_list_prices = castor_singleton_abstract::getInstance('castor_property_list_prices');
				$castor_property_list_prices->gather_lowest_prices_multi($property_uids);

				$castor_media_centre_images = castor_singleton_abstract::getInstance('castor_media_centre_images');
				$castor_media_centre_images->get_images_multi($property_uids, array('property'));

				$featuresArray = array();
				$query = "SELECT hotel_features_uid,hotel_feature_abbv,hotel_feature_full_desc,image FROM #__castor_hotel_features WHERE property_uid = '0' ORDER BY hotel_feature_abbv ";
				$propertyFeaturesList = doSelectSql($query);
				foreach ($propertyFeaturesList as $f) {
					$hotel_feature_abbv = jr_gettext('_CASTOR_CUSTOMTEXT_FEATURES_ABBV'.(int) $f->hotel_features_uid, stripslashes($f->hotel_feature_abbv), false, false);
					$hotel_feature_full_desc = jr_gettext('_CASTOR_CUSTOMTEXT_FEATURES_DESC'.(int) $f->hotel_features_uid, stripslashes($f->hotel_feature_full_desc), false, false);
					$featuresArray[ $f->hotel_features_uid ] = array('hotel_feature_abbv' => $hotel_feature_abbv, 'hotel_feature_full_desc' => $hotel_feature_full_desc, 'image' => $f->image);
				}

				$query = 'SELECT id,ptype FROM #__castor_ptypes';
				$ptypes = doSelectSql($query);
				$property_types = array();
				foreach ($ptypes as $p) {
					$property_types[ $p->id ] = jr_gettext('_CASTOR_CUSTOMTEXT_PROPERTYTYPES'.(int) $p->id, $p->ptype, false, false);
				}

				$no_image_image = CASTOR_IMAGES_RELPATH.'noimage.svg';

				// We need to find out which features are used by all properties found in the search results
				$all_used_features = array();
				foreach ($current_property_details->multi_query_result as $property) {
					$propertyFeaturesArray = explode(',', ($property[ 'property_features' ]));
					if (!empty($propertyFeaturesArray)) {
						foreach ($propertyFeaturesArray as $v) {
							if ($v > 0) {
								$all_used_features[ $v ] = $v;
							}
						}
					}
				}

				$rows = array();
				$property_names_str = '';
				foreach ($current_property_details->multi_query_result as $property_uid => $property) {
					if (in_array($property_uid,$property_uids)) { // due to this being a singleton, stray properties can appear in this array, so we'll filter them out
						$property_names_str .= $property['property_name'].' - ';
						$r = $property;

						$r[ 'PROPERTY_UID' ] = $property_uid;
						$Args = array('property_uid' => $property_uid, 'width' => '119', 'height' => '95', 'disable_ui' => true);
						$MiniComponents->specificEvent('01050', 'x_geocoder', $Args);
						$r[ 'MAP' ] = $MiniComponents->miniComponentData[ '01050' ][ 'x_geocoder' ];

						if (isset($castor_property_list_prices->lowest_prices[$property_uid])) {
							$r[ 'PRICE_PRE_TEXT' ] = $castor_property_list_prices->lowest_prices[$property_uid][ 'PRE_TEXT' ];
							$r[ 'PRICE_PRICE' ] = $castor_property_list_prices->lowest_prices[$property_uid][ 'PRICE' ];
							$r[ 'PRICE_POST_TEXT' ] = $castor_property_list_prices->lowest_prices[$property_uid][ 'POST_TEXT' ];
						}


						$property_image = CASTOR_IMAGES_RELPATH.'noimage.svg';
						if (file_exists(CASTOR_IMAGELOCATION_ABSPATH.$property_uid.'_property_'.$property_uid.'.jpg')) {
							$property_image = CASTOR_IMAGELOCATION_RELPATH.$property_uid.'_property_'.$property_uid.'.jpg';
						}

						$castor_media_centre_images->get_images($property_uid, array('property'));
						$r[ 'IMAGETHUMB' ] = $castor_media_centre_images->images ['property'][0][0]['small'];
						$r[ 'IMAGEMEDIUM' ] = $castor_media_centre_images->images ['property'][0][0]['medium'];

						$r[ 'PROPERTY_IMAGE_OR_SLIDESHOW' ] = $MiniComponents->specificEvent('06000', 'show_property_main_image', array('output_now' => false, 'property_uid' => $property_uid));

						$propertyFeaturesArray = explode(',', ($property[ 'property_features' ]));
						if (!empty($propertyFeaturesArray)) {
							$fs = array();
							foreach ($featuresArray as $k => $v) {
								if (in_array($k, $all_used_features)) {
									if (in_array($k, $propertyFeaturesArray)) {
										$fs[ ] = array('IMAGE' => '<i class="fa fa-check" style="color:green"></i>');
									} else {
										$fs[ ] = array('IMAGE' => '<i class="fa fa-times" style="color:red"></i>');
									}
								}
							}
							$t = new patTemplate();
							$t->addRows('fs', $fs);
							$t->setRoot(CASTOR_TEMPLATEPATH_FRONTEND);
							$t->readTemplatesFromInput('compare_features.html');
							$r[ 'FEATURES' ] = $t->getParsedTemplate();
						}

						$r[ 'STARSIMAGES' ] = $MiniComponents->specificEvent('06000', 'show_property_stars', array('property_uid' => $property_uid , 'output_now' => false ));

						$r[ 'LIVE_SITE' ] = get_showtime('live_site');
						$r[ 'MOREINFORMATIONLINK' ] = get_property_details_url($property_uid);
						$r[ 'MOREINFORMATION' ] = jr_gettext('_CASTOR_COM_A_CLICKFORMOREINFORMATION', '_CASTOR_COM_A_CLICKFORMOREINFORMATION', $editable = false, true);
						$r[ 'RANDOM_IDENTIFIER' ] = generateCastorRandomString(10);
						$ptype = $property[ 'ptype_id' ];
						$r[ 'PROPERTY_TYPE' ] = $property_types[ $ptype ];

						// This is the string where the "remove from this list" url is build from
						$property_uids_url_string = '&property_uids=';
						foreach ($property_uids as $id) {
							if ($id != $property_uid) {
								$property_uids_url_string .= $id.',';
							}
						}
						$r[ '_CASTOR_REMOVE' ] = jr_gettext('_CASTOR_REMOVE', '_CASTOR_REMOVE', false, false);
						$r[ 'REMOVE_LINK' ] = castorURL(CASTOR_SITEPAGE_URL.'&task=compare'.$property_uids_url_string);
						$rows[ ] = $r;
					}

				}
				$i = 0;
				$features = array();
				foreach ($featuresArray as $feature_id => $feature) {
					if (in_array($feature_id, $all_used_features)) {
						if ($i % 2) {
							$class = 'odd';
						} else {
							$class = 'even';
						}
						$features[ ] = array('FEATURE_NAME' => $feature[ 'hotel_feature_abbv' ], 'CLASS' => $class);
						++$i;
					}
				}

				output_ribbon_styling();

				castor_set_page_title( 0 ,  jr_gettext('_CASTOR_COMPARE', '_CASTOR_COMPARE', false).' : '.substr($property_names_str, 0, -3) );

				$pageoutput[ ] = $output;
				$tmpl = new patTemplate();
				$tmpl->addRows('pageoutput', $pageoutput);
				$tmpl->addRows('features', $features);
				$tmpl->addRows('rows', $rows);
				$tmpl->setRoot(CASTOR_TEMPLATEPATH_FRONTEND);
				$tmpl->readTemplatesFromInput('compare.html');
				$tmpl->displayParsedTemplate();
			} else { // Oh, the naughty little tinker, they've removed all properties from their list, we'll just send them back to the search results
				$tmpBookingHandler = castor_singleton_abstract::getInstance('castor_temp_booking_handler');
				$MiniComponents->triggerEvent('01004'); // optional
				$MiniComponents->triggerEvent('01005'); // optional
				$MiniComponents->triggerEvent('01006'); // optional
				$MiniComponents->triggerEvent('01007'); // optional
				$componentArgs[ 'propertys_uid' ] = $tmpBookingHandler->tmpsearch_data[ 'ajax_list_search_results' ];
				$MiniComponents->triggerEvent('01010', $componentArgs); // listPropertys
			}
		}

		public function getRetVals()
		{
			return null;
		}
	}

