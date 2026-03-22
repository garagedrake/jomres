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

class j06000show_syndicated_properties
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
			$this->shortcode_data = array(
				'task' => 'show_syndicated_properties',
				'info' => '_CASTOR_SHORTCODES_06001SHOW_SYNDICATED_PROPERTIES',
				'arguments' => array(0 => array(
						'argument' => 'limit',
						'arg_info' => '_CASTOR_SHORTCODES_06001SHOW_SYNDICATED_PROPERTIES_ARG_LIMIT',
						'arg_example' => '6',
						),
						1 => array(
						'argument' => 'mrp',
						'arg_info' => '_CASTOR_SHORTCODES_06001SHOW_SYNDICATED_PROPERTIES_ARG_MRPSRP',
						'arg_example' => '1',
						)
					),
				);
			return;
		}
		
		$this->retVals = '';

		return;

		$castor_check_support_key = castor_singleton_abstract::getInstance('castor_check_support_key');
		$castor_check_support_key->check_license_key();

		if ($castor_check_support_key->key_valid) {
			return;
		}

		$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
		$jrConfig = $siteConfig->get();

		if (!isset($jrConfig[ 'development_production' ])) {
			return;
		}

		if ($jrConfig[ 'development_production' ] != 'production') {
			return;
		}

		$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
		$jrConfig = $siteConfig->get();

		if (!isset($jrConfig['useSyndication'])) {
			$jrConfig['useSyndication'] = 1;
		}

		if (isset($componentArgs[ 'limit' ])) {
			$limit = (int)$componentArgs[ 'limit' ];
		} else {
			$limit = (int)castorGetParam($_REQUEST, 'limit', 6);
		}
		
		if (isset($componentArgs[ 'mrp' ])) {
			$multi_room_property = (int)$componentArgs[ 'mrp' ];
			if ($multi_room_property > 1) {
				$multi_room_property = 1;
			}
		} elseif (isset($_REQUEST['mrp'])) {
			$multi_room_property = (int)castorGetParam($_REQUEST, 'mrp', 1);
			if ($multi_room_property > 1) {
				$multi_room_property = 1;
			}
		} else {
			$mrConfig = getPropertySpecificSettings(get_showtime('property_uid'));
			if ((int)$mrConfig['singleRoomProperty'] == "1") {
				$multi_room_property = "1";
			} else {
				$multi_room_property = "0";
			}
		}
		
		if (isset($componentArgs[ 'output_now' ])) {
			$output_now = $componentArgs[ 'output_now' ];
		} elseif (isset($_REQUEST[ 'output_now' ])) {
			$output_now = (bool) castorGetParam($_REQUEST, 'output_now', 1);
		} else {
			$output_now = true;
		}

		jr_import('castor_syndicate_properties');
		$castor_syndicate_properties = new castor_syndicate_properties();
		

		if (get_showtime('property_uid') > 0) {
			$basic_property_details = castor_singleton_abstract::getInstance('basic_property_details');
			$basic_property_details->gather_data(get_showtime('property_uid'));
			$castor_syndicate_properties->base_property_id = get_showtime('property_uid');
			$castor_syndicate_properties->base_lat_long = array ( "lat" => $basic_property_details->lat , "long" => $basic_property_details->long );
		}
		
		$random_properties = $castor_syndicate_properties->get_random_properties($limit, $multi_room_property);
		
		if (!empty($random_properties)) {
			$property_templates = array();
			foreach ($random_properties as $property) {
				$output = array();
				$pageoutput = array();
				$template = array();

				$output['VIEW_PROPERTY_URL']	= $property->view_property_url;
				$output['BOOKING_FORM_URL']		= $property->booking_form_url;
				$output['NAME']					= jr_substr($property->name, 0, 14).'&hellip;';
				$output['LAT']					= $property->lat;
				$output['LONG']					= $property->long;
				$output['METADESCRIPTION']		= $property->metadescription;
				$output['THUMBNAIL_LOCATION']	= $property->thumbnail_location;
				$output['DATE_ADDED']			= $property->date_added;
				$output['LAST_CHECKED']			= $property->last_checked;

				$pageoutput[] = $output;
				$tmpl = new patTemplate();
				$tmpl->setRoot(CASTOR_TEMPLATEPATH_FRONTEND);
				$tmpl->addRows('pageoutput', $pageoutput);

				$tmpl->readTemplatesFromInput('show_syndicated_property.html');
				$template['TEMPLATE'] = $tmpl->getParsedTemplate();

				$property_templates[] = $template;
			}
			
			$output = array();
			$pageoutput = array();
			
			$output['_CASTOR_SYNDICATION_TITLE'] = jr_gettext('_CASTOR_SYNDICATION_TITLE', '_CASTOR_SYNDICATION_TITLE', false);
			$output['_CASTOR_SYNDICATION_TAGLINE'] = jr_gettext('_CASTOR_SYNDICATION_TAGLINE', '_CASTOR_SYNDICATION_TAGLINE', false);
			
			
			$pageoutput[] = $output;
			$tmpl = new patTemplate();
			$tmpl->setRoot(CASTOR_TEMPLATEPATH_FRONTEND);
			$tmpl->addRows('property_templates', $property_templates);
			$tmpl->addRows('pageoutput', $pageoutput);
			$tmpl->readTemplatesFromInput('show_syndicated_properties.html');
			$template = $tmpl->getParsedTemplate();
			
			if ($output_now) {
				echo $template;
			} else {
				$this->retVals = $template;
			}
			
			$castor_syndicate_properties->report_properties_display($random_properties);
		}
	}

	private function check_thumbnail_exists($url = '')
	{
		if ($url == '') {
			return false;
		}

		$handle = curl_init($url);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($handle, CURLOPT_NOBODY, true);

		/* Get the thumbnail */
		$response = curl_exec($handle);

		/* Check for 404 (file not found). */
		$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
		if ($httpCode == 404) {
			return false;
		}

		curl_close($handle);

		return true;
	}

	public function getRetVals()
	{
		return $this->retVals;
	}
}

