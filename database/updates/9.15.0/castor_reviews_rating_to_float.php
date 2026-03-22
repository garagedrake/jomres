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
	ALTER TABLE `#__castor_reviews_ratings` CHANGE `rating` `rating` FLOAT(2) NULL DEFAULT NULL;
	";

if (!doInsertSql($query)) {
	$this->setMessage('Error, unable to modify the #__castor_reviews_ratings_replies table', 'danger');
}

