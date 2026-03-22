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

class j16000list_property_categories
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
		$MiniComponents = castor_getSingleton('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = false;

			return;
		}
		
		$output = array();
		$rows = array();
		$pageoutput = array();
		
		$castor_property_categories = castor_singleton_abstract::getInstance('castor_property_categories');
		$castor_property_categories->get_all_property_categories();

		$output['PAGETITLE'] = jr_gettext('_CASTOR_PROPERTY_HCATEGORIES', '_CASTOR_PROPERTY_HCATEGORIES', false);
		$output['HTITLE'] = jr_gettext('_JRPORTAL_CRATE_TITLE', '_JRPORTAL_CRATE_TITLE', false);

		foreach ($castor_property_categories->property_categories as $c) {
			$r = array();

			$r['TITLE'] = $c['title'];
			$r['ID'] = $c['id'];
			
			$toolbar = castor_singleton_abstract::getInstance('castorItemToolbar');
			$toolbar->newToolbar();
			$toolbar->addItem('fa fa-pencil-square-o', 'btn btn-info', '', castorURL(CASTOR_SITEPAGE_URL_ADMIN.'&task=edit_property_category&id='.$c['id']), jr_gettext('COMMON_EDIT', 'COMMON_EDIT', false));
			$toolbar->addSecondaryItem('fa fa-trash-o', '', '', castorURL(CASTOR_SITEPAGE_URL_ADMIN.'&task=delete_property_category&id='.$c['id']), jr_gettext('COMMON_DELETE', 'COMMON_DELETE', false));

			$r['EDITLINK'] = $toolbar->getToolbar();

			$rows[] = $r;
		}

		$jrtbar = castor_getSingleton('castor_toolbar');
		$jrtb = $jrtbar->startTable();
		$image = $jrtbar->makeImageValid(CASTOR_IMAGES_RELPATH.'castorimages/small/AddItem.png');
		$link = CASTOR_SITEPAGE_URL_ADMIN;
		$jrtb .= $jrtbar->customToolbarItem('edit_property_category', $link, $text = 'Add', $submitOnClick = true, $submitTask = 'edit_property_category', $image);
		$jrtb .= $jrtbar->toolbarItem('cancel', CASTOR_SITEPAGE_URL_ADMIN, jr_gettext('_JRPORTAL_CANCEL', '_JRPORTAL_CANCEL', false));
		$jrtb .= $jrtbar->endTable();
		$output['CASTORTOOLBAR'] = $jrtb;

		$pageoutput[] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_ADMINISTRATOR);
		$tmpl->readTemplatesFromInput('list_property_categories.html');
		$tmpl->addRows('pageoutput', $pageoutput);
		$tmpl->addRows('rows', $rows);
		$tmpl->displayParsedTemplate();
	}


	public function getRetVals()
	{
		return null;
	}
}

