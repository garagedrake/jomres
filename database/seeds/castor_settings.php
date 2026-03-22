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
$query = "TRUNCATE TABLE `#__castor_settings`;";

if (!doInsertSql($query)) {
	$this->setMessage('Error, unable to truncate #__castor_settings table', 'danger');
	
	return;
}

include CASTORPATH_BASE.JRDS.'castor_config.php';

$clause = '';

foreach ($mrConfig as $k => $v) {
	$clause .= "(0, '".$k."', '".$v."'), ";
}

$clause = rtrim($clause, ', ');

$query = "INSERT INTO `#__castor_settings` (`property_uid`, `akey`, `value`) VALUES $clause";

if (!doInsertSql($query)) {
	$this->setMessage('Error, unable to insert default property settings in the #__castor_settings table', 'danger');
}

