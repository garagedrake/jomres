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
use ImageOptimizer\OptimizerFactory;

defined('_CASTOR_INITCHECK') or die('');
// ################################################################
	
	/**
	 *
	 * @package Castor\Core\Classes
	 *
	 */
	#[AllowDynamicProperties]
class castor_media_centre_images_optimizer
{
	protected $optimizer;
	
	/**
	 *
	 *
	 *
	 */

	public function __construct()
	{
		$factory = new OptimizerFactory();
		$this->optimizer = $factory->get();
	}
		
	/**
	 *
	 *
	 *
	 */

	public static function getInstance()
	{
		if (!self::$configInstance) {
			self::$configInstance = new self();
		}

		return self::$configInstance;
	}
		
	/**
	 *
	 *
	 *
	 */

	public function optimize($filepath = '')
	{
		if ($filepath == '') {
			return true;
		}
		
		return $this->optimizer->optimize($filepath);
	}
}

