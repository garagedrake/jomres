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

class j16000list_template_overrides
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
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = false;

			return;
		}

		$template_packages = get_showtime('template_packages');

		if (!empty($template_packages)) {
			$template_overrides = castor_singleton_abstract::getInstance('template_overrides');

			$template_files_overrideable = array();
			
			$already_found = array();

			foreach ($template_packages as $packages) {
				foreach ($packages as $package_item) {
					$r = array();
					$template_name = $package_item['template_name'];
					if (!in_array($template_name, $already_found)) {
						$r['TEMPLATE_NAME'] = $template_name;

						$toolbar = castor_singleton_abstract::getInstance('castorItemToolbar');
						if (isset($template_overrides->template_overrides[$template_name])) {
							$r['PATH'] = $template_overrides->template_overrides[$template_name]['path'];
							$toolbar = castor_singleton_abstract::getInstance('castorItemToolbar');
							$toolbar->newToolbar();
							$toolbar->addItem('fa fa-pencil-square-o', 'btn btn-info', '', castorURL(CASTOR_SITEPAGE_URL_ADMIN.'&task=edit_template_override&template_name='.$template_name), jr_gettext('COMMON_EDIT', 'COMMON_EDIT', false));
							$toolbar->addSecondaryItem('fa fa-trash-o', '', '', castorURL(CASTOR_SITEPAGE_URL_ADMIN . '&task=delete_template_override&template_name=' . $template_name), jr_gettext('COMMON_DELETE', 'COMMON_DELETE', false));
						} else {
							$r['PATH'] =jr_gettext('_CASTOR_TEMPLATE_PACKAGE_NOT_OVERRIDDEN', '_CASTOR_TEMPLATE_PACKAGE_NOT_OVERRIDDEN', false);
							$toolbar = castor_singleton_abstract::getInstance('castorItemToolbar');
							$toolbar->newToolbar();
							$toolbar->addItem('fa fa-pencil-square-o', 'btn btn-info', '', castorURL(CASTOR_SITEPAGE_URL_ADMIN.'&task=edit_template_override&template_name='.$template_name), jr_gettext('COMMON_EDIT', 'COMMON_EDIT', false));
						}
						$r['EDITLINK'] = $toolbar->getToolbar();
						$rows[]=$r;
						$already_found[] = $template_name;
					}
				}
			}
			
			$output[ '_CASTOR_TEMPLATE_PACKAGES' ] = jr_gettext('_CASTOR_TEMPLATE_PACKAGES', '_CASTOR_TEMPLATE_PACKAGES', false);
			$output[ '_CASTOR_TEMPLATE_PACKAGES_LEAD' ] = jr_gettext('_CASTOR_TEMPLATE_PACKAGES_LEAD', '_CASTOR_TEMPLATE_PACKAGES_LEAD', false);
			$output[ '_CASTOR_TEMPLATE_PACKAGES_INFO' ] = jr_gettext('_CASTOR_TEMPLATE_PACKAGES_INFO', '_CASTOR_TEMPLATE_PACKAGES_INFO', false);
			
			$output[ '_CASTOR_TEMPLATE_PACKAGE_NAME' ] = jr_gettext('_CASTOR_TEMPLATE_PACKAGE_NAME', '_CASTOR_TEMPLATE_PACKAGE_NAME', false);
			$output[ '_CASTOR_TEMPLATE_PACKAGE_PATH' ] = jr_gettext('_CASTOR_TEMPLATE_PACKAGE_PATH', '_CASTOR_TEMPLATE_PACKAGE_PATH', false);

			$pageoutput[ ] = $output;
			$tmpl = new patTemplate();
			$tmpl->setRoot(CASTOR_TEMPLATEPATH_ADMINISTRATOR);
			$tmpl->readTemplatesFromInput('template_packages.html');
			$tmpl->addRows('pageoutput', $pageoutput);
			$tmpl->addRows('rows', $rows);
			$tmpl->displayParsedTemplate();
		} else {
			echo 'No template packages installed';
		}
	}


	public function getRetVals()
	{
		return null;
	}
}

