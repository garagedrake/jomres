<?php
/**
 * This script is mainly used for bootstrapping Castor. It's old code, but it checks out.
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
* Does a lot of file inclusion, creation of constants etc.
*
*
*/


/**
*
* TRANSACTION_ID is used by the logger class to allow us to track single calls through the system
*
*/
if (!defined('TRANSACTION_ID')) {
	define('TRANSACTION_ID', time());
}


/**
*
* castor root directory name
*
* Wordpress determined for some reason that the Castor root directory should be configurable as a condition of listing Castor on the Wordpress plugin directory. CMS root directories are sometimes not writable by the web server and castor_root.php cannot be created, in which case we will assume that the Castor root directory is called "castor"
*
*/
if (!defined('CASTOR_ROOT_DIRECTORY')) {
	if (file_exists(dirname(__FILE__).'/../castor_root.php')) {
		require_once dirname(__FILE__).'/../castor_root.php';
	} else {
		define('CASTOR_ROOT_DIRECTORY', 'castor');
	}
}


/**
*
* find castor root path
*
*/
if (!defined('CASTORPATH_BASE')) {
	if (!defined('JRDS')) {
		$apacheSig = false;

		$detect_os = strtoupper($_SERVER[ 'SERVER_SOFTWARE' ]); // converted to uppercase
		$isWin32 = strpos($detect_os, 'WIN32');
		$IIS = strpos($detect_os, 'IIS');

		if (isset($_SERVER[ 'SERVER_SIGNATURE' ])) {
			$signature = strtoupper($_SERVER[ 'SERVER_SIGNATURE' ]);
			$apacheSig = strpos($signature, 'APACHE');
		}

		$dir = dirname(realpath(__FILE__));

		if (strpos($dir, ':\\')) {
			define('JRDS', '\\');
		} else {
			if ($isWin32 === false || $apacheSig == true) {
				define('JRDS', '/');
			} else {
				define('JRDS', '\\');
			}
		}
	}

	if (isset($_SERVER[ 'SCRIPT_FILENAME' ])) {
		$dir_path = str_replace($_SERVER[ 'SCRIPT_FILENAME' ], '', dirname(realpath(__FILE__)));
	} else {
		$dir_path = str_replace($_SERVER[ 'SCRIPT_NAME' ], '', dirname(realpath(__FILE__)));
	}

	define('CASTORPATH_BASE', $dir_path.JRDS);
}

/**
*
* check if this is an ajax call or not
*
*/
if (!defined('AJAXCALL')) {
	if (isset($_REQUEST[ 'jrajax' ])) {
		if ((int) $_REQUEST[ 'jrajax' ] == 1) {
			define('AJAXCALL', true);
		} else {
			define('AJAXCALL', false);
		}
	} else {
		$contentType = isset($_SERVER["HTTP_ACCEPT"]) ? trim($_SERVER["HTTP_ACCEPT"]) : '';
		if( stristr($contentType, 'application/json') === TRUE ){
			define('AJAXCALL', true);
		}
		else {
			define('AJAXCALL', false);
		}
	}
}


/**
*
* define castor paths
*
*/
$path = rtrim(substr(CASTORPATH_BASE, 0, strlen(CASTORPATH_BASE) - strlen(CASTOR_ROOT_DIRECTORY.JRDS)), '/') . '/';
define('CASTORCONFIG_ABSOLUTE_PATH', $path);


//app
define('CASTOR_APP_ABSPATH', CASTORPATH_BASE.'core-minicomponents'.JRDS);
define('CASTOR_COREPLUGINS_ABSPATH', CASTORPATH_BASE.'core-plugins'.JRDS);
define('CASTOR_REMOTEPLUGINS_ABSPATH', CASTORPATH_BASE.'remote_plugins'.JRDS);
define('CASTOR_LIBRARIES_ABSPATH', CASTORPATH_BASE.'libraries'.JRDS);
define('CASTOR_CLASSES_ABSPATH', CASTORPATH_BASE.'libraries'.JRDS.'castor'.JRDS.'classes'.JRDS);
define('CASTOR_FUNCTIONS_ABSPATH', CASTORPATH_BASE.'libraries'.JRDS.'castor'.JRDS.'functions'.JRDS);
define('CASTOR_CMSSPECIFIC_ABSPATH', CASTORPATH_BASE.'libraries'.JRDS.'castor'.JRDS.'cms_specific'.JRDS);
define('CASTOR_API_ABSPATH', CASTORPATH_BASE.'api'.JRDS);

