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
$query = "TRUNCATE TABLE `#__castor_property_categories`;";

if (!doInsertSql($query)) {
	$this->setMessage('Error, unable to truncate #__castor_property_categories table', 'danger');
	
	return;
}

$query = "
INSERT INTO `#__castor_property_categories` (`id`, `title`, `description`) VALUES
(1, 'Vacation Rentals', 'This is the rentals category description'),
(2, 'Tours', 'This is the tours category description'),
(3, 'Long Term Rentals', 'Long term rentals category description'),
(4, 'Real Estate', 'Real estate category description');
";

if (!doInsertSql($query)) {
	$this->setMessage('Error, unable to insert sample data in the #__castor_property_categories table', 'danger');
}

