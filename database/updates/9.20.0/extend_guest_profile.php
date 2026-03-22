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
$profiles_cols_added = false;

$query = "SHOW COLUMNS FROM #__castor_guest_profile LIKE 'enc_drivers_license'";
$colExists = doSelectSql( $query );
if (count($colExists) < 1)
	{
	$query = "ALTER TABLE `#__castor_guest_profile` ADD `enc_drivers_license` BLOB ";
	doInsertSql($query,"");
	
	$query = "ALTER TABLE `#__castor_guest_profile` ADD `enc_passport_number` BLOB ";
	doInsertSql($query,"");
	
	$query = "ALTER TABLE `#__castor_guest_profile` ADD `enc_iban` BLOB ";
	doInsertSql($query,"");
	
	$query = "ALTER TABLE `#__castor_guest_profile` ADD `enc_about_me` BLOB ";
	doInsertSql($query,"");
	
	$profiles_cols_added = true;
	}
	



