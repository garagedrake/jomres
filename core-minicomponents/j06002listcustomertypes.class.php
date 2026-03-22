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
defined('_CASTOR_INITCHECK') or die('Direct Access to this file is not allowed.');
// ################################################################
	#[AllowDynamicProperties]
class j06002listcustomertypes
{
	function __construct()
	{
		// Must be in all minicomponents. Minicomponents with templates that can contain editable text should run $this->template_touch() else just return
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable=true;
			return;
		}
		
		$ePointFilepath = get_showtime('ePointFilepath');
		
		$defaultProperty = (int)getDefaultProperty();
		
		$mrConfig = getPropertySpecificSettings();

		//get all guest types
		$basic_guest_type_details = castor_singleton_abstract::getInstance('basic_guest_type_details');
		$basic_guest_type_details->get_all_guest_types($defaultProperty);
		
		//castor item toolbar (bootstrap)
		$toolbar = castor_singleton_abstract::getInstance('castorItemToolbar');
		
		//old toolbar used in jui templates
		$jrtbar = castor_singleton_abstract::getInstance('castor_toolbar');
		
		$output 	= array();
		$pageoutput = array();
		$rows 		= array();

		$output['PAGETITLE'] 		= jr_gettext('_CASTOR_CONFIG_VARIANCES_CUSTOMERTYPES', '_CASTOR_CONFIG_VARIANCES_CUSTOMERTYPES', false);
		$output['HTYPE'] 			= jr_gettext('_CASTOR_VARIANCES_TYPE', '_CASTOR_VARIANCES_TYPE', false);
		$output['HNOTES'] 			= jr_gettext('_CASTOR_VARIANCES_NOTES', '_CASTOR_VARIANCES_NOTES', false);
		$output['HMAXIMUM'] 		= jr_gettext('_CASTOR_VARIANCES_MAXIMUM', '_CASTOR_VARIANCES_MAXIMUM', false);
		$output['HISPERCENTAGE'] 	= jr_gettext('_CASTOR_VARIANCES_ISPERCENTAGE', '_CASTOR_VARIANCES_ISPERCENTAGE', false);
		$output['HPOSNEG'] 			= jr_gettext('_CASTOR_VARIANCES_POSNEG', '_CASTOR_VARIANCES_POSNEG', false);
		$output['HVARIANCE'] 		= jr_gettext('_CASTOR_VARIANCES_VARIANCE', '_CASTOR_VARIANCES_VARIANCE', false);
		$output['HPUBLISHIMAGE'] 	= jr_gettext('_CASTOR_COM_MR_VRCT_PUBLISHED', '_CASTOR_COM_MR_VRCT_PUBLISHED', false);
		$output['HORDER'] 			= jr_gettext('_CASTOR_ORDER', '_CASTOR_ORDER', false);
		
		$output['_CASTOR_GUESTTYPES_INSTRUCTIONS'] 			= jr_gettext('_CASTOR_GUESTTYPES_INSTRUCTIONS', '_CASTOR_GUESTTYPES_INSTRUCTIONS', false);
		$output['_CASTOR_GUESTTYPES_INTRO'] 				= jr_gettext('_CASTOR_GUESTTYPES_INTRO', '_CASTOR_GUESTTYPES_INTRO', false);

