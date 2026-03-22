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

class j06002save_child_rate
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

			return;
		}


		$defaultProperty = getDefaultProperty();

		$id					= (int) $_REQUEST['id'];
		$age_from			= (int) $_REQUEST['age_from'];
		$age_to				= (int) $_REQUEST['age_to'];
		$price				= convert_entered_price_into_safe_float(castorGetParam($_REQUEST, 'price', ''));
		$model				= (string) castorGetParam($_REQUEST, 'model', '');

		if ($model != 'per_night' && $model != 'per_stay') {
			throw new Exception('Error: Invalid model sent, possible hack attempt');
		}

		jr_import('castor_child_rates');
		$castor_child_rates = new castor_child_rates($defaultProperty);

		$castor_child_rates->set_child_rate($id, $age_from, $age_to, $price, $model);
		$castor_child_rates->save_child_rates();

		$webhook_notification						   	= new stdClass();
		$webhook_notification->webhook_event			= 'property_state_change';
		$webhook_notification->webhook_event_description= 'A catchall webhook notification which notes that the property state has changed. Primarily designed for caching features to remove/refresh cache elements';
		$webhook_notification->data					 	= new stdClass();
		$webhook_notification->data->property_uid	   	= $defaultProperty;
		add_webhook_notification($webhook_notification);

		castorRedirect(castorURL(CASTOR_SITEPAGE_URL.'&task=child_policies'), '');
	}

	public function convert_greaterthans($string)
	{
		$string = str_replace('&#38;gt;', '>', $string);

		return $string;
	}
	

	public function getRetVals()
	{
		return null;
	}
}

