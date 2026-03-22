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
class castor_countries
{

	/**
	 *
	 *
	 *
	 */

	public function __construct()
	{
		$this->countries = array();
		$this->used_countries = false;
		
		$this->get_used_property_countries();
	}
	
	/**
	 *
	 *
	 *
	 */

	//get countries used by properties in the system
	public function get_used_property_countries()
	{
		if (is_array($this->used_countries)) {
			return true;
		}
		
		$this->used_countries = array();

		$query = "SELECT `id`,`countrycode`,`countryname` FROM #__castor_countries WHERE `countrycode` IN (SELECT DISTINCT `property_country` FROM #__castor_propertys) ORDER BY `countryname`";
		$result = doSelectSql($query);
		
		if (!empty($result)) {
			foreach ($result as $country) {
				$this->used_countries[ strtoupper($country->countrycode) ] = array('id' => $country->id, 'countrycode' => strtoupper($country->countrycode), 'countryname' => jr_gettext('_CASTOR_CUSTOMTEXT_COUNTRIES_'.$country->id, $country->countryname, false));
			}
		}
		
		unset($result);

		return true;
	}
		
	/**
	 *
	 *
	 *
	 */

	//get all countries in the system
	public function get_all_countries()
	{
		if (!empty($this->countries)) {
			return true;
		}

		$query = "SELECT `id`,`countrycode`,`countryname` FROM #__castor_countries ORDER BY `countryname`";
		$result = doSelectSql($query);
		
		if (!empty($result)) {
			foreach ($result as $country) {
				$this->countries[ strtoupper($country->countrycode) ] = array('id' => $country->id, 'countrycode' => strtoupper($country->countrycode), 'countryname' => jr_gettext('_CASTOR_CUSTOMTEXT_COUNTRIES_'.$country->id, $country->countryname, false));
			}
		}
		
		unset($result);

		return true;
	}
	
	/**
	 *
	 *
	 *
	 */

	//get country by id, used on edit country admin page
	public function get_country_by_id($id = 0)
	{
		if ($id == 0) {
			return false;
		}
		
		$query = "SELECT `id`,`countrycode`,`countryname` FROM #__castor_countries WHERE `id` = " . (int)$id;
		$result = doSelectSql($query, 2);
		
		if (!empty($result)) {
			return $result;
		}

		return false;
	}
}

