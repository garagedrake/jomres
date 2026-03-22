<?php
// No direct access to this file

// Joomla 4

defined('_JEXEC') or die('');

use Joomla\Archive\Archive;
	
	/**
	 *
	 * @package Castor\Core\CMS_Specific
	 *
	 */

class com_castorInstallerScript //http://joomla.stackexchange.com/questions/5687/script-not-running-on-plugin-installation
{

	/**
	 *
	 *
	 *
	 */

	function preflight($type, $parent)
	{
		@ignore_user_abort(true);
		@set_time_limit(0);

		//this is an uninstall, so we simply return true
		if ($type == 'uninstall') {
			return true;
		}
		
		// Clear Joomla system cache.
		/** @var JCache|JCacheController $cache */
		$cache = JFactory::getCache();
		$cache->clean('_system');

		// Remove all compiled files from APC cache.
		if (function_exists('apc_clear_cache')) {
			@apc_clear_cache();
		}
		
		if (!defined('_CASTOR_INITCHECK')) {
			define('_CASTOR_INITCHECK', 1);
		}
		
		//define Castor root dir
		if (!defined('CASTOR_ROOT_DIRECTORY')) {
			if (file_exists(JPATH_ROOT . DIRECTORY_SEPARATOR . 'castor_root.php')) {
				require_once JPATH_ROOT . DIRECTORY_SEPARATOR . 'castor_root.php';
			} else {
				define('CASTOR_ROOT_DIRECTORY', 'castor');
			}
		}
		
		// Let's get on with the business of downloading Castor. If we can`t get the latest version info (maybe becuase of a firewall preventing communication with updates.castor4.net), we`ll abort by returning false
		try {
			$http = Joomla\CMS\Http\HttpFactory::getHttp();
		} catch (Exception $e) {
			throw new \Exception('Castor requires minimum Joomla version 3.8 to run. Please update Joomla first.', 500);
		}
		
		//check disk space
		/* $disk_free_space = $this->free_space();

		if ( $disk_free_space < 300 ) {
			JError::raiseWarning(null, 'There is not enough disk space available to download and extract Castor.');

			return false;
		} */
		
		//set the castor download url
		$url = 'http://updates.castor.net/getlatest.php?includebeta=true';
		$nightly_url = 'http://updates.castor.net/nightly/';
		
		$debugging = JFactory::getConfig()->get('debug');
		$nightly = false;
		
		if ($debugging == '1') {
			$nightly = true;
		} elseif (file_exists(JPATH_ROOT . DIRECTORY_SEPARATOR . CASTOR_ROOT_DIRECTORY . DIRECTORY_SEPARATOR . 'configuration.php')) {
			include JPATH_ROOT . DIRECTORY_SEPARATOR . CASTOR_ROOT_DIRECTORY . DIRECTORY_SEPARATOR . 'configuration.php';
			
			if ($jrConfig['development_production'] == 'development') {
				$nightly = true;
			}
		}

		//get the latest castor version download url
		$response = $http->get($url);

		if (strlen($response->body) == 0) {
			return false;
		}

		//all fine so far, let` start the download
		if (!$nightly) {
			$archivename = JInstallerHelper::downloadPackage($response->body);
		} else {
			$archivename = JInstallerHelper::downloadPackage($nightly_url);
		}
		
		//was the package downloaded?
		if (!$archivename) {
			throw new \Exception('Something went wrong downloading Castor. Quitting', 500);
		}
		
		//clean the archive name
		$archivename = JPath::clean($archivename);

		//set paths
		$tmp_path = JFactory::getConfig()->get('tmp_path');
		
		if ($tmp_path == '') {
			$tmp_path = JPATH_ROOT . DIRECTORY_SEPARATOR . 'tmp';
		}
		
		$castor_path = JPATH_ROOT . DIRECTORY_SEPARATOR . CASTOR_ROOT_DIRECTORY;
		$extraction_path = $tmp_path . DIRECTORY_SEPARATOR . CASTOR_ROOT_DIRECTORY;
		
		//create /tmp/castor dir
		try {
			JFolder::create($extraction_path);
		} catch (Exception $e) {
			throw new \Exception('Something went wrong when trying to create dir ' . $extraction_path, 500);
		}
		
		//create /castor dir
		try {
			JFolder::create($castor_path);
		} catch (Exception $e) {
			throw new \Exception('Something went wrong when trying to create dir ' . $castor_path . '. Using FTP, create the directory manually then re-run the installer, many times this will solve the problem.', 500);
			return false;
		}

		//Unzip Castor
		try {
			$archive = new Archive;

			$extract = $archive->extract($tmp_path . DIRECTORY_SEPARATOR . $archivename, $extraction_path);
		} catch (Exception $e) {
			throw new \Exception('Something went wrong when trying to unzip the archive.', 500);
		}

		if (!$extract) {
			throw new \Exception('Something went wrong when unzipping the archive.', 500);
		}
		
		//move the extracted files to /castor dir
		try {
			JFolder::copy($extraction_path, $castor_path, '', $force = true);
		} catch (Exception $e) {
			throw new \Exception('Something went wrong when trying to move the extracted Castor files.', 500);
		}
		
		//cleanup the extracted files
		try {
			JInstallerHelper::cleanupInstall($archivename, $extraction_path);
		} catch (Exception $e) {
			throw new \Exception('Something went wrong when trying to cleanup castor tmp files.', 500);
		}
	}
		
