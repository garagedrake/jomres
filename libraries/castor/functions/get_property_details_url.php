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
	 * @package Castor\Core\Functions
	 *
	 * The purpose of this function is to allow us to override the property details page link programatically.
	 *
	 * Types:
	 * sef: sef url
	 * nosef: no sef url
	 * sefsafe: sef url not passed through castorURL function
	 * ajax: ajax safe url
	 *
	 */
	if (!function_exists('get_property_details_url')) {
		function get_property_details_url($property_uid = 0, $type = 'sef', $params = '')
		{
			$castor_access_control = castor_singleton_abstract::getInstance('castor_access_control');

			if (!$castor_access_control->this_user_can('viewproperty')) {
				return false;
			}

			switch ($type) {
				case 'sef':
					$url = castorURL(CASTOR_SITEPAGE_URL.'&task=viewproperty&property_uid='.$property_uid.$params);
					break;
				case 'nosef':
					$url = castorURL(CASTOR_SITEPAGE_URL_NOSEF.'&task=viewproperty&property_uid='.$property_uid.$params);
					break;
				case 'sefsafe':
					$url = CASTOR_SITEPAGE_URL.'&task=viewproperty&property_uid='.$property_uid.$params;
					break;
				case 'ajax':
					$url = CASTOR_SITEPAGE_URL_AJAX.'&task=viewproperty&property_uid='.$property_uid.$params;
					break;
				default:
					$url = castorURL(CASTOR_SITEPAGE_URL.'&task=viewproperty&property_uid='.$property_uid.$params);
					break;
			}

			//if we have a joomla menu of type propertydetails created for this specific property, then we`ll use that url insetad, t avoid duplicates. This allows alows us having modules assigned only to this property details page.
			if (this_cms_is_joomla()) {
				$app = JFactory::getApplication();
				$menu = $app->getMenu();
				$menuItem = $menu->getItems('link', 'index.php?option=com_castor&view=default&layout=propertydetails&selected_property='.$property_uid, $firstonly = true);
				if ($menuItem) {
					if ($type == 'sef' || $type == 'sefsafe') {
						$url = JRoute::_($menuItem->link.'&Itemid='.$menuItem->id);
					} else {
						$url = castorURL(get_showtime('live_site').'/'.$menuItem->link.'&Itemid='.$menuItem->id);
					}
				}
			}

			return $url;
		}
	}



