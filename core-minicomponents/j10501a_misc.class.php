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

class j10501a_misc
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

		$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
		$jrConfig = $siteConfig->get();

		$configurationPanel = $componentArgs[ 'configurationPanel' ];
		$lists = $componentArgs[ 'lists' ];
		$bootstrap_ver_dropdown = $componentArgs[ 'bootstrap_ver_dropdown' ];
		$jqueryUIthemesDropdownList = $componentArgs[ 'jqueryUIthemesDropdownList' ];
		$language_context_dropdown = $componentArgs[ 'language_context_dropdown' ];
		$navbar_location_dropdown = $componentArgs[ 'navbar_location_dropdown' ];
		$admin_options_level_dropdown = $componentArgs[ 'admin_options_level_dropdown' ];

		if (!isset($jrConfig[ 'admin_options_level' ])) {
			$jrConfig[ 'admin_options_level' ] = 0;
		}

		$support_key_is_trial_license = '';
		$renewal_link = '';
		
		$castor_check_support_key = castor_singleton_abstract::getInstance('castor_check_support_key');
		$castor_check_support_key->check_license_key(true);

		if (trim($jrConfig['licensekey']) != '') {
			if ($castor_check_support_key->key_valid) {
				if ($castor_check_support_key->allows_plugins == '1') {
					$support_key_message = '<p class="alert alert-success">'.jr_gettext('_CASTOR_SUPPORTKEY_DESC_VALID', '_CASTOR_SUPPORTKEY_DESC_VALID', false, false).'</p>';
				} else {
					$support_key_message = '<p class="alert alert-success">'.jr_gettext('_CASTOR_SUPPORTKEY_DESC_VALID_NO_PLUGINS', '_CASTOR_SUPPORTKEY_DESC_VALID_NO_PLUGINS', false, false).'</p>';
				}
			} else {
				$support_key_message = '<p class="alert alert-danger">'.jr_gettext('_CASTOR_SUPPORTKEY_DESC_INVALID', '_CASTOR_SUPPORTKEY_DESC_INVALID', false, false).'</p>';
				$renewal_link = '<a href="https://www.castor.net/prices">Get Connected!</a>';
			}

			$support_key_status = '<span class="badge bg-primary">Status</span> '.$castor_check_support_key->key_status.'';
			$support_key_owner = '<span class="badge bg-primary">Owner</span> '.$castor_check_support_key->owner.'<br/>';
			$support_key_expires = '<span class="badge bg-primary">Expires</span> '.$castor_check_support_key->expires.'';
			$support_key_license_name = '<span class="badge bg-primary">License name</span> '.$castor_check_support_key->license_name.'';

			if ($castor_check_support_key->is_trial_license == '1') {
				$support_key_is_trial_license = '<span class="badge badge-warning">Trial license</span> ';
			}
		} else {
			$support_key_status = '';
			$support_key_owner = '';
			$support_key_message = '';
			$support_key_expires = '';
			$support_key_license_name = '';
			$support_key_property_limit = '';
		}
		$configurationPanel->startPanel(jr_gettext('_CASTOR_A_TABS_MISC', '_CASTOR_A_TABS_MISC', false));

		$configurationPanel->setleft(jr_gettext('_CASTOR_CONFIG_LEVEL_TITLE', '_CASTOR_CONFIG_LEVEL_TITLE', false));
		$configurationPanel->setmiddle($admin_options_level_dropdown);
		$configurationPanel->setright(jr_gettext('_CASTOR_CONFIG_LEVEL_DESC', '_CASTOR_CONFIG_LEVEL_DESC', false));
		$configurationPanel->insertSetting();

		$configurationPanel->setleft(jr_gettext('_CASTOR_SUPPORTKEY', '_CASTOR_SUPPORTKEY', false));
		$configurationPanel->setmiddle('<input type="password" class="input-xlarge form-control" name="cfg_licensekey" value="'.$jrConfig[ 'licensekey' ].'" /><br/>'.' '.$support_key_status.' '.$support_key_owner.' '.$support_key_expires.' '.$renewal_link.' '.$support_key_is_trial_license.' '.$support_key_license_name);
		$configurationPanel->setright(jr_gettext('_CASTOR_SUPPORTKEY_DESC', '_CASTOR_SUPPORTKEY_DESC', false).' '.$support_key_message);
		$configurationPanel->insertSetting();

		$configurationPanel->setleft(jr_gettext('_CASTOR_BOOTSTRAP_VERSION', '_CASTOR_BOOTSTRAP_VERSION', false));
		$configurationPanel->setmiddle($bootstrap_ver_dropdown);
		$configurationPanel->setright(jr_gettext('_CASTOR_BOOTSTRAP_VERSION_DESC', '_CASTOR_BOOTSTRAP_VERSION_DESC', false));
		$configurationPanel->insertSetting();

		if ($jrConfig[ 'admin_options_level' ] > 1) {
			$configurationPanel->setleft(jr_gettext('_CASTOR_REGION_TRANSLATION_SWITCH_TITLE', '_CASTOR_REGION_TRANSLATION_SWITCH_TITLE', false));
			$configurationPanel->setmiddle($lists[ 'region_names_are_translatable' ]);
			$configurationPanel->setright(jr_gettext('_CASTOR_REGION_TRANSLATION_SWITCH_DESC', '_CASTOR_REGION_TRANSLATION_SWITCH_DESC', false));
			$configurationPanel->insertSetting();
		}

		if ( $jrConfig[ 'admin_options_level' ] > 1 ) {
			$configurationPanel->setleft(jr_gettext('_CASTOR_COM_LANGUAGE_CONTEXT', '_CASTOR_COM_LANGUAGE_CONTEXT', false));
			$configurationPanel->setmiddle($language_context_dropdown);
			$configurationPanel->setright(jr_gettext('_CASTOR_COM_LANGUAGE_CONTEXT_DESC', '_CASTOR_COM_LANGUAGE_CONTEXT_DESC', false));
			$configurationPanel->insertSetting();
		}

		if ($jrConfig[ 'admin_options_level' ] > 1) {
			$configurationPanel->setleft(jr_gettext('_CASTOR_COM_LABEL_PRIORITY_TITLE', '_CASTOR_COM_LABEL_PRIORITY_TITLE', false));
			$configurationPanel->setmiddle($lists[ 'prioritise_sitewide_label_definitions' ]);
			$configurationPanel->setright(jr_gettext('_CASTOR_COM_LABEL_PRIORITY_DESC', '_CASTOR_COM_LABEL_PRIORITY_DESC', false));
			$configurationPanel->insertSetting();
		}

		if ($jrConfig[ 'admin_options_level' ] > 1) {
			$configurationPanel->setleft(jr_gettext('_CASTOR_COM_JRCONFIG_GLOBALEDITING', '_CASTOR_COM_JRCONFIG_GLOBALEDITING', false));
			$configurationPanel->setmiddle($lists[ 'editingModeAffectsAllProperties' ]);
			$configurationPanel->setright(jr_gettext('_CASTOR_COM_JRCONFIG_GLOBALEDITING_DESC', '_CASTOR_COM_JRCONFIG_GLOBALEDITING_DESC', false));
			$configurationPanel->insertSetting();
		}

		if ($jrConfig[ 'admin_options_level' ] > 0) {
			$configurationPanel->setleft(jr_gettext('_CASTOR_CONFIG_ROOM_TYPES_CREATING_TITLE', '_CASTOR_CONFIG_ROOM_TYPES_CREATING_TITLE', false));
			$configurationPanel->setmiddle($lists[ 'frontend_room_type_editing_allowed' ]);
			$configurationPanel->setright(jr_gettext('_CASTOR_CONFIG_ROOM_TYPES_CREATING_DESC', '_CASTOR_CONFIG_ROOM_TYPES_CREATING_DESC', false));
			$configurationPanel->insertSetting();
		}

		if ($jrConfig[ 'admin_options_level' ] > 0) {
			$configurationPanel->setleft(jr_gettext('_CASTOR_PROPERTY_ROOM_TYPES_CONFIG_TITLE', '_CASTOR_PROPERTY_ROOM_TYPES_CONFIG_TITLE', false));
			$configurationPanel->setmiddle($lists[ 'frontend_room_type_editing_show_property_room_types_in_search_options' ]);
			$configurationPanel->setright(jr_gettext('_CASTOR_PROPERTY_ROOM_TYPES_CONFIG_DESC', '_CASTOR_PROPERTY_ROOM_TYPES_CONFIG_DESC', false));
			$configurationPanel->insertSetting();
		}

		if ($jrConfig[ 'admin_options_level' ] > 1) {
			$configurationPanel->setleft(jr_gettext('_CASTOR_CONFIG_JQUERY_UI', '_CASTOR_CONFIG_JQUERY_UI', false));
			$configurationPanel->setmiddle($lists[ 'load_jquery_ui' ]);
			$configurationPanel->setright(jr_gettext('_CASTOR_CONFIG_JQUERY_UI_DESC', '_CASTOR_CONFIG_JQUERY_UI_DESC', false));
			$configurationPanel->insertSetting();
		}

		if ($jrConfig[ 'admin_options_level' ] > 1) {
			$configurationPanel->setleft(jr_gettext('_CASTOR_CONFIG_JQUERY_UI_CSS', '_CASTOR_CONFIG_JQUERY_UI_CSS', false));
			$configurationPanel->setmiddle($lists[ 'load_jquery_ui_css' ]);
			$configurationPanel->setright(jr_gettext('_CASTOR_CONFIG_JQUERY_UI_CSS_DESC', '_CASTOR_CONFIG_JQUERY_UI_CSS_DESC', false));
			$configurationPanel->insertSetting();
		}

		if ($jrConfig[ 'admin_options_level' ] > 1) {
			$configurationPanel->setleft(jr_gettext('_CASTOR_JQUERYTHEME', '_CASTOR_JQUERYTHEME', false));
			$configurationPanel->setmiddle($jqueryUIthemesDropdownList);
			$configurationPanel->setright(jr_gettext('_CASTOR_JQUERYTHEME_DESC', '_CASTOR_JQUERYTHEME_DESC', false));
			$configurationPanel->insertSetting();
		}

		if ($jrConfig[ 'admin_options_level' ] > 1) {
			$configurationPanel->setleft(jr_gettext('_CASTOR_COM_FONTAWESOME', '_CASTOR_COM_FONTAWESOME', false));
			$configurationPanel->setmiddle($lists[ 'load_font_awesome' ]);
			$configurationPanel->setright(jr_gettext('_CASTOR_COM_FONTAWESOME_DESC', '_CASTOR_COM_FONTAWESOME_DESC', false));
			$configurationPanel->insertSetting();
		}

		if ($jrConfig[ 'admin_options_level' ] > 1) {
			$configurationPanel->setleft(jr_gettext('_CASTOR_BOOTSTRAP_LOCATION', '_CASTOR_BOOTSTRAP_LOCATION', false));
			$configurationPanel->setmiddle($navbar_location_dropdown);
			$configurationPanel->setright();
			$configurationPanel->insertSetting();
		}

		if ($jrConfig[ 'admin_options_level' ] > 1) {
			$configurationPanel->setleft(jr_gettext('_CASTOR_BOOTSTRAP_LOCATION_INVERSE', '_CASTOR_BOOTSTRAP_LOCATION_INVERSE', false));
			$configurationPanel->setmiddle($lists[ 'navbar_inverse' ]);
			$configurationPanel->setright();
			$configurationPanel->insertSetting();
		}

		if ($jrConfig[ 'admin_options_level' ] > 0) {
			$configurationPanel->setleft(jr_gettext('_CASTOR_SHOW_POWEREDBY', '_CASTOR_SHOW_POWEREDBY', false));
			$configurationPanel->setmiddle($lists[ 'show_powered_by' ]);
			$configurationPanel->setright();
			$configurationPanel->insertSetting();
		}

		if ($jrConfig[ 'admin_options_level' ] > 1) {
			$configurationPanel->setleft(jr_gettext('_CASTOR_SESSION_HANDLER', '_CASTOR_SESSION_HANDLER', false));
			$configurationPanel->setmiddle($lists[ 'session_handler' ]);
			$configurationPanel->setright(jr_gettext('_CASTOR_SESSION_HANDLER_DESC', '_CASTOR_SESSION_HANDLER_DESC', false));
			$configurationPanel->insertSetting();
		}


		$configurationPanel->setleft(jr_gettext('_CASTOR_UNINSTALL_TABLES', '_CASTOR_UNINSTALL_TABLES', false));
		$configurationPanel->setmiddle($lists[ 'delete_all_data_on_uninstall' ]);
		$configurationPanel->setright(jr_gettext('_CASTOR_UNINSTALL_TABLES_DESC', '_CASTOR_UNINSTALL_TABLES_DESC', false));
		$configurationPanel->insertSetting();

		if ($jrConfig[ 'admin_options_level' ] > 0) {
			$configurationPanel->insertHeading('Frontend Control Panel');

			$configurationPanel->setleft(jr_gettext('_CASTOR_CPANEL_GRID', '_CASTOR_CPANEL_GRID', false));
			$configurationPanel->setmiddle($lists[ 'front_cpanel_home_grid' ]);
			$configurationPanel->setright(jr_gettext('_CASTOR_CPANEL_GRID_DESC', '_CASTOR_CPANEL_GRID_DESC', false));
			$configurationPanel->insertSetting();
		}

		if ($jrConfig[ 'admin_options_level' ] > 0) {
			$configurationPanel->insertHeading(jr_gettext('MACHINE_TRANSLATION', 'MACHINE_TRANSLATION', false));

			$castor_language = castor_singleton_abstract::getInstance('castor_language');
			$language_dropdown = $castor_language-> get_languageselection_dropdown(true, $jrConfig['machine_translations_source_language'], 'cfg_machine_translations_source_language');

			$configurationPanel->setleft(jr_gettext('MACHINE_TRANSLATION_DEFAULT_LANG', 'MACHINE_TRANSLATION_DEFAULT_LANG', false));
			$configurationPanel->setmiddle($language_dropdown);
			$configurationPanel->setright(jr_gettext('MACHINE_TRANSLATION_DEFAULT_LANG_DESC', 'MACHINE_TRANSLATION_DEFAULT_LANG_DESC', false));
			$configurationPanel->insertSetting();
		}

		if ($jrConfig[ 'admin_options_level' ] > 0) {
			$configurationPanel->insertHeading(jr_gettext('_CASTOR_RANDOM_EMAILS_IN_QUICKBOOKINGS_HEADING', '_CASTOR_RANDOM_EMAILS_IN_QUICKBOOKINGS_HEADING', false));

			$configurationPanel->setleft(jr_gettext('_CASTOR_RANDOM_EMAILS_IN_QUICKBOOKINGS_TITLE', '_CASTOR_RANDOM_EMAILS_IN_QUICKBOOKINGS_TITLE', false));
			$configurationPanel->setmiddle($lists[ 'generate_random_emails' ]);
			$configurationPanel->setright(jr_gettext('_CASTOR_RANDOM_EMAILS_IN_QUICKBOOKINGS_DESCRIPTION', '_CASTOR_RANDOM_EMAILS_IN_QUICKBOOKINGS_DESCRIPTION', false));
			$configurationPanel->insertSetting();

			$configurationPanel->setleft(jr_gettext('_CASTOR_RANDOM_EMAILS_IN_QUICKBOOKINGS_DOMAIN_TITLE', '_CASTOR_RANDOM_EMAILS_IN_QUICKBOOKINGS_DOMAIN_TITLE', false));
			$configurationPanel->setmiddle('<input type="text" class="input-large" name="cfg_random_email_domain" value="'.$jrConfig[ 'random_email_domain' ].'" />');
			$configurationPanel->setright(jr_gettext('_CASTOR_RANDOM_EMAILS_IN_QUICKBOOKINGS_DOMIAN_DESCRIPTION', '_CASTOR_RANDOM_EMAILS_IN_QUICKBOOKINGS_DOMIAN_DESCRIPTION', false));
			$configurationPanel->insertSetting();
		}

		
		//plugins can add options to this tab
		$MiniComponents->triggerEvent('10521', $componentArgs);
		
		$configurationPanel->endPanel();
	}


	public function getRetVals()
	{
		return null;
	}
}

