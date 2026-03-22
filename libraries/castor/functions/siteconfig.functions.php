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

/**
 * @package Castor\Core\Functions
 *
 * Compiles data in preparation for showing the site configuration panel.
 */
	if (!function_exists('showSiteConfig')) {
		function showSiteConfig()
		{

			//check castor support key
			//$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
			//echo $MiniComponents->specificEvent('16000', 'show_license_message', array('output_now' => false, 'as_modal' => false));

			$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
			$jrConfig = $siteConfig->get();

			$basic_property_details = castor_singleton_abstract::getInstance('basic_property_details');

			$jrtbar = castor_singleton_abstract::getInstance('castor_toolbar');
			$jrtb = $jrtbar->startTable();
			$image = $jrtbar->makeImageValid(CASTOR_IMAGES_RELPATH.'castorimages/small/Save.png');
			$jrtb .= $jrtbar->toolbarItem('cancel', CASTOR_SITEPAGE_URL_ADMIN, '');
			$jrtb .= $jrtbar->customToolbarItem('saveSiteConfig', CASTOR_SITEPAGE_URL_ADMIN, jr_gettext('_CASTOR_COM_MR_SAVE', '_CASTOR_COM_MR_SAVE', false), $submitOnClick = true, $submitTask = 'save_site_settings', $image);
			$jrtb .= $jrtbar->endTable();

			if (!isset($jrConfig[ 'load_jquery_ui' ])) {
				$jrConfig[ 'load_jquery_ui' ] = '1';
			}

			$lists = array();
			// make a standard yes/no list
			$yesno = array();
			$yesno[ ] = castorHTML::makeOption('0', jr_gettext('_CASTOR_COM_MR_NO', '_CASTOR_COM_MR_NO', false));
			$yesno[ ] = castorHTML::makeOption('1', jr_gettext('_CASTOR_COM_MR_YES', '_CASTOR_COM_MR_YES', false));

			$editoryesno = array();
			$editoryesno[ ] = castorHTML::makeOption('0', jr_gettext('_CASTOR_COM_MR_NO', '_CASTOR_COM_MR_NO', false));
			$editoryesno[ ] = castorHTML::makeOption('1', jr_gettext('_CASTOR_COM_MR_YES', '_CASTOR_COM_MR_YES', false));

			$sortArray = array(); // The search order dropdown list, this configure's the default.
			$sortArray[ ] = castorHTML::makeOption('1', jr_gettext('_CASTOR_SORTORDER_DEFAULT', '_CASTOR_SORTORDER_DEFAULT', false, false));
			$sortArray[ ] = castorHTML::makeOption('2', jr_gettext('_CASTOR_SORTORDER_PROPERTYNAME', '_CASTOR_SORTORDER_PROPERTYNAME', false, false));
			$sortArray[ ] = castorHTML::makeOption('3', jr_gettext('_CASTOR_SORTORDER_PROPERTYREGION', '_CASTOR_SORTORDER_PROPERTYREGION', false, false));
			$sortArray[ ] = castorHTML::makeOption('4', jr_gettext('_CASTOR_SORTORDER_PROPERTYTOWN', '_CASTOR_SORTORDER_PROPERTYTOWN', false, false));
			$sortArray[ ] = castorHTML::makeOption('5', jr_gettext('_CASTOR_SORTORDER_STARS', '_CASTOR_SORTORDER_STARS', false, false));
			$sortArrayDropdown = castorHTML::selectList($sortArray, 'cfg_search_order_default', ' id="sortby" ', 'value', 'text', $jrConfig[ 'search_order_default' ]);

			$jsInputDateFormats[ ] = castorHTML::makeOption('%d/%m/%Y', '01/02/2006 - 1st February 2006');
			$jsInputDateFormats[ ] = castorHTML::makeOption('%Y/%m/%d', '2006/02/01');
			$jsInputDateFormats[ ] = castorHTML::makeOption('%m/%d/%Y', '02/01/2006');
			$jsInputDateFormats[ ] = castorHTML::makeOption('%d-%m-%Y', '01-02-2006');
			$jsInputDateFormats[ ] = castorHTML::makeOption('%Y-%m-%d', '2006-02-01');
			$jsInputDateFormats[ ] = castorHTML::makeOption('%m-%d-%Y', '02-01-2006');
			$jsInputFormatDropdownList = castorHTML::selectList($jsInputDateFormats, 'cfg_cal_input', '', 'value', 'text', $jrConfig[ 'cal_input' ]);

			$jqueryUIthemes = array();
			$cssFiles = searchCSSThemesDirForCSSFiles();
			foreach ($cssFiles as $file) {
				$jqueryUIthemes[ ] = castorHTML::makeOption($file[ 'subdir' ], $file[ 'subdir' ]);
			}

			if ($jrConfig[ 'jquery_ui_theme' ] == 'castor') {
				$jrConfig[ 'jquery_ui_theme' ] = 'base';
			}

			$jqueryUIthemesDropdownList = castorHTML::selectList($jqueryUIthemes, 'cfg_jquery_ui_theme', '', 'value', 'text', $jrConfig[ 'jquery_ui_theme' ]);

			if (!isset($jrConfig[ 'cssColourScheme' ])) {
				$jrConfig[ 'cssColourScheme' ] = 'blue';
			}

			jr_import('jrportal_commissions');
			$jrportal_commissions = new jrportal_commissions();
			$jrportal_commissions->getAllCrates();

			$crateOptions = array();
			foreach ($jrportal_commissions->crates as $c) {
				$crateOptions[ ] = castorHTML::makeOption($c[ 'id' ], $c[ 'title' ]);
			}
			$lists[ 'defaultCrate' ] = castorHTML::selectList($crateOptions, 'cfg_defaultCrate', '', 'value', 'text', $jrConfig[ 'defaultCrate' ]);
			$lists[ 'errorChecking' ] = castorHTML::selectList($yesno, 'cfg_errorChecking', '', 'value', 'text', $jrConfig[ 'errorChecking' ]);
			$lists[ 'useGlobalCurrency' ] = castorHTML::selectList($yesno, 'cfg_useGlobalCurrency', '', 'value', 'text', $jrConfig[ 'useGlobalCurrency' ]);
			$lists[ 'editingModeAffectsAllProperties' ] = castorHTML::selectList($yesno, 'cfg_editingModeAffectsAllProperties', '', 'value', 'text', $jrConfig[ 'editingModeAffectsAllProperties' ]);
			$lists[ 'useGlobalPFeatures' ] = castorHTML::selectList($yesno, 'cfg_useGlobalPFeatures', '', 'value', 'text', $jrConfig[ 'useGlobalPFeatures' ]);
			$lists[ 'useGlobalRoomTypes' ] = castorHTML::selectList($yesno, 'cfg_useGlobalRoomTypes', '', 'value', 'text', $jrConfig[ 'useGlobalRoomTypes' ]);
			$lists[ 'selfRegistrationAllowed' ] = castorHTML::selectList($yesno, 'cfg_selfRegistrationAllowed', '', 'value', 'text', $jrConfig[ 'selfRegistrationAllowed' ]);
			$lists[ 'allowHTMLeditor' ] = castorHTML::selectList($editoryesno, 'cfg_allowHTMLeditor', '', 'value', 'text', $jrConfig[ 'allowHTMLeditor' ]);
			$lists[ 'dumpTemplate' ] = castorHTML::selectList($yesno, 'cfg_dumpTemplate', '', 'value', 'text', $jrConfig[ 'dumpTemplate' ]);
			$lists[ 'emailErrors' ] = castorHTML::selectList($yesno, 'cfg_emailErrors', '', 'value', 'text', $jrConfig[ 'emailErrors' ]);
			$lists[ 'composite_property_details' ] = castorHTML::selectList($yesno, 'cfg_composite_property_details', '', 'value', 'text', $jrConfig[ 'composite_property_details' ]);

			$lists[ 'show_booking_form_in_property_details' ] = castorHTML::selectList($yesno, 'cfg_show_booking_form_in_property_details', '', 'value', 'text', $jrConfig[ 'show_booking_form_in_property_details' ]);

			$geosearchList = array();
			$geosearchList[ ] = castorHTML::makeOption('', '');
			$geosearchList[ ] = castorHTML::makeOption('town', jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_TOWN', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_TOWN', false));
			$geosearchList[ ] = castorHTML::makeOption('region', jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_REGION', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_REGION', false));
			$geosearchDropdownList = castorHTML::selectList($geosearchList, 'cfg_integratedSearch_geosearchtype', '', 'value', 'text', $jrConfig[ 'integratedSearch_geosearchtype' ]);

			$calendarStartDays = array();
			$calendarStartDays[ ] = castorHTML::makeOption('1', jr_gettext('_CASTOR_COM_MR_WEEKDAYS_SUNDAY', '_CASTOR_COM_MR_WEEKDAYS_SUNDAY', false));
			$calendarStartDays[ ] = castorHTML::makeOption('2', jr_gettext('_CASTOR_COM_MR_WEEKDAYS_MONDAY', '_CASTOR_COM_MR_WEEKDAYS_MONDAY', false));
			$calendarStartDaysDropdownList = castorHTML::selectList($calendarStartDays, 'cfg_calendarstartofweekday', '', 'value', 'text', $jrConfig[ 'calendarstartofweekday' ]);

			if (!isset($jrConfig[ 'guestnumbersearch' ])) {
				$jrConfig[ 'guestnumbersearch' ] = 'equal';
			}

			$guestnumbersearchList = array();
			$guestnumbersearchList[ ] = castorHTML::makeOption('lessthan', '<=');
			$guestnumbersearchList[ ] = castorHTML::makeOption('equal', '=');
			$guestnumbersearchList[ ] = castorHTML::makeOption('greaterthan', '>=');
			$guestnumbersearchDropdownList = castorHTML::selectList($guestnumbersearchList, 'cfg_guestnumbersearch', '', 'value', 'text', $jrConfig[ 'guestnumbersearch' ]);

			$currency_codes = castor_singleton_abstract::getInstance('currency_codes');
			$currency_codes_dropdown = $currency_codes->makeCodesDropdown($jrConfig[ 'globalCurrencyCode' ], true);

			$castor_property_types = castor_singleton_abstract::getInstance('castor_property_types');
			$language_context_dropdown = $castor_property_types->getPropertyTypeDescDropdown($jrConfig[ 'language_context' ], 'cfg_language_context');

			$filtering_level = array();
			$filtering_level[ ] = castorHTML::makeOption('weak', jr_gettext('_CASTOR_INPUTFILTERING_LEVEL_WEAK', '_CASTOR_INPUTFILTERING_LEVEL_WEAK', false));
			$filtering_level[ ] = castorHTML::makeOption('strong', jr_gettext('_CASTOR_INPUTFILTERING_LEVEL_STRONG', '_CASTOR_INPUTFILTERING_LEVEL_STRONG', false));
			$filtering_level_dropdown = castorHTML::selectList($filtering_level, 'cfg_input_filtering', '', 'value', 'text', $jrConfig[ 'input_filtering' ]);

			$production_development = array();
			$production_development[ ] = castorHTML::makeOption('production', jr_gettext('_CASTOR_CONFIG_PRODUCTION_DEVELOPMENT_SETTING_PRODUCTION', '_CASTOR_CONFIG_PRODUCTION_DEVELOPMENT_SETTING_PRODUCTION', false));
			$production_development[ ] = castorHTML::makeOption('development', jr_gettext('_CASTOR_CONFIG_PRODUCTION_DEVELOPMENT_SETTING_DEVELOPMENT', '_CASTOR_CONFIG_PRODUCTION_DEVELOPMENT_SETTING_DEVELOPMENT', false));
			$production_development_dropdown = castorHTML::selectList($production_development, 'cfg_development_production', '', 'value', 'text', $jrConfig[ 'development_production' ]);

			if (!isset($jrConfig[ 'navbar_location' ])) {
				$jrConfig[ 'navbar_location' ] = 'component_area';
			}
			$navbar_location = array();
			$navbar_location[ ] = castorHTML::makeOption('component_area', jr_gettext('_CASTOR_BOOTSTRAP_LOCATION_DEFAULT', '_CASTOR_BOOTSTRAP_LOCATION_DEFAULT', false));
			$navbar_location[ ] = castorHTML::makeOption('navbar-fixed-top', jr_gettext('_CASTOR_BOOTSTRAP_LOCATION_TOP', '_CASTOR_BOOTSTRAP_LOCATION_TOP', false));
			// Disabled as looks like pants in BS3
			//$navbar_location[ ] = castorHTML::makeOption('navbar-fixed-bottom', jr_gettext('_CASTOR_BOOTSTRAP_LOCATION_BOTTOM', '_CASTOR_BOOTSTRAP_LOCATION_BOTTOM', false));
			$navbar_location_dropdown = castorHTML::selectList($navbar_location, 'cfg_navbar_location', '', 'value', 'text', $jrConfig[ 'navbar_location' ]);

			if (!isset($jrConfig[ 'admin_options_level' ])) {
				$jrConfig[ 'admin_options_level' ] = 0;
			}
			$admin_options_level = array();
			$admin_options_level[ ] = castorHTML::makeOption(0, jr_gettext('_CASTOR_CONFIG_LEVEL_BASIC', '_CASTOR_CONFIG_LEVEL_BASIC', false));
			$admin_options_level[ ] = castorHTML::makeOption(1, jr_gettext('_CASTOR_CONFIG_LEVEL_COMMON', '_CASTOR_CONFIG_LEVEL_COMMON', false));
			$admin_options_level[ ] = castorHTML::makeOption(2, jr_gettext('_CASTOR_CONFIG_LEVEL_EVERYTHING', '_CASTOR_CONFIG_LEVEL_EVERYTHING', false));
			$admin_options_level_dropdown = castorHTML::selectList($admin_options_level, 'cfg_admin_options_level', '', 'value', 'text', $jrConfig[ 'admin_options_level' ]);



			if (!isset($jrConfig[ 'bootstrap_version' ])) {
				$jrConfig[ 'bootstrap_version' ] = '';
			}

			$bootstrap_ver_opt = array();
			$bootstrap_ver_opt[ ] = castorHTML::makeOption('0', 'No bootstrap in theme');
			$bootstrap_ver_opt[ ] = castorHTML::makeOption('', 'Bootstrap 2');
			$bootstrap_ver_opt[ ] = castorHTML::makeOption('3', 'Bootstrap 3');
			$bootstrap_ver_opt[ ] = castorHTML::makeOption('5', 'Bootstrap 5');
			$bootstrap_ver_dropdown = castorHTML::selectList($bootstrap_ver_opt, 'cfg_bootstrap_version', '', 'value', 'text', $jrConfig[ 'bootstrap_version' ], false);

			$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
			$MiniComponents->triggerEvent('01004', array()); // optional
			$property_list_layouts = get_showtime('property_list_layouts');

			$layout = array();
			foreach ($property_list_layouts as $key => $val) {
				$layout[ ] = castorHTML::makeOption($key, $val[ 'title' ]);
			}
			$layouts = castorHTML::selectList($layout, 'cfg_property_list_layout_default', '', 'value', 'text', $jrConfig[ 'property_list_layout_default' ]);

			$lists[ 'integratedSearch_enable' ] = castorHTML::selectList($yesno, 'cfg_integratedSearch_enable', '', 'value', 'text', $jrConfig[ 'integratedSearch_enable' ]);
			$lists[ 'integratedSearch_useCols' ] = castorHTML::selectList($yesno, 'cfg_integratedSearch_useCols', '', 'value', 'text', $jrConfig[ 'integratedSearch_useCols' ]);
			$lists[ 'integratedSearch_selectcombo' ] = castorHTML::selectList($yesno, 'cfg_integratedSearch_selectcombo', '', 'value', 'text', $jrConfig[ 'integratedSearch_selectcombo' ]);
			$lists[ 'integratedSearch_propertyname' ] = castorHTML::selectList($yesno, 'cfg_integratedSearch_propertyname', '', 'value', 'text', $jrConfig[ 'integratedSearch_propertyname' ]);
			$lists[ 'integratedSearch_propertyname_dropdown' ] = castorHTML::selectList($yesno, 'cfg_integratedSearch_propertyname_dropdown', '', 'value', 'text', $jrConfig[ 'integratedSearch_propertyname_dropdown' ]);
			$lists[ 'integratedSearch_ptype' ] = castorHTML::selectList($yesno, 'cfg_integratedSearch_ptype', '', 'value', 'text', $jrConfig[ 'integratedSearch_ptype' ]);
			$lists[ 'integratedSearch_category' ] = castorHTML::selectList($yesno, 'cfg_integratedSearch_category', '', 'value', 'text', $jrConfig[ 'integratedSearch_category' ]);
			$lists[ 'integratedSearch_ptype_dropdown' ] = castorHTML::selectList($yesno, 'cfg_integratedSearch_ptype_dropdown', '', 'value', 'text', $jrConfig[ 'integratedSearch_ptype_dropdown' ]);
			$lists[ 'integratedSearch_geosearchtype_dropdown' ] = castorHTML::selectList($yesno, 'cfg_integratedSearch_geosearchtype_dropdown', '', 'value', 'text', $jrConfig[ 'integratedSearch_geosearchtype_dropdown' ]);

			if (!isset($jrConfig[ 'integratedSearch_town' ])) {
				$jrConfig[ 'integratedSearch_town' ] = '';
			}
			if (!isset($jrConfig[ 'integratedSearch_town_dropdown' ])) {
				$jrConfig[ 'integratedSearch_town_dropdown' ] = '';
			}

			$lists[ 'integratedSearch_town' ] = castorHTML::selectList($yesno, 'cfg_integratedSearch_town', '', 'value', 'text', $jrConfig[ 'integratedSearch_town' ]);
			$lists[ 'integratedSearch_town_dropdown' ] = castorHTML::selectList($yesno, 'cfg_integratedSearch_town_dropdown', '', 'value', 'text', $jrConfig[ 'integratedSearch_town_dropdown' ]);

			$lists[ 'integratedSearch_room_type' ] = castorHTML::selectList($yesno, 'cfg_integratedSearch_room_type', '', 'value', 'text', $jrConfig[ 'integratedSearch_room_type' ]);
			$lists[ 'integratedSearch_room_type_dropdown' ] = castorHTML::selectList($yesno, 'cfg_integratedSearch_room_type_dropdown', '', 'value', 'text', $jrConfig[ 'integratedSearch_room_type_dropdown' ]);
			$lists[ 'integratedSearch_features' ] = castorHTML::selectList($yesno, 'cfg_integratedSearch_features', '', 'value', 'text', $jrConfig[ 'integratedSearch_features' ]);
			$lists[ 'integratedSearch_features_dropdown' ] = castorHTML::selectList($yesno, 'cfg_integratedSearch_features_dropdown', '', 'value', 'text', $jrConfig[ 'integratedSearch_features_dropdown' ]);
			$lists[ 'integratedSearch_description' ] = castorHTML::selectList($yesno, 'cfg_integratedSearch_description', '', 'value', 'text', $jrConfig[ 'integratedSearch_description' ]);
			$lists[ 'integratedSearch_availability' ] = castorHTML::selectList($yesno, 'cfg_integratedSearch_availability', '', 'value', 'text', $jrConfig[ 'integratedSearch_availability' ]);
			$lists[ 'integratedSearch_priceranges' ] = castorHTML::selectList($yesno, 'cfg_integratedSearch_priceranges', '', 'value', 'text', $jrConfig[ 'integratedSearch_priceranges' ]);

			$lists[ 'integratedSearch_guestnumber' ] = castorHTML::selectList($yesno, 'cfg_integratedSearch_guestnumber', '', 'value', 'text', $jrConfig[ 'integratedSearch_guestnumber' ]);
			$lists[ 'integratedSearch_stars' ] = castorHTML::selectList($yesno, 'cfg_integratedSearch_stars', '', 'value', 'text', $jrConfig[ 'integratedSearch_stars' ]);

			$lists[ 'showLangDropdown' ] = castorHTML::selectList($yesno, 'cfg_showLangDropdown', '', 'value', 'text', $jrConfig[ 'showLangDropdown' ]);

			$jrConfig[ 'useNewusers' ] = '1'; // For Castor v9.11 and GDPR compliance we are now forcing the system to create new users whenever a booking is made. Leaving this here to clarify this point, however site config will no longer offer this option to be changed
			$lists[ 'useNewusers' ] = castorHTML::selectList($yesno, 'cfg_useNewusers', '', 'value', 'text', $jrConfig[ 'useNewusers' ]);

			$lists[ 'is_single_property_installation' ] = castorHTML::selectList($yesno, 'cfg_is_single_property_installation', '', 'value', 'text', $jrConfig[ 'is_single_property_installation' ]);
			$lists[ 'use_html_purifier' ] = castorHTML::selectList($yesno, 'cfg_use_html_purifier', '', 'value', 'text', $jrConfig[ 'use_html_purifier' ]);
			$lists[ 'limit_property_country' ] = castorHTML::selectList($yesno, 'cfg_limit_property_country', '', 'value', 'text', $jrConfig[ 'limit_property_country' ]);

			$lists[ 'use_reviews' ] = castorHTML::selectList($yesno, 'cfg_use_reviews', '', 'value', 'text', $jrConfig[ 'use_reviews' ]);
			$lists[ 'autopublish_reviews' ] = castorHTML::selectList($yesno, 'cfg_autopublish_reviews', '', 'value', 'text', $jrConfig[ 'autopublish_reviews' ]);
			$lists[ 'reviews_test_mode' ] = castorHTML::selectList($yesno, 'cfg_reviews_test_mode', '', 'value', 'text', $jrConfig[ 'reviews_test_mode' ]);
			$lists[ 'show_search_order' ] = castorHTML::selectList($yesno, 'cfg_show_search_order', '', 'value', 'text', $jrConfig[ 'show_search_order' ]);

			$lists[ 'only_guests_can_review' ] = castorHTML::selectList($yesno, 'cfg_only_guests_can_review', '', 'value', 'text', $jrConfig[ 'only_guests_can_review' ]);
			$lists[ 'use_timezone_switcher' ] = castorHTML::selectList($yesno, 'cfg_use_timezone_switcher', '', 'value', 'text', $jrConfig[ 'use_timezone_switcher' ]);
			$lists[ 'load_jquery' ] = castorHTML::selectList($yesno, 'cfg_load_jquery', '', 'value', 'text', $jrConfig[ 'load_jquery' ]);
			$lists[ 'use_commission' ] = castorHTML::selectList($yesno, 'cfg_use_commission', '', 'value', 'text', $jrConfig[ 'use_commission' ]);
			$lists[ 'manager_bookings_trigger_commission' ] = castorHTML::selectList($yesno, 'cfg_manager_bookings_trigger_commission', '', 'value', 'text', $jrConfig[ 'manager_bookings_trigger_commission' ]);
			$lists[ 'commission_autosuspend_on_overdue' ] = castorHTML::selectList($yesno, 'cfg_commission_autosuspend_on_overdue', '', 'value', 'text', $jrConfig[ 'commission_autosuspend_on_overdue' ]);
			$lists[ 'load_jquery_ui' ] = castorHTML::selectList($yesno, 'cfg_load_jquery_ui', '', 'value', 'text', $jrConfig[ 'load_jquery_ui' ]);
			$lists[ 'load_jquery_ui_css' ] = castorHTML::selectList($yesno, 'cfg_load_jquery_ui_css', '', 'value', 'text', $jrConfig[ 'load_jquery_ui_css' ]);
			$lists[ 'use_conversion_feature' ] = castorHTML::selectList($yesno, 'cfg_use_conversion_feature', '', 'value', 'text', $jrConfig[ 'use_conversion_feature' ]);
			$lists[ 'booking_form_modal_popup' ] = castorHTML::selectList($yesno, 'cfg_booking_form_modal_popup', '', 'value', 'text', $jrConfig[ 'booking_form_modal_popup' ]);
			$lists[ 'useNewusers_sendemail' ] = castorHTML::selectList($yesno, 'cfg_useNewusers_sendemail', '', 'value', 'text', $jrConfig[ 'useNewusers_sendemail' ]);
			$lists[ 'show_tax_in_totals_summary' ] = castorHTML::selectList($yesno, 'cfg_show_tax_in_totals_summary', '', 'value', 'text', $jrConfig[ 'show_tax_in_totals_summary' ]);
			$lists[ 'alternate_smtp_use_settings' ] = castorHTML::selectList($yesno, 'cfg_alternate_smtp_use_settings', '', 'value', 'text', $jrConfig[ 'alternate_smtp_use_settings' ]);
			$lists[ 'alternate_smtp_authentication' ] = castorHTML::selectList($yesno, 'cfg_alternate_smtp_authentication', '', 'value', 'text', $jrConfig[ 'alternate_smtp_authentication' ]);
			$lists[ 'alternate_mainmenu' ] = castorHTML::selectList($yesno, 'cfg_alternate_mainmenu', '', 'value', 'text', $jrConfig[ 'alternate_mainmenu' ]);
			$lists[ 'safe_mode' ] = castorHTML::selectList($yesno, 'cfg_safe_mode', '', 'value', 'text', $jrConfig[ 'safe_mode' ]);
			$lists[ 'use_castor_own_editor' ] = castorHTML::selectList($yesno, 'cfg_use_castor_own_editor', '', 'value', 'text', $jrConfig[ 'use_castor_own_editor' ]);
			$lists[ 'property_details_in_tabs' ] = castorHTML::selectList($yesno, 'cfg_property_details_in_tabs', '', 'value', 'text', $jrConfig[ 'property_details_in_tabs' ]);

			$lists[ 'gmap_layer_weather' ] = castorHTML::selectList($yesno, 'cfg_gmap_layer_weather', '', 'value', 'text', $jrConfig[ 'gmap_layer_weather' ]);
			$lists[ 'gmap_layer_panoramio' ] = castorHTML::selectList($yesno, 'cfg_gmap_layer_panoramio', '', 'value', 'text', $jrConfig[ 'gmap_layer_panoramio' ]);
			$lists[ 'gmap_layer_transit' ] = castorHTML::selectList($yesno, 'cfg_gmap_layer_transit', '', 'value', 'text', $jrConfig[ 'gmap_layer_transit' ]);
			$lists[ 'gmap_layer_traffic' ] = castorHTML::selectList($yesno, 'cfg_gmap_layer_traffic', '', 'value', 'text', $jrConfig[ 'gmap_layer_traffic' ]);
			$lists[ 'gmap_layer_bicycling' ] = castorHTML::selectList($yesno, 'cfg_gmap_layer_bicycling', '', 'value', 'text', $jrConfig[ 'gmap_layer_bicycling' ]);
			$lists[ 'gmap_pois' ] = castorHTML::selectList($yesno, 'cfg_gmap_pois', '', 'value', 'text', $jrConfig[ 'gmap_pois' ]);

			$lists[ 'review_nag' ] = castorHTML::selectList($yesno, 'cfg_review_nag', '', 'value', 'text', $jrConfig[ 'review_nag' ]);
			$lists[ 'optimize_images' ] = castorHTML::selectList($yesno, 'cfg_optimize_images', '', 'value', 'text', $jrConfig[ 'optimize_images' ]);

			$lists[ 'frontend_room_type_editing_allowed' ] = castorHTML::selectList($yesno, 'cfg_frontend_room_type_editing_allowed', '', 'value', 'text', $jrConfig[ 'frontend_room_type_editing_allowed' ]);

			$lists[ 'frontend_room_type_editing_show_property_room_types_in_search_options' ] = castorHTML::selectList($yesno, 'cfg_frontend_room_type_editing_show_property_room_types_in_search_options', '', 'value', 'text', $jrConfig[ 'frontend_room_type_editing_show_property_room_types_in_search_options' ]);

			$lists[ 'useSyndication' ] = castorHTML::selectList($yesno, 'cfg_useSyndication', '', 'value', 'text', $jrConfig[ 'useSyndication' ]);

			if (!isset($jrConfig[ 'compatability_property_configuration' ])) { // New installations will automatically set this to Yes, therefore if it's not set this was an updated installation and we should by default set this to No and allow the site managers to decide if they want to enable the setting
				$jrConfig[ 'compatability_property_configuration' ] = 0;
			}
			$lists[ 'compatability_property_configuration' ] = castorHTML::selectList($yesno, 'cfg_compatability_property_configuration', '', 'value', 'text', $jrConfig[ 'compatability_property_configuration' ]);

			if (!isset($jrConfig[ 'collect_analytics_allowed' ])) {
				$jrConfig[ 'collect_analytics_allowed' ] = 0;
			}
			$lists[ 'collect_analytics_allowed' ] = castorHTML::selectList($yesno, 'cfg_collect_analytics_allowed', '', 'value', 'text', $jrConfig[ 'collect_analytics_allowed' ]);



			if (!isset($jrConfig['show_powered_by'])) {
				$jrConfig['show_powered_by'] = '0';
			}
			$lists[ 'show_powered_by' ] = castorHTML::selectList($yesno, 'cfg_show_powered_by', '', 'value', 'text', $jrConfig[ 'show_powered_by' ]);

			$lists[ 'use_budget_feature' ] = castorHTML::selectList($yesno, 'cfg_use_budget_feature', '', 'value', 'text', $jrConfig[ 'use_budget_feature' ]);

			if (!isset($jrConfig[ 'navbar_inverse' ])) {
				$jrConfig[ 'navbar_inverse' ] = 0;
			}

			$lists[ 'navbar_inverse' ] = castorHTML::selectList($yesno, 'cfg_navbar_inverse', '', 'value', 'text', $jrConfig[ 'navbar_inverse' ]);

			if (!isset($jrConfig[ 'automatically_approve_new_properties' ])) {
				$jrConfig[ 'automatically_approve_new_properties' ] = '1';
			}

			$lists[ 'region_names_are_translatable' ] = castorHTML::selectList($yesno, 'cfg_region_names_are_translatable', '', 'value', 'text', $jrConfig[ 'region_names_are_translatable' ]);

			$lists[ 'automatically_approve_new_properties' ] = castorHTML::selectList($yesno, 'cfg_automatically_approve_new_properties', '', 'value', 'text', $jrConfig[ 'automatically_approve_new_properties' ]);

			if (!isset($jrConfig[ 'use_bootstrap_in_frontend' ])) {
				if (this_cms_is_joomla()) {
					$jrConfig[ 'use_bootstrap_in_frontend' ] = '1';
				} else {
					$jrConfig[ 'use_bootstrap_in_frontend' ] = '0';
				}
			}

			$lists[ 'use_bootstrap_in_frontend' ] = castorHTML::selectList($yesno, 'cfg_use_bootstrap_in_frontend', '', 'value', 'text', $jrConfig[ 'use_bootstrap_in_frontend' ]);

			if (!isset($jrConfig['live_scrolling_enabled'])) {
				$jrConfig['live_scrolling_enabled'] = '1';
			}

			$lists[ 'live_scrolling_enabled' ] = castorHTML::selectList($yesno, 'cfg_live_scrolling_enabled', '', 'value', 'text', $jrConfig[ 'live_scrolling_enabled' ]);

			if (!isset($jrConfig[ 'load_font_awesome' ])) {
				$jrConfig[ 'load_font_awesome' ] = '0';
			}
			$lists[ 'load_font_awesome' ] = castorHTML::selectList($yesno, 'cfg_load_font_awesome', '', 'value', 'text', $jrConfig[ 'load_font_awesome' ]);
			$lists[ 'override_property_contact_details' ] = castorHTML::selectList($yesno, 'cfg_override_property_contact_details', '', 'value', 'text', (int) $jrConfig[ 'override_property_contact_details' ]);

			$lists[ 'currency_symbol_swap' ] = castorHTML::selectList($yesno, 'cfg_currency_symbol_swap', '', 'value', 'text', (int) $jrConfig[ 'currency_symbol_swap' ]);

			$map_styles = array();
			$map_style_files = get_map_styles();
			foreach ($map_style_files as $style_file) {
				$map_styles[ ] = castorHTML::makeOption($style_file, $style_file);
			}
			$map_styles_dropdown = castorHTML::selectList($map_styles, 'cfg_map_style', '', 'value', 'text', $jrConfig[ 'map_style' ]);

			$lists[ 'sendErrorEmails' ] = castorHTML::selectList($yesno, 'cfg_sendErrorEmails', '', 'value', 'text', $jrConfig[ 'sendErrorEmails' ]);
			$lists[ 'plist_images_as_slideshow' ] = castorHTML::selectList($yesno, 'cfg_plist_images_as_slideshow', '', 'value', 'text', $jrConfig[ 'plist_images_as_slideshow' ]);
			$lists[ 'delete_all_data_on_uninstall' ] = castorHTML::selectList($yesno, 'cfg_delete_all_data_on_uninstall', '', 'value', 'text', $jrConfig[ 'delete_all_data_on_uninstall' ]);

			$options = array();
			$options[ ] = castorHTML::makeOption('Minicomponent', 'Minicomponent');
			$options[ ] = castorHTML::makeOption('Cron', 'Cron job');
			$lists[ 'cron_method' ] = castorHTML::selectList($options, 'cfg_cron_method', '', 'value', 'text', $jrConfig[ 'cron_method' ]);

			$options = array();
			$options[ ] = castorHTML::makeOption('file', 'File');
			$options[ ] = castorHTML::makeOption('database', 'Database');
			$lists[ 'session_handler' ] = castorHTML::selectList($options, 'cfg_session_handler', '', 'value', 'text', $jrConfig[ 'session_handler' ]);

			$options = array();
			$options[] = castorHTML::makeOption('ROADMAP', 'Roadmap');
			$options[] = castorHTML::makeOption('SATELLITE', 'Satellite');
			$options[] = castorHTML::makeOption('HYBRID', 'Hybrid');
			$options[] = castorHTML::makeOption('TERRAIN', 'Terrain');
			$lists[ 'map_type' ] = castorHTML::selectList($options, 'cfg_map_type', '', 'value', 'text', $jrConfig[ 'map_type' ]);

			//frontend cpanel home page grid options
			$options = array();
			$options[ ] = castorHTML::makeOption('2/3 1/3', '2/3 | 1/3');
			$options[ ] = castorHTML::makeOption('1/3 2/3', '1/3 | 2/3');
			$options[ ] = castorHTML::makeOption('1/3 1/3 1/3', '1/3 | 1/3 | 1/3');
			$options[ ] = castorHTML::makeOption('1/4 1/4 1/4 1/4', '1/4 | 1/4 | 1/4 | 1/4');
			$lists[ 'front_cpanel_home_grid' ] = castorHTML::selectList($options, 'cfg_front_cpanel_home_grid', '', 'value', 'text', $jrConfig[ 'front_cpanel_home_grid' ]);

			$options = array();
			for ($i=1; $i<=23; $i++) {
				$options[] = castorHTML::makeOption($i, $i);
			}
			$lists[ 'map_zoom' ] = castorHTML::selectList($options, 'cfg_map_zoom', '', 'value', 'text', $jrConfig[ 'map_zoom' ]);

			$lists[ 'send_email_copies_to_site_admins' ] = castorHTML::selectList($yesno, 'cfg_send_email_copies_to_site_admins', '', 'value', 'text', $jrConfig[ 'send_email_copies_to_site_admins' ]);

			$lists[ 'enable_gdpr_compliant_fucntionality' ] = castorHTML::selectList($yesno, 'cfg_enable_gdpr_compliant_fucntionality', '', 'value', 'text', (int) $jrConfig[ 'enable_gdpr_compliant_fucntionality' ]);


			$lists[ 'prioritise_sitewide_label_definitions' ] = castorHTML::selectList($yesno, 'cfg_prioritise_sitewide_label_definitions', '', 'value', 'text', $jrConfig[ 'prioritise_sitewide_label_definitions' ]);

			$lists[ 'generate_random_emails' ] = castorHTML::selectList($yesno, 'cfg_generate_random_emails', '', 'value', 'text', $jrConfig[ 'generate_random_emails' ]);

			$lists[ 'use_groupby_fix' ] = castorHTML::selectList($yesno, 'cfg_use_groupby_fix', '', 'value', 'text', $jrConfig[ 'use_groupby_fix' ]);

			$componentArgs = array();
			$componentArgs[ 'lists' ] = $lists;
			$componentArgs[ 'jsInputFormatDropdownList' ] = $jsInputFormatDropdownList;
			$componentArgs[ 'jrtb' ] = $jrtb;
			$componentArgs[ 'geosearchDropdownList' ] = $geosearchDropdownList;
			$componentArgs[ 'currency_codes_dropdown' ] = $currency_codes_dropdown;
			$componentArgs[ 'jqueryUIthemesDropdownList' ] = $jqueryUIthemesDropdownList;
			$componentArgs[ 'sortArrayDropdown' ] = $sortArrayDropdown;
			$componentArgs[ 'calendarStartDaysDropdownList' ] = $calendarStartDaysDropdownList;
			$componentArgs[ 'language_context_dropdown' ] = $language_context_dropdown;
			$componentArgs[ 'guestnumbersearchDropdownList' ] = $guestnumbersearchDropdownList;
			$componentArgs[ 'filtering_level_dropdown' ] = $filtering_level_dropdown;
			$componentArgs[ 'layouts' ] = $layouts;
			$componentArgs[ 'production_development_dropdown' ] = $production_development_dropdown;
			$componentArgs[ 'navbar_location_dropdown' ] = $navbar_location_dropdown;
			$componentArgs[ 'bootstrap_ver_dropdown' ] = $bootstrap_ver_dropdown;
			$componentArgs[ 'map_styles_dropdown' ] = $map_styles_dropdown;
			$componentArgs[ 'admin_options_level_dropdown' ] = $admin_options_level_dropdown;

			ob_start(); ?>
			<h2 class="page-header">Castor <?php echo jr_gettext('_CASTOR_A', '_CASTOR_A', false); ?></h2>
			<form action="<?php echo CASTOR_SITEPAGE_URL_ADMIN; ?>" method="post" name="adminForm">
				<input type="hidden" name="cfg_useGlobalPFeatures" value="<?php echo $jrConfig[ 'useGlobalPFeatures' ]; ?>"/>
				<input type="hidden" name="cfg_useGlobalRoomTypes" value="<?php echo $jrConfig[ 'useGlobalRoomTypes' ]; ?>"/>
				<input type="hidden" name="cfg_dynamicMinIntervalRecalculation" value="<?php echo $jrConfig[ 'dynamicMinIntervalRecalculation' ]; ?>"/>
				<input type="hidden" name="cfg_disableAudit" value="<?php echo $jrConfig[ 'disableAudit' ]; ?>"/>
				<input type="hidden" name="cfg_allowedTags" value="<?php echo $jrConfig[ 'allowedTags' ]; ?>"/>
				<input type="hidden" name="no_html" value="1"/>
				<input type="hidden" name="task" value="save_site_settings"/>
				<input type="hidden" name="option" value="com_castor"/>
				<input type="hidden" name="castor_csrf_token" value="<?php echo csrf::setToken(); ?>"/>

				<?php
					echo $jrtb;

					$bs_version = castor_bootstrap_version();
					if ($bs_version == '2' || $bs_version == '' || $bs_version == '0' || $bs_version == '3') {
						$configurationPanel = castor_singleton_abstract::getInstance('castor_configpanel');
					} elseif ($bs_version == '5') {
						$configurationPanel = castor_singleton_abstract::getInstance('castor_configpanel_bootstrap5');
					}

					$componentArgs[ 'configurationPanel' ] = $configurationPanel;

					$configurationPanel->startTabs();

					$MiniComponents->triggerEvent('10501', $componentArgs); // Generate configuration options tabs

					$configurationPanel->endTabs();
				?>
			</form>
			<?php
			ob_end_flush();
		}
	}


