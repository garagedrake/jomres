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

	if (!function_exists('get_search_form_element_autocomplete')) {
		function get_search_form_element_autocomplete()
		{
			jr_import('castor_data_sources');
			$castor_data_sources = new castor_data_sources();
			$countries = $castor_data_sources->get_data(get_showtime('lang'), 'countries');
			$regions = $castor_data_sources->get_data(get_showtime('lang'), 'regions');
			$towns = $castor_data_sources->get_data(get_showtime('lang'), 'towns');

			$place_names = array();
			foreach ($countries as $country) {
				$place_names[] = array('LABEL' => $country->countryname , 'VALUE' => $country->countrycode);
			}
			foreach ($regions as $region) {
				$place_names[] = array('LABEL' => $region->regionname , 'VALUE' =>$region->regionname);
			}
			foreach ($towns as $town) {
				$place_names[] = array('LABEL' => $town , 'VALUE' => $town);
			}

			$pageoutput[ ] = array();
			$tmpl = new patTemplate();
			$tmpl->setRoot(CASTOR_TEMPLATEPATH_FRONTEND);
			$tmpl->addRows('pageoutput', $pageoutput);
			$tmpl->addRows('place_names', $place_names);

			$tmpl->readTemplatesFromInput('search_form_element_autocomplete.html');

			return $tmpl->getParsedTemplate();
		}
	}


