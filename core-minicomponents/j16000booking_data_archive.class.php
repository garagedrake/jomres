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

class j16000booking_data_archive
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
		$rows = array();

		$output[ 'PAGETITLE' ] = jr_gettext('_CASTOR_DATA_ARCHIVE_TITLE', '_CASTOR_DATA_ARCHIVE_TITLE', false);
		$output[ 'INFO' ] = jr_gettext('_CASTOR_DATA_ARCHIVE_TITLE_DESC', '_CASTOR_DATA_ARCHIVE_TITLE_DESC', false);

		$output[ '_CASTOR_SORTORDER_PROPERTYNAME' ] = jr_gettext('_CASTOR_SORTORDER_PROPERTYNAME', '_CASTOR_SORTORDER_PROPERTYNAME', false);
		$output[ '_JRPORTAL_LISTBOOKINGS_HEADER_DATEARCHIVED' ] = jr_gettext('_JRPORTAL_LISTBOOKINGS_HEADER_DATEARCHIVED', '_JRPORTAL_LISTBOOKINGS_HEADER_DATEARCHIVED', false);

		$query = 'SELECT id,data,date FROM #__castor_booking_data_archive LIMIT 500';
		$result = doSelectSql($query);
		foreach ($result as $res) {
			$r = array();
			// data comes in two arrays, tmpbooking and tmpguest. We'll cycle through these two sub arrays to construct the popup's contents.
			$popup_content = '';
			$data_arrays = unserialize($res->data);

			if ((int) $data_arrays[ 'tmpbooking' ][ 'property_uid' ] > 0) {
				$property_uid = (int) $data_arrays[ 'tmpbooking' ][ 'property_uid' ];
				$r[ 'PROPERTY_NAME' ] = getPropertyName($property_uid);

				foreach ($data_arrays[ 'tmpbooking' ] as $key => $val) {
					if (is_array($val)) {
						$popup_content .= '<b>'.$key.'</b> : '.serialize($val).' ::: ';
					} else {
						$popup_content .= '<b>'.$key.'</b> : '.str_replace('"', '', $val).' ::: ';
					}
				}
				foreach ($data_arrays[ 'tmpguest' ] as $key => $val) {
					$popup_content .= '<b>'.$key.'</b> : '.str_replace('"', '', $val).' ::: ';
				}

				$data = castor_makeTooltip('xxx'.$res->id, $r[ 'PROPERTY_NAME' ], $popup_content, $res->date);
				$r[ 'DATE_TOOTIP' ] = $data;

				$rows[ ] = $r;
			}
		}

		$jrtbar = castor_singleton_abstract::getInstance('castor_toolbar');
		$jrtb = $jrtbar->startTable();
		$jrtb .= $jrtbar->toolbarItem('cancel', CASTOR_SITEPAGE_URL_ADMIN, jr_gettext('_JRPORTAL_CANCEL', '_JRPORTAL_CANCEL', false));
		$jrtb .= $jrtbar->endTable();
		$output[ 'CASTORTOOLBAR' ] = $jrtb;

		$pageoutput[ ] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_ADMINISTRATOR);
		$tmpl->readTemplatesFromInput('booking_data_archive.html');
		$tmpl->addRows('pageoutput', $pageoutput);
		$tmpl->addRows('rows', $rows);
		$tmpl->displayParsedTemplate();
	}


	public function getRetVals()
	{
		return null;
	}
}

