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
	
	/**
	 *
	 * @package Castor\Core\CMS_Specific
	 *
	 */

defined('_JEXEC') or die('Direct Access to this location is not allowed.');

if (!defined('_CASTOR_INITCHECK')) {
	define('_CASTOR_INITCHECK', 1);
}

define('_CASTOR_INITCHECK_ADMIN', 1);


JToolBarHelper::title('Castor', 'home.png');

if (!defined('CASTOR_ROOT_DIRECTORY')) {
	if (file_exists(dirname(__FILE__).'/../../../castor_root.php')) {
		require_once(dirname(__FILE__).'/../../../castor_root.php');
	} else {
		define('CASTOR_ROOT_DIRECTORY', "castor") ;
	}
}

require_once(dirname(__FILE__) . '/../../../'.CASTOR_ROOT_DIRECTORY.'/admin.php');