		foreach ($basic_guest_type_details->guest_types as $g) {
			$r = array();
			
			$r['ID'] = $g['id'];
			
			$r['TYPE'] 	= $g['type'];
			$r['NOTES'] 	= $g['notes'];
			$r['MAXIMUM'] 	= $g['maximum'];
			
			if ($g['is_percentage'] == 1) {
				$r['ISPERCENTAGE'] = jr_gettext('_CASTOR_COM_MR_YES', '_CASTOR_COM_MR_YES', false);
			} else {
				$r['ISPERCENTAGE'] = jr_gettext('_CASTOR_COM_MR_NO', '_CASTOR_COM_MR_NO', false);
			}
			
			if ($g['posneg'] == 1) {
				$r['POSNEG'] = "+";
			} else {
				$r['POSNEG'] = "-";
			}
			
			$r['VARIANCE'] = number_format($g['variance'], 2, '.', '');
			
			//old toolbar, jquery ui, not used in bootstrap templates
			$r['EDITLINK'] = '';
			if (!using_bootstrap()) {
				$jrtb  = $jrtbar->startTable();
				
				$jrtb .= $jrtbar->toolbarItem('edit', castorURL(CASTOR_SITEPAGE_URL."&task=editcustomertype&id=".$g['id']), '');
				if ($g['published'] == 0) {
					$jrtb .= $jrtbar->toolbarItem('publish', castorURL(CASTOR_SITEPAGE_URL."&task=publishcustomertype&id=".$g['id']), '');
				} else {
					$jrtb .= $jrtbar->toolbarItem('unpublish', castorURL(CASTOR_SITEPAGE_URL."&task=publishcustomertype&id=".$g['id']), '');
				}
				$jrtb .= $jrtbar->toolbarItem('delete', castorURL(CASTOR_SITEPAGE_URL."&task=deletecustomertype&id=".$g['id']), '');
				
				$jrtb .= $jrtbar->endTable();
				$r['EDITLINK'] = $jrtb;
			}
			
			//bootstrap toolbar built with the castorItemToolbar class
			$r['ITEM_TOOLBAR'] = '';
			if (using_bootstrap()) {
				$toolbar->newToolbar();
				
				if ($g['published'] == 0) {
					$toolbar->addSecondaryItem('icon-cancel', '', '', castorURL(CASTOR_SITEPAGE_URL . '&task=publishcustomertype' . '&id=' . $g['id']), jr_gettext('_CASTOR_COM_MR_VRCT_PUBLISH', '_CASTOR_COM_MR_VRCT_PUBLISH', false));
				} else {
					$toolbar->addSecondaryItem('icon-ok icon-white', '', '', castorURL(CASTOR_SITEPAGE_URL . '&task=publishcustomertype' . '&id=' . $g['id']), jr_gettext('_CASTOR_COM_MR_VRCT_UNPUBLISH', '_CASTOR_COM_MR_VRCT_UNPUBLISH', false));
				}
				
				$toolbar->addItem('icon-edit', 'btn btn-info', '', castorURL(CASTOR_SITEPAGE_URL . '&task=editcustomertype' . '&id=' . $g['id']), jr_gettext('COMMON_EDIT', 'COMMON_EDIT', false));
				$toolbar->addSecondaryItem('icon-trash', '', '', castorURL(CASTOR_SITEPAGE_URL . '&task=deletecustomertype' . '&id=' . $g['id']), jr_gettext('COMMON_DELETE', 'COMMON_DELETE', false));
				
				$r['ITEM_TOOLBAR']=$toolbar->getToolbar();
			}

			//sort order
			if ($g['order'] == '') {
				$r['ORDER'] = 0;
			} else {
				$r['ORDER'] = $g['order'];
			}

			$rows[]=$r;
		}

		$jrtbar = castor_singleton_abstract::getInstance('castor_toolbar');
		$jrtb  = $jrtbar->startTable();
		$jrtb .= $jrtbar->toolbarItem('new', castorURL(CASTOR_SITEPAGE_URL."&task=editcustomertype"), '');
		$jrtb .= $jrtbar->toolbarItem('save', '', jr_gettext('_CASTOR_ORDER', '_CASTOR_ORDER', false, true), true, 'savecustomertypeorder');
		$jrtb .= $jrtbar->endTable();
		$output['CASTORTOOLBAR']=$jrtb;

		$pageoutput[]=$output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_BACKEND);
		$tmpl->readTemplatesFromInput('list_customertypes.html');
		$tmpl->addRows('pageoutput', $pageoutput);
		$tmpl->addRows('rows', $rows);
		$tmpl->displayParsedTemplate();
	}


	function touch_template_language()
	{
		$output=array();

		$output[]		=jr_gettext('_CASTOR_CONFIG_VARIANCES_CUSTOMERTYPES', '_CASTOR_CONFIG_VARIANCES_CUSTOMERTYPES');
		$output[]		=jr_gettext('_CASTOR_VARIANCES_TYPE', '_CASTOR_VARIANCES_TYPE');
		$output[]		=jr_gettext('_CASTOR_VARIANCES_TYPE_TT', '_CASTOR_VARIANCES_TYPE_TT');
		$output[]		=jr_gettext('_CASTOR_VARIANCES_NOTES', '_CASTOR_VARIANCES_NOTES');
		$output[]		=jr_gettext('_CASTOR_VARIANCES_NOTES_TT', '_CASTOR_VARIANCES_NOTES_TT');
		$output[]		=jr_gettext('_CASTOR_VARIANCES_MAXIMUM', '_CASTOR_VARIANCES_MAXIMUM');
		$output[]		=jr_gettext('_CASTOR_VARIANCES_MAXIMUM_TT', '_CASTOR_VARIANCES_MAXIMUM_TT');
		$output[]		=jr_gettext('_CASTOR_VARIANCES_ISPERCENTAGE', '_CASTOR_VARIANCES_ISPERCENTAGE');
		$output[]		=jr_gettext('_CASTOR_VARIANCES_ISPERCENTAGE_TT', '_CASTOR_VARIANCES_ISPERCENTAGE_TT');
		$output[]		=jr_gettext('_CASTOR_VARIANCES_POSNEG', '_CASTOR_VARIANCES_POSNEG');
		$output[]		=jr_gettext('_CASTOR_VARIANCES_POSNEG_TT', '_CASTOR_VARIANCES_POSNEG_TT');
		$output[]		=jr_gettext('_CASTOR_VARIANCES_VARIANCE', '_CASTOR_VARIANCES_VARIANCE');
		$output[]		=jr_gettext('_CASTOR_VARIANCES_VARIANCE_TT', '_CASTOR_VARIANCES_VARIANCE_TT');


		foreach ($output as $o) {
			echo $o;
			echo "<br/>";
		}
	}

	// This must be included in every Event/Mini-component
	function getRetVals()
	{
		return null;
	}
}

