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
$query = "TRUNCATE TABLE `#__castor_rates`;";

if (!doInsertSql($query)) {
	$this->setMessage('Error, unable to truncate #__castor_rates table', 'danger');
	
	return;
}

$query = "
INSERT INTO `#__castor_rates` (`rates_uid`, `rate_title`, `rate_description`, `validfrom`, `validto`, `roomrateperday`, `mindays`, `maxdays`, `minpeople`, `maxpeople`, `roomclass_uid`, `ignore_pppn`, `allow_ph`, `allow_we`, `property_uid`) VALUES 
(1, 'Double room rate', 'Double room rate', '2018/01/01', '2028/01/01', 110, 1, 1000, 1, 4, 1, 0, 1, 1, 1);
";

if (!doInsertSql($query)) {
	$this->setMessage('Error, unable to insert sample data in the #__castor_rates table', 'danger');
}

