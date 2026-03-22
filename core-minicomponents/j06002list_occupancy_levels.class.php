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

class j06002list_occupancy_levels
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
			$this->template_touchable = false;

			return;
		}
		
		$property_uid = getDefaultProperty();

		jr_import('castor_occupancy_levels');
		$castor_occupancy_levels = new castor_occupancy_levels($property_uid);

		$output[ 'CASTOR_OCCUPANCY_LEVELS_TITLE' ] = jr_gettext('CASTOR_OCCUPANCY_LEVELS_TITLE', 'CASTOR_OCCUPANCY_LEVELS_TITLE', false);

		$output[ '_CASTOR_COM_MR_VRCT_TAB_ROOMTYPES' ] = jr_gettext('_CASTOR_COM_MR_VRCT_TAB_ROOMTYPES', '_CASTOR_COM_MR_VRCT_TAB_ROOMTYPES', false);
		$output[ 'CASTOR_OCCUPANCY_LEVELS_MAX_ADULTS' ] = jr_gettext('CASTOR_OCCUPANCY_LEVELS_MAX_ADULTS', 'CASTOR_OCCUPANCY_LEVELS_MAX_ADULTS', false);
		$output[ 'CASTOR_OCCUPANCY_LEVELS_MAX_CHILDREN' ] = jr_gettext('CASTOR_OCCUPANCY_LEVELS_MAX_CHILDREN', 'CASTOR_OCCUPANCY_LEVELS_MAX_CHILDREN', false);
		$output[ 'CASTOR_OCCUPANCY_LEVELS_MAX_OCCUPANCY' ] = jr_gettext('CASTOR_OCCUPANCY_LEVELS_MAX_OCCUPANCY', 'CASTOR_OCCUPANCY_LEVELS_MAX_OCCUPANCY', false);
		$output[ 'CASTOR_OCCUPANCY_LEVELS_INFO' ] = jr_gettext('CASTOR_OCCUPANCY_LEVELS_INFO', 'CASTOR_OCCUPANCY_LEVELS_INFO', false);

		$rows = array();
		if (!empty($castor_occupancy_levels->occupancy_levels)) {
			foreach ($castor_occupancy_levels->occupancy_levels as $id => $occupancy_level) {
				$r = array();

				$toolbar = castor_singleton_abstract::getInstance('castorItemToolbar');
				$toolbar->newToolbar();
				$toolbar->addItem('fa fa-pencil-square-o', 'btn btn-info', '', castorURL(CASTOR_SITEPAGE_URL_NOSEF.'&task=edit_occupancy_level&id='.(int) $occupancy_level['room_type_id']), jr_gettext('COMMON_EDIT', 'COMMON_EDIT', false));
				//$toolbar->addSecondaryItem('fa fa-trash-o', '', '', castorURL(CASTOR_SITEPAGE_URL_NOSEF.'&task=delete_child_rate&id='.(int) $id ), jr_gettext('COMMON_DELETE', 'COMMON_DELETE', false));

				$r['EDITLINK'] = $toolbar->getToolbar();

				$r['ROOM_TYPE_NAME']	= $occupancy_level['room_type_name'];
				$r['MAX_ADULTS'] = $occupancy_level['max_adults'];
				$r['MAX_CHILDREN'] = $occupancy_level['max_children'];
				$r['MAX_OCCUPANCY'] = $occupancy_level['max_occupancy'];

				$rows[] = $r;
			}
		}

		$pageoutput[ ] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_BACKEND);
		$tmpl->readTemplatesFromInput('occupancy_levels.html');
		$tmpl->addRows('pageoutput', $pageoutput);
		$tmpl->addRows('rows', $rows);
		$tmpl->displayParsedTemplate();
	}


	public function getRetVals()
	{
		return null;
	}
}

