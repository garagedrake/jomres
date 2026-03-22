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
$query = "TRUNCATE TABLE `#__castor_images`;";

if (doInsertSql($query)) {
	$this->siteConfig->update_setting('images_imported_to_db', '0');
} else {
	$this->setMessage('Error, unable to truncate #__castor_images table', 'danger');
	
	return;
}

//define MEDIACENTRE_ROOMJS so that when media centre will trigger minicomponents to discover available resources, their js won`t be echoed
define('MEDIACENTRE_ROOMJS', 1);

jr_import('castor_media_centre_images_dbimport');

$castor_media_centre_images_dbimport = new castor_media_centre_images_dbimport(array(1), true);

if ($castor_media_centre_images_dbimport->run()) {
	$this->siteConfig->update_setting('images_imported_to_db', '1');
} else {
	$this->setMessage('Error, unable to insert image details to database', 'danger');
}

