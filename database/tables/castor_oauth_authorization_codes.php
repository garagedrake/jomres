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
 * Database creation during installation
 *
 **/
$query = "
CREATE TABLE IF NOT EXISTS  #__castor_oauth_authorization_codes (
	`authorization_code` VARCHAR(40) NOT NULL, 
	`client_id` VARCHAR(80) NOT NULL, 
	`user_id` INT UNSIGNED NOT NULL DEFAULT 0, 
	`redirect_uri` VARCHAR(2000), 
	`expires` DATETIME NOT NULL DEFAULT '1970-01-01 00:00:01', 
	`scope` VARCHAR(2000), 
	CONSTRAINT auth_code_pk 
	PRIMARY KEY (`authorization_code`),
	INDEX `client_id` (`client_id`),
	INDEX `user_id` (`user_id`)
	)
	ENGINE = InnoDB 
	DEFAULT CHARSET = utf8mb4 
	COLLATE = utf8mb4_unicode_ci;
";

if (!doInsertSql($query)) {
	$this->setMessage('Error, unable to create the #__castor_oauth_authorization_codes table', 'danger');
}

