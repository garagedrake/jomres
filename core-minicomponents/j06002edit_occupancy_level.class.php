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

class j06002edit_occupancy_level
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

		$mrConfig = getPropertySpecificSettings($property_uid);

		$id = (int)$_REQUEST['id'];


		$output[ 'CASTOR_OCCUPANCY_LEVELS_MAX_ADULTS' ]		= jr_gettext('CASTOR_OCCUPANCY_LEVELS_MAX_ADULTS', 'CASTOR_OCCUPANCY_LEVELS_MAX_ADULTS', false);
		$output[ 'CASTOR_OCCUPANCY_LEVELS_MAX_CHILDREN' ]	= jr_gettext('CASTOR_OCCUPANCY_LEVELS_MAX_CHILDREN', 'CASTOR_OCCUPANCY_LEVELS_MAX_CHILDREN', false);
		$output[ 'CASTOR_OCCUPANCY_LEVELS_MAX_OCCUPANCY' ]	= jr_gettext('CASTOR_OCCUPANCY_LEVELS_MAX_OCCUPANCY', 'CASTOR_OCCUPANCY_LEVELS_MAX_OCCUPANCY', false);

		$output[ 'ID' ] = $id ;

		jr_import('castor_occupancy_levels');
		$castor_occupancy_levels = new castor_occupancy_levels($property_uid);



		if ($mrConfig['accommodates'] ==0) { // The occupancy levels haven't been properly set yet, let's do that now
			$first_key=array_key_first($castor_occupancy_levels->occupancy_levels);
			$mrConfig['accommodates'] = $castor_occupancy_levels->occupancy_levels[$first_key]['max_adults'];
		}

		$output[ 'PAGE_TITLE' ] = jr_gettext('CASTOR_OCCUPANCY_LEVELS_EDIT', 'CASTOR_OCCUPANCY_LEVELS_EDIT', false);

		$output['ROOM_TYPE_NAME'] = $castor_occupancy_levels->occupancy_levels[$id]['room_type_name'];

		$output['MAX_ADULTS'] = castorHTML::integerSelectList(0, 100, 1, 'max_adults', '', (int)  $castor_occupancy_levels->occupancy_levels[$id]['max_adults']);
		$output['MAX_CHILDREN'] = castorHTML::integerSelectList(0, 100, 1, 'max_children', '', (int) $castor_occupancy_levels->occupancy_levels[$id]['max_children']);


		$jrtbar = castor_singleton_abstract::getInstance('castor_toolbar');
		$jrtb = $jrtbar->startTable();
		$image = $jrtbar->makeImageValid(CASTOR_IMAGES_RELPATH.'castorimages/small/Save.png');
		$link = CASTOR_SITEPAGE_URL;
		$jrtb .= $jrtbar->toolbarItem('cancel', CASTOR_SITEPAGE_URL.'&task=occupancy_levels', '');
		$jrtb .= $jrtbar->customToolbarItem('save_occupancy_level', $link, jr_gettext('_CASTOR_COM_MR_SAVE', '_CASTOR_COM_MR_SAVE', false), $submitOnClick = true, $submitTask = 'save_occupancy_level', $image);
		$jrtb .= $jrtbar->endTable();
		$output[ 'CASTORTOOLBAR' ] = $jrtb;

		$pageoutput[ ] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_BACKEND);
		$tmpl->readTemplatesFromInput('edit_occupancy_level.html');
		$tmpl->addRows('pageoutput', $pageoutput);
		$tmpl->displayParsedTemplate();
	}


	public function getRetVals()
	{
		return null;
	}
}

