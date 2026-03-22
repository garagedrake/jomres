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
	 * @package Castor\Core\Classes
	 *
	 */
	#[AllowDynamicProperties]
class castor_messages
{

	/**
	 *
	 *
	 *
	 */

	public function __construct()
	{
		$this->castor_messages = array();
		
		if (isset($_COOKIE[ 'castor_messages' ])) {
			$messages = $_COOKIE[ 'castor_messages' ];
			
			foreach ($messages as $msg_id => $msg) {
				$this->castor_messages[$msg_id] = json_decode(stripslashes($msg), true);
			}
		}
	}
	
	/**
	 *
	 *
	 *
	 */

	public function get_messages()
	{
		foreach ($this->castor_messages as $key => $val) {
			$index = 'castor_messages['.$key.']';
			setcookie($index, '', time() - 3600);
		}

		return $this->castor_messages;
	}
	
	/**
	 *
	 *
	 *
	 */

	public function set_message($message = '', $class = 'alert-info')
	{
		if ($message == '') {
			return false;
		}
		
		$counter = count($this->castor_messages) + 1;
		$index = 'castor_messages['.$counter.']';
		$data = array(
			'message' => htmlspecialchars($message),
			'class' => htmlspecialchars($class)
		);
		
		setcookie($index, json_encode($data, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE), time() + 5, '/');
		
		return true;
	}
}

