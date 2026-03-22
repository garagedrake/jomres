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
$query = "TRUNCATE TABLE `#__castor_ptypes`;";

if (!doInsertSql($query)) {
	$this->setMessage('Error, unable to truncate #__castor_ptypes table', 'danger');
	
	return;
}

$query = "
INSERT INTO `#__castor_ptypes` (`id`, `ptype`, `ptype_desc`, `published`, `order`, `mrp_srp_flag`, `marker`, `has_stars`) VALUES
(1, 'Hotel', 'propertyrental', 1, 0, 0, 'building.png', 1),
(2, 'Yacht', 'yacht', 1, 0, 1, 'free-map-marker-icon-blue.png', 1),
(3, 'Car', 'car', 1, 0, 1, 'free-map-marker-icon-red.png', 0),
(4, 'Camp Site', 'campsite', 1, 0, 0, 'free-map-marker-icon-red.png', 1),
(5, 'Bed and Breakfast', 'propertyrental', 1, 0, 0, 'free-map-marker-icon-red.png', 1),
(6, 'Villa', 'propertyrental', 1, 0, 1, 'free-map-marker-icon-red.png', 1),
(7, 'Apartment', 'propertyrental', 1, 0, 1, 'free-map-marker-icon-red.png', 0),
(8, 'Cottage', 'propertyrental', 1, 0, 1, 'free-map-marker-icon-red.png', 1),
(9, 'Tour', 'tour', 1, 0, 3, 'free-map-marker-icon-red.png', 0),
(10, 'For Sale', 'realestate', 1, 0, 4, 'free-map-marker-icon-red.png', 1);
";

if (!doInsertSql($query)) {
	$this->setMessage('Error, unable to insert sample data in the #__castor_ptypes table', 'danger');
}