//assets
define('CASTOR_ASSETS_ABSPATH', CASTORPATH_BASE.'assets'.JRDS);
define('CASTOR_CSS_RELPATH', CASTOR_ROOT_DIRECTORY.'/assets/css/');
define('CASTOR_CSS_ABSPATH', CASTOR_ASSETS_ABSPATH.'css'.JRDS);
define('CASTOR_JS_RELPATH', CASTOR_ROOT_DIRECTORY.'/assets/js/');
define('CASTOR_JS_ABSPATH', CASTOR_ASSETS_ABSPATH.'js'.JRDS);

//storage
define('CASTOR_SESSIONS_ABSPATH', CASTORPATH_BASE.'sessions'.JRDS);
define('CASTOR_TEMP_ABSPATH', CASTORPATH_BASE.'temp'.JRDS);
define('CASTOR_CACHE_ABSPATH', CASTORPATH_BASE.'cache'.JRDS);
define('CASTOR_UPDATES_ABSPATH', CASTORPATH_BASE.'updates'.JRDS);

//mPDF
define('CASTOR_MPDF_ABSPATH', CASTOR_TEMP_ABSPATH.'pdfs'.JRDS);

//vendors
define('CASTOR_PACKAGES_ABSPATH', CASTOR_LIBRARIES_ABSPATH.'packages'.JRDS);
define('CASTOR_VENDOR_ABSPATH', CASTOR_LIBRARIES_ABSPATH.'packages'.JRDS.'vendor'.JRDS);
define('CASTOR_NODE_MODULES_ABSPATH', CASTOR_LIBRARIES_ABSPATH.'packages'.JRDS.'node_modules'.JRDS);
define('CASTOR_NODE_MODULES_RELPATH', CASTOR_ROOT_DIRECTORY.'/libraries/packages/node_modules/');

require_once(CASTOR_CLASSES_ABSPATH.'core_package_management.class.php');

$core_package_management = new core_package_management();

if (!file_exists(CASTOR_VENDOR_ABSPATH.'autoload.php')) {
	$core_package_management->force_packages_reinstall();
}

//includes
require_once CASTOR_VENDOR_ABSPATH.'autoload.php';
require_once CASTORPATH_BASE.'detect_cms.php';
require_once CASTOR_FUNCTIONS_ABSPATH.'load_custom_functions.php';
require_once CASTOR_FUNCTIONS_ABSPATH.'database.php';
require_once CASTOR_FUNCTIONS_ABSPATH.'input_filtering.php';
require_once CASTOR_FUNCTIONS_ABSPATH.'output_filters.php';
require_once CASTOR_FUNCTIONS_ABSPATH.'functions.php';
require_once CASTOR_FUNCTIONS_ABSPATH.'multibye_functions.php';
require_once CASTOR_FUNCTIONS_ABSPATH.'jr_gettext.php';
require_once CASTOR_FUNCTIONS_ABSPATH.'countries.php';
require_once CASTOR_FUNCTIONS_ABSPATH.'countries.php';
require_once CASTOR_CLASSES_ABSPATH.'castor_empty_class.class.php';
require_once CASTOR_CLASSES_ABSPATH.'castor_singleton_abstract.class.php';
require_once CASTOR_FUNCTIONS_ABSPATH.'php-8.1-strftime.php';

	jr_import('castor_gdpr_optin_consent');
/**
*
* include the classes registry file and make $classes a global variable to be easily accessible, so we`ll avoid calling include() more times
*
*/
global $classes;

if (file_exists(CASTOR_TEMP_ABSPATH.'registry_classes.php')) {
	include_once CASTOR_TEMP_ABSPATH.'registry_classes.php';
} else {
	$classes = search_core_and_remote_dirs_for_classfiles();
}

//includes
require_once _CASTOR_DETECTED_CMS_SPECIFIC_FILES.'init_config_vars.php';
require_once _CASTOR_DETECTED_CMS_SPECIFIC_FILES.'cms_specific_functions.php';

//patTemplate
if (!class_exists('patTemplate')) {
	require_once CASTOR_LIBRARIES_ABSPATH.'phptools'.JRDS.'patTemplate.php';
}

if (!class_exists('patErrorManager')) {
	require_once CASTOR_LIBRARIES_ABSPATH.'phptools'.JRDS.'patErrorManager.php';
}

// The purpose here is to prevent Castor temp booking handler from storing sessions in the db, there's no point.
// We are ok with crawlers visiting, after all we want our data to be indexed, but the session data will never be used, so let's not store it if we think it's a crawler
include_once(CASTOR_LIBRARIES_ABSPATH.'Crawler-Detect-master/src/CrawlerDetect.php');
include_once(CASTOR_LIBRARIES_ABSPATH.'Crawler-Detect-master/src/Fixtures/AbstractProvider.php');
include_once(CASTOR_LIBRARIES_ABSPATH.'Crawler-Detect-master/src/Fixtures/Crawlers.php');
include_once(CASTOR_LIBRARIES_ABSPATH.'Crawler-Detect-master/src/Fixtures/Exclusions.php');
include_once(CASTOR_LIBRARIES_ABSPATH.'Crawler-Detect-master/src/Fixtures/Headers.php');

