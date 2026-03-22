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

class j10501properties
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

		if (!isset($jrConfig[ 'admin_options_level' ])) {
			$jrConfig[ 'admin_options_level' ] = 0;
		}

		$configurationPanel = $componentArgs[ 'configurationPanel' ];
		$lists = $componentArgs[ 'lists' ];

		$configurationPanel->startPanel(ucwords(jr_gettext('_CASTOR_CUSTOMCODE_MENUCATEGORIES_PORTAL', '_CASTOR_CUSTOMCODE_MENUCATEGORIES_PORTAL', false)));

		$configurationPanel->setleft(jr_gettext('_CASTOR_SINGLEPROPERTYINSTALLATION_TITLE', '_CASTOR_SINGLEPROPERTYINSTALLATION_TITLE', false));
		$configurationPanel->setmiddle($lists[ 'is_single_property_installation' ]);
		$configurationPanel->setright(jr_gettext('_CASTOR_SINGLEPROPERTYINSTALLATION_DESC', '_CASTOR_SINGLEPROPERTYINSTALLATION_DESC', false));
		$configurationPanel->insertSetting();

		// Transitioning to Micromanage only tariff configuration
		/*if ($jrConfig[ 'admin_options_level' ] > 1) {
			$configurationPanel->setleft(jr_gettext('CASTOR_COMPATABILITY_MODE', 'CASTOR_COMPATABILITY_MODE', false));
			$configurationPanel->setmiddle($lists[ 'compatability_property_configuration' ]);
			$configurationPanel->setright(jr_gettext('CASTOR_COMPATABILITY_MODE_DESC', 'CASTOR_COMPATABILITY_MODE_DESC', false));
			$configurationPanel->insertSetting();
		}*/

