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
// https://github.com/WoollyinWalesIT/castor/issues/272

$query = "TRUNCATE TABLE `#__castor_oauth_access_tokens`;";

if (!doInsertSql($query)) {
	$this->setMessage('Error, unable to truncate #__castor_oauth_access_tokens table', 'danger');
	
	return;
}

