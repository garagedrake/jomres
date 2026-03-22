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
	 * Called by the 01010 script, given a list of property uids, it will rejig the order of the ids based on the jomsearch_sortby variable that can be passed via a variety of methods (componentArgs, $_REQUEST, user_settings etc)
	 */

	class j01009a_filterproperties
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
				$this->template_touchable = true;

				return;
			}
			$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
			$jrConfig = $siteConfig->get();

			$data_only = false;
			if (defined('CASTOR_NOHTML') && CASTOR_NOHTML == 1) {
				$data_only = true;
			}
			if (isset($_REQUEST[ 'dataonly' ])) {
				$data_only = true;
			}
			$propertys_uids = $componentArgs[ 'propertys_uids' ];

			$tmpBookingHandler = castor_singleton_abstract::getInstance('castor_temp_booking_handler');
			if (!isset($_REQUEST[ 'jomsearch_sortby' ]) && isset($tmpBookingHandler->user_settings[ 'jomsearch_sortby' ])) {
				$sortid = $tmpBookingHandler->user_settings[ 'jomsearch_sortby' ];
			} else {
				if (isset($_REQUEST[ 'jomsearch_sortby' ])) {
					$sortid = intval(castorGetParam($_REQUEST, 'jomsearch_sortby', 1));
				} else {
					$sortid = $jrConfig[ 'search_order_default' ];
				}
			}
			$tmpBookingHandler->user_settings[ 'jomsearch_sortby' ] = $sortid;

			switch ($sortid) {
				//########################################################################################
				case '2':
					$query = 'SELECT propertys_uid, property_name FROM #__castor_propertys WHERE propertys_uid IN ('.castor_implode($propertys_uids).') ORDER BY property_name';
					$uids = doSelectSql($query);
					foreach ($uids as $u) {
						$this->propertys_uids[ ] = $u->propertys_uid;
					}
					break;
				//########################################################################################
				case '3':
					$query = 'SELECT propertys_uid, property_region FROM #__castor_propertys WHERE propertys_uid IN ('.castor_implode($propertys_uids).') ';
					$regions = doSelectSql($query);
					foreach ($regions as $r) {
						if (is_numeric($r->property_region)) {
							$castor_regions = castor_singleton_abstract::getInstance('castor_regions');
							$r->property_region = jr_gettext('_CASTOR_CUSTOMTEXT_REGIONS_'.$r->property_region, $castor_regions->get_region_name($r->property_region), false);
						} else {
							$r->property_region = jr_gettext('_CASTOR_CUSTOMTEXT_PROPERTY_REGION', $r->property_region, false);
						}
					}

					if (!function_exists('cmp')) {
						function cmp($a, $b)
						{
							return strcmp($a->property_region, $b->property_region);
						}
					}

					usort($regions, 'cmp');

					foreach ($regions as $u) {
						$this->propertys_uids[ ] = $u->propertys_uid;
					}
					break;
				//########################################################################################
				case '4':
					$query = 'SELECT propertys_uid, property_town FROM #__castor_propertys WHERE propertys_uid IN ('.castor_implode($propertys_uids).') ORDER BY property_town';
					$uids = doSelectSql($query);
					foreach ($uids as $u) {
						$this->propertys_uids[ ] = $u->propertys_uid;
					}
					break;
				//########################################################################################
				case '5':
					$query = 'SELECT propertys_uid, stars FROM #__castor_propertys WHERE propertys_uid IN ('.castor_implode($propertys_uids).') ORDER BY stars DESC';
					$uids = doSelectSql($query);
					foreach ($uids as $u) {
						$this->propertys_uids[ ] = $u->propertys_uid;
					}
					break;
				//########################################################################################
				// Many thanks Derek B from Adonis Media Ltd
				case '6':
					$query = 'SELECT p.propertys_uid, rr.roomrateperday FROM #__castor_propertys AS p LEFT JOIN #__castor_rates AS rr ON p.propertys_uid = rr.property_uid WHERE propertys_uid IN ('.castor_implode($propertys_uids).') ORDER BY rr.roomrateperday ASC';
					$uids = doSelectSql($query);
					foreach ($uids as $u) {
						//if ( (float)$u->roomrateperday > 0)
						$this->propertys_uids[ ] = $u->propertys_uid;
					}
					break;
				//########################################################################################
				// Many thanks Derek B from Adonis Media Ltd
				case '7':
					$query = 'SELECT p.propertys_uid, rr.roomrateperday FROM #__castor_propertys AS p LEFT JOIN #__castor_rates AS rr ON p.propertys_uid = rr.property_uid WHERE propertys_uid IN ('.castor_implode($propertys_uids).') ORDER BY rr.roomrateperday DESC';
					$uids = doSelectSql($query);
					foreach ($uids as $u) {
						$this->propertys_uids[ ] = $u->propertys_uid;
					}
					break;
				//########################################################################################
				default:
					$this->propertys_uids = $propertys_uids;
					break;
			}

			//we`ll set the property uids array to showtime so we can further filter them if necessary in other j01009 minicomponents
			set_showtime('filtered_property_uids', $this->propertys_uids);

			$sortArray = array();
			$rows = array();
			$selected = '';
			if ( $sortid ==1) {
				$selected = 'selected="selected"';
			}
			$sortArray[ ] = castorHTML::makeOption('1', jr_gettext('_CASTOR_SORTORDER_DEFAULT', '_CASTOR_SORTORDER_DEFAULT', false, false));

			$selected = '';
			if ( $sortid ==2) {
				$selected = 'selected="selected"';
			}
			$sortArray[ ] = castorHTML::makeOption('2', jr_gettext('_CASTOR_SORTORDER_PROPERTYNAME', '_CASTOR_SORTORDER_PROPERTYNAME', false, false));

			$selected = '';
			if ( $sortid ==3) {
				$selected = 'selected="selected"';
			}
			$sortArray[ ] = castorHTML::makeOption('3', jr_gettext('_CASTOR_SORTORDER_PROPERTYREGION', '_CASTOR_SORTORDER_PROPERTYREGION', false, false));

			$selected = '';
			if ( $sortid ==4) {
				$selected = 'selected="selected"';
			}
			$sortArray[ ] = castorHTML::makeOption('4', jr_gettext('_CASTOR_SORTORDER_PROPERTYTOWN', '_CASTOR_SORTORDER_PROPERTYTOWN', false, false));

			$selected = '';
			if ( $sortid ==5) {
				$selected = 'selected="selected"';
			}
			$sortArray[ ] = castorHTML::makeOption('5', jr_gettext('_CASTOR_SORTORDER_STARS', '_CASTOR_SORTORDER_STARS', false, false));

			$order[ 'HORDER' ] = jr_gettext('_CASTOR_ORDER', '_CASTOR_ORDER');

			$order[ 'ORDER' ] = castorHTML::selectList($sortArray, 'sortby', ' onchange="generic_reload(\'jomsearch_sortby\',this.value); " ', 'value', 'text', $sortid);
			$sortorder = array();
			$sortorder[ ] = $order;

			if (!$data_only && $jrConfig[ 'show_search_order' ] == '1') {
				$tmpl = new patTemplate();
				$tmpl->setRoot(CASTOR_TEMPLATEPATH_FRONTEND);
				$tmpl->readTemplatesFromInput('order.html');
				$tmpl->addRows('sort_order', $sortorder);
				set_showtime('order_dropdown', $tmpl->getParsedTemplate());
			}
		}


		public function getRetVals()
		{
			return $this->propertys_uids;
		}
	}

