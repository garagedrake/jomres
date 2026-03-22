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

class j16000castor_system_info
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

		$output = array();
		$pageoutput = array();

		//castor version
		$configfile = CASTORPATH_BASE.JRDS.'castor_config.php'; // This is to pull in the Castor version from mrConfig
		include $configfile;

		$this_version = get_castor_current_version();
		$latest_version = get_latest_castor_version();
		

		$output[ '_CASTOR_VERSIONCHECK_THISCASTORVERSION' ] = jr_gettext('_CASTOR_VERSIONCHECK_THISCASTORVERSION', '_CASTOR_VERSIONCHECK_THISCASTORVERSION', false);
		$output[ '_CASTOR_VERSIONCHECK_LATESTCASTORVERSION' ] = jr_gettext('_CASTOR_VERSIONCHECK_LATESTCASTORVERSION', '_CASTOR_VERSIONCHECK_LATESTCASTORVERSION', false);
		
		$output[ 'THIS_CASTOR_VERSION' ] = $this_version;
		$output[ 'CASTOR_VERSION_LABEL_CLASS' ] = 'label-green';
		
		$output[ 'ERROR' ] = '';
		$output[ 'HIGHLIGHT' ] = '';
		$output[ 'ALERT' ] = '';

		if (empty($latest_version)) {
			$output[ 'CASTOR_VERSION_LABEL_CLASS' ] = 'label-red';
			$output[ 'LATEST_CASTOR_VERSION' ] = 'Unknown';
			$output[ 'ERROR' ] = 'Sorry, could not get latest version of Castor, is there a firewall preventing communication with http://updates.castor.net ? Alternatively, please check that CURL is enabled on this webserver<p>';
			$output[ 'HIGHLIGHT' ] = (using_bootstrap() ? 'alert alert-error' : 'ui-state-error');
		} else {
			$current_version_is_uptodate = check_castor_version();

			if (!$current_version_is_uptodate) {
				$key_validation = castor_singleton_abstract::getInstance('castor_check_support_key');
				
				$this->key_valid = $key_validation->key_valid;

				$output[ 'HIGHLIGHT' ] = (using_bootstrap() ? 'alert alert-error' : 'ui-state-error');
				$output[ 'ALERT' ] = '<a href="'.CASTOR_SITEPAGE_URL_ADMIN.'&task=updates" >'.jr_gettext('_CASTOR_VERSIONCHECK_VERSIONWARNING', '_CASTOR_VERSIONCHECK_VERSIONWARNING', false).'</a>';

				if ($this->key_valid) {
					$output[ 'UPDATEINFO' ] = jr_gettext('_CASTOR_VERSIONCHECK_VERSIONWARNING_UPDATEINFO_KEYVALID', '_CASTOR_VERSIONCHECK_VERSIONWARNING_UPDATEINFO_KEYVALID', false);
				} else {
					$output[ 'UPDATEINFO' ] = jr_gettext('_CASTOR_VERSIONCHECK_VERSIONWARNING_UPDATEINFO', '_CASTOR_VERSIONCHECK_VERSIONWARNING_UPDATEINFO', false);
					$output[ '_CASTOR_VERSIONCHECK_VERSIONWARNING_RENEWALS' ] = jr_gettext('_CASTOR_VERSIONCHECK_VERSIONWARNING_RENEWALS', '_CASTOR_VERSIONCHECK_VERSIONWARNING_RENEWALS', false);
				}
				
				
				$output[ 'CASTOR_VERSION_LABEL_CLASS' ] = 'label-red';
			}

			$output[ 'LATEST_CASTOR_VERSION' ] = $latest_version;
		}

		
		$output[ '_ADMIN_CPANEL_SYSTEM_INFO' ] = jr_gettext('_ADMIN_CPANEL_SYSTEM_INFO', '_ADMIN_CPANEL_SYSTEM_INFO', false);
		
		$pageoutput[ ] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_ADMINISTRATOR);
		$tmpl->addRows('pageoutput', $pageoutput);
		$tmpl->readTemplatesFromInput('castor_system_info.html');

		if ($output_now) {
			$tmpl->displayParsedTemplate();
		} else {
			$this->retVals = $tmpl->getParsedTemplate();
		}
	}


	public function getRetVals()
	{
		return $this->retVals;
	}
}

