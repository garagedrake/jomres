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
 * @package Castor\Core\Functions
 *
 * Where possible use mb_string functionality for substr.
 */
	if (!function_exists('jr_substr')) {
		function jr_substr($str, $arg1, $arg2)
		{
			if (!function_exists('mb_substr')) {
				$result = substr($str, $arg1, $arg2);
			} else {
				$result = mb_substr($str, $arg1, $arg2, 'UTF-8');
			}

			return $result;
		}
	}


/**
 * @package Castor\Core\Functions
 *
 *          Where possible use mb_string functionalty for strtolower
 *
 *
 */
	if (!function_exists('jr_strtolower')) {
		function jr_strtolower($str)
		{
			if (!function_exists('mb_strtolower')) {
				$result = strtolower($str);
			} else {
				$result = mb_strtolower($str);
			}

			return $result;
		}
	}


/**
 * @package Castor\Core\Functions
 *
 *          Where possible use mb_string functionality to return uppercase words
 *
 */
	if (!function_exists('jr_ucwords')) {
		function jr_ucwords($str)
		{
			return mb_ucwords($str);
		}
	}


/**
 *
 * @package Castor\Core\Functions
 *
 *          A roll-your-own implementation of mb_ucwords
 *
 */
if (!function_exists('mb_ucwords')) {
	function mb_ucwords($str)
	{
		return mb_convert_case($str, MB_CASE_TITLE, 'UTF-8');
	}
}

