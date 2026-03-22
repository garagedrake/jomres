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
$query = "TRUNCATE TABLE `#__castorportal_c_rates`;";

if (!doInsertSql($query)) {
	$this->setMessage('Error, unable to truncate #__castorportal_c_rates table', 'danger');
	
	return;
}

$query = "
INSERT INTO `#__castorportal_c_rates` (`id`, `title`, `type`, `value`, `currencycode`, `tax_rate`) VALUES
(1, 'default', 2, 10, 'EUR', 1);
";

if (!doInsertSql($query)) {
	$this->setMessage('Error, unable to insert sample data in the #__castorportal_c_rates table', 'danger');
}

