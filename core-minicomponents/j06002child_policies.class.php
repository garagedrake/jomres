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

class j06002child_policies
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

		jr_import('castor_child_policies');
		$castor_child_policies = new castor_child_policies($property_uid);

		$output[ 'CASTOR_POLICIES_CHILDREN' ] = jr_gettext('CASTOR_POLICIES_CHILDREN', 'CASTOR_POLICIES_CHILDREN', false);
		$output[ 'CASTOR_POLICIES_CHILDREN_AGES_ALLOWED' ] = jr_gettext('CASTOR_POLICIES_CHILDREN_AGES_ALLOWED', 'CASTOR_POLICIES_CHILDREN_AGES_ALLOWED', false);
		$output[ 'CASTOR_POLICIES_CHILDREN_CHILD_RATE_NEW' ] = jr_gettext('CASTOR_POLICIES_CHILDREN_CHILD_RATE_NEW', 'CASTOR_POLICIES_CHILDREN_CHILD_RATE_NEW', false);
		$output[ 'CASTOR_POLICIES_CHILDREN_CHILD_RATE_AGE_FROM' ] = jr_gettext('CASTOR_POLICIES_CHILDREN_CHILD_RATE_AGE_FROM', 'CASTOR_POLICIES_CHILDREN_CHILD_RATE_AGE_FROM', false);
		$output[ 'CASTOR_POLICIES_CHILDREN_CHILD_RATE_AGE_TO' ] = jr_gettext('CASTOR_POLICIES_CHILDREN_CHILD_RATE_AGE_TO', 'CASTOR_POLICIES_CHILDREN_CHILD_RATE_AGE_TO', false);
		$output[ 'CASTOR_POLICIES_CHILDREN_CHILD_RATE_MODEL' ] = jr_gettext('CASTOR_POLICIES_CHILDREN_CHILD_RATE_MODEL', 'CASTOR_POLICIES_CHILDREN_CHILD_RATE_MODEL', false);
		$output[ 'CASTOR_POLICIES_CHILDREN_CHILD_RATE_PRICE' ] = jr_gettext('CASTOR_POLICIES_CHILDREN_CHILD_RATE_PRICE', 'CASTOR_POLICIES_CHILDREN_CHILD_RATE_PRICE', false);

		$output[ 'CHILD_MIN_AGE' ] = castorHTML::integerSelectList(0, 17, 1, 'child_min_age', '', $castor_child_policies->child_policies['child_min_age'], '');
		$output[ 'NEW_RATE_URL' ] = CASTOR_SITEPAGE_URL_NOSEF.'&task=edit_child_rate&id=0';

		jr_import('castor_child_rates');
		$castor_child_rates = new castor_child_rates($property_uid);

		$rows = array();
		if (!empty($castor_child_rates->child_rates)) {
			foreach ($castor_child_rates->child_rates as $id => $child_rate) {
				$r = array();

				$toolbar = castor_singleton_abstract::getInstance('castorItemToolbar');
				$toolbar->newToolbar();
				$toolbar->addItem('fa fa-pencil-square-o', 'btn btn-info', '', castorURL(CASTOR_SITEPAGE_URL_NOSEF.'&task=edit_child_rate&id='.(int) $id), jr_gettext('COMMON_EDIT', 'COMMON_EDIT', false));
				$toolbar->addSecondaryItem('fa fa-trash-o', '', '', castorURL(CASTOR_SITEPAGE_URL_NOSEF.'&task=delete_child_rate&id='.(int) $id), jr_gettext('COMMON_DELETE', 'COMMON_DELETE', false));

				$r['EDITLINK'] = $toolbar->getToolbar();

				$r['AGE_FROM'] = $child_rate['age_from'];
				$r['AGE_TO'] = $child_rate['age_to'];
				$r['PRICE'] = $child_rate['price'];

				if ($child_rate['model'] == 'per_night') {
					$r['MODEL'] = jr_gettext('CASTOR_POLICIES_CHILDREN_CHARGE_MODEL_PER_NIGHT', 'CASTOR_POLICIES_CHILDREN_CHARGE_MODEL_PER_NIGHT', false);
				} else {
					$r['MODEL'] = jr_gettext('CASTOR_POLICIES_CHILDREN_CHARGE_MODEL_PER_STAY', 'CASTOR_POLICIES_CHILDREN_CHARGE_MODEL_PER_STAY', false);
				}

				$rows[] = $r;
			}
		}

		$jrtbar = castor_singleton_abstract::getInstance('castor_toolbar');
		$jrtb = $jrtbar->startTable();
		$image = $jrtbar->makeImageValid(CASTOR_IMAGES_RELPATH.'castorimages/small/Save.png');
		$link = CASTOR_SITEPAGE_URL;
		$jrtb .= $jrtbar->toolbarItem('cancel', CASTOR_SITEPAGE_URL, '');
		$jrtb .= $jrtbar->customToolbarItem('save_child_policies', $link, jr_gettext('_CASTOR_COM_MR_SAVE', '_CASTOR_COM_MR_SAVE', false), $submitOnClick = true, $submitTask = 'save_child_policies', $image);
		$jrtb .= $jrtbar->endTable();
		$output[ 'CASTORTOOLBAR' ] = $jrtb;

		$pageoutput[ ] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_BACKEND);
		$tmpl->readTemplatesFromInput('child_policies.html');
		$tmpl->addRows('pageoutput', $pageoutput);
		$tmpl->addRows('rows', $rows);
		$tmpl->displayParsedTemplate();
	}


	public function getRetVals()
	{
		return null;
	}
}

