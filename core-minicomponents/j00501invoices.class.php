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
	 * Property Configuration page tabs. Offers invoice number related settings.
	 *
	 */


class j00501invoices
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

		$configurationPanel->startPanel(jr_gettext('_CASTOR_INVOICE_NUMBERS', '_CASTOR_INVOICE_NUMBERS', false));

		if ($mrConfig[ 'is_real_estate_listing' ] == 0) {
			$configurationPanel->setleft(jr_gettext('_CASTOR_USE_CUSTOM_INVOICE_NUMBERS_TITLE', '_CASTOR_USE_CUSTOM_INVOICE_NUMBERS_TITLE', false));
			$configurationPanel->setmiddle($lists[ 'use_custom_invoice_numbers' ]);
			$configurationPanel->setright(jr_gettext('_CASTOR_USE_CUSTOM_INVOICE_NUMBERS_DESC', '_CASTOR_USE_CUSTOM_INVOICE_NUMBERS_DESC', false));
			$configurationPanel->insertSetting();

			$configurationPanel->setleft(jr_gettext('_CASTOR_CUSTOM_INVOICE_NUMBERS_START_NUMBER_TITLE', '_CASTOR_CUSTOM_INVOICE_NUMBERS_START_NUMBER_TITLE', false));
			$configurationPanel->setmiddle('<input type="url" class="inputbox form-control"  size="50" name="cfg_last_invoice_number" value="'.$mrConfig[ 'last_invoice_number' ].'" />');
			$configurationPanel->setright(jr_gettext('_CASTOR_CUSTOM_INVOICE_NUMBERS_START_NUMBER_DESC', '_CASTOR_CUSTOM_INVOICE_NUMBERS_START_NUMBER_DESC', false));
			$configurationPanel->insertSetting();
			
			$configurationPanel->setleft(jr_gettext('_CASTOR_CUSTOM_INVOICE_NUMBERS_PATTERN_TITLE', '_CASTOR_CUSTOM_INVOICE_NUMBERS_PATTERN_TITLE', false));
			$configurationPanel->setmiddle('<input type="url" class="inputbox form-control"  size="50" name="cfg_custom_invoice_pattern" value="'.$mrConfig[ 'custom_invoice_pattern' ].'" />');
			$configurationPanel->setright(jr_gettext('_CASTOR_CUSTOM_INVOICE_NUMBERS_PATTERN_DESC', '_CASTOR_CUSTOM_INVOICE_NUMBERS_PATTERN_DESC', false));
			$configurationPanel->insertSetting();
		}

		$configurationPanel->endPanel();
	}


	public function getRetVals()
	{
		return null;
	}
}

