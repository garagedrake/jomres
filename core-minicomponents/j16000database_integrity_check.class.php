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

class j16000database_integrity_check
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

		$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
		$jrConfig = $siteConfig->get();
		
		$castor_version = '<p>Castor files version: '.$jrConfig['version'].'</p>';
		$castor_db_version = '<p>Castor database version: '.$jrConfig['castor_db_version'].'</p>';

		// If we're in dev mode it's ok to go right ahead and run the installer
		if ($jrConfig[ 'development_production' ] == 'development') {
			castorRedirect(castorURL(CASTOR_SITEPAGE_URL_ADMIN.'&task=castor_install'), '');
		}

		if ($jrConfig['version'] > $jrConfig['castor_db_version']) {
			echo '
<div class="alert alert-warning">
	<h3>WARNING: Castor database tables are not up to date.</h3>'
			.$castor_version.$castor_db_version.
			'<p>Before attempting to solve this problem, please make a full site backup, then click the button below.</p>
	<a href="'.castorUrl(CASTOR_SITEPAGE_URL_ADMIN.'&task=castor_install').'" class="btn btn-warning">Update database tables</a>
</div>';
		} elseif ($jrConfig['version'] < $jrConfig['castor_db_version']) {
			echo '
<div class="alert alert-danger">
	<h3>ERROR: Castor files are older than the database version.<h3>'
			.$castor_version.$castor_db_version.
			'<p>To solve this problem, you`ll need to run the Castor update again or contact support for further assistance.</p>
</div>';
		} else {
			echo '
<div class="alert alert-success">
	<h3>Congratulations! No problems detected.</h3>'
			.$castor_version.$castor_db_version.
			'</div>';
		}
	}


	public function getRetVals()
	{
		return null;
	}
}

