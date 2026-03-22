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

class j16000save_tax_rule
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
	 
	public function __construct()
	{
		// Must be in all minicomponents. Minicomponents with templates that can contain editable text should run $this->template_touch() else just return
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = false;

			return;
		}

		$id = intval(castorGetParam($_POST, 'id', 0));
		$tax_rate = castorGetParam($_POST, 'tax_rate', '');
		$country = castorGetParam($_POST, 'guest_country', '');
		
		$castor_countries = castor_singleton_abstract::getInstance('castor_countries');
		$castor_countries->get_all_countries();
		
		foreach ($castor_countries->countries as $c) {
			if ($c['countrycode'] == $country) {
				$country_id = $c['id'];
			}
		}
		
		$region_id = castorGetParam($_POST, 'region', 0);

		if ($id == 0) {
			$query = "INSERT INTO #__castor_tax_rules SET `tax_rate_id`='".(int) $tax_rate."',`country_id`='".(int) $country_id."',`region_id`='".(int) $region_id."'";
			doInsertSql($query);
		} else {
			$query = "UPDATE #__castor_tax_rules SET `tax_rate_id`='".(int) $tax_rate."',`country_id`='".(int) $country_id."',`region_id`='".(int) $region_id."' WHERE id=".(int) $id;
			doInsertSql($query);
		}

		castorRedirect(castorURL(CASTOR_SITEPAGE_URL_ADMIN.'&task=list_tax_rules'), '');
	}


	public function getRetVals()
	{
		return null;
	}
}

