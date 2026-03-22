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
	 * Returns google map template. Used by numerous scripts to provide mapping output.
	 *
	 */

class j01050x_geocoder
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
	 
	public function __construct($componentArgs = null)
	{
		// Must be in all minicomponents. Minicomponents with templates that can contain editable text should run $this->template_touch() else just return
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = false;

			return;
		}
		
		$this->retVals ='';
		
		$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
		$jrConfig = $siteConfig->get();

		if (trim($jrConfig['google_maps_api_key']) == '') {
			return;
		}
		
		if (isset($componentArgs[ 'property_uid' ])) {
			$property_uid = (int) $componentArgs[ 'property_uid' ];
		} else {
			$property_uid = getDefaultProperty();
		}

		$editing = false;
		if (isset($componentArgs[ 'editing_map' ])) {
			$editing = $componentArgs[ 'editing_map' ];
		}

		add_gmaps_source();

		$output = array();
		$pageoutput = array();

		$override_directory = get_override_directory().'custom_code'.JRDS;

		if ( file_exists($override_directory.'custom_map_style.style') ) {
			$output['MAP_STYLE'] = file_get_contents($override_directory.'custom_map_style.style');
		} else {
			$output['MAP_STYLE'] = file_get_contents(CASTOR_ASSETS_ABSPATH.'map_styles'.JRDS.$jrConfig['map_style'].'.style');
		}

		$output['ZOOMLEVEL'] = (int)$jrConfig['map_zoom'];
		if (isset($_REQUEST['map_zoom'])) {
			$output['ZOOMLEVEL'] = (int) $_REQUEST['map_zoom'];
		}
		$output['MAPTYPE'] = strtoupper($jrConfig['map_type']);

		$output[ 'DISABLE_UI' ] = '';
		if (isset($componentArgs[ 'disable_ui' ])) {
			$output[ 'DISABLE_UI' ] = 'disableDefaultUI: true,';
		}

		$output[ 'LATLONG_DESC' ] = jr_gettext('_CASTOR_LATLONG_DESC', '_CASTOR_LATLONG_DESC', false);

		$task = castorGetParam($_REQUEST, 'task', '');
		$map_identifier = castorGetParam($_REQUEST, 'map-identifier', '');

		if ($map_identifier != '') {
			$output[ 'RANDOM_IDENTIFIER' ] = $map_identifier;
		} else {
			$output[ 'RANDOM_IDENTIFIER' ] = generateCastorRandomString(10);
		}

		$output[ 'MAP_WIDTH' ] = 300;
		$output[ 'MAP_HEIGHT' ] = $jrConfig['map_height'];
		$output[ 'MARKER_PATH' ] = '';
		if (isset($componentArgs[ 'width' ])) {
			$output[ 'MAP_WIDTH' ] = (int) $componentArgs[ 'width' ];
			$output[ 'MAP_HEIGHT' ] = (int) $componentArgs[ 'height' ];
		}

		if ($property_uid == 999999999) {
			$output[ 'LAT' ] = $jrConfig[ 'default_lat' ];
			$output[ 'LONG' ] = $jrConfig[ 'default_long' ];
		} else {
			$current_property_details = castor_singleton_abstract::getInstance('basic_property_details');
			$current_property_details->gather_data($property_uid);

			$castor_property_types = castor_singleton_abstract::getInstance('castor_property_types');
			$castor_property_types->get_property_type($current_property_details->ptype_id);

			if (isset($castor_property_types->property_type['marker_image'])) {
				$output[ 'marker_image'] = $castor_property_types->property_type['marker_image'];
			}

			$propertyData[ 'lat' ] = $current_property_details->multi_query_result[ $property_uid ][ 'lat' ];
			$propertyData[ 'long' ] = $current_property_details->multi_query_result[ $property_uid ][ 'long' ];

			if ($propertyData[ 'lat' ] != null) {
				$output[ 'LAT' ] = $propertyData[ 'lat' ];
				$output[ 'LONG' ] = $propertyData[ 'long' ];
			} elseif ($editing) {
				$output[ 'LAT' ] = $jrConfig[ 'default_lat' ];
				$output[ 'LONG' ] = $jrConfig[ 'default_long' ];
			} else {
				return;
			} // If we're editing it's ok to use the default data. If we're not, it isn't and it's best to simply not show the map at all
		}
		
		$output[ 'LAT' ] = str_replace("&#45;", "-", $output[ 'LAT' ]);
		$output[ 'LONG' ] = str_replace("&#45;", "-", $output[ 'LONG' ]);


		if (!isset($jrConfig[ 'gmap_layer_transit' ])) {
			$jrConfig[ 'gmap_layer_transit' ] = '0';
		}

		if ($jrConfig[ 'gmap_layer_transit' ] == '1') {
			$output[ 'TRANSIT_LAYER' ] = '
				var transitLayer = new google.maps.TransitLayer();
				transitLayer.setMap(map);
			';
		}

		if ($jrConfig[ 'gmap_pois' ] == '0') {
			$output[ 'SUPPRESS_POIS' ] = '
			,
		styles:[{
			featureType:"poi",
			elementType:"labels",
			stylers:[{
				visibility:"off"
				}]
			}]
			';
		}

		if ($editing) {
			$output[ 'DRAGABLE' ] = ',
		draggable: true,';
			$output[ 'DRAG_LISTENER' ] = '
		
		var postcodeInput = document.getElementById(\'property_postcode\');
		var townInput = document.getElementById(\'property_street\');
		var streetInput = document.getElementById(\'property_town\');

		
		google.maps.event.addDomListener(postcodeInput, \'change\', function() {
			var address = build_address();
			if (address != "") {
				codeAddress(address);
				}
			});
		google.maps.event.addDomListener(townInput, \'change\', function() {
			var address = build_address();
			if (address != "") {
				codeAddress(address);
				}
			});
		google.maps.event.addDomListener(streetInput, \'change\', function() {
			var address = build_address();
			if (address != "") {
				codeAddress(address);
				}
			});
			
			updateMarkerPosition(latLng);
			google.maps.event.addListener(marker, \'drag\', function() {
			updateMarkerPosition(marker.getPosition());
			});
			google.maps.event.addListener(marker, \'dragend\', function() {
			map.setCenter(marker.getPosition());
			});
				';

			if (!defined('UPDATE_POSITION_FUNCTION_EXISTS')) {
				define('UPDATE_POSITION_FUNCTION_EXISTS', 1);
				$output[ 'UPDATEMARKERPOSITION' ] = "function updateMarkerPosition(latLng) {
		castorJquery('#lat').val(latLng.lat().toFixed(7));
		castorJquery('#lng').val(latLng.lng(5).toFixed(7));
					}";
			}
		}

		set_showtime('current_map_identifier', $output[ 'RANDOM_IDENTIFIER' ]);

		$sanitised_lat_long_hyphes = array ("&#38;#45;" , "&#45;" );
		$output[ 'LAT' ] = str_replace($sanitised_lat_long_hyphes, "-", $output[ 'LAT' ]) ;
		$output[ 'LONG' ] = str_replace($sanitised_lat_long_hyphes, "-", $output[ 'LONG' ]) ;
		
		$pageoutput[ ] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_FRONTEND);
		$tmpl->readTemplatesFromInput('geocoder_latlong.html');
		$tmpl->addRows('pageoutput', $pageoutput);

		$this->retVals = $tmpl->getParsedTemplate();
	}


	public function getRetVals()
	{
		return $this->retVals;
	}
}

