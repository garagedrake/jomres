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

class j16000list_taxrates
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
		$editIcon = '<img src="'.CASTOR_IMAGES_RELPATH.'castorimages/small/EditItem.png" border="0" alt="editicon" />';

		$jrportal_taxrate = castor_singleton_abstract::getInstance('jrportal_taxrate');

		$output = array();
		$pageoutput = array();
		$rows = array();

		$output[ 'PAGETITLE' ] = jr_gettext('_JRPORTAL_TAXRATES_TITLE', '_JRPORTAL_TAXRATES_TITLE', false);
		$output[ 'HCODE' ] = jr_gettext('_JRPORTAL_TAXRATES_CODE', '_JRPORTAL_TAXRATES_CODE', false);
		$output[ 'HDESCRIPTION' ] = jr_gettext('_JRPORTAL_TAXRATES_DESCRIPTION', '_JRPORTAL_TAXRATES_DESCRIPTION', false);
		$output[ 'HRATE' ] = jr_gettext('_JRPORTAL_TAXRATES_RATE', '_JRPORTAL_TAXRATES_RATE', false);
		$output[ '_CASTOR_IS_EU_COUNTRY' ] = jr_gettext('_CASTOR_IS_EU_COUNTRY', '_CASTOR_IS_EU_COUNTRY', false);

		$output[ '_CASTOR_TAX_RATES_IMPORT' ] = jr_gettext('_CASTOR_TAX_RATES_IMPORT', '_CASTOR_TAX_RATES_IMPORT', false);
		$output[ '_CASTOR_TAX_RATES_IMPORT_INFO' ] = jr_gettext('_CASTOR_TAX_RATES_IMPORT_INFO', '_CASTOR_TAX_RATES_IMPORT_INFO', false);
		$output[ 'IMPORT_LINK'] = CASTOR_SITEPAGE_URL_ADMIN.'&task=import_eu_tax_rates';

		foreach ($jrportal_taxrate->taxrates as $rate) {
			$r = array();
			$r[ 'ID' ] = $rate[ 'id' ];
			$r[ 'CODE' ] = $rate[ 'code' ];
			$r[ 'DESCRIPTION' ] = $rate[ 'description' ];
			$r[ 'RATE' ] = $rate[ 'rate' ];

			$r['IS_EU_COUNTRY'] = jr_gettext('_CASTOR_COM_MR_NO', '_CASTOR_COM_MR_NO', false);
			if ($rate[ 'is_eu_country' ] == '1') {
				$r['IS_EU_COUNTRY'] = jr_gettext('_CASTOR_COM_MR_YES', '_CASTOR_COM_MR_YES', false);
			}

			if (!using_bootstrap()) {
				$r[ 'EDITLINK' ] = '<a href="'.CASTOR_SITEPAGE_URL_ADMIN.'&task=edit_taxrate&id='.$rate[ 'id' ].'">'.$editIcon.'</a>';
			} else {
				$toolbar = castor_singleton_abstract::getInstance('castorItemToolbar');
				$toolbar->newToolbar();
				$toolbar->addItem('fa fa-pencil-square-o', 'btn btn-info', '', castorURL(CASTOR_SITEPAGE_URL_ADMIN.'&task=edit_taxrate&id='.$rate[ 'id' ]), jr_gettext('COMMON_EDIT', 'COMMON_EDIT', false));
				$toolbar->addSecondaryItem('fa fa-trash-o', '', '', castorURL(CASTOR_SITEPAGE_URL_ADMIN.'&task=delete_taxrate&id='.$rate[ 'id' ]), jr_gettext('COMMON_DELETE', 'COMMON_DELETE', false));

				$r['EDITLINK'] = $toolbar->getToolbar();
			}

			$rows[ ] = $r;
		}

		$jrtbar = castor_singleton_abstract::getInstance('castor_toolbar');
		$jrtb = $jrtbar->startTable();
		$jrtb .= $jrtbar->toolbarItem('cancel', castorURL(CASTOR_SITEPAGE_URL_ADMIN), '');
		$jrtb .= $jrtbar->toolbarItem('new', castorURL(CASTOR_SITEPAGE_URL_ADMIN.'&task=edit_taxrate'), '');

		$jrtb .= $jrtbar->endTable();
		$output[ 'CASTORTOOLBAR' ] = $jrtb;

		$pageoutput[ ] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_ADMINISTRATOR);
		$tmpl->readTemplatesFromInput('list_taxrates.html');
		$tmpl->addRows('pageoutput', $pageoutput);
		$tmpl->addRows('rows', $rows);
		$tmpl->displayParsedTemplate();
	}


	public function getRetVals()
	{
		return null;
	}
}

