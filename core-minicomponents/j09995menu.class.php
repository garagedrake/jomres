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

	class j09995menu
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

			if (defined('API_STARTED')) {
				return;
			}

			$menuoff = get_showtime('menuoff');
			if ($menuoff === true) {
				return;
			}

			$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
			$jrConfig = $siteConfig->get();

			$property_uid = getDefaultProperty();

			$mrConfig = getPropertySpecificSettings($property_uid);

			$thisJRUser = castor_singleton_abstract::getInstance('jr_user');

			$castor_menu = castor_singleton_abstract::getInstance('castor_menu');

			jr_import('castor_api_capability_test');
			$castor_api_capability_test = new castor_api_capability_test();
			$system_is_api_capable = $castor_api_capability_test->is_system_capable();

			$viewproperty_url = get_property_details_url($property_uid);
			$dobooking_url = get_booking_url($property_uid);

			jr_import('castor_occupancy_levels');
			$castor_occupancy_levels = new castor_occupancy_levels($property_uid);


			// There are some differences between J3 & J4 and the font awesome icons
			$font_awesome_envelope = 'fa-envelope-o';
			$font_awesome_picture = 'fa-picture-o';
			$font_awesome_dashboard = 'fa-dashboard';
			$font_awesome_logout = 'fa-sign-out';
			$font_awesome_delete = 'fa-trash-o';
			$font_awesome_tariffs = 'fa-usd';
			$font_awesome_edit = 'fa-pencil-square-o';
			$font_awesome_childpolicies = 'fa-users';
			$font_awesome_language = 'fa-language';

			if (castor_bootstrap_version() == '5') {
				$font_awesome_envelope = 'fa-envelope';
				$font_awesome_picture = 'fa-images';
				$font_awesome_dashboard = 'fa-tachometer-alt';
				$font_awesome_logout = 'fa-sign-out-alt';
				$font_awesome_delete = 'fa-trash';
				$font_awesome_tariffs = 'fa-dollar-sign';
				$font_awesome_edit = 'fa-edit';
				$font_awesome_childpolicies = 'fa-child';
				$font_awesome_language = 'fas fa-language';
			}

			//define the core sections
			$castor_menu->add_section(1, jr_gettext('_CASTOR_CUSTOMCODE_CASTORMAINMENU_RECEPTION_HOME', '_CASTOR_CUSTOMCODE_CASTORMAINMENU_RECEPTION_HOME', false));
			$castor_menu->add_section(10, jr_gettext('_CASTOR_CUSTOMCODE_CASTORMAINMENU_RECEPTION_MYACCOUNT', '_CASTOR_CUSTOMCODE_CASTORMAINMENU_RECEPTION_MYACCOUNT', false));
			$castor_menu->add_section(20, jr_gettext('_CASTOR_CUSTOMCODE_CASTORMAINMENU_RECEPTION_PROPERTIES', '_CASTOR_CUSTOMCODE_CASTORMAINMENU_RECEPTION_PROPERTIES', false));
			$castor_menu->add_section(30, jr_gettext('_CASTOR_CUSTOMCODE_CASTORMAINMENU_RECEPTION_BOOKINGS', '_CASTOR_CUSTOMCODE_CASTORMAINMENU_RECEPTION_BOOKINGS', false));
			$castor_menu->add_section(40, jr_gettext('_CASTOR_CUSTOMCODE_CASTORMAINMENU_RECEPTION_GUESTS', '_CASTOR_CUSTOMCODE_CASTORMAINMENU_RECEPTION_GUESTS', false));
			$castor_menu->add_section(50, jr_gettext('_CASTOR_CUSTOMCODE_CASTORMAINMENU_RECEPTION_ACCOUNTING', '_CASTOR_CUSTOMCODE_CASTORMAINMENU_RECEPTION_ACCOUNTING', false));
			$castor_menu->add_section(60, jr_gettext('_CASTOR_CUSTOMCODE_CASTORMAINMENU_MANAGER_REPORTS', '_CASTOR_CUSTOMCODE_CASTORMAINMENU_MANAGER_REPORTS', false));
			$castor_menu->add_section(70, jr_gettext('_CASTOR_CUSTOMCODE_CASTORMAINMENU_RECEPTION_MISC', '_CASTOR_CUSTOMCODE_CASTORMAINMENU_RECEPTION_MISC', false));
			$castor_menu->add_section(80, jr_gettext('_CASTOR_CUSTOMCODE_CASTORMAINMENU_RECEPTION_SETTINGS', '_CASTOR_CUSTOMCODE_CASTORMAINMENU_RECEPTION_SETTINGS', false));
			$castor_menu->add_section(90, jr_gettext('_CASTOR_CUSTOMCODE_MENUCATEGORIES_HELP', '_CASTOR_CUSTOMCODE_MENUCATEGORIES_HELP', false));

			//define the core  menu items
			//dashboard section menus
			if ($thisJRUser->accesslevel >= 50) {
				$castor_menu->add_item(1, jr_gettext('_JRPORTAL_CPANEL', '_JRPORTAL_CPANEL', false), 'cpanel', $font_awesome_dashboard);

				if ($mrConfig[ 'is_real_estate_listing' ] != '1') {
					$castor_menu->add_item(1, jr_gettext('_CASTOR_TIMELINE', '_CASTOR_TIMELINE', false), 'dashboard', 'fa-calendar');
				}
			}

			//my account section menus
			if ($thisJRUser->accesslevel >= 1) {
				$castor_menu->add_item(10, jr_gettext('_CASTOR_MY_ACCOUNT_EDIT', '_CASTOR_MY_ACCOUNT_EDIT', false), 'edit_my_account', 'fa-user');
				$castor_menu->add_item(10, jr_gettext('GUEST_PROFILE_TITLE_MY', 'GUEST_PROFILE_TITLE_MY', false), 'show_user_profile', 'fa-user');
			}

			if ($thisJRUser->accesslevel == 1 && $jrConfig['is_single_property_installation'] == '0' && $jrConfig[ 'selfRegistrationAllowed' ] == '1') {
				$castor_menu->add_item(10, jr_gettext('_CASTOR_USER_LISTMYPROPERTY', '_CASTOR_USER_LISTMYPROPERTY', false), 'new_property', 'fa-plus');
			}

			if ($thisJRUser->accesslevel == 1) {
				$castor_menu->add_item(10, jr_gettext('_JOMCOMP_MYUSER_LISTBOOKINGS', '_JOMCOMP_MYUSER_LISTBOOKINGS', false), 'mulistbookings', 'fa-list');
				$castor_menu->add_item(10, jr_gettext('_JRPORTAL_INVOICES_SHOWINVOICES', '_JRPORTAL_INVOICES_SHOWINVOICES', false), 'list_invoices', 'fa-list');
			}

			if ($thisJRUser->accesslevel >= 1 && get_showtime('numberOfPropertiesInSystem') > 1) {
				$castor_menu->add_item(10, jr_gettext('_JOMCOMP_MYUSER_VIEWFAVOURITES', '_JOMCOMP_MYUSER_VIEWFAVOURITES', false), 'muviewfavourites', 'fa-heart');
			}

			if ($thisJRUser->accesslevel == 0) {
				$castor_menu->add_item(10, 'Register', 'cms_user_register', 'fa-user-plus');
				$castor_menu->add_item(10, jr_gettext('_CASTOR_CUSTOMCODE_CASTORMAINMENU_LOGIN', '_CASTOR_CUSTOMCODE_CASTORMAINMENU_LOGIN', false), 'cms_user_login', 'fa-sign-in-alt');
			}

			if ($thisJRUser->accesslevel >= 1) {
				$castor_menu->add_item(10, jr_gettext('_CASTOR_CUSTOMCODE_CASTORMAINMENU_LOGOUT', '_CASTOR_CUSTOMCODE_CASTORMAINMENU_LOGOUT', false), 'logout', $font_awesome_logout);
			}

			if (!isset($jrConfig[ 'api_core_show' ])) {
				$jrConfig[ 'api_core_show' ] =1;
			}

			if ($thisJRUser->accesslevel >= 1 && $jrConfig[ 'api_core_show' ] == '1' && $system_is_api_capable === true ) {
				$castor_menu->add_item(10, jr_gettext('_OAUTH_TITLE', '_OAUTH_TITLE', false), 'oauth', 'fa-key');
				$castor_menu->add_item(10, jr_gettext('API_DOCUMENTATION_TITLE', 'API_DOCUMENTATION_TITLE', false), 'api_documentation', 'fa-book');
			}

			if (!isset($jrConfig[ 'webhooks_core_show' ])) {
				$jrConfig[ 'webhooks_core_show' ] =1;
			}

			if ($thisJRUser->accesslevel >= 50 && $jrConfig[ 'api_core_show' ] == '1') {
				$castor_menu->add_item(10, jr_gettext('WEBHOOKS_CORE', 'WEBHOOKS_CORE', false), 'webhooks_core', 'fa-key');
				$castor_menu->add_item(10, jr_gettext('WEBHOOKS_DOCUMENTATION_TITLE', 'WEBHOOKS_DOCUMENTATION_TITLE', false), 'webhooks_core_documentation', 'fa-book');
			}

			if ($jrConfig[ 'enable_gdpr_compliant_fucntionality' ] == "1") {
				$castor_menu->add_item(10, jr_gettext('_CASTOR_GDPR_APP_MENU_ITEM', '_CASTOR_GDPR_APP_MENU_ITEM', false), 'show_consent_form', 'fa-lock');
				$castor_menu->add_item(10, jr_gettext('_CASTOR_GDPR_MY_DATA', '_CASTOR_GDPR_MY_DATA', false), 'gdpr_my_data', 'fa-lock');
			}

			//properties section menus
			if ($thisJRUser->accesslevel >= 50 && get_showtime('numberOfPropertiesInSystem') > 1 ) {
				$castor_menu->add_item(20, jr_gettext('_JRPORTAL_CPANEL_LISTPROPERTIES', '_JRPORTAL_CPANEL_LISTPROPERTIES', false), 'listyourproperties', 'fa-list');
			}

			$property_limit_reached = false;
			if (function_exists("get_number_of_allowed_properties")) {
				if (get_showtime('numberOfPropertiesInSystem') >= get_number_of_allowed_properties()) {
					$property_limit_reached = true;
				}
			}

			if ($thisJRUser->accesslevel > 50 && $jrConfig['is_single_property_installation'] == '0' && ($jrConfig[ 'selfRegistrationAllowed' ] == '1' || $thisJRUser->accesslevel >= 90) && !$property_limit_reached) {
				$castor_menu->add_item(20, jr_gettext('_CASTOR_COM_MR_NEWPROPERTY', '_CASTOR_COM_MR_NEWPROPERTY', false), 'new_property', 'fa-plus');
			}

			if ($thisJRUser->accesslevel >= 50) {
				if ($viewproperty_url) {
					$castor_menu->add_item(20, jr_gettext('_CASTOR_FRONT_PREVIEW', '_CASTOR_FRONT_PREVIEW', false), $viewproperty_url, 'fa-eye', true);
				}
			}

			if ($thisJRUser->accesslevel > 50 && get_showtime('numberOfPropertiesInSystem') > 1 && isset($thisJRUser->authorisedProperties[1])) {
				$castor_menu->add_item(20, jr_gettext('_CASTOR_COM_MR_PROPERTY_DELETE', '_CASTOR_COM_MR_PROPERTY_DELETE', false), 'delete_property', $font_awesome_delete);
			}

			//booking section menus
			if ($thisJRUser->accesslevel >= 50 && $mrConfig[ 'is_real_estate_listing' ] != '1') {
				$castor_menu->add_item(30, jr_gettext('_CASTOR_FRONT_MR_MENU_ADMIN_LISTBOOKINGS', '_CASTOR_FRONT_MR_MENU_ADMIN_LISTBOOKINGS', false), 'list_bookings', 'fa-list');

				if ($dobooking_url) {
					$castor_menu->add_item(30, jr_gettext('_CASTOR_HNEW_BOOKING', '_CASTOR_HNEW_BOOKING', false), $dobooking_url, 'fa-plus', true);
				}
			}

			//guests section menus
			if ($thisJRUser->accesslevel >= 50 && $mrConfig[ 'is_real_estate_listing' ] != '1') {
				$castor_menu->add_item(40, jr_gettext('_CASTOR_HLIST_GUESTS_MENU', '_CASTOR_HLIST_GUESTS_MENU', false), 'list_guests', 'fa-list');
				$castor_menu->add_item(40, jr_gettext('_CASTOR_COM_MR_NEWGUEST', '_CASTOR_COM_MR_NEWGUEST', false), 'edit_guest', 'fa-plus');
			}

			//invoices section menus
			if ($thisJRUser->accesslevel >= 50 && $mrConfig[ 'is_real_estate_listing' ] != '1') {
				$castor_menu->add_item(50, jr_gettext('_CASTOR_HLIST_INVOICES_MENU', '_CASTOR_HLIST_INVOICES_MENU', false), 'list_invoices', 'fa-list');
			}

			//reports section menus
			if ($thisJRUser->accesslevel > 50 && $mrConfig[ 'is_real_estate_listing' ] != '1') {
				$castor_menu->add_item(60, jr_gettext('_CASTOR_CHARTS', '_CASTOR_CHARTS', false), 'charts', 'fa-line-chart');
				$castor_menu->add_item(60, jr_gettext('_CASTOR_OVERALL_ROOMS_BOOKED', '_CASTOR_OVERALL_ROOMS_BOOKED', false), 'weekly_occupancy_percentages', 'fa-percent');
			}

			//misc section menus
			if ($thisJRUser->accesslevel >= 50) {
				$castor_menu->add_item(90, jr_gettext('_CASTOR_CUSTOMCODE_CASTORMAINMENU_SEARCH', '_CASTOR_CUSTOMCODE_CASTORMAINMENU_SEARCH', false), 'search', 'fa-search');
				$castor_menu->add_item(90, 'Typography', 'typography', 'fas fa-paint-brush');
			}

			//settings section menus
			if ($thisJRUser->accesslevel > 50) {
				$castor_menu->add_item(80, jr_gettext('_CASTOR_PATHWAY_PROPERTYDETAILS', '_CASTOR_PATHWAY_PROPERTYDETAILS', false), 'edit_property', $font_awesome_edit);
				$castor_menu->add_item(80, jr_gettext('_CASTOR_COM_MR_GENERALCONFIGDESC', '_CASTOR_COM_MR_GENERALCONFIGDESC', false), 'business_settings', 'fa-cogs');
				$castor_menu->add_item(80, jr_gettext('_CASTOR_COM_A_GATEWAYLIST', '_CASTOR_COM_A_GATEWAYLIST', false), 'payment_gateways', "fas fa-wallet" );


				$castor_menu->add_item(80, jr_gettext('CASTOR_TRANSLATIONS_TITLE', 'CASTOR_TRANSLATIONS_TITLE', false), 'translating', $font_awesome_language);

				$castor_menu->add_item(80, jr_gettext('_CASTOR_MEDIA_CENTRE_TITLE', '_CASTOR_MEDIA_CENTRE_TITLE', false), 'media_centre', $font_awesome_picture);

				if ($mrConfig[ 'is_real_estate_listing' ] != '1' && $mrConfig['tariffmode'] != '0' && !get_showtime('is_jintour_property')) {
					$castor_menu->add_item(80, jr_gettext('_CASTOR_COM_MR_VRCT_TAB_ROOM', '_CASTOR_COM_MR_VRCT_TAB_ROOM', false), 'list_resources', 'fa-bed');
				}

				if ($mrConfig[ 'is_real_estate_listing' ] != '1' && $mrConfig['tariffmode'] == '0' && !get_showtime('is_jintour_property')) {
					$castor_menu->add_item(80, jr_gettext('_CASTOR_COM_MR_LISTTARIFF_TITLE', '_CASTOR_COM_MR_LISTTARIFF_TITLE', false).' &amp; '.jr_gettext('_CASTOR_COM_MR_VRCT_TAB_ROOM', '_CASTOR_COM_MR_VRCT_TAB_ROOM', false), 'edit_tariffs_normal', $font_awesome_tariffs);
				}

				if ($mrConfig[ 'is_real_estate_listing' ] == 0 && !get_showtime('is_jintour_property') && !$mrConfig['item_hire_property'] ) {
					// This secret setting will not be modifyable through site config, but by adding it to /castor/configuration.php admins will be able to use old guest types and not be forced to use new occupancies
					if (isset($jrConfig[ 'secret_setting_use_old_guest_types' ]) && $jrConfig[ 'secret_setting_use_old_guest_types' ] === "1") {
						$castor_menu->add_item(80, jr_gettext('_CASTOR_CONFIG_VARIANCES_CUSTOMERTYPES', '_CASTOR_CONFIG_VARIANCES_CUSTOMERTYPES', false), 'listcustomertypes', 'fa-users');
					} else {
						// Other tariff config modes are being disabled, consolidating to just Micromanage.
						$castor_menu->add_item(80, jr_gettext('CASTOR_OCCUPANCY_LEVELS_TITLE', 'CASTOR_OCCUPANCY_LEVELS_TITLE', false), 'list_occupancy_levels', 'fa-users');
						$castor_menu->add_item(80, jr_gettext('CASTOR_POLICIES_CHILDREN', 'CASTOR_POLICIES_CHILDREN', false), 'child_policies', $font_awesome_childpolicies);
					}

					$castor_menu->add_item(80, jr_gettext('_CASTOR_EMAIL_TEMPLATES_TITLE', '_CASTOR_EMAIL_TEMPLATES_TITLE', false), 'list_emails', $font_awesome_envelope);
				}
				if ($mrConfig[ 'is_real_estate_listing' ] != '1' &&
					!get_showtime('is_jintour_property') &&
					$mrConfig[ 'singleRoomProperty' ] != '1' &&
					$jrConfig[ 'frontend_room_type_editing_allowed' ] == "1"
				) {
					$castor_menu->add_item(80, jr_gettext('_CASTOR_PROPERTY_ROOM_TYPES_EDIT', '_CASTOR_PROPERTY_ROOM_TYPES_EDIT', false), 'list_room_types', 'fa-pencil-square-o');
				}
			}

			//help section menus
			if ($thisJRUser->accesslevel >= 50) { //FAQ works for guests too, but since it doesn`t have any content by default, we`ll just hide the menu for guests
				$castor_menu->add_item(90, jr_gettext('_CASTOR_FAQ', '_CASTOR_FAQ', false), 'faq', 'fa-question');
				$castor_menu->add_item(90, jr_gettext('VIDEO_TUTORIALS', 'VIDEO_TUTORIALS', false), 'videos', 'fa-youtube-play');
			}
		}


		public function getRetVals()
		{
			return null;
		}
	}