	/**
	 *
	 *
	 *
	 */

	function install($parent)
	{
		if (!defined('_CASTOR_INITCHECK')) {
			define('_CASTOR_INITCHECK', 1);
		}

		//define Castor root dir
		if (!defined('CASTOR_ROOT_DIRECTORY')) {
			if (file_exists(JPATH_ROOT . DIRECTORY_SEPARATOR . 'castor_root.php')) {
				require_once JPATH_ROOT . DIRECTORY_SEPARATOR . 'castor_root.php';
			} else {
				define('CASTOR_ROOT_DIRECTORY', 'castor');
			}
		}

		if (!file_exists(JPATH_ROOT . DIRECTORY_SEPARATOR . CASTOR_ROOT_DIRECTORY . '/libraries/castor/classes/castor_install.class.php')) {
			throw new \Exception("Castor installer class does not exist. It is possible that the Castor.zip file downloaded during the installation process was not unzipped properly. One possible cause is that this hosting account has run out of disk space.", 500);
		}

		try {
			require_once JPATH_ROOT . DIRECTORY_SEPARATOR . CASTOR_ROOT_DIRECTORY . '/libraries/castor/classes/castor_install.class.php';

			$castor_install = new castor_install('install');
			
			$messages = $castor_install->getMessages();

			foreach ($messages as $m) {
				throw new \Exception($m, 500);
			}
		} catch (Exception $e) {
			throw new \Exception('Something went wrong when running the Castor installation script.', 500);
		}
	}
	
	/**
	 *
	 *
	 *
	 */

	function update($parent)
	{
		if (!defined('_CASTOR_INITCHECK')) {
			define('_CASTOR_INITCHECK', 1);
		}

		//define Castor root dir
		if (!defined('CASTOR_ROOT_DIRECTORY')) {
			if (file_exists(JPATH_ROOT . DIRECTORY_SEPARATOR . 'castor_root.php')) {
				require_once JPATH_ROOT . DIRECTORY_SEPARATOR . 'castor_root.php';
			} else {
				define('CASTOR_ROOT_DIRECTORY', 'castor');
			}
		}

		if (!file_exists(JPATH_ROOT . DIRECTORY_SEPARATOR . CASTOR_ROOT_DIRECTORY . '/libraries/castor/classes/castor_install.class.php')) {
			throw new \Exception("Castor installer class does not exist. It is possible that the Castor.zip file downloaded during the installation process was not unzipped properly. One possible cause is that this hosting account has run out of disk space.", 500);
		}

		try {
			require_once JPATH_ROOT . DIRECTORY_SEPARATOR . CASTOR_ROOT_DIRECTORY . '/libraries/castor/classes/castor_install.class.php';

			$castor_install = new castor_install('update');
			
			$messages = $castor_install->getMessages();

			foreach ($messages as $m) {
				throw new \Exception($m, 500);
			}
		} catch (Exception $e) {
			throw new \Exception('Something went wrong when updating', 500);
		}
	}
	
	/**
	 *
	 *
	 *
	 */

	function uninstall($parent)
	{
		if (!defined('_CASTOR_INITCHECK')) {
			define('_CASTOR_INITCHECK', 1);
		}

		//define Castor root dir
		if (!defined('CASTOR_ROOT_DIRECTORY')) {
			if (file_exists(JPATH_ROOT . DIRECTORY_SEPARATOR . 'castor_root.php')) {
				require_once JPATH_ROOT . DIRECTORY_SEPARATOR . 'castor_root.php';
			} else {
				define('CASTOR_ROOT_DIRECTORY', 'castor');
			}
		}

		if (!file_exists(JPATH_ROOT . DIRECTORY_SEPARATOR . CASTOR_ROOT_DIRECTORY . '/libraries/castor/classes/castor_install.class.php')) {
			throw new \Exception("Castor installer class does not exist. It is possible that Castor has already been partially uninstalled. If so, you will need to finish the uninstallation manually by removing the com_castor directories in both the /administrator/components and /components directories", 500);
		}

		try {
			require_once JPATH_ROOT . DIRECTORY_SEPARATOR . CASTOR_ROOT_DIRECTORY . '/libraries/castor/classes/castor_install.class.php';

			$castor_install = new castor_install('uninstall');
			
			$messages = $castor_install->getMessages();

			foreach ($messages as $m) {
				throw new \Exception($m, 500);
			}
		} catch (Exception $e) {
			throw new \Exception('Something went wrong when uninstalling', 500);
		}
	}
	
	/**
	 *
	 *
	 *
	 */

	function postflight($type, $parent)
	{
		//
	}
		
	/**
	 *
	 *
	 *
	 */

	function free_space($path = JPATH_ROOT)
	{
		$space = @disk_free_space($path);
		
		if ($space === false || is_null($space)) {
			return 0;
		}
		
		//convert to MB
		$space = round($space / 1024 / 1024);
		
		return $space;
	}
}

