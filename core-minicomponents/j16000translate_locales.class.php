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

class j16000translate_locales
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
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = false;

			return;
		}
		if (!translation_user_check()) {
			return;
		}
		echo '<h2>'.jr_gettext('_CASTOR_TOUCHTEMPLATES', '_CASTOR_TOUCHTEMPLATES', false).' - '.get_showtime('lang').'</h2><br/>';

		$output = array();

		$query = 'SELECT `id`, `countrycode`, `countryname` FROM #__castor_countries ORDER BY countryname';
		$countryList = doSelectSql($query);
		if (!empty($countryList)) {
			foreach ($countryList as $country) {
				$output[] = jr_gettext('_CASTOR_CUSTOMTEXT_COUNTRIES_'.$country->id, $country->countryname);
			}
		}

		$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
		$jrConfig = $siteConfig->get();
		if ($jrConfig[ 'region_names_are_translatable' ] == '1') {
			$query = 'SELECT `id`, `countrycode`, `regionname` FROM #__castor_regions ORDER BY countrycode, regionname';
			$regionList = doSelectSql($query);
			if (!empty($regionList)) {
				foreach ($regionList as $region) {
					$output[] = jr_gettext('_CASTOR_CUSTOMTEXT_REGIONS_'.$region->id, $region->regionname);
				}
			}
		}

		foreach ($output as $o) {
			echo $o;
			echo '<br/>';
		}
	}


	public function getRetVals()
	{
		return null;
	}
}

