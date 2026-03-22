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
	if (!function_exists('get_search_form_element_stars')) {
		function get_search_form_element_stars()
		{
			$lang = get_showtime('lang');
			jr_import('castor_data_sources');
			$castor_data_sources = new castor_data_sources();
			$stars = array ( 0 => $castor_data_sources->get_data($lang, 'stars'));

			$stars_output = [];
			foreach ($stars[0] as $key => $val) {
				if ($key == 0) {
					$stars_output[] = array (
						'VALUE' => $key ,
						'STARS' => jr_gettext('_CASTOR_SEARCH_ALL', '_CASTOR_SEARCH_ALL', false, false) ,
						'NUMBER_OF_PROPERTIES' => '' ,
						'PROPERTIES' => ''
					);
				} else {
					$stars_output[] = array (
						'VALUE' => $key ,
						'STARS' => $key ,
						'NUMBER_OF_PROPERTIES' => $val ,
						'PROPERTIES' => jr_gettext('_CASTOR_CUSTOMCODE_CASTORMAINMENU_RECEPTION_PROPERTIES', '_CASTOR_CUSTOMCODE_CASTORMAINMENU_RECEPTION_PROPERTIES', false, false)
					);
				}
			}

			$pageoutput = [];
			$output = [];

			$output['LABEL'] =jr_gettext('_CASTOR_COM_A_INTEGRATEDSEARCH_BYTARS', '_CASTOR_COM_A_INTEGRATEDSEARCH_BYTARS', false, false);

			$pageoutput[ ] = $output;
			$tmpl = new patTemplate();
			$tmpl->setRoot(CASTOR_TEMPLATEPATH_FRONTEND);
			$tmpl->addRows('pageoutput', $pageoutput);
			$tmpl->addRows('stars', $stars_output);

			$tmpl->readTemplatesFromInput('search_form_element_stars.html');
			return $tmpl->getParsedTemplate();
		}
	}


