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

/**
#
 * Constructs and displays tariff list
 #
* @package Castor
#
 */
class j06002list_tariffs_advanced
{
	/**
	#
	 * Constructor: Constructs and displays tariff list - admin side
	#
	 */
	function __construct()
	{
		// Must be in all minicomponents. Minicomponents with templates that can contain editable text should run $this->template_touch() else just return
		$MiniComponents =castor_singleton_abstract::getInstance('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable=true;
			return;
		}
		
		$ePointFilepath = get_showtime('ePointFilepath');
		
		$defaultProperty = getDefaultProperty();
		
		$mrConfig = getPropertySpecificSettings();
		
		if ($mrConfig['tariffmode'] != '1' || $mrConfig[ 'is_real_estate_listing' ] == '1' || get_showtime('is_jintour_property')) {
			return;
		}

		$output = array();
		$rows = array();
		
		$current_property_details = castor_singleton_abstract::getInstance('basic_property_details');
		
		$toolbar = castor_singleton_abstract::getInstance('castorItemToolbar');
		
		$basic_rate_details = castor_singleton_abstract::getInstance('basic_rate_details');
		$basic_rate_details->get_rates($defaultProperty);
		
		foreach ($basic_rate_details->rates as $roomclass_uid => $t) {
			foreach ($t as $tarifftype_id => $r) {
				foreach ($r as $rates_uid => $v) {
					$rw = array();
					
					if (!using_bootstrap()) {
						$jrtbar = castor_singleton_abstract::getInstance('castor_toolbar');
						$jrtb  = $jrtbar->startTable();
						$jrtb .= $jrtbar->toolbarItem('edit', castorURL(CASTOR_SITEPAGE_URL."&task=edit_tariff_advanced&tariffUid=".$v['rates_uid']), '');
						$jrtb .= $jrtbar->toolbarItem('copy', castorURL(CASTOR_SITEPAGE_URL."&task=edit_tariff_advanced&tariffUid=".$v['rates_uid']."&clone=1"), '');
						$jrtb .= $jrtbar->toolbarItem('delete', castorURL(CASTOR_SITEPAGE_URL."&task=delete_tariff_advanced&tariffUid=".$v['rates_uid']), '');
						$jrtb .= $jrtbar->endTable();
						$rw['LINKTEXT'] = $jrtb;
					} else {
						$toolbar->newToolbar();
						$toolbar->addItem('icon-edit', 'btn btn-info', '', castorURL(CASTOR_SITEPAGE_URL . '&task=edit_tariff_advanced' . '&tariffUid=' . $v['rates_uid']), jr_gettext('COMMON_EDIT', 'COMMON_EDIT', false));
						$toolbar->addSecondaryItem('icon-copy', '', '', castorURL(CASTOR_SITEPAGE_URL . '&task=edit_tariff_advanced' . '&tariffUid=' . $v['rates_uid'] . "&clone=1"), jr_gettext('_CASTOR_COM_MR_LISTTARIFF_LINKTEXTCLONE', '_CASTOR_COM_MR_LISTTARIFF_LINKTEXTCLONE', false));
						$toolbar->addSecondaryItem('icon-trash', '', '', castorURL(CASTOR_SITEPAGE_URL . '&task=delete_tariff_advanced' . '&tariffUid=' . $v['rates_uid']), jr_gettext('COMMON_DELETE', 'COMMON_DELETE', false));
						$rw['LINKTEXT'] = $toolbar->getToolbar();
					}
					
					$rw['ROOMCLASS'] = $current_property_details->all_room_types[ $roomclass_uid ][ 'room_class_abbv' ];
				
					$rw['ROOMRATEPERDAY'] = output_price($v['roomrateperday']);
					
					$rw['VALIDFROM']	= outputDate($v['validfrom']);
					$rw['VALIDTO']		= outputDate($v['validto']);
					$rw['RATETITLE'] 	= $v['rate_title'];
					//$rw['RATETITLE']	= jr_gettext('_CASTOR_CUSTOMTEXT_TARIFF_TITLE'.$tariff->rates_uid,stripslashes($tariff->rate_title));
					$rw['MINDAYS']		= $v['mindays'];
					$rw['MAXDAYS']		= $v['maxdays'];
					$rw['MINPEOPLE']	= $v['minpeople'];
					$rw['MAXPEOPLE']	= $v['maxpeople'];
					
					if ($v['ignore_pppn']) {
						$rw['IGNOREPPN'] = jr_gettext('_CASTOR_COM_MR_YES', '_CASTOR_COM_MR_YES', false);
					} else {
						$rw['IGNOREPPN'] = jr_gettext('_CASTOR_COM_MR_NO', '_CASTOR_COM_MR_NO', false);
					}

					if ($v['allow_we']) {
						$rw['ALLOWWE'] = jr_gettext('_CASTOR_COM_MR_YES', '_CASTOR_COM_MR_YES', false);
					} else {
						$rw['ALLOWWE'] = jr_gettext('_CASTOR_COM_MR_NO', '_CASTOR_COM_MR_NO', false);
					}

					if ($v['weekendonly']) {
						$rw['WEEKENDONLY'] = jr_gettext('_CASTOR_COM_MR_YES', '_CASTOR_COM_MR_YES', false);
					} else {
						$rw['WEEKENDONLY'] = jr_gettext('_CASTOR_COM_MR_NO', '_CASTOR_COM_MR_NO', false);
					}
					
					$rows[] = $rw;
				}
			}
		}

		$output['HLINKTEXT'] = jr_gettext('_CASTOR_COM_MR_LISTTARIFF_LINKTEXT', '_CASTOR_COM_MR_LISTTARIFF_LINKTEXT', false);
		$output['HLINKTEXTCLONE'] = jr_gettext('_CASTOR_COM_MR_LISTTARIFF_LINKTEXTCLONE', '_CASTOR_COM_MR_LISTTARIFF_LINKTEXTCLONE', false);
		$output['HRATETITLE'] = jr_gettext('_CASTOR_COM_MR_LISTTARIFF_RATETITLE', '_CASTOR_COM_MR_LISTTARIFF_RATETITLE', false) ;
		$output['HRATEDESCRIPTION'] = jr_gettext('_CASTOR_COM_MR_LISTTARIFF_RATEDESCRIPTION', '_CASTOR_COM_MR_LISTTARIFF_RATEDESCRIPTION', false);
		$output['HVALIDFROM'] = jr_gettext('_CASTOR_COM_MR_LISTTARIFF_VALIDFROM', '_CASTOR_COM_MR_LISTTARIFF_VALIDFROM', false);
		$output['HVALIDTO'] = jr_gettext('_CASTOR_COM_MR_LISTTARIFF_VALIDTO', '_CASTOR_COM_MR_LISTTARIFF_VALIDTO', false);
		$output['HMINDAYS'] = jr_gettext('_CASTOR_COM_MR_LISTTARIFF_MINDAYS', '_CASTOR_COM_MR_LISTTARIFF_MINDAYS', false);
		$output['HMAXDAYS'] = jr_gettext('_CASTOR_COM_MR_LISTTARIFF_MAXDAYS', '_CASTOR_COM_MR_LISTTARIFF_MAXDAYS', false);
		$output['HMINPEOPLE'] = jr_gettext('_CASTOR_COM_MR_LISTTARIFF_MINPEOPLE', '_CASTOR_COM_MR_LISTTARIFF_MINPEOPLE', false);
		$output['HMAXPEOPLE'] = jr_gettext('_CASTOR_COM_MR_LISTTARIFF_MAXPEOPLE', '_CASTOR_COM_MR_LISTTARIFF_MAXPEOPLE', false);
		$output['HROOMCLASS'] = jr_gettext('_CASTOR_COM_MR_LISTTARIFF_ROOMCLASS', '_CASTOR_COM_MR_LISTTARIFF_ROOMCLASS', false);
		$output['HIGNOREPPN'] = jr_gettext('_CASTOR_COM_MR_LISTTARIFF_IGNOREPPN', '_CASTOR_COM_MR_LISTTARIFF_IGNOREPPN', false);
		$output['HALLOWWE'] = jr_gettext('_CASTOR_COM_MR_LISTTARIFF_ALLOWWE', '_CASTOR_COM_MR_LISTTARIFF_ALLOWWE', false);
		$output['HWEEKENDONLY'] = jr_gettext('_CASTOR_COM_WEEKENDONLY', '_CASTOR_COM_WEEKENDONLY', false);
		$output['PAGETITLE'] = jr_gettext('_CASTOR_COM_MR_LISTTARIFF_TITLE', '_CASTOR_COM_MR_LISTTARIFF_TITLE', false);
		
		if ($mrConfig['tariffChargesStoredWeeklyYesNo']=="1") {
			$output['HROOMRATEPERDAY']=jr_gettext('_CASTOR_COM_MR_LISTTARIFF_ROOMRATEPERWEEK', '_CASTOR_COM_MR_LISTTARIFF_ROOMRATEPERWEEK', false);
		} else {
			$output['HROOMRATEPERDAY']=jr_gettext('_CASTOR_COM_MR_LISTTARIFF_ROOMRATEPERDAY', '_CASTOR_COM_MR_LISTTARIFF_ROOMRATEPERDAY', false);
		}

		$jrtbar =castor_singleton_abstract::getInstance('castor_toolbar');
		$jrtb  = $jrtbar->startTable();
		$jrtb .= $jrtbar->toolbarItem('new', castorURL(CASTOR_SITEPAGE_URL."&task=edit_tariff_advanced"), '');
		//$jrtb .= $jrtbar->toolbarItem('cancel',castorURL(CASTOR_SITEPAGE_URL.""),'');
		$jrtb .= $jrtbar->endTable();
		$output['CASTORTOOLBAR']=$jrtb;
		
		$pageoutput[]=$output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_BACKEND);
		$tmpl->readTemplatesFromInput('list_advanced_tariffs.html');
		$tmpl->addRows('pageoutput', $pageoutput);
		$tmpl->addRows('rows', $rows);
		$tmpl->displayParsedTemplate();
	}

	function touch_template_language()
	{
		$output=array();

		$output[]		=jr_gettext('_CASTOR_COM_MR_LISTTARIFF_LINKTEXT', '_CASTOR_COM_MR_LISTTARIFF_LINKTEXT');
		$output[]		=jr_gettext('_CASTOR_COM_MR_LISTTARIFF_LINKTEXTCLONE', '_CASTOR_COM_MR_LISTTARIFF_LINKTEXTCLONE');
		$output[]		=jr_gettext('_CASTOR_COM_MR_LISTTARIFF_RATETITLE', '_CASTOR_COM_MR_LISTTARIFF_RATETITLE') ;
		$output[]		=jr_gettext('_CASTOR_COM_MR_LISTTARIFF_RATEDESCRIPTION', '_CASTOR_COM_MR_LISTTARIFF_RATEDESCRIPTION');
		$output[]		=jr_gettext('_CASTOR_COM_MR_LISTTARIFF_VALIDFROM', '_CASTOR_COM_MR_LISTTARIFF_VALIDFROM');
		$output[]		=jr_gettext('_CASTOR_COM_MR_LISTTARIFF_VALIDTO', '_CASTOR_COM_MR_LISTTARIFF_VALIDTO');
		$output[]		=jr_gettext('_CASTOR_COM_MR_LISTTARIFF_ROOMRATEPERWEEK', '_CASTOR_COM_MR_LISTTARIFF_ROOMRATEPERWEEK');
		$output[]		=jr_gettext('_CASTOR_COM_MR_LISTTARIFF_ROOMRATEPERDAY', '_CASTOR_COM_MR_LISTTARIFF_ROOMRATEPERDAY');
		$output[]		=jr_gettext('_CASTOR_COM_MR_LISTTARIFF_MINDAYS', '_CASTOR_COM_MR_LISTTARIFF_MINDAYS');
		$output[]		=jr_gettext('_CASTOR_COM_MR_LISTTARIFF_MAXDAYS', '_CASTOR_COM_MR_LISTTARIFF_MAXDAYS');
		$output[]		=jr_gettext('_CASTOR_COM_MR_LISTTARIFF_MINPEOPLE', '_CASTOR_COM_MR_LISTTARIFF_MINPEOPLE');
		$output[]		=jr_gettext('_CASTOR_COM_MR_LISTTARIFF_MAXPEOPLE', '_CASTOR_COM_MR_LISTTARIFF_MAXPEOPLE');
		$output[]		=jr_gettext('_CASTOR_COM_MR_LISTTARIFF_ROOMCLASS', '_CASTOR_COM_MR_LISTTARIFF_ROOMCLASS');
		$output[]		=jr_gettext('_CASTOR_COM_MR_LISTTARIFF_IGNOREPPN', '_CASTOR_COM_MR_LISTTARIFF_IGNOREPPN');
		$output[]		=jr_gettext('_CASTOR_COM_MR_LISTTARIFF_ALLOWWE', '_CASTOR_COM_MR_LISTTARIFF_ALLOWWE');
		$output[]		=jr_gettext('_CASTOR_COM_WEEKENDONLY', '_CASTOR_COM_WEEKENDONLY');
		$output[]		=jr_gettext('_CASTOR_COM_MR_LISTTARIFF_TITLE', '_CASTOR_COM_MR_LISTTARIFF_TITLE');

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

