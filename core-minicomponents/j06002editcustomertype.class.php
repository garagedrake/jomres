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
class j06002editcustomertype
{
	function __construct()
	{
		// Must be in all minicomponents. Minicomponents with templates that can contain editable text should run $this->template_touch() else just return
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable=true;
			return;
		}
		
		$ePointFilepath=get_showtime('ePointFilepath');
		
		$defaultProperty=getDefaultProperty();
		
		$id = (int)castorGetParam($_REQUEST, 'id', 0);
		
		jr_import('jrportal_guest_types');
		$jrportal_guest_types = new jrportal_guest_types();
		$jrportal_guest_types->id = $id;
		$jrportal_guest_types->property_uid = $defaultProperty;
		
		$output 	= array();
		$pageoutput = array();
		
		$yesno = array();
		$yesno[] = castorHTML::makeOption('0', jr_gettext("_CASTOR_COM_MR_NO", '_CASTOR_COM_MR_NO', false));
		$yesno[] = castorHTML::makeOption('1', jr_gettext("_CASTOR_COM_MR_YES", '_CASTOR_COM_MR_YES', false));

		$posneg = array();
		$posneg[] = castorHTML::makeOption('0', "-");
		$posneg[] = castorHTML::makeOption('1', "+");

		$output['PAGETITLE']=jr_gettext('_CASTOR_HEDIT_GUEST_TYPE', '_CASTOR_HEDIT_GUEST_TYPE', false);
		
		$output['_CASTOR_GUESTTYPES_INSTRUCTIONS'] 			= jr_gettext('_CASTOR_GUESTTYPES_INSTRUCTIONS', '_CASTOR_GUESTTYPES_INSTRUCTIONS', false);
		$output['_CASTOR_GUESTTYPES_INTRO'] 				= jr_gettext('_CASTOR_GUESTTYPES_INTRO', '_CASTOR_GUESTTYPES_INTRO', false);
		
		$output['HTYPE']=jr_gettext('_CASTOR_VARIANCES_TYPE', '_CASTOR_VARIANCES_TYPE', false);
		$output['HTYPE_TT']=castor_makeTooltip('_CASTOR_VARIANCES_TYPE_TT', $hover_title="", jr_gettext('_CASTOR_VARIANCES_TYPE', '_CASTOR_VARIANCES_TYPE', false), '_CASTOR_VARIANCES_TYPE_TT', $class="", $type="infoimage");
		$output['HTYPE_TT_TEXT']=jr_gettext('_CASTOR_VARIANCES_TYPE_TT', '_CASTOR_VARIANCES_TYPE_TT', false);
		
		$output['HNOTES']=jr_gettext('_CASTOR_VARIANCES_NOTES', '_CASTOR_VARIANCES_NOTES', false);
		$output['HNOTES_TT']=castor_makeTooltip('_CASTOR_VARIANCES_NOTES_TT', $hover_title="", jr_gettext('_CASTOR_VARIANCES_NOTES', '_CASTOR_VARIANCES_NOTES', false), '_CASTOR_VARIANCES_NOTES_TT', $class="", $type="infoimage");
		$output['HNOTES_TT_TEXT']=jr_gettext('_CASTOR_VARIANCES_NOTES_TT', '_CASTOR_VARIANCES_NOTES_TT', false);
		
		$output['HMAXIMUM']=jr_gettext('_CASTOR_VARIANCES_MAXIMUM', '_CASTOR_VARIANCES_MAXIMUM', false);
		$output['HMAXIMUM_TT']=castor_makeTooltip('_CASTOR_VARIANCES_MAXIMUM_TT', $hover_title="", jr_gettext('_CASTOR_VARIANCES_MAXIMUM', '_CASTOR_VARIANCES_MAXIMUM', false), '_CASTOR_VARIANCES_MAXIMUM_TT', $class="", $type="infoimage");
		$output['HMAXIMUM_TT_TEXT']=jr_gettext('_CASTOR_VARIANCES_MAXIMUM_TT', '_CASTOR_VARIANCES_MAXIMUM_TT', false);
		
		$output['HISPERCENTAGE']=jr_gettext('_CASTOR_VARIANCES_ISPERCENTAGE', '_CASTOR_VARIANCES_ISPERCENTAGE', false);
		$output['HISPERCENTAGE_TT']=castor_makeTooltip('_CASTOR_VARIANCES_ISPERCENTAGE', $hover_title="", jr_gettext('_CASTOR_VARIANCES_ISPERCENTAGE', '_CASTOR_VARIANCES_ISPERCENTAGE', false), '_CASTOR_VARIANCES_ISPERCENTAGE', $class="", $type="infoimage");
		$output['HISPERCENTAGE_TT_TEXT']=jr_gettext('_CASTOR_VARIANCES_ISPERCENTAGE_TT', '_CASTOR_VARIANCES_ISPERCENTAGE_TT', false);
		
