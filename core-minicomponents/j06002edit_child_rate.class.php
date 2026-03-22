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

class j06002edit_child_rate
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


		$output[ 'CASTOR_POLICIES_CHILDREN_CHILD_RATE_DESC' ]		= jr_gettext('CASTOR_POLICIES_CHILDREN_CHILD_RATE_DESC', 'CASTOR_POLICIES_CHILDREN_CHILD_RATE_DESC', false);
		$output[ 'CASTOR_POLICIES_CHILDREN_CHILD_RATE_AGE_FROM' ]	= jr_gettext('CASTOR_POLICIES_CHILDREN_CHILD_RATE_AGE_FROM', 'CASTOR_POLICIES_CHILDREN_CHILD_RATE_AGE_FROM', false);
		$output[ 'CASTOR_POLICIES_CHILDREN_CHILD_RATE_AGE_TO' ]		= jr_gettext('CASTOR_POLICIES_CHILDREN_CHILD_RATE_AGE_TO', 'CASTOR_POLICIES_CHILDREN_CHILD_RATE_AGE_TO', false);
		$output[ 'CASTOR_POLICIES_CHILDREN_CHILD_RATE_MODEL' ]		= jr_gettext('CASTOR_POLICIES_CHILDREN_CHILD_RATE_MODEL', 'CASTOR_POLICIES_CHILDREN_CHILD_RATE_MODEL', false);
		$output[ 'CASTOR_POLICIES_CHILDREN_CHILD_RATE_PRICE' ]		= jr_gettext('CASTOR_POLICIES_CHILDREN_CHILD_RATE_PRICE', 'CASTOR_POLICIES_CHILDREN_CHILD_RATE_PRICE', false);
		$output[ 'CASTOR_POLICIES_CHILDREN_CHILD_RATE_PRICE_DESC' ]	= jr_gettext('CASTOR_POLICIES_CHILDREN_CHILD_RATE_PRICE_DESC', 'CASTOR_POLICIES_CHILDREN_CHILD_RATE_PRICE_DESC', false);


		$output[ 'ID' ] = $id ;

		jr_import('castor_child_rates');
		$castor_child_rates = new castor_child_rates($property_uid);

		if ($id > 0) {
			$age_from_selected = $castor_child_rates->child_rates[$id]['age_from'];
			$age_to_selected = $castor_child_rates->child_rates[$id]['age_to'];
		} else {
			$age_from_selected = 0;
			$age_to_selected = 0;
		}

		$output['PRICE'] = '';
		if (isset($castor_child_rates->child_rates[$id])) {
			$output['PRICE'] = $castor_child_rates->child_rates[$id]['price'];
		}

		$output[ 'PAGE_TITLE' ] = jr_gettext('CASTOR_POLICIES_CHILDREN_CHILD_RATES', 'CASTOR_POLICIES_CHILDREN_CHILD_RATES', false);

		$output['AGE_FROM'] = castorHTML::integerSelectList(0, 17, 1, 'age_from', '', (int)  $age_from_selected);
		$output['AGE_TO'] = castorHTML::integerSelectList(0, 17, 1, 'age_to', '', (int) $age_to_selected);

		$output['model'] = $castor_child_rates->build_rate_model_dropdown($id);

		$jrtbar = castor_singleton_abstract::getInstance('castor_toolbar');
		$jrtb = $jrtbar->startTable();
		$image = $jrtbar->makeImageValid(CASTOR_IMAGES_RELPATH.'castorimages/small/Save.png');
		$link = CASTOR_SITEPAGE_URL;
		$jrtb .= $jrtbar->toolbarItem('cancel', CASTOR_SITEPAGE_URL.'&task=child_policies', '');
		$jrtb .= $jrtbar->customToolbarItem('save_child_rate', $link, jr_gettext('_CASTOR_COM_MR_SAVE', '_CASTOR_COM_MR_SAVE', false), $submitOnClick = true, $submitTask = 'save_child_rate', $image);
		$jrtb .= $jrtbar->endTable();
		$output[ 'CASTORTOOLBAR' ] = $jrtb;

		$pageoutput[ ] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_BACKEND);
		$tmpl->readTemplatesFromInput('edit_child_rate.html');
		$tmpl->addRows('pageoutput', $pageoutput);
		$tmpl->displayParsedTemplate();
	}


	public function getRetVals()
	{
		return null;
	}
}

