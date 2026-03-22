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

class j06000view_agent
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
			$this->shortcode_data = array(
				'task' => 'view_agent',
				'info' => '_CASTOR_SHORTCODES_06000VIEW_AGENT',
				'arguments' => array(
					array(
						'argument' => 'property_uid',
						'arg_info' => '_CASTOR_SHORTCODES_06000VIEW_AGENT_ARG_PROPERTY_UID',
						'arg_example' => '1',
						),
					array(
						'argument' => 'id',
						'arg_info' => '_CASTOR_SHORTCODES_06000VIEW_AGENT_ARG_ID',
						'arg_example' => '1',
						),
					),
				);

			return;
		}
		$MiniComponents->triggerEvent('01004', $componentArgs); // optional
		$MiniComponents->triggerEvent('01005', $componentArgs); // optional
		$MiniComponents->triggerEvent('01006', $componentArgs); // optional
		$MiniComponents->triggerEvent('01007', $componentArgs); // optional

		jr_import('castor_encryption');
		$castor_encryption = new castor_encryption();
				
		$output = array();
		$this->retVals = '';

		$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
		$jrConfig = $siteConfig->get();

		$property_manager_xref = get_showtime('property_manager_xref');
		if (is_null($property_manager_xref)) {
			$property_manager_xref = build_property_manager_xref_array();
		}
		
		if (isset($componentArgs[ 'property_uid' ])) {
			$property_uid = (int)$componentArgs[ 'property_uid' ];
		} else {
			$property_uid = (int)castorGetParam($_REQUEST, 'property_uid', 0);
		}

		if ($property_uid > 0) {
			if (array_key_exists($property_uid, $property_manager_xref)) {
				$manager_id = $property_manager_xref[ $property_uid ];
			} else {
				return;
			}
		} else {
			$manager_id = castorGetParam($_REQUEST, 'id', 0);
		}

		$query = 'SELECT manager_uid  FROM #__castor_managers WHERE userid  = '.(int) $manager_id;
		$result = doSelectSql($query, 1);
		if (!$result) { // this id doesn't correspond to a manager in the system, progress no further
			return;
		}

		$output[ 'HFIRSTNAME' ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_FIRSTNAME', '_CASTOR_COM_MR_DISPGUEST_FIRSTNAME');
		$output[ 'HSURNAME' ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_SURNAME', '_CASTOR_COM_MR_DISPGUEST_SURNAME');
		$output[ 'HHOUSE' ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_HOUSE', '_CASTOR_COM_MR_DISPGUEST_HOUSE');
		$output[ 'HSTREET' ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_STREET', '_CASTOR_COM_MR_DISPGUEST_STREET');
		$output[ 'HTOWN' ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_TOWN', '_CASTOR_COM_MR_DISPGUEST_TOWN');
		$output[ 'HREGION' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_REGION', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_REGION');
		$output[ 'HCOUNTRY' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_COUNTRY', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_COUNTRY');
		$output[ 'HPOSTCODE' ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_POSTCODE', '_CASTOR_COM_MR_DISPGUEST_POSTCODE');
		$output[ 'HLANDLINE' ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_LANDLINE', '_CASTOR_COM_MR_DISPGUEST_LANDLINE');
		$output[ 'HMOBILE' ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_MOBILE', '_CASTOR_COM_MR_DISPGUEST_MOBILE');
		$output[ 'HFAX' ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_FAX', '_CASTOR_COM_MR_DISPGUEST_FAX');
		$output[ 'HEMAIL' ] = jr_gettext('_CASTOR_COM_MR_EB_GUEST_CASTOR_EMAIL_EXPL', '_CASTOR_COM_MR_EB_GUEST_CASTOR_EMAIL_EXPL');
		$output[ '_CASTOR_AGENT_DETAILS' ] = jr_gettext('_CASTOR_AGENT_DETAILS', '_CASTOR_AGENT_DETAILS');
		$output[ '_CASTOR_AGENT_LISTINGS' ] = jr_gettext('_CASTOR_AGENT_LISTINGS', '_CASTOR_AGENT_LISTINGS');

		$query = 'SELECT enc_firstname,enc_surname,enc_house,enc_street,enc_town,enc_county,enc_country,enc_postcode,enc_tel_landline,enc_tel_mobile,enc_email FROM #__castor_guest_profile WHERE cms_user_id = '.(int) $manager_id.' LIMIT 1';
		$managerData = doSelectSql($query);

		if (!empty($managerData)) {
			foreach ($managerData as $data) {
				$output[ 'FIRSTNAME' ] = $castor_encryption->decrypt($data->enc_firstname);
				$output[ 'SURNAME' ] = $castor_encryption->decrypt($data->enc_surname);
				if (get_showtime("task") == "view_agent") {
					castor_set_page_title( 0 , castor_purify_html($castor_encryption->decrypt($data->enc_firstname)." ".$castor_encryption->decrypt($data->enc_surname)) );
				}
				
				$output[ 'HOUSE' ] = $castor_encryption->decrypt($data->enc_house);
				$output[ 'STREET' ] = $castor_encryption->decrypt($data->enc_street);
				$output[ 'TOWN' ] = $castor_encryption->decrypt($data->enc_town);
				$output[ 'REGION' ] = $castor_encryption->decrypt($data->enc_county);
				if (is_numeric($castor_encryption->decrypt($data->enc_county))) {
					$castor_regions = castor_singleton_abstract::getInstance('castor_regions');
					$output[ 'REGION' ] = jr_gettext('_CASTOR_CUSTOMTEXT_REGIONS_'.$data->enc_county, $castor_regions->get_region_name($castor_encryption->decrypt($data->enc_county)), false, false);
				} else {
					$output[ 'REGION' ] = jr_gettext('_CASTOR_CUSTOMTEXT_PROPERTY_REGION'.$castor_encryption->decrypt($data->enc_county), $castor_encryption->decrypt($data->enc_county), false, false);
				}
				$output[ 'COUNTRY' ] = getSimpleCountry($castor_encryption->decrypt($data->enc_country));
				$output[ 'POSTCODE' ] = $castor_encryption->decrypt($data->enc_postcode);
				$output[ 'LANDLINE' ] = $castor_encryption->decrypt($data->enc_tel_landline);
				$output[ 'MOBILE' ] = $castor_encryption->decrypt($data->enc_tel_mobile);
				$output[ 'EMAIL' ] = castor_hide_email($castor_encryption->decrypt($data->enc_email));

				$castor_media_centre_images = castor_singleton_abstract::getInstance('castor_media_centre_images');
				$castor_media_centre_images->get_site_images('userimages');
				
				if (isset($castor_media_centre_images->site_images['userimages'][$manager_id][0]['small'])) {
					$output[ 'IMAGE' ] = $castor_media_centre_images->site_images['userimages'][$manager_id][0]['small'];
				}
			}
		}
/* 		else
			{
			echo "Sorry, no manager data is available for that property";
			return;
			} */
		$pageoutput[ ] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_FRONTEND);
		$tmpl->readTemplatesFromInput('view_agent.html');
		$tmpl->addRows('pageoutput', $pageoutput);
		$template = $tmpl->getParsedTemplate();

		if (!isset($componentArgs[ 'output_now' ])) {
			$componentArgs[ 'output_now' ] = true;
		}

		if ($componentArgs[ 'output_now' ]) {
			echo $template;
		} else {
			$this->retVals = $template;
		}

		if ($componentArgs[ 'output_now' ]) { // We'll also include a list of the manager's properties.
			$property_uids = array();
			foreach ($property_manager_xref as $property_id => $m_id) {
				if ($m_id == $manager_id) {
					$property_uids[ ] = $property_id;
				}
			}

			$gOr = genericOr($property_uids, 'propertys_uid');
			$query = 'SELECT propertys_uid FROM #__castor_propertys WHERE approved = 1 AND `published` = 1 AND propertys_uid IN ('.castor_implode($property_uids).') ';
			$result = doSelectSql($query);

			$property_uids = array();
			foreach ($result as $property) {
				$property_uids[ ] = $property->propertys_uid;
			}

			$componentArgs = array();
			$componentArgs[ 'propertys_uid' ] = $property_uids;
			$MiniComponents->specificEvent('01010', 'listpropertys', $componentArgs);
		}
	}


	public function getRetVals()
	{
		return $this->retVals;
	}
}

