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
$query = "TRUNCATE TABLE `#__castor_rooms`;";

if (!doInsertSql($query)) {
	$this->setMessage('Error, unable to truncate #__castor_rooms table', 'danger');
	
	return;
}

$query = "
INSERT INTO `#__castor_rooms` (`room_uid`, `room_classes_uid`, `propertys_uid`, `room_features_uid`, `room_name`, `room_number`, `room_floor`, `max_people`, `singleperson_suppliment`, `tagline`, `description`) VALUES
(1, 1, 1, NULL, 'The Pitt', '1', '1', 2, 0, NULL, NULL),
(2, 1, 1, NULL, 'The Hole', '2', '1', 2, 0, NULL, NULL);
";

if (!doInsertSql($query)) {
	$this->setMessage('Error, unable to insert sample data in the #__castor_rooms table', 'danger');
}

