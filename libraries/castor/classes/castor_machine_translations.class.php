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
 * @package Castor\Core\Classes
* Doesn't do anything by itself, instead it is a placeholder that plugins can override to provide their own machine translation features.
*
*/
	#[AllowDynamicProperties]
class castor_machine_translations
{
	
	private static $internal_debugging;
	
	/**
	 *
	 *
	 *
	 */

	public function __construct()
	{
		self::$internal_debugging = false;

		$this->init_service();
	}

		
	/**
	 *
	 *
	 *
	 */

	public function init_service()
	{
		$castor_language = castor_singleton_abstract::getInstance('castor_language');
	}

	
	/**
	 *
	 *
	 *
	 */

	public function get_translation($default_text, $constant, $target_language)
	{
		return $default_text;
	}
}

