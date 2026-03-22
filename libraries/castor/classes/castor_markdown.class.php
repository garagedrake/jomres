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

// A wrapper class for parsing data through a markdown class

	/**
	 *
	 * @package Castor\Core\Classes
	 *
	 */
	#[AllowDynamicProperties]
class castor_markdown
{

	/**
	 *
	 *
	 *
	 */

	public function __construct()
	{
		//require_once (CASTOR_LIBRARIES_ABSPATH.'Parsedown'.JRDS.'Parsedown.php');
		$this->Parsedown = new Parsedown();
		$this->Parsedown->setBreaksEnabled(true);
	}

	/**
	 *
	 *
	 *
	 */

	public function get_markdown($string)
	{
		$string = str_replace("&#10;", "\n", $string);
		$string = str_replace("&#38;", "&", $string);

		return $this->Parsedown->text($string);
	}
}

