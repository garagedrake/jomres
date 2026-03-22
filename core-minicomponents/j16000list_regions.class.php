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

class j16000list_regions
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
		
		$output = array();
		$rows = array();
		$pageoutput = array();

		$output[ '_CASTOR_EDIT_REGION_TITLE' ] = jr_gettext('_CASTOR_EDIT_REGION_TITLE', '_CASTOR_EDIT_REGION_TITLE', false);
		$output[ '_CASTOR_EDIT_REGION_ID' ] = jr_gettext('_CASTOR_EDIT_REGION_ID', '_CASTOR_EDIT_REGION_ID', false);
		$output[ '_CASTOR_EDIT_REGION_COUNTRYCODE' ] = jr_gettext('_CASTOR_EDIT_REGION_COUNTRYCODE', '_CASTOR_EDIT_REGION_COUNTRYCODE', false);
		$output[ '_CASTOR_EDIT_REGION_REGIONNAME' ] = jr_gettext('_CASTOR_EDIT_REGION_REGIONNAME', '_CASTOR_EDIT_REGION_REGIONNAME', false);
		$output[ '_CASTOR_EDIT_COUNTRY_COUNTRYNAME' ] = jr_gettext('_CASTOR_EDIT_COUNTRY_COUNTRYNAME', '_CASTOR_EDIT_COUNTRY_COUNTRYNAME', false);

		$castor_regions = castor_singleton_abstract::getInstance('castor_regions');
		$castor_regions->get_all_regions();

		if (empty($castor_regions->regions)) {
			import_regions();
		}

		foreach ($castor_regions->regions as $region) {
			if ($region[ 'countrycode' ] != '') {
				$r = array();
				
				$r[ 'REGIONNAME' ] = $region[ 'regionname' ];
				$r[ 'COUNTRYCODE' ] = $region[ 'countrycode' ];
				$r[ 'COUNTRYNAME' ] = getSimpleCountry($region[ 'countrycode' ]);
				$r[ 'ID' ] = $region[ 'id' ];

				$toolbar = castor_singleton_abstract::getInstance('castorItemToolbar');
				$toolbar->newToolbar();
				$toolbar->addItem('fa fa-pencil-square-o', 'btn btn-info', '', castorURL(CASTOR_SITEPAGE_URL_ADMIN.'&task=edit_region&id='.$region[ 'id' ]), jr_gettext('COMMON_EDIT', 'COMMON_EDIT', false));
				$toolbar->addSecondaryItem('fa fa-trash-o', '', '', castorURL(CASTOR_SITEPAGE_URL_ADMIN.'&task=delete_region&id='.$region[ 'id' ]), jr_gettext('COMMON_DELETE', 'COMMON_DELETE', false));

				$r['EDITLINK'] = $toolbar->getToolbar();
				
				$rows[] = $r;
			}
		}

		$jrtbar = castor_singleton_abstract::getInstance('castor_toolbar');
		$jrtb = $jrtbar->startTable();
		$jrtb .= $jrtbar->toolbarItem('cancel', castorURL(CASTOR_SITEPAGE_URL_ADMIN), '');
		$jrtb .= $jrtbar->toolbarItem('new', castorURL(CASTOR_SITEPAGE_URL_ADMIN.'&task=edit_region'), '');
		$jrtb .= $jrtbar->endTable();

		$output[ 'CASTORTOOLBAR' ] = $jrtb;

		$pageoutput[ ] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_ADMINISTRATOR);
		$tmpl->readTemplatesFromInput('list_regions.html');
		$tmpl->addRows('pageoutput', $pageoutput);
		$tmpl->addRows('rows', $rows);
		$tmpl->displayParsedTemplate();
	}


	public function getRetVals()
	{
		return null;
	}
}

