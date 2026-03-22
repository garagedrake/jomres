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

class j16000edit_template_override
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

		$template_overrides = castor_singleton_abstract::getInstance('template_overrides');
		
		$template_name = castorGetParam($_REQUEST, 'template_name', '');
		$template_packages = get_showtime('template_packages');

		$output = array();
		$pageoutput = array();
		
		$valid_files = array();
		$file_info = array();
		foreach ($template_packages as $parent_package => $package_files) {
			foreach ($package_files as $file) {
				if ($file['template_name'] == $template_name) {
					$valid_files[] = castorHTML::makeOption($file['path'], $file['title']);
					$file_info[$parent_package] = $file;
				}
			}
		}

/* 		// Might need this if decide to extend the edit override page with more information
		$rows = array();
		foreach ($valid_files as $file ) {
			$r=array();
			//$r['title'] = title

			$rows[]= $r;
		} */

		$default = '';
		if (isset($template_overrides->template_overrides[$template_name])) {
			$default = $template_overrides->template_overrides[$template_name]['path'];
		}
		
		$output['DROPDOWN'] = castorHTML::selectList($valid_files, 'template_path', 'class="input-large" size="1"', 'value', 'text', $default, false);


		$output['TEMPLATE_NAME'] = $template_name;
		$output['_CASTOR_TEMPLATE_PACKAGES_EDIT_INFO'] =jr_gettext("_CASTOR_TEMPLATE_PACKAGES_EDIT_INFO", '_CASTOR_TEMPLATE_PACKAGES_EDIT_INFO', false, false);
		
		$jrtbar = castor_singleton_abstract::getInstance('castor_toolbar');
		$jrtb = $jrtbar->startTable();

		$jrtb .= $jrtbar->toolbarItem('cancel', CASTOR_SITEPAGE_URL_ADMIN.'&task=list_template_overrides', '');
		$jrtb .= $jrtbar->toolbarItem('save', '', '', true, 'save_template_override');
		$jrtb .= $jrtbar->endTable();
		$output[ 'CASTORTOOLBAR' ] = $jrtb;

		$pageoutput[ ] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_ADMINISTRATOR);
		$tmpl->readTemplatesFromInput('edit_template_override.html');
		$tmpl->addRows('pageoutput', $pageoutput);
		// $tmpl->addRows('rows', $rows);
		$tmpl->displayParsedTemplate();
	}


	public function getRetVals()
	{
		return null;
	}
}

