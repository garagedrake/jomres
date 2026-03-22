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

class j06000selectcombo
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
		jr_import('jomSearch');
		/*
		if( !function_exists('json_encode') )
			{
			require_once(CASTOR_LIBRARIES_ABSPATH.'json'.JRDS.'JSON.php');
			function json_encode($data)
				{
				$json = new Services_JSON();
				return( $json->encode($data) );
				}
			}
		*/
		$allPropertyLocations = prepGeographicSearch();
		$filter = castorGetParam($_REQUEST, '_name', '');
		$q = castorGetParam($_REQUEST, '_value', '');
		$searchAll = jr_gettext('_CASTOR_SEARCH_ALL', '_CASTOR_SEARCH_ALL', false, false);

		if ($filter == 'country') {
			$regions = array();
			$regions[ ] = $searchAll;
			foreach ($allPropertyLocations as $locations) {
				foreach ($locations as $location) {
					$t = $location[ 'region' ];
					if ($location[ 'country' ] == $q) {
						$regions[ $t ] = castor_decode($t);
					}
					// else if ($q==$searchAll)
					// $regions[$t]=$t;
				}
			}
			if (!empty($regions)) {
				$ret_array = array_unique($regions);
			}
		}

		if ($filter == 'region') {
			$towns = array();
			$towns[ ] = $searchAll;
			foreach ($allPropertyLocations as $locations) {
				foreach ($locations as $location) {
					$t = $location[ 'property_town' ];
					if ($location[ 'region' ] == $q) {
						$towns[ $t ] = str_replace('&#39;', "'", $t);
					}
					// else if ($q==$searchAll)
					// $towns[$t]=$t;
				}
			}
			if (!empty($towns)) {
				$ret_array = array_unique($towns);
			}
		}
		$ret_json = array();

		foreach ($ret_array as $key => $val) {
			$ret_json[ ] = array($key => $val);
		}
		$ret_string = json_encode($ret_json);

		echo $ret_string;
	}


	public function getRetVals()
	{
		return null;
	}
}

