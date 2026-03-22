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

class j16000castor_plugin_update_check
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

		if (isset($componentArgs[ 'output_now' ])) {
			$output_now = $componentArgs[ 'output_now' ];
		} else {
			$output_now = true;
		}

		$this->retVals = false;

		$items_requiring_attention = get_number_of_items_requiring_attention_for_menu_option('showplugins');
		if (!empty($items_requiring_attention)) {
			$output = array();
			$pageoutput = array();

			$output['NUMBER'] =  $items_requiring_attention['red'];

			$output[ 'PLUGIN_MANAGER_URL' ] = CASTOR_SITEPAGE_URL_ADMIN.'&task=showplugins';
			$output[ 'SITE_CONFIGURATION_URL' ] = CASTOR_SITEPAGE_URL_ADMIN.'&task=site_settings';
			$output[ 'CASTOR_DOT_NET_PRICES_URL' ] = 'https://www.castor.net/pricing';

			$output[ 'PLUGIN_UPDATE_MESSAGE_TITLE' ] = jr_gettext('PLUGIN_UPDATE_MESSAGE_TITLE', 'PLUGIN_UPDATE_MESSAGE_MESSAGE1', false);
			$output[ 'PLUGIN_UPDATE_MESSAGE_NUMBER' ] = jr_gettext('PLUGIN_UPDATE_MESSAGE_NUMBER', 'PLUGIN_UPDATE_MESSAGE_NUMBER', false);
			$output[ 'PLUGIN_UPDATE_MESSAGE_MESSAGE1' ] = jr_gettext('PLUGIN_UPDATE_MESSAGE_MESSAGE1', 'PLUGIN_UPDATE_MESSAGE_MESSAGE1', false);
			$output[ 'PLUGIN_UPDATE_MESSAGE_MESSAGE2' ] = jr_gettext('PLUGIN_UPDATE_MESSAGE_MESSAGE2', 'PLUGIN_UPDATE_MESSAGE_MESSAGE2', false);
			$output[ 'PLUGIN_UPDATE_MESSAGE1_LINK' ] = jr_gettext('PLUGIN_UPDATE_MESSAGE1_LINK', 'PLUGIN_UPDATE_MESSAGE1_LINK', false);
			$output[ 'PLUGIN_UPDATE_MESSAGE2_LINK1' ] = jr_gettext('PLUGIN_UPDATE_MESSAGE2_LINK1', 'PLUGIN_UPDATE_MESSAGE2_LINK1', false);
			$output[ 'PLUGIN_UPDATE_MESSAGE2_LINK2' ] = jr_gettext('PLUGIN_UPDATE_MESSAGE2_LINK2', 'PLUGIN_UPDATE_MESSAGE2_LINK2', false);

			$pageoutput[ ] = $output;
			$tmpl = new patTemplate();
			$tmpl->setRoot(CASTOR_TEMPLATEPATH_ADMINISTRATOR);
			$tmpl->addRows('pageoutput', $pageoutput);
			$tmpl->readTemplatesFromInput('castor_plugin_update_check.html');

			if ($output_now) {
				$tmpl->displayParsedTemplate();
			} else {
				$this->retVals = $tmpl->getParsedTemplate();
			}


		} else {
			$this->retVals = '';
		}
	}


	public function getRetVals()
	{
		return $this->retVals;
	}
}