/*		if ( $jrConfig[ 'admin_options_level' ] > 0 ) {
			$configurationPanel->setleft(jr_gettext('_CASTOR_CONFIG_SYNDICATION_TITLE', '_CASTOR_CONFIG_SYNDICATION_TITLE', false));
			$configurationPanel->setmiddle($lists[ 'useSyndication' ]);
			$configurationPanel->setright(jr_gettext('_CASTOR_CONFIG_SYNDICATION_DESC', '_CASTOR_CONFIG_SYNDICATION_DESC', false));
			$configurationPanel->insertSetting();
		}*/

		if ($jrConfig[ 'admin_options_level' ] > 0) {
			$configurationPanel->setleft(jr_gettext('_CASTOR_COM_SELFREGISTRATION', '_CASTOR_COM_SELFREGISTRATION', false));
			$configurationPanel->setmiddle($lists[ 'selfRegistrationAllowed' ]);
			$configurationPanel->setright(jr_gettext('_CASTOR_COM_SELFREGISTRATION_DESC', '_CASTOR_COM_SELFREGISTRATION_DESC', false));
			$configurationPanel->insertSetting();
		}

		if ($jrConfig[ 'admin_options_level' ] > 0) {
			$configurationPanel->setleft(jr_gettext('_CASTOR_APPROVALS_CONFIG_TITLE', '_CASTOR_APPROVALS_CONFIG_TITLE', false));
			$configurationPanel->setmiddle($lists[ 'automatically_approve_new_properties' ]);
			$configurationPanel->setright(jr_gettext('_CASTOR_APPROVALS_CONFIG_DESC', '_CASTOR_APPROVALS_CONFIG_DESC', false));
			$configurationPanel->insertSetting();
		}

		if ($jrConfig[ 'admin_options_level' ] > 0) {
			$configurationPanel->setleft(jr_gettext('_CASTOR_ADMIN_REGISTRATION_LIMITPROPERTY_YESNO', '_CASTOR_ADMIN_REGISTRATION_LIMITPROPERTY_YESNO', false));
			$configurationPanel->setmiddle($lists[ 'limit_property_country' ]);
			$configurationPanel->setright(jr_gettext('_CASTOR_ADMIN_REGISTRATION_LIMITPROPERTY_YESNO_DESC', '_CASTOR_ADMIN_REGISTRATION_LIMITPROPERTY_YESNO_DESC', false));
			$configurationPanel->insertSetting();
		}

		if ($jrConfig[ 'admin_options_level' ] > 0) {
			$configurationPanel->setleft(jr_gettext('_CASTOR_ADMIN_REGISTRATION_LIMITPROPERTY_COUNTRY', '_CASTOR_ADMIN_REGISTRATION_LIMITPROPERTY_COUNTRY', false));
			$configurationPanel->setmiddle(limitCountriesDropdown());
			$configurationPanel->setright();
			$configurationPanel->insertSetting();
		}

		$configurationPanel->insertHeading(jr_gettext('CASTOR_SOCIAL_MEDIA_LINKS', 'CASTOR_SOCIAL_MEDIA_LINKS', false));


		$configurationPanel->setleft(jr_gettext('_CASTOR_OVERRIDE_PROPERTY_CONTACT_DETAILS', '_CASTOR_OVERRIDE_PROPERTY_CONTACT_DETAILS', false));
		$configurationPanel->insertDescription(jr_gettext('CASTOR_SOCIAL_MEDIA_LINKS_INFO', 'CASTOR_SOCIAL_MEDIA_LINKS_INFO', false));

		$social_meeja_platforms = get_sm_platforms();
		foreach ($social_meeja_platforms as $key => $val) {
			if (!isset($jrConfig[$key])) {
				if ($key == 'social_media_whatsapp') {
					$jrConfig[$key] = '359884339947';
				} else {
					$jrConfig[$key] = 'castor';
				}
			}
			$configurationPanel->setleft($val['name']);
			$configurationPanel->setmiddle($val['url'].'<input type="text" class="input-large" name="cfg_'.$key.'" value="'.$jrConfig[ $key ].'" />');
			$configurationPanel->setright($val['notes']);
			$configurationPanel->insertSetting();
		}

		if ($jrConfig[ 'admin_options_level' ] > 0) {
			$configurationPanel->insertHeading(jr_gettext('_CASTOR_CONTACT_SETTINGS', '_CASTOR_CONTACT_SETTINGS', false));

			$configurationPanel->insertDescription(jr_gettext('_CASTOR_CONTACT_SETTINGS_DESC', '_CASTOR_CONTACT_SETTINGS_DESC', false));

			$configurationPanel->setleft(jr_gettext('_CASTOR_OVERRIDE_PROPERTY_CONTACT_DETAILS', '_CASTOR_OVERRIDE_PROPERTY_CONTACT_DETAILS', false));
			$configurationPanel->setmiddle($lists[ 'override_property_contact_details' ]);
			$configurationPanel->setright(jr_gettext('_CASTOR_OVERRIDE_PROPERTY_CONTACT_DETAILS_DESC', '_CASTOR_OVERRIDE_PROPERTY_CONTACT_DETAILS_DESC', false));
			$configurationPanel->insertSetting();

			$configurationPanel->setleft(jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_EMAIL', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_EMAIL', false));
			$configurationPanel->setmiddle('<input type="text" class="input-large" name="cfg_override_property_contact_email" value="'.$jrConfig[ 'override_property_contact_email' ].'" />');
			$configurationPanel->setright();
			$configurationPanel->insertSetting();

			$configurationPanel->setleft(jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_TELEPHONE', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_TELEPHONE', false));
			$configurationPanel->setmiddle('<input type="text" class="input-large" name="cfg_override_property_contact_tel" value="'.$jrConfig[ 'override_property_contact_tel' ].'" />');
			$configurationPanel->setright();
			$configurationPanel->insertSetting();

			$configurationPanel->setleft(jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_FAX', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_FAX', false));
			$configurationPanel->setmiddle('<input type="text" class="input-large" name="cfg_override_property_contact_fax" value="'.$jrConfig[ 'override_property_contact_fax' ].'" />');
			$configurationPanel->setright();
			$configurationPanel->insertSetting();
		}
	
		//plugins can add options to this tab
		$MiniComponents->triggerEvent('10527', $componentArgs);

		$configurationPanel->endPanel();
	}


	public function getRetVals()
	{
		return null;
	}
}

