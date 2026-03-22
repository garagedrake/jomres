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
// Must do this here, because if we cannot create the encryption key, we cannot encode the user's data
try 
	{
	jr_import('castor_encryption');
	$castor_encryption = new castor_encryption();
	}
	catch (Exception $e) 
	{
		echo $e->getMessage();
		return;
	}

