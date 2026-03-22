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

class j19995menu
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
		// Must be in all minicomponents. Minicomponents with templates that can contain editable text should run $this->template_touch() else just return
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = false;

			return;
		}
		
		if (AJAXCALL) {
			return;
		}

		$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
		$jrConfig = $siteConfig->get();

		if (!isset($jrConfig[ 'admin_options_level' ])) {
			$jrConfig[ 'admin_options_level' ] = 0;
		}

		$castor_menu = castor_singleton_abstract::getInstance('castor_menu');
		
		//define the core admin sections
		$castor_menu->add_admin_section(1, jr_gettext('_ADMIN_MENU_SECTIONS_DASHBOARD', '_ADMIN_MENU_SECTIONS_DASHBOARD', false, false));
		$castor_menu->add_admin_section(10, jr_gettext('_ADMIN_MENU_SECTIONS_USERS', '_ADMIN_MENU_SECTIONS_USERS', false, false));
		$castor_menu->add_admin_section(20, jr_gettext('_ADMIN_MENU_SECTIONS_COMMISSION', '_ADMIN_MENU_SECTIONS_COMMISSION', false, false));
		$castor_menu->add_admin_section(30, jr_gettext('_ADMIN_MENU_SECTIONS_SUBSCRIPTIONS', '_ADMIN_MENU_SECTIONS_SUBSCRIPTIONS', false, false));
		$castor_menu->add_admin_section(40, jr_gettext('_ADMIN_MENU_SECTIONS_INVOICES', '_ADMIN_MENU_SECTIONS_INVOICES', false, false));
		$castor_menu->add_admin_section(50, jr_gettext('_ADMIN_MENU_SECTIONS_PORTAL', '_ADMIN_MENU_SECTIONS_PORTAL', false, false));
		$castor_menu->add_admin_section(60, jr_gettext('_ADMIN_MENU_SECTIONS_TRANSLATIONS', '_ADMIN_MENU_SECTIONS_TRANSLATIONS', false, false));
		$castor_menu->add_admin_section(70, jr_gettext('_ADMIN_MENU_SECTIONS_TOOLS', '_ADMIN_MENU_SECTIONS_TOOLS', false, false));
		$castor_menu->add_admin_section(80, jr_gettext('_ADMIN_MENU_SECTIONS_REPORTS', '_ADMIN_MENU_SECTIONS_REPORTS', false, false));
		$castor_menu->add_admin_section(90, jr_gettext('_ADMIN_MENU_SECTIONS_SETTINGS', '_ADMIN_MENU_SECTIONS_SETTINGS', false, false));
		$castor_menu->add_admin_section(100, jr_gettext('_ADMIN_MENU_SECTIONS_HELP', '_ADMIN_MENU_SECTIONS_HELP', false, false));
		


		//define the core admin menu items
		//dashboard section menus
		$castor_menu->add_admin_item(1, jr_gettext('_CASTOR_FRONT_MR_MENU_ADMIN_HOME', '_CASTOR_FRONT_MR_MENU_ADMIN_HOME', false), '', 'fa-tachometer');
		$castor_menu->add_admin_item(1, jr_gettext('_CASTOR_CUSTOMCODE_PLUGINMANAGER', '_CASTOR_CUSTOMCODE_PLUGINMANAGER', false), 'showplugins', 'fa-cloud-download');
		$castor_menu->add_admin_item(1, jr_gettext('_CASTOR_CUSTOMCODE_UPGRADES', '_CASTOR_CUSTOMCODE_UPGRADES', false), 'updates', 'fa-cloud-download');
		
		//users section menus
		$castor_menu->add_admin_item(10, jr_gettext('_CASTOR_COM_MR_SHOWPROFILES', '_CASTOR_COM_MR_SHOWPROFILES', false), 'list_users', 'fa-user');
		if ($jrConfig[ 'admin_options_level' ] > 0) {
			$castor_menu->add_admin_item(10, jr_gettext('_CASTOR_HLIST_GUESTS_MENU', '_CASTOR_HLIST_GUESTS_MENU', false), 'list_guests', 'fa-list');
		}

		//invoices section menus
		if ($jrConfig[ 'use_commission' ] == '1' || $jrConfig[ 'useSubscriptions' ] == '1') {
			$castor_menu->add_admin_item(40, jr_gettext('_JRPORTAL_INVOICES_TITLE', '_JRPORTAL_INVOICES_TITLE', false), 'list_invoices', 'fa-file-text-o');
		}
		
		//portal section menus
		if ($jrConfig[ 'admin_options_level' ] > 0) {
			$castor_menu->add_admin_item(50, jr_gettext('_CASTOR_APPROVALS_MENU_NAME', '_CASTOR_APPROVALS_MENU_NAME', false), 'property_approvals', 'fa-check-circle');
			$castor_menu->add_admin_item(50, jr_gettext('_CASTOR_REVIEWS', '_CASTOR_REVIEWS', false), 'list_reviews', 'fa-thumbs-up');
		}

		//translations section menus
		$castor_menu->add_admin_item(60, jr_gettext('_CASTOR_TOUCHTEMPLATES', '_CASTOR_TOUCHTEMPLATES', false), 'touch_templates', 'fa-globe');
		$castor_menu->add_admin_item(60, jr_gettext('_CASTOR_COM_TRANSLATE_LANGUAGEFILES', '_CASTOR_COM_TRANSLATE_LANGUAGEFILES', false), 'translate_lang_file_strings', 'fa-globe');
		if ($jrConfig[ 'admin_options_level' ] > 1) {
			$castor_menu->add_admin_item(60, jr_gettext('_CASTOR_COM_TRANSLATE_COUNTRIESREGIONS', '_CASTOR_COM_TRANSLATE_COUNTRIESREGIONS', false), 'translate_locales', 'fa-globe');
			$castor_menu->add_admin_item(60, jr_gettext('_CASTOR_EXPORT_DEFINITIONS', '_CASTOR_EXPORT_DEFINITIONS', false), 'export_definitions', 'fa-floppy-o');
		}

		//tools section menus
		$castor_menu->add_admin_item(70, jr_gettext('_CASTOR_SHORTCODES', '_CASTOR_SHORTCODES', false), 'asamodule_report', 'fa-code');
		//$castor_menu->add_admin_item(70, jr_gettext('_CASTOR_DATA_ARCHIVE_TITLE', '_CASTOR_DATA_ARCHIVE_TITLE', false), 'booking_data_archive', 'fa-archive');
		$castor_menu->add_admin_item(70, 'Changelog', 'changelog', 'fa-file-code-o');
		//$castor_menu->add_admin_item(70, jr_gettext('INTEGRITY_CHECK', 'INTEGRITY_CHECK', false), 'filesystem_integrity_check', 'fa-check-square-o');
		$castor_menu->add_admin_item(70, jr_gettext('DATABASE_INTEGRITY_CHECK', 'DATABASE_INTEGRITY_CHECK', false), 'database_integrity_check', 'fa-check-square-o');

		if ($jrConfig[ 'admin_options_level' ] > 0) {
			$castor_menu->add_admin_item(70, jr_gettext('OBSOLETE_FILES_CHECK', 'OBSOLETE_FILES_CHECK', false), 'obsolete_files_check', 'fa-check-square-o');
		}
		if ($jrConfig[ 'admin_options_level' ] > 0) {
			$castor_menu->add_admin_item(70, jr_gettext('FIREWALL_CHECK', 'FIREWALL_CHECK', false), $task = 'firewall_check', 'fa-check-square-o');
		}

		if ($jrConfig[ 'admin_options_level' ] > 0) {
			$castor_menu->add_admin_item(70, jr_gettext('CASTOR_COM_A_AVAILABLELOGS', 'CASTOR_COM_A_AVAILABLELOGS', false), 'list_error_logs', 'fa-exclamation-triangle');
		}

		if ($jrConfig[ 'admin_options_level' ] > 1) {
			$castor_menu->add_admin_item(70, jr_gettext('_CASTOR_REGISTRYREBUILD', '_CASTOR_REGISTRYREBUILD', false), 'rebuildregistry', 'fa-refresh');
		}

		if ($jrConfig[ 'admin_options_level' ] > 0) {
			$castor_menu->add_admin_item(70, jr_gettext('EMPTY_TEMP_DIR', 'EMPTY_TEMP_DIR', false), 'empty_temp_directory', 'fa-trash');
		}

		if ($jrConfig[ 'admin_options_level' ] > 0) {
			$castor_menu->add_admin_item(70, jr_gettext('_CASTOR_REST_API_CONNECTIVITY_TEST', '_CASTOR_REST_API_CONNECTIVITY_TEST', false), 'rest_api_connectivity_test', 'fa-arrows-h');
		}

		if ($jrConfig[ 'admin_options_level' ] > 0) {
			$castor_menu->add_admin_item(70, jr_gettext('_CASTOR_LIBRARY_PACKAGES', '_CASTOR_LIBRARY_PACKAGES', false), 'refresh_library_packages', 'fa-book');
		}

		if ($jrConfig[ 'admin_options_level' ] > 0) {
			$template_packages = get_showtime('template_packages');
			if (!empty($template_packages)) { // There are some override packages installed, we can go ahead and show the override manager menu option
				$castor_menu->add_admin_item(70, jr_gettext('_CASTOR_TEMPLATE_PACKAGES', '_CASTOR_TEMPLATE_PACKAGES', false), 'list_template_overrides', 'fa-puzzle-piece');
			}
		}

		if ($jrConfig[ 'admin_options_level' ] > 0) {
			if ($jrConfig[ 'images_imported_to_db' ] == '1') {
				$castor_menu->add_admin_item(70, jr_gettext('_CASTOR_MEDIA_CENTRE_DBIMPORT_FORCE', '_CASTOR_MEDIA_CENTRE_DBIMPORT_FORCE', false), castorUrl(CASTOR_SITEPAGE_URL_ADMIN).'&task=media_centre_dbimport&force=1', 'fa-database', true, true);
			}

			if ($jrConfig[ 'images_imported_to_s3' ] == '1' && $jrConfig[ 'amazon_s3_active' ] == '1') {
				$castor_menu->add_admin_item(70, jr_gettext('_CASTOR_MEDIA_CENTRE_S3IMPORT_FORCE', '_CASTOR_MEDIA_CENTRE_S3IMPORT_FORCE', false), castorUrl(CASTOR_SITEPAGE_URL_ADMIN).'&task=media_centre_s3import&force=1', 'fa-amazon', true, true);
			}
		}


		//reports section menus
		if ($jrConfig[ 'admin_options_level' ] > 0) {
			$castor_menu->add_admin_item(80, jr_gettext('_CASTOR_CHARTS', '_CASTOR_CHARTS', false), 'charts', 'fa-line-chart');
		}


		//settings section menus
		$castor_menu->add_admin_item(90, jr_gettext('_CASTOR_A', '_CASTOR_A', false), 'site_settings', 'fa-cogs');
		if ($jrConfig[ 'admin_options_level' ] > 0) {
			$castor_menu->add_admin_item(90, jr_gettext('_CASTOR_COM_PTYPES_LIST_TITLE', '_CASTOR_COM_PTYPES_LIST_TITLE', false), 'list_property_types', 'fa-building');
		}
		if ($jrConfig[ 'admin_options_level' ] > 0) {
			$castor_menu->add_admin_item(90, jr_gettext('_CASTOR_PROPERTY_HCATEGORIES', '_CASTOR_PROPERTY_HCATEGORIES', false), 'list_property_categories', 'fa-list');
		}

		$castor_menu->add_admin_item(90, jr_gettext('_CASTOR_COM_MR_VRCT_ROOMTYPES_HEADER_LINK', '_CASTOR_COM_MR_VRCT_ROOMTYPES_HEADER_LINK', false), 'listGlobalroomTypes', 'fa-bed');
		$castor_menu->add_admin_item(90, jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTYFEATURES_HEADER_LINK', '_CASTOR_COM_MR_VRCT_PROPERTYFEATURES_HEADER_LINK', false), 'listPfeatures', 'fa-list');
		if ($jrConfig[ 'admin_options_level' ] > 0) {
			$castor_menu->add_admin_item(90, jr_gettext('_CASTOR_PROPERTYFEATURES_HCATEGORIES', '_CASTOR_PROPERTYFEATURES_HCATEGORIES', false), 'listPfeaturesCategories', 'fa-list-ul');
		}
		$castor_menu->add_admin_item(90, jr_gettext('_CASTOR_COM_A_GATEWAYLIST', '_CASTOR_COM_A_GATEWAYLIST', false), 'list_gateways', 'fa-money');
		if ($jrConfig[ 'admin_options_level' ] > 0) {
			$castor_menu->add_admin_item(90, jr_gettext('_JRPORTAL_TAXRATES_TITLE', '_JRPORTAL_TAXRATES_TITLE', false), 'list_taxrates', 'fa-percent');
		}
		//$castor_menu->add_admin_item(90, jr_gettext('_CASTOR_TAX_RULES_LIST', '_CASTOR_TAX_RULES_LIST', false), 'list_tax_rules', 'fa-cogs');
		if ($jrConfig[ 'admin_options_level' ] > 0) {
			$castor_menu->add_admin_item(90, jr_gettext('_CASTOR_COM_LISTCOUNTRIES', '_CASTOR_COM_LISTCOUNTRIES', false), 'list_countries', 'fa-globe');
		}
		if ($jrConfig[ 'admin_options_level' ] > 0) {
			$castor_menu->add_admin_item(90, jr_gettext('_CASTOR_COM_LISTREGIONS', '_CASTOR_COM_LISTREGIONS', false), 'list_regions', 'fa-globe');
		}
		$castor_menu->add_admin_item(90, jr_gettext('_CASTOR_MEDIA_CENTRE_TITLE', '_CASTOR_MEDIA_CENTRE_TITLE', false), 'media_centre', 'fas fa-images fa-picture-o');

		//help section menus
		$castor_menu->add_admin_item(100, jr_gettext('_CASTOR_CUSTOMCODE_SUPPORT_GETTINGSTARTED', '_CASTOR_CUSTOMCODE_SUPPORT_GETTINGSTARTED', false), 'getting_started', 'fa-book');
		//$castor_menu->add_admin_item(100, jr_gettext('_CASTOR_CUSTOMCODE_MANUAL', '_CASTOR_CUSTOMCODE_MANUAL', false), 'https://www.castor.net/manual/', 'fa-book', true, true);
		//$castor_menu->add_admin_item(100, 'Shortcodes', 'http://www.castor.net/manual/developers-guide/305-shortcodes', 'fa-book', true, true);
		//$castor_menu->add_admin_item(100, jr_gettext('_CASTOR_CUSTOMCODE_SUPPORT_ABOUTCASTOR', '_CASTOR_CUSTOMCODE_SUPPORT_ABOUTCASTOR', false), 'http://www.castor.net/manual/developers-guide/60-castor-manual/intro/344-about-castor', 'fa-book', true, true);
		$castor_menu->add_admin_item(100, 'Castor Partners', 'partners', 'fa-book');
		$castor_menu->add_admin_item(100, jr_gettext('API_METHODS_TITLE', 'API_METHODS_TITLE', false), 'https://api.castor.net/', 'fa-book', true, true);
		$castor_menu->add_admin_item(100, jr_gettext('VIDEO_TUTORIALS', 'VIDEO_TUTORIALS', false), 'videos', 'fa-youtube-play');
	}


	public function getRetVals()
	{
		return null;
	}
}

