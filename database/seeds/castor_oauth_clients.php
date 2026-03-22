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
 * Seed data for various tables
 *
 **/
$query = "TRUNCATE TABLE `#__castor_oauth_clients`;";

if (!doInsertSql($query)) {
	$this->setMessage('Error, unable to truncate #__castor_oauth_clients table', 'danger');
	
	return;
}

$query = "
INSERT INTO `#__castor_oauth_clients` (`client_id`, `client_secret`, `redirect_uri`, `grant_types`, `scope`, `user_id`) VALUES 
('system', '".createNewAPIKey()."', '', NULL, '*', 4294967295);
";
	
if (!doInsertSql($query)) {
	$this->setMessage('Error, unable to insert default system user to #__castor_oauth_clients table', 'danger');
}


