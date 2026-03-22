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

class j06000search
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
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = false;
			$this->shortcode_data = array(
				'task' => 'search',
				'info' => '_CASTOR_SHORTCODES_06000SEARCH',
				'arguments' => array(
					array(
						'argument' => 'country',
						'arg_info' => '_CASTOR_SHORTCODES_06000SEARCH_ARG_COUNTRY',
						'arg_example' => 'GB',
					),
					array(
						'argument' => 'region',
						'arg_info' => '_CASTOR_SHORTCODES_06000SEARCH_ARG_REGION',
						'arg_example' => '1111',
					),
					array(
						'argument' => 'town',
						'arg_info' => '_CASTOR_SHORTCODES_06000SEARCH_ARG_TOWN',
						'arg_example' => 'Torquay',
					),
					array(
						'argument' => 'feature_uids',
						'arg_info' => '_CASTOR_SHORTCODES_06000SEARCH_ARG_FEATURE_UIDS',
						'arg_example' => '32',
					),
					array(
						'argument' => 'room_type',
						'arg_info' => '_CASTOR_SHORTCODES_06000SEARCH_ARG_ROOM_TYPE',
						'arg_example' => '2',
					),
					array(
						'argument' => 'ptype',
						'arg_info' => '_CASTOR_SHORTCODES_06000SEARCH_ARG_PTYPE',
						'arg_example' => '1',
					),
					array(
						'argument' => 'priceranges',
						'arg_info' => '_CASTOR_SHORTCODES_06000SEARCH_ARG_PRICERANGES',
						'arg_example' => '100-200',
					),
					array(
						'argument' => 'guestnumber',
						'arg_info' => '_CASTOR_SHORTCODES_06000SEARCH_ARG_GUESTNUMBER',
						'arg_example' => '1',
					),
					array(
						'argument' => 'stars',
						'arg_info' => '_CASTOR_SHORTCODES_06000SEARCH_ARG_STARS',
						'arg_example' => '4',
					),
					array(
						'argument' => 'arrivalDate',
						'arg_info' => '_CASTOR_SHORTCODES_06000SEARCH_ARG_ARRIVALDATE',
						'arg_example' => date('Y/m/d', strtotime(date('Y/m/d').'+1 day')),
					),
					array(
						'argument' => 'departureDate',
						'arg_info' => '_CASTOR_SHORTCODES_06000SEARCH_ARG_DEPARTUREDATE',
						'arg_example' => date('Y/m/d', strtotime(date('Y/m/d').'+2 days')),
					),
					array(
						'argument' => 'cat_id',
						'arg_info' => '_CASTOR_SHORTCODES_06000SEARCH_ARG_CATEGORY',
						'arg_example' => '1',
					),
				)
			);
			return;
		}
		$this->retVals = '';
		$thisJRUser = castor_singleton_abstract::getInstance('jr_user');
		$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
		$jrConfig = $siteConfig->get();
		$option = castorGetParam($_REQUEST, 'option', '');

		$data_only = false;
		if (isset($_REQUEST[ 'dataonly' ])) {
			$data_only = true;
		}

		unset($sch);
		$doSearch = false;
		$includedInModule = false;
		$calledByModule = '';
		$searchRestarted = false;
		$showSearchOptions = true;

		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');

		if (isset($componentArgs[ 'doSearch' ])) {
			$doSearch = $componentArgs[ 'doSearch' ];
		}
		if (isset($componentArgs[ 'includedInModule' ])) {
			$includedInModule = $componentArgs[ 'includedInModule' ];
		}
		if (isset($componentArgs[ 'calledByModule' ])) {
			$calledByModule = $componentArgs[ 'calledByModule' ];
		} else {
			$calledByModule = castorGetParam($_REQUEST, 'calledByModule', '');
		}

		if (!$includedInModule) {
			$doSearch = true;
		} else {
			$tmpBookingHandler = castor_singleton_abstract::getInstance('castor_temp_booking_handler');
			$tmpBookingHandler->initBookingSession();
			$showSearchOptions = true;
		}

		if ($calledByModule == '' && !isset($_REQUEST[ 'next' ])) {
			if ($jrConfig[ 'integratedSearch_enable' ] == '1') {
				$calledByModule = 'mod_jomsearch_m0';
			}
		}

		$calledByModule = getEscaped($calledByModule);

		$infoIcon = CASTOR_IMAGES_RELPATH.'information.png';
		if (isset($componentArgs['form_elements'])) {
			$output = $componentArgs['form_elements']; // Allows calling scripts to add form elements that are then handed to the template without needing to make any other changes to this script
		} else {
			$output = array();
		}

		$pageoutput = array();
		$showButton = false;
		$searchAll = jr_gettext('_CASTOR_SEARCH_ALL', '_CASTOR_SEARCH_ALL', false, false);

		jr_import('jomSearch');

		$sch = new jomSearch($calledByModule, $includedInModule);
		if ($sch->some_published_properties_exist === false) {
			echo '<div class="alert alert-danger" role="alert">Error, no published properties exist in the installation. You need at least one published property before you can use the search feature</div>';
			return;
		}

		$sch->searchAll = $searchAll;
		$searchOptions = $sch->searchOptions;

		if (isset($componentArgs['template_file'])) {
			$sch->templateFile = $componentArgs['template_file']; // Send a custom filename which is stored in the theme/com_castor/html directory
		}


		$h = '<input type="hidden" name="calledByModule" value="'.$sch->calledByModule.'"/><input type="hidden" name="Itemid" value="'.get_showtime('castorItemid').'"/>';

		if (this_cms_is_wordpress()) {
			$h = '<input type="hidden" name="calledByModule" value="'.$sch->calledByModule.'"/><input type="hidden" name="page_id" value="'.get_showtime('castorItemid').'"/>';
		}

		$output[ 'HIDDEN' ] = $h;
		$castorSearchFormname = $sch->formname;
		$searchOutput = $sch->searchOutput;
		$featurecols = $sch->featurecols;

		$metaTitle = '';

		$unwanted = array('%', "'", '"');

		$propertyname = castorGetParam($_REQUEST, 'propertyname', '');

		if ($propertyname != '') {
			if ($propertyname == $searchAll) {
				$sch->filter[ 'propertyname' ] = '%';
			} else {
				$sch->filter[ 'propertyname' ] = $propertyname;
				$sch->filter[ 'propertyname' ] = str_replace($unwanted, '', $sch->filter[ 'propertyname' ]);
				$metaTitle .= ' '.htmlspecialchars_decode($sch->filter[ 'propertyname' ], ENT_QUOTES);
			}
		}

		$country = castorGetParam($_REQUEST, 'country', '');

		if ($country != '') {
			if ($country == $searchAll) {
				$sch->filter[ 'country' ] = '%';
			} else {
				$sch->filter[ 'country' ] = $country;
				$sch->filter[ 'country' ] = str_replace($unwanted, '', $sch->filter[ 'country' ]);
				$metaTitle .= ' '.getSimpleCountry($sch->filter[ 'country' ]);
			}
		}

		$region = castorGetParam($_REQUEST, 'region', '');

		if ($region != '') {
			if ($region == $searchAll) {
				$sch->filter[ 'region' ] = '%';
			} else {
				$sch->filter[ 'region' ] = $region;
				$sch->filter[ 'region' ] = str_replace($unwanted, '', $sch->filter[ 'region' ]);
				$region_name = find_region_name($sch->filter[ 'region' ]);
				$metaTitle .= ' '.htmlspecialchars_decode($region_name, ENT_QUOTES);
			}
		}

		$town = castorGetParam($_REQUEST, 'town', '');

		if ($town != '') {
			if ($town == $searchAll) {
				$sch->filter[ 'town' ] = '%';
			} else {
				$sch->filter[ 'town' ] = $town;
				$sch->filter[ 'town' ] = str_replace($unwanted, '', $sch->filter[ 'town' ]);
				$metaTitle .= ' '.htmlspecialchars_decode($sch->filter[ 'town' ], ENT_QUOTES);
			}
		}

		if (!empty($_REQUEST[ 'description' ])) {
			if ($_REQUEST[ 'description' ] != '') {
				$sch->filter[ 'description' ] = castorGetParam($_REQUEST, 'description', '');
				$sch->filter[ 'description' ] = str_replace($unwanted, '', $sch->filter[ 'description' ]);
			}
		}

		if (!empty($_REQUEST[ 'feature_uids' ])) {
			if ($_REQUEST[ 'feature_uids' ] == $searchAll) {
				$sch->filter[ 'feature_uids' ] = '%';
			} else {
				$sch->filter[ 'feature_uids' ] = castorGetParam($_REQUEST, 'feature_uids', array());
			}
		}

		if (!empty($_REQUEST[ 'room_type' ])) {
			if ($_REQUEST[ 'room_type' ] == $searchAll) {
				$sch->filter[ 'room_type' ] = '%';
			} else {
				$sch->filter[ 'room_type' ] = (int) castorGetParam($_REQUEST, 'room_type', 0);
			}
		}

		if (!empty($_REQUEST[ 'ptype' ])) {
			if ($_REQUEST[ 'ptype' ] == $searchAll) {
				$sch->filter[ 'ptype' ] = '%';
			} else {
				$sch->filter[ 'ptype' ] = (int) castorGetParam($_REQUEST, 'ptype', '');
			}
		}

		if (!empty($_REQUEST[ 'cat_id' ])) {
			$cat_id = castorGetParam($_REQUEST, 'cat_id', 0);

			if ($cat_id == 0) {
				$sch->filter[ 'cat_id' ] = '%';
			} else {
				$sch->filter[ 'cat_id' ] = $cat_id;
			}
		}

		//	$_REQUEST['priceranges']="0-50";
		if (!empty($_REQUEST[ 'priceranges' ])) {
			if ($_REQUEST[ 'priceranges' ] == $searchAll) {
				$sch->filter[ 'priceranges' ] = '%';
			} else {
				$ranges = castorGetParam($_REQUEST, 'priceranges', '');
				$rangeArr = explode('-', $ranges);
				$sch->filter[ 'priceranges' ] = array('from' => (int) $rangeArr[ 0 ], 'to' => (int) $rangeArr[ 1 ], 'raw' => $ranges);
			}
		}

		if (isset($_REQUEST[ 'pricerange_value_from' ]) && isset($_REQUEST[ 'pricerange_value_to' ] )) {
			$rangefrom = castorGetParam($_REQUEST, 'pricerange_value_from', 0);
			$rangeto = castorGetParam($_REQUEST, 'pricerange_value_to', 0);
			$sch->filter[ 'priceranges' ] = array('from' => (int)$rangefrom , 'to' => (int)$rangeto, 'raw' => [] );
		}


		if (!empty($_REQUEST[ 'guestnumber' ])) {
			if ($_REQUEST[ 'guestnumber' ] == $searchAll) {
				$sch->filter[ 'guestnumber' ] = '%';
			} else {
				$sch->filter[ 'guestnumber' ] = (int) castorGetParam($_REQUEST, 'guestnumber', '');
			}
		}

		if (!empty($_REQUEST[ 'sleeps_adults' ])) {
			if ($_REQUEST[ 'sleeps_adults' ] == $searchAll) {
				$sch->filter[ 'sleeps_adults' ] = '%';
			} else {
				$sch->filter[ 'sleeps_adults' ] = (int) castorGetParam($_REQUEST, 'sleeps_adults', '');
			}
		}

		if (!empty($_REQUEST[ 'sleeps_children' ])) {
			if ($_REQUEST[ 'sleeps_children' ] == $searchAll) {
				$sch->filter[ 'sleeps_children' ] = '%';
			} else {
				$sch->filter[ 'sleeps_children' ] = (int) castorGetParam($_REQUEST, 'sleeps_children', '');
			}
		}

		if (!empty($_REQUEST[ 'stars' ])) {
			if ($_REQUEST[ 'stars' ] == $searchAll) {
				$sch->filter[ 'stars' ] = '%';
			} else {

				foreach ($_REQUEST[ 'stars' ] as $k => $v) {
					$sch->filter[ 'stars' ][]  = (int) $v;
				}

				//$sch->filter[ 'stars' ] = (int) castorGetParam($_REQUEST, 'stars', '');
			}
		}

		if ($option == 'com_castor' && ($propertyname != '' || $country != '' || $region != '' || $town != '')) {
			castor_cmsspecific_setmetadata('title', $metaTitle);
		}

		if (!empty($_REQUEST[ 'arrivalDate' ]) && in_array('availability', $sch->searchOptions)) {
			$sch->filter[ 'arrival' ] = $sch->prep[ 'arrival' ];
			$sch->filter[ 'departure' ] = $sch->prep[ 'departure' ];
			$sch->filter[ 'arrival' ] = str_replace($unwanted, '', $sch->filter[ 'arrival' ]);
			$sch->filter[ 'departure' ] = str_replace($unwanted, '', $sch->filter[ 'departure' ]);
		}


		$output[ 'CASTOR_SEARCH_GEO_COUNTRYSEARCH' ] = jr_gettext('_CASTOR_SEARCH_GEO_COUNTRYSEARCH', '_CASTOR_SEARCH_GEO_COUNTRYSEARCH', false);
		$output[ 'CASTOR_SEARCH_GEO_REGIONSEARCH' ] = jr_gettext('_CASTOR_SEARCH_GEO_REGIONSEARCH', '_CASTOR_SEARCH_GEO_REGIONSEARCH', false);
		$output[ 'CASTOR_SEARCH_GEO_TOWNSEARCH' ] = jr_gettext('_CASTOR_SEARCH_GEO_TOWNSEARCH', '_CASTOR_SEARCH_GEO_TOWNSEARCH', false);
		$output[ 'CASTOR_SEARCH_DESCRIPTION_INFO' ] = jr_gettext('_CASTOR_SEARCH_DESCRIPTION_INFO', '_CASTOR_SEARCH_DESCRIPTION_INFO', false);
		$output[ 'CASTOR_SEARCH_DESCRIPTION_LABEL' ] = jr_gettext('_CASTOR_SEARCH_DESCRIPTION_LABEL', '_CASTOR_SEARCH_DESCRIPTION_LABEL', false);
		$output[ 'CASTOR_SEARCH_FEATURE_INFO' ] = jr_gettext('_CASTOR_SEARCH_FEATURE_INFO', '_CASTOR_SEARCH_FEATURE_INFO', false);
		$output[ 'CASTOR_SEARCH_RTYPES' ] = jr_gettext('_CASTOR_SEARCH_RTYPES', '_CASTOR_SEARCH_RTYPES', false);
		$output[ 'CASTOR_SEARCH_AVL_INFO' ] = jr_gettext('_CASTOR_SEARCH_AVL_INFO', '_CASTOR_SEARCH_AVL_INFO', false);
		$output[ 'HARRIVALDATE' ] = jr_gettext('_CASTOR_COM_MR_VIEWBOOKINGS_ARRIVAL', '_CASTOR_COM_MR_VIEWBOOKINGS_ARRIVAL', false);
		$output[ 'HDEPARTUREDATE' ] = jr_gettext('_CASTOR_COM_MR_VIEWBOOKINGS_DEPARTURE', '_CASTOR_COM_MR_VIEWBOOKINGS_DEPARTURE', false);
		$output[ 'CASTOR_SEARCH_PTYPES' ] = jr_gettext('_CASTOR_SEARCH_PTYPES', '_CASTOR_SEARCH_PTYPES', false);
		$output[ 'CASTOR_SEARCH_CATEGORY' ] = jr_gettext('_CASTOR_HCATEGORY', '_CASTOR_HCATEGORY', false);
		$output[ 'CASTOR_SEARCH_PRICERANGES' ] = jr_gettext('_CASTOR_SEARCH_PRICERANGES', '_CASTOR_SEARCH_PRICERANGES', false);
		$output[ 'HGUESTNUMBER' ] = jr_gettext('_CASTOR_SEARCH_GUESTNUMBER', '_CASTOR_SEARCH_GUESTNUMBER', false);
		$output[ 'HSTARS' ] = jr_gettext('_CASTOR_SEARCH_STARS', '_CASTOR_SEARCH_STARS', false);
		$output[ '_JRPORTAL_PROPERTIES_PROPERTYNAME' ] = jr_gettext('_JRPORTAL_PROPERTIES_PROPERTYNAME', '_JRPORTAL_PROPERTIES_PROPERTYNAME', false);
		$output[ '_CASTOR_PROPERTY_HCATEGORIES' ] = jr_gettext('_CASTOR_PROPERTY_HCATEGORIES', '_CASTOR_PROPERTY_HCATEGORIES', false);
		$output[ '_CASTOR_REVIEWS_RATING_2' ] = jr_gettext('_CASTOR_REVIEWS_RATING_2', '_CASTOR_REVIEWS_RATING_2', false);
		$output[ '_CASTOR_COM_A_RESET' ] = jr_gettext('_CASTOR_COM_A_RESET', '_CASTOR_COM_A_RESET', false);
		$output[ 'CASTOR_COM_A_ACCOMMODATES' ] = jr_gettext('CASTOR_COM_A_ACCOMMODATES', 'CASTOR_COM_A_ACCOMMODATES', false);
		$output[ '_CASTOR_SEARCH_FORM_ADULTS' ] = jr_gettext('_CASTOR_SEARCH_FORM_ADULTS', '_CASTOR_SEARCH_FORM_ADULTS', false);
		$output[ '_CASTOR_SEARCH_FORM_CHILDREN' ] = jr_gettext('_CASTOR_SEARCH_FORM_CHILDREN', '_CASTOR_SEARCH_FORM_CHILDREN', false);
		$output[ '_CASTOR_SEARCH_FORM_WHERE_TO_GO' ] = jr_gettext('_CASTOR_SEARCH_FORM_WHERE_TO_GO', '_CASTOR_SEARCH_FORM_WHERE_TO_GO', false);




		$output[ 'SUBMITURL' ] = castorURL(CASTOR_SITEPAGE_URL_NOSEF);
		$output[ 'FORMNAME' ] = $castorSearchFormname;

		$output[ 'SELECTCOMBO_HIDDENDROPDOWNS_TOWN' ] = '';

		if (!$data_only) {
			if (castor_bootstrap_version() == '5') {
				$select_css_class = 'form-select';
			} else {
				$select_css_class = 'inputbox search_dropdown';
			}

			// -------------------------------------------------------------------------------------------------------------------------------------------
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------
		if ($showSearchOptions) {
			if (!empty($sch->prep[ 'propertyname' ])) {
				$propertyname = array();
				if (empty($sch->filter[ 'propertyname' ])) {
					$selectOption = $sch->prep[ 'propertyname' ][ 0 ][ 'pn' ];
				} else {
					$selectOption = $sch->filter[ 'propertyname' ];
				}

				if ($searchOutput[ 'propertyname' ] == 'dropdown') {
					foreach ($sch->prep[ 'propertyname' ] as $property) {
						$propertyname[ ] = castorHTML::makeOption(castor_decode($property[ 'pn' ]), castor_decode($property[ 'pn' ]));
					}
					$output[ 'propertyname' ] = castorHTML::selectList($propertyname, 'propertyname', ' class="'.$select_css_class.'" ', 'value', 'text', $selectOption);
					$showButton = true;
				} else {
					$r = '';
					foreach ($sch->prep[ 'propertyname' ] as $property) {
						// you need to use special chars here otherwise the url will not work for non latin searches
						$l = htmlspecialchars(get_property_details_url($property[ 'puid' ], 'sefsafe'));
						$link = castorURL($l);
						$link = castorValidateUrl($link);
						$r .= '<a href="'.$link.'">'.castor_decode($property[ 'pn' ]).'</a>&nbsp;';
						if ($sch->cols == '1') {
							$r .= '<br>';
						}
					}
					$output[ 'propertyname' ] = $r;
				}
			} else {
				$output[ 'propertyname' ] = 'EMPTY';
			}
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------
		if ($showSearchOptions) {
			$countryArray = array();

			if (!empty($sch->prep[ 'country' ])) {
				if (empty($sch->filter[ 'country' ])) {
					$selectOption = $sch->prep[ 'country' ][ 0 ][ 'countrycode' ];
				} else {
					$selectOption = $sch->filter[ 'country' ];
				}

				if ($searchOutput[ 'country' ] == 'dropdown') {
					foreach ($sch->prep[ 'country' ] as $country) {
						$countryArray[ ] = castorHTML::makeOption($country[ 'countrycode' ], castor_decode($country[ 'countryname' ]));
					}

					$output[ 'country' ] = castorHTML::selectList($countryArray, 'country', ' class="'.$select_css_class.'" placeholder="'.$output[ 'CASTOR_SEARCH_GEO_COUNTRYSEARCH' ].'"', 'value', 'text', $selectOption);
					$showButton = true;
				} else {
					$r = '';
					foreach ($sch->prep[ 'country' ] as $country) {
						$l = htmlspecialchars(CASTOR_SITEPAGE_URL.'&calledByModule='.$calledByModule.'&country='.$country[ 'countrycode' ]);
						$link = castorURL($l);
						$link = castorValidateUrl($link);
						$r .= '<a href="'.$link.'">'.castor_decode($country[ 'countryname' ]).'</a>&nbsp;';
						if ($sch->cols == '1') {
							$r .= '<br>';
						}
					}
					$output[ 'country' ] = $r;
				}
			} else {
				$output[ 'country' ] = 'EMPTY';
			}
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------
		if ($showSearchOptions) {
			$regionArray = array();
			if (!empty($sch->prep[ 'region' ])) {
				if (empty($sch->filter[ 'region' ])) {
					$selectOption = $sch->prep[ 'region' ][ 0 ][ 'region' ];
				} else {
					$selectOption = $sch->filter[ 'region' ];
				}

				if ($searchOutput[ 'region' ] == 'dropdown') {
					foreach ($sch->prep[ 'region' ] as $region) {
						$t = str_replace('&#39;', "'", $region[ 'region' ]); // This is important. php will not pass back, eg Sant&#39;Antimo, it will only pass back Sant, therefore we need to convert the &#39; to a ' to be shown in the url. When castorGetParam runs it'll convert the ' back to &#39; and the search will run successfully.

						if ($region['region'] == $searchAll || $t == '') {
							$region_id = $sch->searchAll;
							$region_name = $searchAll;
						} else {
							$region_id = find_region_id(castor_cmsspecific_stringURLSafe($t));
							$region_name = find_region_name($t);
						}

						$regionArray[ ] = castorHTML::makeOption($region_id, castor_decode($region_name));
					}
					$output[ 'region' ] = castorHTML::selectList($regionArray, 'region', ' class="'.$select_css_class.'" placeholder="'.$output[ 'CASTOR_SEARCH_GEO_REGIONSEARCH' ].'"', 'value', 'text', $selectOption);

					$showButton = true;
				} else {
					$r = '';
					foreach ($sch->prep[ 'region' ] as $region) {
						$t = str_replace('&#39;', "'", $region[ 'region' ]); // This is important. php will not pass back, eg Sant&#39;Antimo, it will only pass back Sant, therefore we need to convert the &#39; to a ' to be shown in the url. When castorGetParam runs it'll convert the ' back to &#39; and the search will run successfully.
						$region_id = find_region_id(castor_cmsspecific_stringURLSafe($t));
						$region_name = find_region_name($t);
						$l = htmlspecialchars(CASTOR_SITEPAGE_URL.'&calledByModule='.$calledByModule.'&region='.$region_id);
						$link = castorURL($l);
						$link = castorValidateUrl($link);
						$r .= '<a href="'.$link.'">'.castor_decode($region_name).'</a>&nbsp;';
						if ($sch->cols == '1') {
							$r .= '<br>';
						}
					}
					$output[ 'region' ] = $r;
				}
			} else {
				$output[ 'region' ] = 'EMPTY';
			}
		}
		// -------------------------------------------------------------------------------------------------------------------------------------------
		if ($showSearchOptions) {
			$townArray = array();

			$ta = $sch->prep[ 'town' ];
			if (!empty($sch->prep[ 'town' ])) {
				if (empty($sch->filter[ 'town' ])) {
					$selectOption = $ta[ 0 ][ 'town' ];
				} else {
					$selectOption = $sch->filter[ 'town' ];
				}
				if ($searchOutput[ 'town' ] == 'dropdown') {
					foreach ($ta as $town) {
						$t = str_replace('&#39;', "'", $town[ 'town' ]); // This is important. php will not pass back, eg Sant&#39;Antimo, it will only pass back Sant, therefore we need to convert the &#39; to a ' to be shown in the url. When castorGetParam runs it'll convert the ' back to &#39; and the search will run successfully.
						$townArray[ ] = castorHTML::makeOption($town[ 'town' ], castor_decode($t));
					}
					$output[ 'town' ] = castorHTML::selectList($townArray, 'town', ' class="'.$select_css_class.'" placeholder="'.$output[ 'CASTOR_SEARCH_GEO_TOWNSEARCH' ].'"', 'value', 'text', $selectOption);
					$showButton = true;
				} else {
					$r = '';
					foreach ($ta as $town) {
						$t = str_replace('&#39;', "'", $town[ 'town' ]); // This is important. php will not pass back, eg Sant&#39;Antimo, it will only pass back Sant, therefore we need to convert the &#39; to a ' to be shown in the url. When castorGetParam runs it'll convert the ' back to &#39; and the search will run successfully.
						$l = htmlspecialchars(CASTOR_SITEPAGE_URL.'&calledByModule='.$calledByModule.'&town='.$t);
						$link = castorURL($l);
						$link = castorValidateUrl($link);
						$r .= '<a href="'.$link.'">'.castor_decode($town[ 'town' ]).'</a>&nbsp;';
						if ($sch->cols == '1') {
							$r .= '<br>';
						}
					}
					$output[ 'town' ] = $r;
				}
			} else {
				$output[ 'town' ] = 'EMPTY';
			}
		}
		// -------------------------------------------------------------------------------------------------------------------------------------------
		if ($showSearchOptions) {
			if (empty($sch->filter[ 'description' ])) {
				$selectOption = $output[ 'CASTOR_SEARCH_DESCRIPTION_LABEL' ];
			} else {
				$selectOption = $sch->filter[ 'description' ];
			}
			$showButton = true;
			$output[ 'DESCRIPTION' ] = '<input class="form-control" type="text" name="description" value="'.$sch->filter[ 'description' ].'"  onfocus="if (this.value ==\''.$output[ 'CASTOR_SEARCH_DESCRIPTION_LABEL' ].'\') {this.value = \'\'}" />';
		}
		// -------------------------------------------------------------------------------------------------------------------------------------------
		if ($showSearchOptions) {
			$featureArray = array();
			if (!empty($sch->prep[ 'features' ])) {
				if (empty($sch->filter[ 'feature_uids' ])) {
					$selectOption = $sch->prep[ 'features' ][ 0 ];
				} else {
					$selectOption = $sch->filter[ 'feature_uids' ][ 0 ];
				}
				if ($searchOutput[ 'feature_uids' ] == 'dropdown') {
					foreach ($sch->prep[ 'features' ] as $feature) {
						$feature_abbv = jr_gettext('_CASTOR_CUSTOMTEXT_FEATURES_ABBV'.(int) $feature[ 'id' ], castor_decode($feature[ 'title' ]), false, false);
						$featureArray[ ] = castorHTML::makeOption($feature[ 'id' ], castor_decode($feature_abbv));
					}
					$output[ 'feature' ] = castorHTML::selectList($featureArray, 'feature_uids[]', ' class="'.$select_css_class.'" placeholder="'.$output[ 'CASTOR_SEARCH_FEATURE_INFO' ].'"', 'value', 'text', $selectOption);
				} else { // Show the features as javascript popup
					$r = '';
					$counter = 0;
					array_shift($sch->prep[ 'features' ]); // Gets rid of the "searchAll" option
					foreach ($sch->prep[ 'features' ] as $feature) {
						++$counter;
						$pid = $feature[ 'id' ];
						$ischecked = '';
						if (is_array($sch->filter[ 'feature_uids' ])) {
							if (in_array($pid, $sch->filter[ 'feature_uids' ])) {
								$ischecked = 'checked';
							}
						}

						$feature_abbv = jr_gettext('_CASTOR_CUSTOMTEXT_FEATURES_ABBV'.(int) $feature[ 'id' ], castor_decode($feature[ 'title' ]), false, false);
						$feature_desc = jr_gettext('_CASTOR_CUSTOMTEXT_FEATURES_DESC'.(int) $feature[ 'id' ], castor_decode($feature[ 'description' ]), false, false);

						$tmp = castor_makeTooltip($feature_abbv, $feature_abbv, $feature_desc, $feature[ 'image' ], '', 'property_feature', array());

						$rows[ ] = $r;
						$r .= '<div style="float : left;" >'.$tmp.'<input type="checkbox" name="feature_uids[]" value="'.$pid.'" '.$ischecked.' /></div>';
					}
					$output[ 'feature' ] = $r;
				}
			} else {
				$output[ 'feature' ] = 'EMPTY';
			}
			$showButton = true;
		}
		// -------------------------------------------------------------------------------------------------------------------------------------------
		if ($showSearchOptions) {
			$rtypeArray = array();

			if (!empty($sch->prep[ 'rtypes' ])) {
				$r = '';
				if (empty($sch->filter[ 'room_type' ])) {
					$selectOption = $sch->prep[ 'rtypes' ][ 0 ][ 'id' ];
				} else {
					$selectOption = $sch->filter[ 'room_type' ];
				}
				if ($searchOutput[ 'room_type' ] == 'dropdown') {
					foreach ($sch->prep[ 'rtypes' ] as $rtype) {
						$roomClassAbbv = jr_gettext('_CASTOR_CUSTOMTEXT_ROOMTYPES_ABBV'.(int) $rtype[ 'id' ], castor_decode($rtype[ 'title' ]), false, false);
						$rtypeArray[ ] = castorHTML::makeOption($rtype[ 'id' ], $roomClassAbbv);
					}
					$output[ 'room_type' ] = castorHTML::selectList($rtypeArray, 'room_type', ' class="'.$select_css_class.'" placeholder="'.$output[ 'CASTOR_SEARCH_RTYPES' ].'"', 'value', 'text', $selectOption);
					$showButton = true;
				} else {
					foreach ($sch->prep[ 'rtypes' ] as $room_type) {
						$roomClassAbbv = jr_gettext('_CASTOR_CUSTOMTEXT_ROOMTYPES_ABBV'.(int) $room_type[ 'id' ], castor_decode($room_type[ 'title' ]), false, false);
						$l = htmlspecialchars(CASTOR_SITEPAGE_URL.'&calledByModule='.$calledByModule.'&room_type='.$room_type[ 'id' ]);
						$link = castorURL($l);
						$link = castorValidateUrl($link);
						$r .= '<a href="'.$link.'">'.$roomClassAbbv.'</a>&nbsp;';
						if ($sch->cols == '1') {
							$r .= '<br>';
						}
					}
					$output[ 'room_type' ] = $r;
				}
			} else {
				$output[ 'room_type' ] = 'EMPTY';
			}
		}
		// -------------------------------------------------------------------------------------------------------------------------------------------
		if ($showSearchOptions) {
			$ptypeArray = array();
			if (!empty($sch->prep[ 'ptypes' ])) {
				if (empty($sch->filter[ 'ptype' ])) {
					$selectOption = $sch->prep[ 'ptypes' ][ 0 ][ 'id' ];
				} else {
					$selectOption = $sch->filter[ 'ptype' ];
				}
				if ($searchOutput[ 'ptype' ] == 'dropdown') {
					foreach ($sch->prep[ 'ptypes' ] as $ptype) {
						$ptypeAbbv = jr_gettext('_CASTOR_CUSTOMTEXT_PROPERTYTYPE'.$ptype[ 'id' ], castor_decode($ptype[ 'ptype' ]), false, false);

						$ptypeArray[ ] = castorHTML::makeOption($ptype[ 'id' ], $ptypeAbbv);
					}
					$output[ 'ptype' ] = castorHTML::selectList($ptypeArray, 'ptype', '  class="'.$select_css_class.'" placeholder="'.$output[ 'CASTOR_SEARCH_PTYPES' ].'"', 'value', 'text', $selectOption);
					$showButton = true;
				} else {
					$r = '';
					foreach ($sch->prep[ 'ptypes' ] as $ptype) {
						$ptypeAbbv = jr_gettext('_CASTOR_CUSTOMTEXT_PROPERTYTYPE'.$ptype[ 'id' ], castor_decode($ptype[ 'ptype' ]), true, true);
						//echo $ptypeAbbv;
						$l = htmlspecialchars(CASTOR_SITEPAGE_URL.'&calledByModule='.$calledByModule.'&ptype='.$ptype[ 'id' ]);
						$link = castorURL($l);
						$link = castorValidateUrl($link);
						$r .= '<a href="'.$link.'">'.$ptypeAbbv.'</a>';
						if ($sch->cols == '1') {
							$r .= '<br>';
						}
					}
					$output[ 'ptype' ] = $r;
				}
			} else {
				$output[ 'ptype' ] = 'EMPTY';
			}
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------
		if ($showSearchOptions) {
			$categoriesArray = array();
			if (!empty($sch->prep[ 'categories' ])) {
				if (empty($sch->filter[ 'categories' ])) {
					$selectOption = $sch->prep[ 'categories' ][ 0 ][ 'id' ];
				} else {
					$selectOption = $sch->filter[ 'categories' ];
				}

				foreach ($sch->prep[ 'categories' ] as $c) {
					$categoriesArray[ ] = castorHTML::makeOption($c[ 'id' ], $c[ 'title' ]);
				}
				$output[ 'categories' ] = castorHTML::selectList($categoriesArray, 'cat_id', '  class="'.$select_css_class.'" placeholder="'.$output[ '_CASTOR_PROPERTY_HCATEGORIES' ].'"', 'value', 'text', $selectOption);
				$showButton = true;
			} else {
				$output[ 'categories' ] = 'EMPTY';
			}
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------
		if ($showSearchOptions) {
			$rangeArray = array();
			if (!empty($sch->prep[ 'priceranges' ])) {
				if (empty($sch->filter[ 'priceranges' ])) {
					$selectOption = $sch->prep[ 'priceranges' ][ 0 ];
				} else {
					$selectOption = $sch->filter[ 'priceranges' ][ 'raw' ];
				}
				foreach ($sch->prep[ 'priceranges' ] as $priceranges) {
					$rangeArray[ ] = castorHTML::makeOption($priceranges, $priceranges);
				}
				$output[ 'PRICERANGES' ] = castorHTML::selectList($rangeArray, 'priceranges', '  class="'.$select_css_class.'" ', 'value', 'text', $selectOption);
				$showButton = true;
			}
		}
		// -------------------------------------------------------------------------------------------------------------------------------------------

		$output[ 'ARRIVALDATE' ] = generateDateInput('arrivalDate', $sch->prep[ 'arrival' ], 'ad', true);
		$output[ 'ARRIVALDATE_LABEL_ID'] = get_showtime('date_input_label_id');
		$output[ 'DEPARTUREDATE' ] = generateDateInput('departureDate', $sch->prep[ 'departure' ], false, true, false);
		$output[ 'DEPARTUREDATE_LABEL_ID'] = get_showtime('departure_date_unique_id');

		$showButton = true;

		// -------------------------------------------------------------------------------------------------------------------------------------------
		if ($showSearchOptions) {
			$guestnumberArray = array();

			if (!empty($sch->prep[ 'guestnumber' ])) {
				if (empty($sch->filter[ 'guestnumber' ])) {
					$selectOption = $sch->prep[ 'guestnumber' ][ 0 ][ 'id' ];
				} else {
					$selectOption = $sch->filter[ 'guestnumber' ];
				}
				foreach ($sch->prep[ 'guestnumber' ] as $guestnumber) {
					$guestnumberArray[ ] = castorHTML::makeOption($guestnumber[ 'id' ], $guestnumber[ 'guestnumber' ]);
				}
				$output[ 'guestnumber' ] = castorHTML::selectList($guestnumberArray, 'guestnumber', '  class="'.$select_css_class.'" ', 'value', 'text', $selectOption);
				$showButton = true;
			} else {
				$output[ 'guestnumber' ] = 'EMPTY';
			}
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------
		if ($showSearchOptions) {
			$sleepsArray = array();

			if (!empty($sch->prep[ 'occupancy_levels' ])) {
				$output[ 'highest_adults' ] = $sch->prep[ 'occupancy_levels' ]["highestOccupancyLevels"] ["highest_adults"];
				$output[ 'highest_children' ] = $sch->prep[ 'occupancy_levels' ]["highestOccupancyLevels"] ["highest_children"];

				$showButton = true;
			} else {
				$output[ 'highest_adults' ] = 0;
				$output[ 'highest_children' ] = 0;
			}

			$sleeps_adults_selected = 2;

			if (isset($tmpBookingHandler->tmpsearch_data['ajax_search_composite_selections']['sleeps_adults'][0])) {
				$sleeps_adults_selected = $tmpBookingHandler->tmpsearch_data['ajax_search_composite_selections']['sleeps_adults'][0];
			}

			$sleeps_children_selected = 0;
			if (isset($tmpBookingHandler->tmpsearch_data['ajax_search_composite_selections']['sleeps_children'][0])) {
				$sleeps_children_selected = $tmpBookingHandler->tmpsearch_data['ajax_search_composite_selections']['sleeps_children'][0];
			}
			$output[ 'sleeps_adults_selected' ] = $sleeps_adults_selected;
			$output[ 'sleeps_children_selected' ] = $sleeps_children_selected;

			$output[ 'sleeps_adults_dropdown' ] = castorHTML::integerSelectList(0, $output[ 'highest_adults' ], 1, 'sleeps_adults', '', $sleeps_adults_selected);
			$output[ 'sleeps_children_dropdown' ] = castorHTML::integerSelectList(0, $output[ 'highest_children' ], 1, 'sleeps_children', '', $sleeps_children_selected);
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------

		if ($showSearchOptions) {
			$starsArray = array();
			if (!empty($sch->prep[ 'stars' ])) {
				if (empty($sch->filter[ 'stars' ])) {
					$selectOption = $sch->prep[ 'stars' ][ 0 ][ 'id' ];
				} else {
					$selectOption = $sch->filter[ 'stars' ];
				}

				foreach ($sch->prep[ 'stars' ] as $stars) {
					$starsArray[ ] = castorHTML::makeOption($stars[ 'id' ], $stars[ 'stars' ]);
				}
				$output[ 'stars' ] = castorHTML::selectList($starsArray, 'stars', '  class="'.$select_css_class.'" ', 'value', 'text', $selectOption);
				$showButton = true;
			} else {
				$output[ 'stars' ] = 'EMPTY';
			}
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------

		$output[ 'AUTOCOMPLETE' ] = get_search_form_element_autocomplete();

		// -------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------


		if ($doSearch) {
			$numberOfPropertiesInSystem = get_showtime('numberOfPropertiesInSystem');
			if ($numberOfPropertiesInSystem > 1 && !$includedInModule && $calledByModule == '' && !isset($_REQUEST[ 'next' ]) && get_showtime('task') == '') {
				$sch->jomSearch_random();
			} else {
				if (in_array('propertyname', $searchOptions) && !empty($sch->filter[ 'propertyname' ])) {
					$links = array();
					foreach ($sch->prep[ 'propertyname' ] as $p) {
						if ($p[ 'pn' ] == $sch->filter[ 'propertyname' ]) {
							$links[] = htmlspecialchars(get_property_details_url($p[ 'puid' ], 'sefsafe'));
						}
					}
					if (count($links) == 1) {
						castorRedirect(castorURL($links[0]), $saveMessage);
					} else {
						$sch->jomSearch_propertyname();
					}
				}

				if (!empty($sch->filter[ 'country' ])) {
					$sch->jomSearch_country();
				}
				if (!empty($sch->filter[ 'region' ])) {
					$sch->jomSearch_region();
				}
				if (!empty($sch->filter[ 'town' ])) {
					$sch->jomSearch_town();
				}
				if (!empty($sch->filter[ 'ptype' ])) {
					$sch->jomSearch_ptypes();
				}
				if (!empty($sch->filter[ 'cat_id' ])) {
					$sch->jomSearch_categories();
				}
				if (!empty($sch->filter[ 'guestnumber' ])) {
					$sch->jomSearch_guestnumber();
				}

				if (!empty($sch->filter[ 'sleeps_adults' ])) {
					$sch->jomSearch_sleeps_adults();
				}
				if (!empty($sch->filter[ 'sleeps_children' ])) {
					$sch->jomSearch_sleeps_children();
				}

				if (!empty($sch->filter[ 'stars' ])) {
					$sch->jomSearch_stars();
				}

				if (!empty($sch->filter[ 'priceranges' ])) {
					$sch->jomSearch_priceranges();
				}
				if (!empty($sch->filter[ 'feature_uids' ])) {
					$sch->jomSearch_features();
				}
				if (!empty($sch->filter[ 'room_type' ])) {
					$sch->jomSearch_roomtypes();
				}
				if (!empty($sch->filter[ 'description' ])) {
					$sch->jomSearch_description();
				}
				if (!empty($sch->filter[ 'arrival' ])) {
					$sch->jomSearch_availability();
				}

				if (isset($_REQUEST['autocomplete_field'])) {
					$sch->jomSearch_autocomplete();
				}
			}
		}
		if ($showButton == true) {
			$output[ 'SEARCHBLURB' ] = jr_gettext('_CASTOR_FRONT_MR_SEARCH_HERE', '_CASTOR_FRONT_MR_SEARCH_HERE');
			$output[ 'HSEARCH' ] = jr_gettext('_CASTOR_SEARCH_BUTTON', '_CASTOR_SEARCH_BUTTON', false);

			if (!using_bootstrap()) {
				$output[ 'THEBUTTON' ] = '<input type="submit" name="send" value="'.jr_gettext('_CASTOR_SEARCH_BUTTON', '_CASTOR_SEARCH_BUTTON', false).'" class="button" />';
			} else {
				if (isset($_REQUEST['search_widget'])) {
					$output[ 'THEBUTTON' ] = '<button type="submit" class="btn btn-primary btn-search-form" name="send" />'.jr_gettext('_CASTOR_SEARCH_BUTTON', '_CASTOR_SEARCH_BUTTON', false).'</button>';
				} else {
					$output[ 'THEBUTTON' ] = '<input type="submit" class="btn btn-primary btn-search-form" name="send" value="'.jr_gettext('_CASTOR_SEARCH_BUTTON', '_CASTOR_SEARCH_BUTTON', false).'" />';
				}
			}
		}

		$output_now = true;
		if (isset($componentArgs['templateFilePath']) && $componentArgs['templateFilePath'] != '' && isset($componentArgs['templateFile']) && $componentArgs['templateFile'] != '') {
			$sch->templateFilePath = $componentArgs['templateFilePath'];
			$sch->templateFile = $componentArgs['templateFile'];
			$output_now = false;
		}

		if (!$doSearch) {
			$pageoutput = array($output);
			$stmpl = new patTemplate();
			$stmpl->setRoot($sch->templateFilePath);
			$stmpl->readTemplatesFromInput($sch->templateFile);
			$stmpl->addRows('search', $pageoutput);
			$this->retVals = $stmpl->getParsedTemplate();

			if ($output_now) {
				echo $this->retVals;
			} else {
				return $this->retVals;
			}
		}
		if ($doSearch && !isset($_REQUEST[ 'srchOnly' ])) {
			$sch->jomSearch_showresults();
		}
		unset($sch);
	}


	/**
	 * Must be included in every mini-component.
	#
	 * Returns any settings that the mini-component wants to send back to the calling script. In addition to being returned to the calling script they are put into an array in the mcHandler object as eg. $mcHandler->miniComponentData[$ePoint][$eName]
	 */

	public function getRetVals()
	{
		return $this->retVals;
	}
}