/**
 * @package Castor\Core\Functions
 *
 * Saves the site configuration data.
 */
	if (!function_exists('saveSiteConfig')) {
		function saveSiteConfig($overrides = array())
		{

			ignore_user_abort(true);

			if (file_exists(CASTOR_TEMP_ABSPATH.'key.php')) {
				unlink(CASTOR_TEMP_ABSPATH.'key.php');
			}

			if (file_exists(CASTORCONFIG_ABSOLUTE_PATH.JRDS.CASTOR_ROOT_DIRECTORY.JRDS.'configuration.php')) {
				include CASTORCONFIG_ABSOLUTE_PATH.JRDS.CASTOR_ROOT_DIRECTORY.JRDS.'configuration.php';
				$tmpConfig = $jrConfig;
			} else {
				include CASTORCONFIG_ABSOLUTE_PATH.JRDS.CASTOR_ROOT_DIRECTORY.JRDS.'site_config.php';
				$tmpConfig = $jrConfig;
			}
			if (!empty($overrides)) {
				foreach ($overrides as $key => $val) {
					$tmpConfig[$key] = $val;
				}
			}

			foreach ($_POST as $k => $v) {
				if (strpos($k, 'cfg_') !== false && !is_array($v)) {
					$v = castorGetParam($_POST, $k, '');

					$dirty = (string) $k;
					$k = substr(addslashes($dirty), 4);
					$v = filter_var($v, FILTER_SANITIZE_SPECIAL_CHARS);

					$tmpConfig[ $k ] = $v;
				} elseif (strpos($k, 'cfgArr_') !== false) {
					$v = castorGetParam($_POST, $k, array());
					$v = implode(',', $v);

					$dirty = (string) $k;
					$k = substr(addslashes($dirty), 7);
					$v = filter_var($v, FILTER_SANITIZE_SPECIAL_CHARS);

					$tmpConfig[ $k ] = $v;
				} elseif (is_array($v)) { // Adds support for multi-dimensional arrays being used for channel manager framework
					$dirty = (string) $k;
					$k = substr(addslashes($dirty), 4);
					if (is_array($v)) {
						foreach ($v as $a => $b) {
							if (is_array($b)) {
								foreach ($b as $c => $d) {
									$a = (string)filter_var($a, FILTER_SANITIZE_SPECIAL_CHARS);
									$c = (string)filter_var($c, FILTER_SANITIZE_SPECIAL_CHARS);
									$d = (string)filter_var($d, FILTER_SANITIZE_SPECIAL_CHARS);

									$tmpConfig[$k][$a][$c] = $d;
								}
							}
						}
					}
				}
			}

			//save config to file
			$config_last_modified = filemtime(CASTORCONFIG_ABSOLUTE_PATH.JRDS.CASTOR_ROOT_DIRECTORY.JRDS.'configuration.php');

			$result = file_put_contents(
				CASTORCONFIG_ABSOLUTE_PATH.JRDS.CASTOR_ROOT_DIRECTORY.JRDS.'configuration.php',
				'<?php
##################################################################
defined( \'_CASTOR_INITCHECK\' ) or die( \'\' );
##################################################################

$jrConfig = ' .var_export($tmpConfig, true).';
'
			);

			// On my Ubuntu box, and on some client boxes, there's a delay in saving the config file so we will wait, then wait a bit more after the file mod time has been updated

			do {
				sleep(1); // Writing the file could take a moment
				clearstatcache();
				$newest_last_modified_check = filemtime(CASTORCONFIG_ABSOLUTE_PATH.JRDS.CASTOR_ROOT_DIRECTORY.JRDS.'configuration.php');
			} while ($newest_last_modified_check <= $config_last_modified);

			sleep(2);

			if (!$result) {
				trigger_error('ERROR: '.CASTORCONFIG_ABSOLUTE_PATH.JRDS.CASTOR_ROOT_DIRECTORY.JRDS.'configuration.php'.' can`t be saved. Please solve the permission problem and try again.', E_USER_ERROR);
				exit;
			}

			//cleanup
			$registry = castor_singleton_abstract::getInstance('minicomponent_registry');
			$registry->regenerate_registry();


			if (file_exists(CASTOR_TEMP_ABSPATH.'latest_version.php')) {
				unlink(CASTOR_TEMP_ABSPATH.'latest_version.php');
			}

			if (empty($overrides)) { // If we've come from the Site Config page, we want to redirect the user back to the site configuration page, otherwise we don't redirect.
				castorRedirect(castorURL(CASTOR_SITEPAGE_URL_ADMIN.'&task=site_settings'), 'Configuration saved');
			}
		}
	}


/**
 * @package Castor\Core\Functions
 *
 *  Colour schemes for google maps
 */
	if (!function_exists('searchCSSThemesDirForCSSFiles')) {
		function searchCSSThemesDirForCSSFiles()
		{
			$cssFiles = array();
			$jrePath = CASTOR_NODE_MODULES_ABSPATH.'jquery-ui-themes'.JRDS.'themes'.JRDS;
			$d = @dir($jrePath);
			$docs = array();
			if ($d) {
				while (false !== ($entry = $d->read())) {
					if (substr($entry, 0, 1) != '.') {
						$docs[ ] = $entry;
					}
				}
				$d->close();
				if (!empty($docs)) {
					sort($docs);
					foreach ($docs as $doc) {
						$listdir = $jrePath.$doc.JRDS;
						$dr = @dir($listdir);
						if ($dr) {
							while (false !== ($entry = $dr->read())) {
								$filename = $entry;
								$tmpArr = explode('.', $filename);
								$extension = $tmpArr[ count($tmpArr) - 1 ];
								if ($filename == 'jquery-ui.min.css') {
									$cssFiles[ ] = array('cssfile' => $filename, 'subdir' => $doc);
								}
							}
							$dr->close();
						}
					}
				}
			}
			$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
			$colourSchemeDataArray = $MiniComponents->triggerEvent('00021'); // optional

			if (is_array($colourSchemeDataArray)) {
				$cssFiles = array_merge($cssFiles, $colourSchemeDataArray);
			}

			return $cssFiles;
		}
	}


/**
 * @package Castor\Core\Functions
 *
 *  Find google map styles
 */
	if (!function_exists('get_map_styles')) {
		function get_map_styles()
		{
			$map_style_dir = CASTOR_ASSETS_ABSPATH.'map_styles'.JRDS;
			$styles = array();
			foreach (new DirectoryIterator($map_style_dir) as $file) {
				if ($file->isFile()) {
					$bang = explode('.', $file->getfilename());
					$styles[] = $bang[0];
				}
			}
			natsort($styles);

			return $styles;
		}
	}


