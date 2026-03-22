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
	 * Property Configuration page tabs. Offers property details page settings.
	 *
	 */


class j00501propertydetailsoptions
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

		if ($componentArgs['is_channel_property']) {
			return;
		}

		$configurationPanel = $componentArgs[ 'configurationPanel' ];

		$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
		$jrConfig = $siteConfig->get();
		$mrConfig = getPropertySpecificSettings();
		$lists = $componentArgs[ 'lists' ];


		$configurationPanel->startPanel(jr_gettext('PROPERTY_DETAILS_PAGE_OPTIONS', 'PROPERTY_DETAILS_PAGE_OPTIONS', false));

		$configurationPanel->setleft(jr_gettext('HIDDEN_ADDRESS_SETTING', 'HIDDEN_ADDRESS_SETTING', false));
		$configurationPanel->setmiddle($lists['hide_local_address']);
		$configurationPanel->setright(jr_gettext('HIDDEN_ADDRESS_SETTING_DESC', 'HIDDEN_ADDRESS_SETTING_DESC', false));
		$configurationPanel->insertSetting();

		if (!isset($mrConfig['galleryLink'])) {
			$mrConfig['galleryLink'] = '';
		}

		$configurationPanel->setleft(jr_gettext('_CASTOR_COM_A_GALLERYLINK', '_CASTOR_COM_A_GALLERYLINK', false));
		$configurationPanel->setmiddle('<input type="url" class="inputbox form-control"  size="50" name="cfg_galleryLink" value="' . $mrConfig['galleryLink'] . '" />');
		$configurationPanel->setright(jr_gettext('_CASTOR_COM_A_GALLERYLINK_DESC', '_CASTOR_COM_A_GALLERYLINK_DESC', false));
		$configurationPanel->insertSetting();

		$configurationPanel->endPanel();
	}


	public function getRetVals()
	{
		return null;
	}
}

