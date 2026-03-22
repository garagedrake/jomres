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
$query = "TRUNCATE TABLE `#__castorportal_properties_crates_xref`;";

if (!doInsertSql($query)) {
	$this->setMessage('Error, unable to truncate #__castorportal_properties_crates_xref table', 'danger');
	
	return;
}

$query = "
INSERT INTO `#__castorportal_properties_crates_xref` (`id`, `property_id`, `crate_id`) VALUES
(1, 1, 1);
";

if (!doInsertSql($query)) {
	$this->setMessage('Error, unable to insert sample data in the #__castorportal_properties_crates_xref table', 'danger');
}

