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

/**
*
* Prevents scripts from trying to run files directly from the Castor directory.
*
* Recent changes to WP 5.x mean that WP was trying to call index.php in the Castor dir. Previously this script did nothing except to say "don't use me" however now it will require the index.php in the directory above, essentially ensuring that Castor is correctly triggered through the WP framework instead.
* 
*/

require_once( __DIR__.DIRECTORY_SEPARATOR.'../index.php');

