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

class j16000edit_gateway
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

		$this->plugin = castorGetParam($_REQUEST, 'plugin', '');

		//$settings = get_plugin_settings($this->plugin); // Can't use get_plugin_settings here as you'll disappear down the recursion rabbithole.
		$current_settings = array();
		$query = "SELECT setting,value FROM #__castor_pluginsettings WHERE prid = 0 AND plugin = '".$this->plugin."' ";
		$settingsList = doSelectSql($query);
		foreach ($settingsList as $set) {
			$current_settings[ $set->setting ] = $set->value;
		}

		$output['GATEWAY'] = $this->plugin;

		$settings = $MiniComponents->specificEvent('10510', $this->plugin);
		$active['active'] = array(
			'default' => '0',
			'setting_title' => jr_gettext('_CASTOR_STATUS_ACTIVE', '_CASTOR_STATUS_ACTIVE'),
			'setting_description' => '',
			'format' => 'boolean',
			);

		$this->all_settings = array_merge($active, $settings['settings']);

		$results = array();
		foreach ($this->all_settings as $key => $setting) {
			if (isset($current_settings[$key])) {
				$setting['default'] = $current_settings[$key];
			}

			switch ($setting['format']) {
				case 'boolean':
						$results[] = $this->get_snippet_bool($key, $setting);
					break;
				case 'currencycode':
						$results[] = $this->get_snippet_currencycode($key, $setting);
					break;
				case 'input':
						$results[] = $this->get_snippet_input($key, $setting);
					break;
				case 'area':
						$results[] = $this->get_snippet_area($key, $setting);
					break;
				case 'html':
						$results[] = $this->get_snippet_html($key, $setting);
					break;
				case 'select':
						$results[] = $this->get_snippet_select($key, $setting);
					break;
				default:
			}
		}
		foreach ($results as $r) {
			$snippets[]['SNIPPET'] = $r;
		}

		$output['NOTES'] = $settings['notes'];

		$jrtbar = castor_singleton_abstract::getInstance('castor_toolbar');
		$jrtb = $jrtbar->startTable();

		$jrtb .= $jrtbar->toolbarItem('cancel', CASTOR_SITEPAGE_URL_ADMIN.'&task=list_gateways', '');
		$jrtb .= $jrtbar->toolbarItem('save', '', '', true, 'save_gateway');
		$jrtb .= $jrtbar->endTable();
		$output[ 'CASTORTOOLBAR' ] = $jrtb;

		$pageoutput[ ] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_ADMINISTRATOR);
		$tmpl->readTemplatesFromInput('edit_gateway.html');
		$tmpl->addRows('pageoutput', $pageoutput);
		$tmpl->addRows('snippets', $snippets);
		$tmpl->displayParsedTemplate();
	}

		// Allows gateway developers to supply their own html if
	public function get_snippet_html($key, $setting)
	{
		$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
		$jrConfig = $siteConfig->get();

		$index = $key;

		$output = array();
		$pageoutput = array();

		$output['INPUT_NAME'] = $index;
		$output['HTML'] = $setting['html'];
		$output['TITLE'] = $setting['setting_title'];
		$output['DESCRIPTION'] = $setting['setting_description'];

		$pageoutput[ ] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_ADMINISTRATOR);
		$tmpl->readTemplatesFromInput('edit_gateway_snippet_html.html');
		$tmpl->addRows('pageoutput', $pageoutput);

		return $tmpl->getParsedTemplate();
	}

	public function get_snippet_area($key, $setting)
	{
		$index = $key;

		$output = array();
		$pageoutput = array();

		$output['INPUT_NAME'] = $index;
		$output['VALUE'] = $setting['default'];
		$output['TITLE'] = $setting['setting_title'];
		$output['DESCRIPTION'] = $setting['setting_description'];

		$pageoutput[ ] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_ADMINISTRATOR);
		$tmpl->readTemplatesFromInput('edit_gateway_snippet_area.html');
		$tmpl->addRows('pageoutput', $pageoutput);

		return $tmpl->getParsedTemplate();
	}

	public function get_snippet_input($key, $setting)
	{
		$index = $key;

		$output = array();
		$pageoutput = array();

		$output['INPUT_NAME'] = $index;
		$output['VALUE'] = $setting['default'];
		$output['TITLE'] = $setting['setting_title'];
		$output['DESCRIPTION'] = $setting['setting_description'];

		$pageoutput[ ] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_ADMINISTRATOR);
		$tmpl->readTemplatesFromInput('edit_gateway_snippet_input.html');
		$tmpl->addRows('pageoutput', $pageoutput);

		return $tmpl->getParsedTemplate();
	}

	public function get_snippet_currencycode($key, $setting)
	{
		$index = $key;

		$currency_codes = castor_singleton_abstract::getInstance('currency_codes');
		$currency_codes_dropdown = $currency_codes->makeCodesDropdown('', false, $index);

		$output = array();
		$pageoutput = array();

		$output['INPUT_NAME'] = $index;
		$output['SWITCH'] = $currency_codes_dropdown;
		$output['TITLE'] = $setting['setting_title'];
		$output['DESCRIPTION'] = $setting['setting_description'];

		$pageoutput[ ] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_ADMINISTRATOR);
		$tmpl->readTemplatesFromInput('edit_gateway_snippet_currencycode.html');
		$tmpl->addRows('pageoutput', $pageoutput);

		return $tmpl->getParsedTemplate();
	}

	public function get_snippet_bool($key, $setting)
	{
		$index = $key;

		$yesno = array();
		$yesno[] = castorHTML::makeOption('0', jr_gettext('_CASTOR_COM_MR_NO', '_CASTOR_COM_MR_NO', false));
		$yesno[] = castorHTML::makeOption('1', jr_gettext('_CASTOR_COM_MR_YES', '_CASTOR_COM_MR_YES', false));

		$input = castorHTML::selectList($yesno, $index, '', 'value', 'text', $setting['default']);

		$output = array();
		$pageoutput = array();

		$output['INPUT_NAME'] = $index;
		$output['SWITCH'] = $input;
		$output['TITLE'] = $setting['setting_title'];
		$output['DESCRIPTION'] = $setting['setting_description'];

		$pageoutput[ ] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_ADMINISTRATOR);
		$tmpl->readTemplatesFromInput('edit_gateway_snippet_bool.html');
		$tmpl->addRows('pageoutput', $pageoutput);

		return $tmpl->getParsedTemplate();
	}

	public function get_snippet_select($key, $setting)
	{
		if (isset($setting['options']) && is_array($setting['options'])) {
			$index = $key;

			$options = array();
			foreach ($setting['options'] as $selection => $text) {
				$options[] = castorHTML::makeOption($selection, $text);
			}

			$input = castorHTML::selectList($options, $index, '', 'value', 'text', $setting['default']);

			$output = array();
			$pageoutput = array();

			$output['INPUT_NAME'] = $index;
			$output['SWITCH'] = $input;
			$output['TITLE'] = $setting['setting_title'];
			$output['DESCRIPTION'] = $setting['setting_description'];

			$pageoutput[ ] = $output;
			$tmpl = new patTemplate();
			$tmpl->setRoot(CASTOR_TEMPLATEPATH_ADMINISTRATOR);
			$tmpl->readTemplatesFromInput('edit_gateway_snippet_select.html');
			$tmpl->addRows('pageoutput', $pageoutput);

			return $tmpl->getParsedTemplate();
		}
	}


	public function getRetVals()
	{
		return null;
	}
}

