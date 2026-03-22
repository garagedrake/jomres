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
$query = "TRUNCATE TABLE `#__castorportal_taxrates`;";

if (!doInsertSql($query)) {
	$this->setMessage('Error, unable to truncate #__castorportal_taxrates table', 'danger');
	
	return;
}

$query = "
INSERT INTO `#__castorportal_taxrates` (`id`, `code`, `description`, `rate`, `is_eu_country`) VALUES
(1, '01', 'VAT', 20, 0);
";

if (!doInsertSql($query)) {
	$this->setMessage('Error, unable to insert sample data in the #__castorportal_taxrates table', 'danger');
}

