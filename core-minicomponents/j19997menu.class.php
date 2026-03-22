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

class j19997menu
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

		$this->ret_vals = '';
		$menu_sections = array();
		
		$castor_menu = castor_singleton_abstract::getInstance('castor_menu');
		$castor_menu->generate_admin_menu();
		
		if (empty($castor_menu->admin_menu)) {
			return;
		}

		//section params are in $castor_menu->admin_sections[section_id]
		//menu items params are in $castor_menu->admin_items[task]
		//now let`s generate the menu output
		foreach ($castor_menu->admin_menu as $section_id => $tasks) {
			$pageoutput = array();
			$rows = array();
			$output = array();
			
			$output[ 'COLLAPSE_IN' ] = '';

			foreach ($tasks as $task) {
				$r = array();
				
				//menu item name
				$r['MENU_NAME'] = $castor_menu->admin_items[$task]['title'];

				//menu item url
				if ($castor_menu->admin_items[$task]['is_url']) {
					$r['LINK'] = $task;
				} elseif ($task != 'blank') {
					$r['LINK'] = castorUrl(CASTOR_SITEPAGE_URL_ADMIN.'&task='.$task);
				} else {
					$r['LINK'] = castorUrl(CASTOR_SITEPAGE_URL_ADMIN);
				}
				
				//menu item icon
				$r['ICON_CLASS'] = $castor_menu->admin_items[$task]['icon'];

				//menu item target
				$r[ 'TARGET' ] = '';
				if ($castor_menu->admin_items[$task]['external']) {
					$r[ 'TARGET' ] = ' target="_blank" ';
				}
				
				//menu item disabled class
				$r[ 'DISABLED_CLASS' ] = '';
				if ($castor_menu->admin_items[$task]['disabled']) {
					$r[ 'LINK' ] = '#';
					$r[ 'DISABLED_CLASS' ] = 'disabled';
				}
				
				//menu item badges TODO: find a better way or remove this completely
				$r[ 'BADGES' ] = '';

				if (!$castor_menu->admin_items[$task]['is_url'] && get_showtime('task') != 'addplugin') {
					$items_requiring_attention = get_number_of_items_requiring_attention_for_menu_option($task);
					
					if (!empty($items_requiring_attention)) {
						foreach ($items_requiring_attention as $colour => $number) {
							if ($number > 0) {
								$tmpl = new patTemplate();
								$tmpl->setRoot(CASTOR_TEMPLATEPATH_ADMINISTRATOR);
								$tmpl->readTemplatesFromInput('menu_badge_'.$colour.'.html');
								$tmpl->addRows('items_requiring_attention', array(array('NUMBER' => $number)));
								$r[ 'BADGES' ] = $tmpl->getParsedTemplate();
							}
						}
					}
				}
				
				//active menu item
				$r[ 'ACTIVE' ] = '';
				
				if (get_showtime('task') == $task) {
					$r[ 'ACTIVE' ] = 'active';
					$output[ 'COLLAPSE_IN' ] = 'in';
				} elseif (get_showtime('task') == '' && $task == 'blank' && !$castor_menu->admin_items[$task]['external']) {
					$r[ 'ACTIVE' ] = 'active';
					$output[ 'COLLAPSE_IN' ] = 'in';
				}
				
				$rows[] = $r;
			}
			
			$output[ 'CATEGORY' ] = $castor_menu->admin_sections[$section_id]['title'];
			$output[ 'ID' ] = 'cpanel-category-'.$section_id;

			$pageoutput[ ] = $output;
			$tmpl = new patTemplate();
			$tmpl->setRoot(CASTOR_TEMPLATEPATH_ADMINISTRATOR);
			if (this_cms_is_joomla() && _CASTOR_DETECTED_CMS == 'joomla3') {
				$tmpl->readTemplatesFromInput('control_panel_menu_options_vertical.html');
			} else {
				$tmpl->readTemplatesFromInput('control_panel_menu_options_horizontal.html');
			}
			$tmpl->addRows('pageoutput', $pageoutput);
			$tmpl->addRows('rows', $rows);
			$menu_sections[][ 'SECTION' ] = $tmpl->getParsedTemplate();
		}
		
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_ADMINISTRATOR);
		
		if (this_cms_is_joomla() && _CASTOR_DETECTED_CMS == 'joomla3') {
			$tmpl->readTemplatesFromInput('control_panel_menu_wrapper_vertical.html');
		} else {
			$tmpl->readTemplatesFromInput('control_panel_menu_wrapper_horizontal.html');
		}
		$tmpl->addRows('menu_sections', $menu_sections);
		$this->ret_vals = $tmpl->getParsedTemplate();
	}


	public function getRetVals()
	{
		return $this->ret_vals;
	}
}

