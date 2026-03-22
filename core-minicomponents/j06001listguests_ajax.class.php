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

class j06001listguests_ajax
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
		
		jr_import('castor_encryption');
		$this->castor_encryption = new castor_encryption();
		
		$thisJRUser = castor_singleton_abstract::getInstance('jr_user');
		$defaultProperty = getDefaultProperty();

		$basic_property_details = castor_singleton_abstract::getInstance('basic_property_details');
		$basic_property_details->get_property_name_multi($thisJRUser->authorisedProperties);

		$historic = (int) castorGetParam($_GET, 'historic', '2');
		$show_all = (int) castorGetParam($_GET, 'show_all', '0');

		$rows = array();

		//set the table coulmns, in the exact orcer in which they`re displayed in the table
		$aColumns = array('a.guests_uid', 'a.guests_uid', 'a.enc_firstname', 'a.enc_surname', 'a.enc_house', 'a.enc_street', 'a.enc_town', 'a.enc_county', 'a.enc_postcode', 'a.enc_country', 'a.enc_tel_landline', 'a.enc_tel_mobile', 'a.enc_email', 'a.enc_vat_number', 'a.discount', 'a.property_uid');

		//set columns count
		$n = count($aColumns);

		/*
		 * Paging
		 */
		$sLimit = '';
		if (isset($_GET['start']) && $_GET['start'] != '-1') {
			$sLimit = 'LIMIT '.(int)$_GET['start'].', '.(int)$_GET['length'];
		}

		/*
		 * Ordering
		 */
		$sOrder = '';
		if (isset($_GET['jr_order'])) {
			$sOrder = 'ORDER BY ';
			for ($i = 0; $i < $n; ++$i) {
				if (isset($_GET['jr_order'][$i]['column'])) {
					$column_id = (int)$_GET['jr_order'][$i]['column'];
					$sOrder .= ''.$aColumns[$column_id].' '.($_GET['jr_order'][$i]['dir'] === 'asc' ? 'ASC' : 'DESC').', ';
				}
			}
			if ($sOrder == 'ORDER BY ') {
				$sOrder = '';
			} else {
				$sOrder = rtrim($sOrder, ', ');
			}
		}

		/*
		 * Prefilter
		 */
		if ($show_all == 1) {
			$clause = 'WHERE a.property_uid IN ('.castor_implode($thisJRUser->authorisedProperties).') ';
		} else {
			$clause = "WHERE a.property_uid = '".$defaultProperty."' ";
		}

		if ($historic == 0) {
			$clause .= "AND ( b.cancelled = '0' AND b.bookedout = '0' ) ";
		} elseif ($historic == 1) {
			$clause .= "AND ( b.cancelled = '1' OR b.bookedout = '1' ) ";
		}

		/*
		 * Build and execute the query
		 */

		$query = 'SET SQL_BIG_SELECTS=1';
		doInsertSql($query);

		$query = SET_GLOBAL_STRING.
			"
            SELECT SQL_CALC_FOUND_ROWS 
						a.guests_uid, 
						a.mos_userid, 
						a.enc_firstname, 
						a.enc_surname, 
						a.enc_house, 
						a.enc_street, 
						a.enc_town, 
						a.enc_county, 
						a.enc_country, 
						a.enc_postcode, 
						a.enc_tel_landline, 
						a.enc_tel_mobile, 
						a.enc_email, 
						a.enc_vat_number, 
						a.discount,
						a.property_uid 
					FROM #__castor_guests a 
						LEFT JOIN #__castor_contracts b ON a.guests_uid = b.guest_uid "
					.$clause
					.' GROUP BY a.guests_uid '
					.$sOrder
					.' '.$sLimit;
		$castorGuestsList = doSelectSql($query);

		/*
		 * Total number of rows
		 */
		$query = 'SELECT FOUND_ROWS()';
		$mp = (int) doSelectSql($query, 1);
		if ($mp == 0) {
			$output = array(
				'draw' => (int)$_GET['draw'],
				'recordsTotal' => 0,
				'recordsFiltered' => 0,
				'data' => array(),
				);
			echo json_encode($output);
			exit;
		}

		/*
		 * Start building the output array. The columns data should be built in the exact order in which they`ll be displayed in the table.
		 */
		$output = array(
			'draw' => (int)$_GET['draw'],
			'recordsTotal' => $mp,
			'recordsFiltered' => $mp,
			'data' => array(),
		);

		/*
		 * Filtering
		 * NOTE this does not match the built-in DataTables filtering which does it
		 * word by word on any field. It's possible to do here, but concerned about efficiency
		 * on very large tables, and MySQL's regex functionality is very limited
		 */
		$filters = array();
		$search = castorGetParam($_GET, 'jr_search', array());
		if (isset($search['value']) && $search['value'] != '') {
			for ($i = 0; $i < $n; ++$i) {
				$value = filter_var($search['value'], FILTER_SANITIZE_SPECIAL_CHARS);
				$filters[] = $value;
			}
			$filters = array_unique($filters);
		}

		foreach ($castorGuestsList as $g) {
			$r = array();

			$thisProperty = '';
			if ($show_all == 1 && ((int) $g->property_uid != (int) $defaultProperty)) {
				$thisProperty = '&thisProperty='.$g->property_uid;
			}

			if (!using_bootstrap()) {
				$jrtbar = castor_singleton_abstract::getInstance('castor_toolbar');
				$jrtb = $jrtbar->startTable();
				$jrtb .= $jrtbar->toolbarItem('', castorURL(CASTOR_SITEPAGE_URL.'&task=list_invoices&guest_id='.$g->guests_uid), jr_gettext('_CASTOR_MANAGER_SHOWINVOICES', '_CASTOR_MANAGER_SHOWINVOICES', false));
				$jrtb .= $jrtbar->toolbarItem('', castorURL(CASTOR_SITEPAGE_URL.'&task=list_bookings&guest_uid='.$g->guests_uid), jr_gettext('_JRPORTAL_CPANEL_LISTBOOKINGS', '_JRPORTAL_CPANEL_LISTBOOKINGS', false));
				$jrtb .= $jrtbar->toolbarItem('edit', castorURL(CASTOR_SITEPAGE_URL.'&task=edit_guest&id='.$g->guests_uid.$thisProperty), jr_gettext('COMMON_EDIT', 'COMMON_EDIT', false));
				$jrtb .= $jrtbar->toolbarItem('delete', castorURL(CASTOR_SITEPAGE_URL.'&task=delete_guest&id='.$g->guests_uid.$thisProperty), jr_gettext('COMMON_DELETE', 'COMMON_DELETE', false));
				$r[] = $jrtb .= $jrtbar->endTable();
			} else {
				$toolbar = castor_singleton_abstract::getInstance('castorItemToolbar');
				$toolbar->newToolbar();
				$toolbar->addItem('fa fa-pencil-square-o', 'btn btn-info', '', castorURL(CASTOR_SITEPAGE_URL.'&task=edit_guest&id='.$g->guests_uid.$thisProperty), jr_gettext('COMMON_EDIT', 'COMMON_EDIT', false));
				$toolbar->addSecondaryItem('fa fa-file-text', '', '', castorURL(CASTOR_SITEPAGE_URL.'&task=list_invoices&guest_id='.$g->guests_uid), jr_gettext('_CASTOR_MANAGER_SHOWINVOICES', '_CASTOR_MANAGER_SHOWINVOICES', false));
				$toolbar->addSecondaryItem('fa fa-address-book-o', '', '', castorURL(CASTOR_SITEPAGE_URL.'&task=review_guest&guest_id='.$g->guests_uid), jr_gettext('GUEST_PROFILE_REVIEW_GUEST', 'GUEST_PROFILE_REVIEW_GUEST', false));
				$toolbar->addSecondaryItem('fa fa-list', '', '', castorURL(CASTOR_SITEPAGE_URL.'&task=list_bookings&guest_uid='.$g->guests_uid), jr_gettext('_JRPORTAL_CPANEL_LISTBOOKINGS', '_JRPORTAL_CPANEL_LISTBOOKINGS', false));
				$toolbar->addSecondaryItem('fa fa-trash-o', '', '', castorURL(CASTOR_SITEPAGE_URL.'&task=delete_guest&id='.$g->guests_uid.$thisProperty), jr_gettext('COMMON_DELETE', 'COMMON_DELETE', false));
				$r[] = $toolbar->getToolbar();
			}

			$firstname		= $this->castor_encryption->decrypt($g->enc_firstname);
			$surname		= $this->castor_encryption->decrypt($g->enc_surname);
			$house			= $this->castor_encryption->decrypt($g->enc_house);
			$street			= $this->castor_encryption->decrypt($g->enc_street);
			$town			= $this->castor_encryption->decrypt($g->enc_town);
			$county			= castor_decode(find_region_name($this->castor_encryption->decrypt($g->enc_county)));
			$postcode		= $this->castor_encryption->decrypt($g->enc_postcode);
			$country		= $this->castor_encryption->decrypt($g->enc_country);
			$tel_landline	= $this->castor_encryption->decrypt($g->enc_tel_landline);
			$tel_mobile		= $this->castor_encryption->decrypt($g->enc_tel_mobile);
			$email			= restore_task_specific_email_address($this->castor_encryption->decrypt($g->enc_email));
			$vat_number		= $this->castor_encryption->decrypt($g->enc_vat_number);
			
			$found = true;
			
			if (!empty($filters)) {
				foreach ($filters as $filter) {
					if (stristr($firstname, $filter)		== false &&
						stristr($surname, $filter)			== false &&
						stristr($house, $filter)			== false &&
						stristr($street, $filter)			== false &&
						stristr($town, $filter)			== false &&
						stristr($county, $filter)			== false &&
						stristr($postcode, $filter)		== false &&
						stristr($country, $filter)			== false &&
						stristr($tel_landline, $filter)	== false &&
						stristr($tel_mobile, $filter)		== false &&
						stristr($email, $filter)			== false &&
						stristr($vat_number, $filter)		== false
					) {
						$found = false;
					}
				}
			}
			
			if ($found) {
				$r[] = $g->guests_uid;
				$r[] = $basic_property_details->property_names[$g->property_uid];
				$r[] = '<a href="'.castorUrl(CASTOR_SITEPAGE_URL.'&task=show_user_profile&cms_user_id='.$g->mos_userid).'" target="_blank">'.$firstname.'</a>';
				$r[] = '<a href="'.castorUrl(CASTOR_SITEPAGE_URL.'&task=show_user_profile&cms_user_id='.$g->mos_userid).'" target="_blank">'.$surname.'</a>';
				$r[] = $email;
				$r[] = $house;
				$r[] = $street;
				$r[] = $town;
				$r[] = $county;
				$r[] = $postcode;
				$r[] = $country;
				$r[] = $tel_landline;
				$r[] = $tel_mobile;
				$r[] = $vat_number;
				$r[] = $g->discount.'%';


				$output['data'][] = $r;
			}
		}

		/*
		 * Return the json encoded data to populate the table rows
		 */
		echo json_encode($output);
	}


	public function getRetVals()
	{
		return null;
	}
}

