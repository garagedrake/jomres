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

class j06001listyourproperties
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
		
		$this->retVals = '';

		$thisJRUser = castor_singleton_abstract::getInstance('jr_user');
		$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
		$jrConfig = $siteConfig->get();

		$published = (int) castorGetParam($_POST, 'published', '2');
		$approved = (int) castorGetParam($_POST, 'approved', '2');
		$ptype_id = (int) castorGetParam($_POST, 'ptype', '0');
		
		if (isset($componentArgs[ 'output_now' ])) {
			$output_now = $componentArgs[ 'output_now' ];
		} else {
			$output_now = true;
		}

		$output = array();
		$subsoutput = array();
		$rows = array();
		$subs = array();

		$output[ 'PAGETITLE' ] = jr_gettext('_CASTOR_HLIST_PROPERTIES', '_CASTOR_HLIST_PROPERTIES', false);
		$output[ 'HSTATUS' ] = jr_gettext('_JRPORTAL_INVOICES_STATUS', '_JRPORTAL_INVOICES_STATUS', false);
		$output[ 'HPROPERTYUID' ] = 'Uid';
		$output[ 'HPROPERTYNAME' ] = jr_gettext('_JRPORTAL_PROPERTIES_PROPERTYNAME', '_JRPORTAL_PROPERTIES_PROPERTYNAME', false);
		$output[ 'HPROPERTY_STREET' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_STREET', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_STREET', false);
		$output[ 'HPROPERTY_TOWN' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_TOWN', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_TOWN', false);
		$output[ 'HPROPERTY_REGION' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_REGION', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_REGION', false);
		$output[ 'HPROPERTY_COUNTRY' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_COUNTRY', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_COUNTRY', false);
		$output[ 'HPROPERTY_POSTCODE' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_POSTCODE', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_POSTCODE', false);
		$output[ 'HPROPERTY_TEL' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_TELEPHONE', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_TELEPHONE', false);
		$output[ 'HPROPERTY_EMAIL' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_EMAIL', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_EMAIL', false);
		$output[ 'HPROPERTY_FAX' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_FAX', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_FAX', false);
		$output[ 'HPROPERTY_STARS' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_STARS', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_STARS', false);
		$output[ 'HPROPERTY_SUPERIOR' ] = jr_gettext('CASTOR_SUPERIOR', 'CASTOR_SUPERIOR');
		$output[ 'HPROPERTY_LAT' ] = 'Lat';
		$output[ 'HPROPERTY_LONG' ] = 'Long';
		$output[ 'HAPPROVED' ] = jr_gettext('_CASTOR_HSTATUS_APPROVED', '_CASTOR_HSTATUS_APPROVED', false);
		$output[ 'HACTIVE' ] = jr_gettext('_CASTOR_HSTATUS_CURRENT', '_CASTOR_HSTATUS_CURRENT', false);
		$output[ 'HLASTCHANGED' ] = jr_gettext('_CASTOR_HLASTCHANGED', '_CASTOR_HLASTCHANGED', false);
		$output[ 'HLEGEND' ] = jr_gettext('_CASTOR_HLEGEND', '_CASTOR_HLEGEND', false);
		$output[ 'HNOTCOMPLETED' ] = jr_gettext('CASTOR_INCOMPLETE', 'CASTOR_INCOMPLETE');
		$output[ 'HWAITINGAPPROVAL' ] = jr_gettext('CASTOR_WATING_APPROVAL', 'CASTOR_WATING_APPROVAL');

		if ($thisJRUser->accesslevel > 50) { //higher than receptionist
			$r = array();
			$r['HNEW_PROPERTY'] = jr_gettext('_CASTOR_COM_MR_NEWPROPERTY', '_CASTOR_COM_MR_NEWPROPERTY', false);
			$r['NEW_PROPERTY_URL'] = castorUrl(CASTOR_SITEPAGE_URL.'&task=new_property');
			$rows[] = $r;
		}

		//filters output
		$output['HFILTER'] = jr_gettext('_CASTOR_HFILTER', '_CASTOR_HFILTER', false);
		$output['HPUBLISHED_STATUS'] = jr_gettext('_CASTOR_HSTATUS_PUBLISHING', '_CASTOR_HSTATUS_PUBLISHING', false);
		$output['HPTYPE'] = jr_gettext('_CASTOR_FRONT_PTYPE', '_CASTOR_FRONT_PTYPE', false);

		$options = array();
		$options[] = castorHTML::makeOption('2', jr_gettext('_CASTOR_STATUS_ANY', '_CASTOR_STATUS_ANY', false));
		$options[] = castorHTML::makeOption('1', jr_gettext('_CASTOR_STATUS_PUBLISHED', '_CASTOR_STATUS_PUBLISHED', false));
		$options[] = castorHTML::makeOption('0', jr_gettext('_CASTOR_STATUS_NOT_PUBLISHED', '_CASTOR_STATUS_NOT_PUBLISHED', false));
		$output['PUBLISHED_STATUS'] = castorHTML::selectList($options, 'published', '', 'value', 'text', $published);

		$options = array();
		$options[] = castorHTML::makeOption('2', jr_gettext('_CASTOR_STATUS_ANY', '_CASTOR_STATUS_ANY', false));
		$options[] = castorHTML::makeOption('1', jr_gettext('_CASTOR_COM_MR_YES', '_CASTOR_COM_MR_YES', false));
		$options[] = castorHTML::makeOption('0', jr_gettext('_CASTOR_COM_MR_NO', '_CASTOR_COM_MR_NO', false));
		$output['APPROVED_STATUS'] = castorHTML::selectList($options, 'approved', '', 'value', 'text', $approved);

		//property type filter
		$options = array();
		$options[] = castorHTML::makeOption('0', jr_gettext('_CASTOR_STATUS_ANY', '_CASTOR_STATUS_ANY', false));

		$current_property_details = castor_singleton_abstract::getInstance('basic_property_details');

		foreach ($current_property_details->all_property_type_titles as $k => $v) {
			$options[] = castorHTML::makeOption($k, $v);
		}

		$output['PTYPE'] = castorHTML::selectList($options, 'ptype', '', 'value', 'text', $ptype_id);

		$output['AJAX_URL'] = CASTOR_SITEPAGE_URL_AJAX.'&task=listyourproperties_ajax&published='.$published.'&approved='.$approved.'&ptype='.$ptype_id;

		$pageoutput[ ] = $output;
		$subsoutput[ ] = $subs;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_FRONTEND);
		$tmpl->readTemplatesFromInput('frontend_list_properties.html');
		$tmpl->addRows('pageoutput', $pageoutput);
		$tmpl->addRows('rows', $rows);
		$tmpl->addRows('subs', $subsoutput);
		if ($output_now) {
			$tmpl->displayParsedTemplate();
		} else {
			$this->retVals = $tmpl->getParsedTemplate();
		}
	}


	public function getRetVals()
	{
		return $this->retVals;
	}
}

