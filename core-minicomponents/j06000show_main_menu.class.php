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
	#[AllowDynamicProperties]
	/**
	 * @package Castor\Core\Minicomponents
	 *
	 *
	 */

class j06000show_main_menu
{

	/**
	 *
	 * Constructor
	 *
	 * Main functionality of the Minicomponent
	 *
	 *
	 *
	 */
	 
	public function __construct($componentArgs)
	{
		// Must be in all minicomponents. Minicomponents with templates that can contain editable text should run $this->template_touch() else just return
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = false;
			$this->shortcode_data = array(
				'task' => 'show_main_menu',
				'info' => '_CASTOR_SHORTCODES_06000SHOW_MAIN_MENU',
				'arguments' => array()
				);

			return;
		}

		if (isset($componentArgs['menu_location_div_id'])) {
			$main_menu_location = $componentArgs['menu_location_div_id'];
		} elseif(isset($_REQUEST['menu_location_div_id'])) {
			$main_menu_location = castorGetParam($_REQUEST, 'menu_location_div_id', 'main_menu_location');
		} else {
			echo '<div id="main_menu_location"></div>';
			$main_menu_location = 'main_menu_location';
		}

		set_showtime('menu_location_div_id', $main_menu_location);
		$disabled = get_showtime('main_menu_disabled');

		if (isset($disabled) && $disabled == true) {
			return;
		}

		$MiniComponents->triggerEvent('09995');
		echo $MiniComponents->specificEvent('09997', 'menu', array());
	}


	public function getRetVals()
	{
		return null;
	}
}

