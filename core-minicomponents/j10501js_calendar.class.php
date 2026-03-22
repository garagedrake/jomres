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

class j10501js_calendar
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
		$jsInputFormatDropdownList = $componentArgs[ 'jsInputFormatDropdownList' ];
		$calendarStartDaysDropdownList = $componentArgs[ 'calendarStartDaysDropdownList' ];

		$configurationPanel->startPanel(jr_gettext('_CASTOR_COM_A_AVLCAL', '_CASTOR_COM_A_AVLCAL', false));

		$configurationPanel->setleft(jr_gettext('_CASTOR_COM_CALENDARINPUT', '_CASTOR_COM_CALENDARINPUT', false));
		$configurationPanel->setmiddle($jsInputFormatDropdownList);
		$configurationPanel->setright(jr_gettext('_CASTOR_COM_CALENDARINPUT_DESC', '_CASTOR_COM_CALENDARINPUT_DESC', false));
		$configurationPanel->insertSetting();

		$configurationPanel->setleft(jr_gettext('_CASTOR_COM_CALENDAR_STARTDAY', '_CASTOR_COM_CALENDAR_STARTDAY', false));
		$configurationPanel->setmiddle($calendarStartDaysDropdownList);
		$configurationPanel->setright();
		$configurationPanel->insertSetting();
		
		//plugins can add options to this tab
		$MiniComponents->triggerEvent('10526', $componentArgs);

		$configurationPanel->endPanel();
	}


	public function getRetVals()
	{
		return null;
	}
}

