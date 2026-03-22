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
$query = "TRUNCATE TABLE `#__jomcomp_tarifftype_rate_xref`;";

if (!doInsertSql($query)) {
	$this->setMessage('Error, unable to truncate #__jomcomp_tarifftype_rate_xref table', 'danger');
	
	return;
}

$query = "
INSERT INTO `#__jomcomp_tarifftype_rate_xref` (`id`, `tarifftype_id`, `tariff_id`, `roomclass_uid`, `property_uid`) VALUES
(1, 1, 1, 1, 1);
";

if (!doInsertSql($query)) {
	$this->setMessage('Error, unable to insert sample data in the #__jomcomp_tarifftype_rate_xref table', 'danger');
}

