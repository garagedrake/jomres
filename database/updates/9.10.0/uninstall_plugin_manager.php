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
 * @package Castor\Core\Database
 *
 * Database modification during updates
 *
 **/
$castor_check_support_key = castor_singleton_abstract::getInstance('castor_check_support_key');

//delete plugin manager (only if the user has a valid license) so users will be forced to install the latest version
if ( $castor_check_support_key->key_status == "Active" ) {
	try {
		//delete plugin manager
		$this->filesystem->deleteDir( 'local://' . CASTOR_ROOT_DIRECTORY . '/core-plugins/plugin_manager' );
		
		//rebuild registry
		$this->minicomponent_registry->regenerate_registry();
	} 
	catch (Exception $e) {
		//do nothing
	}
}

