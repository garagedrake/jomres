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

class j16000refresh_library_packages
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

		if (isset($_REQUEST['go'])) {
			if (isset($_REQUEST['package_manager_install'])) {
				castorRedirect(castorURL(CASTOR_SITEPAGE_URL_ADMIN.'&task=dashboard'), '');
			} else {
				$core_package_management = new core_package_management();
				$core_package_management->show_installer_html();
			}
		} else {
			$output = array();
			$pageoutput = array();
			
			$output['_CASTOR_LIBRARY_PACKAGES'] = jr_gettext('_CASTOR_LIBRARY_PACKAGES', '_CASTOR_LIBRARY_PACKAGES', false);
			$output['_CASTOR_LIBRARY_PACKAGES_DESC'] = jr_gettext('_CASTOR_LIBRARY_PACKAGES_DESC', '_CASTOR_LIBRARY_PACKAGES_DESC', false);
			$output['_CASTOR_LIBRARY_PACKAGES_REFRESH'] = jr_gettext('_CASTOR_LIBRARY_PACKAGES_REFRESH', '_CASTOR_LIBRARY_PACKAGES_REFRESH', false);
			
			$output['URL'] = CASTOR_SITEPAGE_URL_ADMIN_AJAX.'&task=refresh_library_packages&go=1';
			

			$pageoutput[ ] = $output;
			$tmpl = new patTemplate();
			$tmpl->setRoot(CASTOR_TEMPLATEPATH_ADMINISTRATOR);
			$tmpl->addRows('pageoutput', $pageoutput);
			$tmpl->readTemplatesFromInput('package_reinstallation.html');

			$tmpl->displayParsedTemplate();
		}
	}


	public function getRetVals()
	{
		return null;
	}
}

