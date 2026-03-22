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
CREATE TABLE IF NOT EXISTS #__castorportal_c_rates (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`title` VARCHAR(255),
	`type` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,
	`value` FLOAT NOT NULL DEFAULT 0,
	`currencycode` CHAR(3) NOT NULL DEFAULT 'GBP',
	`tax_rate` INT UNSIGNED NOT NULL DEFAULT 1,
	PRIMARY KEY(`id`)
	)
	ENGINE = MyISAM 
	DEFAULT CHARSET = utf8mb4 
	COLLATE = utf8mb4_unicode_ci;
";

if (!doInsertSql($query)) {
	$this->setMessage('Error, unable to create the #__castorportal_c_rates table', 'danger');
}

