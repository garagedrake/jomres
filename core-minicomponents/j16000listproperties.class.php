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

class j16000listproperties
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

		$published = (int) castorGetParam($_POST, 'published', '2');
		$approved = (int) castorGetParam($_POST, 'approved', '2');
		$ptype_id = (int) castorGetParam($_POST, 'ptype', '0');

		if (isset($componentArgs[ 'output_now' ])) {
			$output_now = $componentArgs[ 'output_now' ];
		} else {
			$output_now = true;
		}

		//we`ll show the table in a panel on admin cpanel frontpage
		$show_as_panel = false;
		if (isset($componentArgs['show_as_panel'])) {
			$show_as_panel = (bool) $componentArgs['show_as_panel'];
		}

		$output = array();
		$rows = array();

		$output[ 'PAGETITLE' ] = jr_gettext('_CASTOR_HLIST_PROPERTIES', '_CASTOR_HLIST_PROPERTIES', false);
		$output[ 'WARNING' ] = jr_gettext('_CASTOR_HLIST_PROPERTIES_WARNING', '_CASTOR_HLIST_PROPERTIES_WARNING', false);
		$output[ 'HSTATUS' ] = jr_gettext('COMMON_EDIT', 'COMMON_EDIT');
		$output[ 'HPROPERTYUID' ] = 'Uid';
		$output[ 'HPROPERTYNAME' ] = jr_gettext('_JRPORTAL_PROPERTIES_PROPERTYNAME', '_JRPORTAL_PROPERTIES_PROPERTYNAME');
		$output[ 'HPROPERTY_STREET' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_STREET', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_STREET');
		$output[ 'HPROPERTY_TOWN' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_TOWN', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_TOWN');
		$output[ 'HPROPERTY_REGION' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_REGION', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_REGION');
		$output[ 'HPROPERTY_COUNTRY' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_COUNTRY', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_COUNTRY');
		$output[ 'HPROPERTY_POSTCODE' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_POSTCODE', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_POSTCODE');
		$output[ 'HPROPERTY_TEL' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_TELEPHONE', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_TELEPHONE');
		$output[ 'HPROPERTY_EMAIL' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_EMAIL', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_EMAIL');
		$output[ 'HPROPERTY_FAX' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_FAX', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_FAX');
		$output[ 'HPROPERTY_STARS' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_STARS', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_STARS');
		$output[ 'HPROPERTY_SUPERIOR' ] = jr_gettext('CASTOR_SUPERIOR', 'CASTOR_SUPERIOR');
		$output[ 'HPROPERTY_LAT' ] = 'Lat';
		$output[ 'HPROPERTY_LONG' ] = 'Long';
		$output[ 'HAPPROVED' ] = jr_gettext('_CASTOR_HSTATUS_APPROVED', '_CASTOR_HSTATUS_APPROVED');
		$output[ 'HACTIVE' ] = jr_gettext('_CASTOR_HSTATUS_CURRENT', '_CASTOR_HSTATUS_CURRENT');
		$output[ 'HLASTCHANGED' ] = jr_gettext('_CASTOR_HLASTCHANGED', '_CASTOR_HLASTCHANGED', false);
		$output[ 'HCRATE' ] = jr_gettext('_JRPORTAL_PROPERTIES_COMMISSIONRATE', '_JRPORTAL_PROPERTIES_COMMISSIONRATE', false);
		$output[ 'HLEGEND' ] = jr_gettext('_CASTOR_HLEGEND', '_CASTOR_HLEGEND');
		$output[ 'HNOTCOMPLETED' ] = jr_gettext('CASTOR_INCOMPLETE', 'CASTOR_INCOMPLETE');
		$output[ 'HWAITINGAPPROVAL' ] = jr_gettext('CASTOR_WATING_APPROVAL', 'CASTOR_WATING_APPROVAL');

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

		$output['AJAX_URL'] = CASTOR_SITEPAGE_URL_ADMIN_AJAX.'&task=listproperties_ajax&published='.$published.'&approved='.$approved.'&ptype='.$ptype_id;

		$pageoutput[ ] = $output;

		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_ADMINISTRATOR);
		if (!$show_as_panel) {
			$tmpl->readTemplatesFromInput('admin_listproperties.html');
		} else {
			$tmpl->readTemplatesFromInput('admin_listproperties_panel.html');
		}
		$tmpl->addRows('pageoutput', $pageoutput);
		$tmpl->addRows('rows', $rows);

		if (!$output_now) {
			$this->retVals = $tmpl->getParsedTemplate();
		} else {
			$this->retVals = '';
			$tmpl->displayParsedTemplate();
		}
	}


	public function getRetVals()
	{
		return $this->retVals;
	}
}