		$output['HPOSNEG']=jr_gettext('_CASTOR_VARIANCES_POSNEG', '_CASTOR_VARIANCES_POSNEG', false);
		$output['HPOSNEG_TT']=castor_makeTooltip('_CASTOR_VARIANCES_POSNEG', $hover_title="", jr_gettext('_CASTOR_VARIANCES_POSNEG', '_CASTOR_VARIANCES_POSNEG', false), '_CASTOR_VARIANCES_POSNEG_TT', $class="", $type="infoimage");
		$output['HPOSNEG_TT_TEXT']=jr_gettext('_CASTOR_VARIANCES_POSNEG_TT', '_CASTOR_VARIANCES_POSNEG_TT', false);
		
		$output['HVARIANCE']=jr_gettext('_CASTOR_VARIANCES_VARIANCE', '_CASTOR_VARIANCES_VARIANCE', false);
		$output['HVARIANCE_TT']=castor_makeTooltip('_CASTOR_VARIANCES_VARIANCE', $hover_title="", jr_gettext('_CASTOR_VARIANCES_VARIANCE', '_CASTOR_VARIANCES_VARIANCE', false), '_CASTOR_VARIANCES_VARIANCE', $class="", $type="infoimage");
		$output['HVARIANCE_T_TEXT']=jr_gettext('_CASTOR_VARIANCES_VARIANCE_TT', '_CASTOR_VARIANCES_VARIANCE_TT', false);
		
		$output['H_CASTOR_GUESTTYPES_IS_CHILD']=jr_gettext('_CASTOR_GUESTTYPES_IS_CHILD', '_CASTOR_GUESTTYPES_IS_CHILD', false);
		$output['HCASTOR_GUESTTYPES_IS_CHILD_TT']=castor_makeTooltip('_CASTOR_GUESTTYPES_IS_CHILD', $hover_title="", jr_gettext('_CASTOR_GUESTTYPES_IS_CHILD', '_CASTOR_GUESTTYPES_IS_CHILD', false), '_CASTOR_GUESTTYPES_IS_CHILD', $class="", $type="infoimage");
		$output['HCASTOR_GUESTTYPES_IS_CHILD_T_TEXT']=jr_gettext('_CASTOR_GUESTTYPES_IS_CHILD_DESC', '_CASTOR_GUESTTYPES_IS_CHILD_DESC', false);
		
		if ($id > 0 && $jrportal_guest_types->get_guest_type()) {
			$output['ID'] 			= $id;
			$output['TYPE'] 		= $jrportal_guest_types->type;
			$output['NOTES']		= $jrportal_guest_types->notes;
			$output['MAXIMUM']		= $jrportal_guest_types->maximum;
			$output['ISPERCENTAGE']	= castorHTML::selectList($yesno, 'is_percentage', '', 'value', 'text', $jrportal_guest_types->is_percentage);
			$output['POSNEG']		= castorHTML::selectList($posneg, 'posneg', '', 'value', 'text', $jrportal_guest_types->posneg);
			$output['VARIANCE']		= number_format($jrportal_guest_types->variance, 2, '.', '');
			$output['ISCHILD']		= castorHTML::selectList($yesno, 'is_child', '', 'value', 'text', $jrportal_guest_types->is_child);
		} else {
			$output['ID'] 			= 0;
			$output['TYPE']			= "";
			$output['NOTES']		= "";
			$output['MAXIMUM']		= "10";
			$output['ISPERCENTAGE']	= castorHTML::selectList($yesno, 'is_percentage', '', 'value', 'text', "0");
			$output['POSNEG']		= castorHTML::selectList($posneg, 'posneg', '', 'value', 'text', "0");
			$output['VARIANCE']		= number_format(0, 2);
			$output['ISCHILD']		= castorHTML::selectList($yesno, 'is_child', '', 'value', 'text', '0');
		}

		$jrtbar =castor_singleton_abstract::getInstance('castor_toolbar');
		$jrtb  = $jrtbar->startTable();
		$jrtb .= $jrtbar->toolbarItem('save', '', '', true, 'savecustomertype');
		$jrtb .= $jrtbar->toolbarItem('cancel', castorURL(CASTOR_SITEPAGE_URL."&task=listcustomertypes"), '');
		$jrtb .= $jrtbar->endTable();
		$output['CASTORTOOLBAR']=$jrtb;
		
		$pageoutput[]=$output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_BACKEND);
		$tmpl->readTemplatesFromInput('edit_customertype.html');
		$tmpl->addRows('pageoutput', $pageoutput);
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

