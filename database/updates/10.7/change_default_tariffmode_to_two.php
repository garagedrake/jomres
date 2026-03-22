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
 * Database modification during updates
 *
 **/
$query = "
UPDATE `#__castor_settings` SET `value` = '2' WHERE `#__castor_settings`.`property_uid` = 0 AND `#__castor_settings`.`akey` = 'tariffmode' ; 
";

if (!doInsertSql($query)) {
	$this->setMessage('Error, unable to change the default tariff editing mode from 5 to 3', 'danger');
}

