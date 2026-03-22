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
	
	/**
	 *
	 * @package Castor\Core\Classes
	 *
	 */
	#[AllowDynamicProperties]
class castor_version_check
{

	/**
	 *
	 *
	 *
	 */

	public function __construct()
	{
		$this->latest_castor_version = get_latest_castor_version();
		$this->tmp_dir = CASTOR_TEMP_ABSPATH."version_checks";
		$this->secret = md5(get_showtime('secret')); // We will use the secret variable because we don't want temporary files with just the version number as this is a security risk. Bad people could just come and test for version numbers and if a version has a vulnerability then that could be zeroed in on. So, we'll md5 hash the site secret to help with creating the version file.
		
		if (!is_dir($this->tmp_dir)) {
			if (!mkdir($this->tmp_dir)) {
				 throw new Exception('Error, could not create version check temporary directory');
			}
		}
		
		$this->check_file = $this->tmp_dir.JRDS.$this->latest_castor_version.'_'.$this->secret.'.txt';
		if (!file_exists($this->check_file)) {
			$this->check_version();
		}
	}
	
	/**
	 *
	 *
	 *
	 */

	public function check_version() // We'll get the current version,
	{
		$current_version_is_uptodate = check_castor_version(false);
		if (!$current_version_is_uptodate) {
			$subject = jr_gettext('_CASTOR_VERSIONCHECK_LATESTCASTORVERSION', '_CASTOR_VERSIONCHECK_LATESTCASTORVERSION', false, false).' '.$this->latest_castor_version;
			$message = jr_gettext('_CASTOR_VERSIONCHECK_VERSIONWARNING', '_CASTOR_VERSIONCHECK_VERSIONWARNING', false, false)." \n\r <a href='".CASTOR_SITEPAGE_URL_ADMIN."'>".CASTOR_SITEPAGE_URL_ADMIN."</a>";
			sendAdminEmail($subject, $message);
		}
		$this->write_checkfile();
	}
		
	/**
	 *
	 *
	 *
	 */

	private function write_checkfile()
	{
		touch($this->check_file);
		return;
	}
}

