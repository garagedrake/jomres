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

class j10521webhooks_core
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
	 
	function __construct($componentArgs)
	{
		// Must be in all minicomponents. Minicomponents with templates that can contain editable text should run $this->template_touch() else just return
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = false;

			return;
		}

		$siteConfig		 = castor_singleton_abstract::getInstance('castor_config_site_singleton');
		$jrConfig		   = $siteConfig->get();

		if (!isset($jrConfig[ 'admin_options_level' ])) {
			$jrConfig[ 'admin_options_level' ] = 0;
		}

		if ($jrConfig[ 'admin_options_level' ] < 2) {
			return;
		}

		$configurationPanel 			= $componentArgs[ 'configurationPanel' ];

		if (!isset($jrConfig[ 'webhooks_core_show' ])) {
			$jrConfig[ 'webhooks_core_show' ] =1;
		}
		
		// make a standard yes/no list
		$yesno	= array ();
		$yesno[ ] = castorHTML::makeOption('0', jr_gettext('_CASTOR_COM_MR_NO', '_CASTOR_COM_MR_NO', false));
		$yesno[ ] = castorHTML::makeOption('1', jr_gettext('_CASTOR_COM_MR_YES', '_CASTOR_COM_MR_YES', false));
		
		$configurationPanel->insertHeading(jr_gettext("WEBHOOKS_CORE", 'WEBHOOKS_CORE', false));
			
		$configurationPanel->setleft(jr_gettext('_WEBHOOKS_CONFIG_SHOW', '_WEBHOOKS_CONFIG_SHOW', false));
		$configurationPanel->setmiddle(castorHTML::selectList($yesno, 'cfg_webhooks_core_show', '', 'value', 'text', $jrConfig[ 'webhooks_core_show' ]));
		$configurationPanel->setright(jr_gettext('_WEBHOOKS_CONFIG_SHOW_DESC', '_WEBHOOKS_CONFIG_SHOW_DESC', false));
		$configurationPanel->insertSetting();
	}
	

	function getRetVals()
	{
		return null;
	}
}