/**
*
* The API includes the logger class. As the API doesn't always include the framework ( for performance ) to use the logger within Castor itself, we'll need to make the distinction here
*
*/
if (!defined('CASTOR_API_CMS_ROOT')) {
	require_once CASTOR_API_ABSPATH.'classes'.JRDS.'logging.class.php';
}

//site config
$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
$jrConfig = $siteConfig->get();

//define castor logs path
if (!isset($jrConfig['log_path']) || $jrConfig['log_path'] == '') {
	$jrConfig['log_path'] = CASTORPATH_BASE.'logs'.JRDS;
}

define('CASTOR_SYSTEMLOG_PATH', fix_path($jrConfig['log_path']));

	/**
	 *
	 * define core images paths
	 *
	 */
	if (!defined('CASTOR_API_CMS_ROOT')) {
		$uri = parse_url(get_showtime('live_site'));
		$path = '';

		if (isset($uri['path'])) {
			$path = $uri['path'];
		}
	} else {
		$path = get_showtime('live_site');
	}

	define('CASTOR_IMAGES_ABSPATH', CASTOR_ASSETS_ABSPATH.'images'.JRDS);
	define('CASTOR_IMAGES_RELPATH', $path.'/'.CASTOR_ROOT_DIRECTORY.'/assets/images/');

	/**
	 *
	 * define uploaded images paths
	 *
	 */
	if (!defined('CASTOR_IMAGELOCATION_ABSPATH')) {
		define('CASTOR_IMAGELOCATION_ABSPATH', CASTORPATH_BASE.'uploadedimages'.JRDS);

		if ( isset($jrConfig['amazon_s3_active'])) {
			if ($jrConfig['amazon_s3_active'] != '1' || $jrConfig['amazon_s3_bucket'] == '') {
				define('CASTOR_IMAGELOCATION_RELPATH', get_showtime('live_site').'/'.CASTOR_ROOT_DIRECTORY.'/uploadedimages/');
			} else {
				if ($jrConfig['amazon_cloudfront_domain'] != '') {
					$amazon_url = 'https://'.$jrConfig['amazon_cloudfront_domain'];
				} else {
					$amazon_url = 'https://'.$jrConfig['amazon_s3_bucket'].'.s3.amazonaws.com';
				}
				define('CASTOR_IMAGELOCATION_RELPATH', $amazon_url.'/uploadedimages/');
			}
		}
	}

/**
*
* fullscreen view setup
*
*/
set_showtime('tmplcomponent', 'castor');
set_showtime('tmplcomponent_source', CASTOR_LIBRARIES_ABSPATH.'fullscreen_view'.JRDS.'castor.php');


/**
*
* copy fullscreen_view/castor.php to the joomla template dir to help with fullscreen mode
*
*/

if (!defined('AUTO_UPGRADE') && !defined('API_STARTED') ) {
	castor_cmsspecific_patchJoomlaTemplate();
}

//cms specific urls
require_once _CASTOR_DETECTED_CMS_SPECIFIC_FILES.'cms_specific_urls.php';

//castor parse request
castor_parseRequest();

//error reporting
if ($jrConfig[ 'development_production' ] == 'production') {
	set_error_handler('errorHandler');
} else {
	error_reporting(-1);
	ini_set('display_errors', 'On');

	// Only enable the following line when digging for depreciations
	//set_error_handler('output_fatal_error');
}

//TODO find a better place, maybe castor.php and framework.php
$castorHTML = castor_singleton_abstract::getInstance('castorHTML');

// CSRF handling
require_once CASTOR_LIBRARIES_ABSPATH.JRDS.'crsfhandler'.JRDS.'csrfhandler.lib.php';

// Currently disabled. Gateways POST payment information, and because gateways use different sessions (i.e. different sessions than the guest or manager's browser session) there's no reliable way to validate CSRF tokens yet
//

/* if (!empty($_POST)) {
	$token = isset($_POST['castor_csrf_token']) ? $_POST['castor_csrf_token'] : '';
	$valid = !empty($token) && $isValid = csrf::checkToken($token);
	if (!$valid) {
		// log then die
		logging::log_message('CSRF token failed to validate ', 'Core', 'WARNING');
		die("Could not validate token");
	}
	csrf::flushKeys();
} */

// Stops here


