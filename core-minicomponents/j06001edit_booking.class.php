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

class j06001edit_booking
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
			$this->template_touchable = true;

			return;
		}
		$mrConfig = getPropertySpecificSettings();
		$defaultProperty = getDefaultProperty();

		$contract_uid = castorGetParam($_REQUEST, 'contract_uid', 0);
		if ($contract_uid == 0) {
			return;
		}

		$current_property_details = castor_singleton_abstract::getInstance('basic_property_details');
		$current_property_details->gather_data($defaultProperty);

		$current_contract_details = castor_singleton_abstract::getInstance('basic_contract_details');
		$current_contract_details->gather_data($contract_uid, $defaultProperty);

		if (!array_key_exists($contract_uid, $current_contract_details->contract)) {
			return;
		}

		$popup = get_showtime('popup');
		$thisJRUser = castor_singleton_abstract::getInstance('jr_user');

		//check if the booking can be approved or not
		$can_be_approved = true;
		$approval_msg = array();
		
		$noshow = array();
		if ($current_contract_details->contract[$contract_uid]['contractdeets']['noshow_flag'] == '1') {
			$noshow[0]['CLASS'] = 'alert alert-error alert-danger';
			$noshow[0]['MESSAGE'] = jr_gettext('BOOKING_NOSHOW_AUDIT_LOG', 'BOOKING_NOSHOW_AUDIT_LOG', false);
		}
		
		
		
		if ((int) $current_contract_details->contract[$contract_uid]['contractdeets']['approved'] == 0) {
			$rooms_tariffs = $current_contract_details->contract[$contract_uid]['contractdeets']['rooms_tariffs'];
			$date_range_string = $current_contract_details->contract[$contract_uid]['contractdeets']['date_range_string'];
			$dateRangeArray = explode(',', $date_range_string);
			$n = count($dateRangeArray);

			$query = 'SELECT room_uid, `date` FROM #__castor_room_bookings WHERE ';
			for ($i = 0, $n; $i < $n; ++$i) {
				$roomBookedDate = $dateRangeArray[ $i ];
				$selected = explode(',', $rooms_tariffs);
				foreach ($selected as $roomsRequested) {
					$rm = explode('^', $roomsRequested);
					$rmuid = $rm[ 0 ];

					$query .= "(`room_uid` = '".(int) $rmuid."' AND `date` = '".$roomBookedDate."') OR ";
				}
			}

			$query = substr_replace($query, '', -4);
			$result = doSelectSql($query);

			if (!empty($result)) {
				$can_be_approved = false;

				if (using_bootstrap()) {
					$approval_msg['CLASS'] = 'alert alert-error alert-danger';
				} else {
					$approval_msg['CLASS'] = 'ui-state-error';
				}

				$approval_msg['MESSAGE'] = jr_gettext('_CASTOR_CANT_BE_APPROVED', '_CASTOR_CANT_BE_APPROVED', false);
			} else {
				if (using_bootstrap()) {
					$approval_msg['CLASS'] = 'alert alert-success';
				} else {
					$approval_msg['CLASS'] = 'ui-state-default';
				}

				$approval_msg['MESSAGE'] = jr_gettext('_CASTOR_CAN_BE_APPROVED', '_CASTOR_CAN_BE_APPROVED', false);
			}
		}

		//display a message if the booking is cancelled
		if ((int) $current_contract_details->contract[$contract_uid]['contractdeets']['cancelled'] == 1) {
			if (using_bootstrap()) {
				$approval_msg['CLASS'] = 'alert alert-error alert-danger';
			} else {
				$approval_msg['CLASS'] = 'ui-state-error';
			}

			$approval_msg['MESSAGE'] = jr_gettext('_CASTOR_STATUS_CANCELLED', '_CASTOR_STATUS_CANCELLED', false);
		}

		//toolbar
		$lang = substr($current_contract_details->contract[$contract_uid]['contractdeets']['booking_language'], 0, 2);
		if ($thisJRUser->userIsManager) {
			$jrtbar = castor_singleton_abstract::getInstance('castor_toolbar');
			$jrtb = $jrtbar->startTable();
			if (!$popup) {
				//booking approvals
				if ((int) $current_contract_details->contract[$contract_uid]['contractdeets']['approved'] == 0 && isset($MiniComponents->registeredClasses['00005']['booking_enquiries'])) {
					if ($can_be_approved) {
						$output[ 'HAPPROVEBOOKING' ] = jr_gettext('_CASTOR_BOOKING_APPROVE_INQUIRY', '_CASTOR_BOOKING_APPROVE_INQUIRY', $editable = false, $isLink = true);
						$link = CASTOR_SITEPAGE_URL.'&task=approve_enquiry&contractUid='.$contract_uid;
						$targetTask = 'booking_approval';
						$image = CASTOR_IMAGES_RELPATH.'castorimages/'.$jrtbar->imageSize.'/Tick.png';

						$jrtb .= $jrtbar->customToolbarItem($targetTask, $link, $output[ 'HAPPROVEBOOKING' ], $submitOnClick = false, $submitTask = '', $image);
					}

					$output[ 'HREJECTBOOKING' ] = jr_gettext('_CASTOR_BOOKING_REJECT_INQUIRY', '_CASTOR_BOOKING_REJECT_INQUIRY', $editable = false, $isLink = true);
					$link = CASTOR_SITEPAGE_URL.'&task=reject_enquiry&contractUid='.$contract_uid;
					$targetTask = 'booking_rejection';
					$image = CASTOR_IMAGES_RELPATH.'castorimages/'.$jrtbar->imageSize.'/Cancel.png';

					$jrtb .= $jrtbar->customToolbarItem($targetTask, $link, $output[ 'HREJECTBOOKING' ], $submitOnClick = false, $submitTask = '', $image);
				}

				
				$output[ 'HEDIT_GUEST' ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_EDITDETAILS', '_CASTOR_COM_MR_DISPGUEST_EDITDETAILS', $editable = false, $isLink = true);
				$link = CASTOR_SITEPAGE_URL.'&task=edit_guest&id='.$current_contract_details->contract[$contract_uid]['contractdeets']['guest_uid'];
				$targetTask = 'edit_guest';
				$image = CASTOR_IMAGES_RELPATH.'castorimages/'.$jrtbar->imageSize.'/Edit.png';

				$jrtb .= $jrtbar->customToolbarItem($targetTask, $link, $output[ 'HEDIT_GUEST' ], $submitOnClick = false, $submitTask = '', $image);
					
				//amend booking
				$output[ 'HAMENDBOOKING' ] = jr_gettext('_CASTOR_CONFIRMATION_AMEND', '_CASTOR_CONFIRMATION_AMEND', $editable = false, $isLink = true);
				$link = CASTOR_SITEPAGE_URL.'&task=amendBooking&no_html=1&contractUid='.$contract_uid;
				$targetTask = 'amendBooking';
				$image = CASTOR_IMAGES_RELPATH.'castorimages/'.$jrtbar->imageSize.'/HotelReservationEdit.png';

				if ((int) $current_contract_details->contract[$contract_uid]['contractdeets']['bookedout'] != 1 && (int) $current_contract_details->contract[$contract_uid]['contractdeets']['cancelled'] != 1) {
					$jrtb .= $jrtbar->customToolbarItem($targetTask, $link, $output[ 'HAMENDBOOKING' ], $submitOnClick = false, $submitTask = '', $image);
				}

				if (get_showtime('include_room_booking_functionality')) {
					$today = date('Y/m/d');
					if ((int) $current_contract_details->contract[$contract_uid]['contractdeets']['bookedout'] != 1 && (int) $current_contract_details->contract[$contract_uid]['contractdeets']['cancelled'] != 1 && (int) $current_contract_details->contract[$contract_uid]['contractdeets']['approved'] == 1) {
						if ((int) $current_contract_details->contract[$contract_uid]['contractdeets']['booked_in'] == 0) {
							//if ($today >= $current_contract_details->contract[$contract_uid]['contractdeets']['arrival']) {
								$output[ 'HBOOKGUESTIN' ] = jr_gettext('_CASTOR_FRONT_MR_MENU_ADMIN_BOOKAGUESTIN', '_CASTOR_FRONT_MR_MENU_ADMIN_BOOKAGUESTIN', $editable = false, $isLink = true);
								$link = CASTOR_SITEPAGE_URL.'&task=checkin&contract_uid='.$contract_uid;
								$targetTask = 'bookGuestIn';
								$image = CASTOR_IMAGES_RELPATH.'castorimages/'.$jrtbar->imageSize.'/BookGuestIn.png';

								$jrtb .= $jrtbar->customToolbarItem($targetTask, $link, $output[ 'HBOOKGUESTIN' ], $submitOnClick = false, $submitTask = '', $image);
							//}

							if ((int) $current_contract_details->contract[$contract_uid]['contractdeets']['channel_manager_booking'] != 1) {
								if ($current_contract_details->contract[$contract_uid]['contractdeets']['noshow_flag'] != '1') {
									$output[ 'BOOKING_NOSHOW_MENU' ] = jr_gettext('BOOKING_NOSHOW_MENU', 'BOOKING_NOSHOW_MENU', $editable = false, $isLink = true);
									$link = CASTOR_SITEPAGE_URL.'&task=mark_booking_noshow&contract_uid='.$contract_uid.'&property_uid='.$defaultProperty;
									$targetTask = 'mark_booking_noshow';
									
									$jrtb .= $jrtbar->customToolbarItem($targetTask, $link, $output[ 'BOOKING_NOSHOW_MENU' ], $submitOnClick = false, $submitTask = '', $image);
									$jrtb .= $jrtbar->toolbarItem('cancel_booking', castorURL(CASTOR_SITEPAGE_URL.'&task=cancel_booking&popup=1&contract_uid='.$contract_uid), '');
								}
							}
						} else {
							$output[ 'HBOOKGUESTOUT' ] = jr_gettext('_CASTOR_FRONT_MR_MENU_ADMIN_BOOKAGUESTOUT', '_CASTOR_FRONT_MR_MENU_ADMIN_BOOKAGUESTOUT', $editable = false, $isLink = true);
							$link = CASTOR_SITEPAGE_URL.'&task=checkout&contract_uid='.$contract_uid;
							$targetTask = 'bookGuestOut';
							$image = CASTOR_IMAGES_RELPATH.'castorimages/'.$jrtbar->imageSize.'/BookGuestOut.png';

							$jrtb .= $jrtbar->customToolbarItem($targetTask, $link, $output[ 'HBOOKGUESTOUT' ], $submitOnClick = false, $submitTask = '', $image);
						}
					}
				}

				if ((int) $current_contract_details->contract[$contract_uid]['contractdeets']['deposit_paid'] != 1 && (int) $current_contract_details->contract[$contract_uid]['contractdeets']['bookedout'] != 1 && (int) $current_contract_details->contract[$contract_uid]['contractdeets']['cancelled'] != 1) {
					$jrtb .= $jrtbar->toolbarItem('edit_deposit', castorURL(CASTOR_SITEPAGE_URL.'&task=edit_deposit&contractUid='.$contract_uid), '');
				}

				$status = 'status=no,toolbar=yes,scrollbars=yes,titlebar=yes,menubar=yes,resizable=yes,width=710,height=500,directories=no,location=no';
				$link = CASTOR_SITEPAGE_URL.'&task=confirmation_letter&popup=1&tmpl='.get_showtime('tmplcomponent').'&contract_uid='.$contract_uid.'&lang='.$lang;

				if ((int) $current_contract_details->contract[$contract_uid]['contractdeets']['bookedout'] != 1 && (int) $current_contract_details->contract[$contract_uid]['contractdeets']['cancelled'] != 1) {
					$jrtb .= $jrtbar->toolbarItem('addservice', castorURL(CASTOR_SITEPAGE_URL.'&task=add_service_to_bill&contract_uid='.$contract_uid), jr_gettext('_CASTOR_COM_ADDSERVICE_TITLE', '_CASTOR_COM_ADDSERVICE_TITLE', $editable = false, $isLink = false));
				}

				if ((int) $current_contract_details->contract[$contract_uid]['contractdeets']['bookedout'] != 1 && (int) $current_contract_details->contract[$contract_uid]['contractdeets']['cancelled'] != 1) {
					$jrtb .= $jrtbar->toolbarItem('printer', 'javascript:void window.open(\''.$link.'\', \'win2\', \''.$status.'\');', jr_gettext('_CASTOR_COM_CONFIRMATION_PRINT', '_CASTOR_COM_CONFIRMATION_PRINT', $editable = false, $isLink = false));
				}

				$link = CASTOR_SITEPAGE_URL.'&task=confirmation_letter&no_html=1&popup=1&tmpl='.get_showtime('tmplcomponent').'&contract_uid='.$contract_uid.'&sendemail=1&lang='.$lang;
				if ((int) $current_contract_details->contract[$contract_uid]['contractdeets']['bookedout'] != 1 && (int) $current_contract_details->contract[$contract_uid]['contractdeets']['cancelled'] != 1) {
					$jrtb .= $jrtbar->toolbarItem('emailsend', 'javascript:void window.open(\''.$link.'\', \'win2\', \''.$status.'\');', jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_EMAIL', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_EMAIL', $editable = false, $isLink = false));
				}

				if ((int) $current_contract_details->contract[$contract_uid]['contractdeets']['invoice_uid'] > 0) {
					$output[ 'SHOWINVOICE' ] = jr_gettext('_CASTOR_MANAGER_SHOWINVOICE', '_CASTOR_MANAGER_SHOWINVOICE', $editable = false, $isLink = true);
					$link = CASTOR_SITEPAGE_URL.'&task=view_invoice&id='.(int) $current_contract_details->contract[$contract_uid]['contractdeets']['invoice_uid'];
					$targetTask = 'view_invoice';
					$image = CASTOR_IMAGES_RELPATH.'castorimages/'.$jrtbar->imageSize.'/Invoice.png';

					$jrtb .= $jrtbar->customToolbarItem($targetTask, $link, $output[ 'SHOWINVOICE' ], $submitOnClick = false, $submitTask = '', $image);
				}

				$notesLink = CASTOR_SITEPAGE_URL.'&task=addnote&contract_uid='.$contract_uid;
				$jrtb .= $jrtbar->toolbarItem('note', $notesLink, jr_gettext('_JOMCOMP_BOOKINGNOTES_ADD', '_JOMCOMP_BOOKINGNOTES_ADD', $editable = false, $isLink = false));

				if (get_showtime('include_room_booking_functionality') && (int) $current_contract_details->contract[$contract_uid]['contractdeets']['cancelled'] != 1 && isset($MiniComponents->registeredClasses['00005']['castor_ical'])) {
					$output[ 'ICAL_EXPORT' ] = jr_gettext('_CASTOR_ICAL_EVENT', '_CASTOR_ICAL_EVENT', $editable = false, $isLink = true);
					$link = CASTOR_SITEPAGE_URL.'&task=ical_export_contract&contract_uid='.$contract_uid.'&property_uid='.$defaultProperty;
					$targetTask = 'ical_export_contract';
					$image = CASTOR_IMAGES_RELPATH.'calendar.png';

					$jrtb .= $jrtbar->customToolbarItem($targetTask, $link, $output[ 'ICAL_EXPORT' ], $submitOnClick = false, $submitTask = '', $image);
				}
			} else {
				if ($current_contract_details->contract[$contract_uid]['contractdeets']['noshow_flag'] != '1') {
					$output[ 'BOOKING_NOSHOW_MENU' ] = jr_gettext('BOOKING_NOSHOW_MENU', 'BOOKING_NOSHOW_MENU', $editable = false, $isLink = true);
					$link = CASTOR_SITEPAGE_URL.'&task=mark_booking_noshow&contract_uid='.$contract_uid.'&property_uid='.$defaultProperty;
					$targetTask = 'mark_booking_noshow';
					
					$jrtb .= $jrtbar->customToolbarItem($targetTask, $link, $output[ 'BOOKING_NOSHOW_MENU' ], $submitOnClick = false, $submitTask = '', $image);
					
					$jrtb .= $jrtbar->toolbarItem('cancel_booking', castorURL(CASTOR_SITEPAGE_URL.'&task=cancel_booking&popup=1&contract_uid='.$contract_uid), '');
				}
			}
			$jrtb .= $jrtbar->endTable();

			$output = array();
			$pageoutput = array();

			//$output['NETWORK_STATS'] = $MiniComponents->specificEvent('06001', 'show_network_stats_for_contract', array('output_now' => false , 'contract_uid' => $contract_uid , 'property_uid' => $defaultProperty ));
			
			$output[ '_CASTOR_BOOKING_NUMBER' ] = jr_gettext('_CASTOR_BOOKING_NUMBER', '_CASTOR_BOOKING_NUMBER', $editable = true, $isLink = false);

			$output[ 'BOOKING_NUMBER' ] = $current_contract_details->contract[$contract_uid]['contractdeets']['tag'];
			$output[ 'GUEST_FIRSTNAME' ] = $current_contract_details->contract[$contract_uid]['guestdeets']['firstname'];
			$output[ 'GUEST_SURNAME' ] = $current_contract_details->contract[$contract_uid]['guestdeets']['surname'];

			$MiniComponents->triggerEvent('03500');
			$edit_booking_buttons = get_showtime('edit_booking_buttons');

			$new_buttons = array();
			if (!empty($edit_booking_buttons)) {
				foreach ($edit_booking_buttons as $button) {
					$new_buttons[] = array ( 'button' => $button );
				}
			}

			$output[ 'TOOLBAR' ] = $jrtb;

			$pageoutput[] = $output;
			$approval_message[] = $approval_msg;
			$tmpl = new patTemplate();
			$tmpl->setRoot(CASTOR_TEMPLATEPATH_BACKEND);
			$tmpl->readTemplatesFromInput('edit_booking_header.html');
			$tmpl->addRows('pageoutput', $pageoutput);
			$tmpl->addRows('approval_message', $approval_message);
			$tmpl->addRows('noshow_status', $noshow);

			if (!empty($new_buttons)) {
				$tmpl->addRows('new_buttons', $new_buttons);
			}

			echo $tmpl->getParsedTemplate();
		}

		//arr-dep tab
		$output = array();
		$pageoutput = array();

		if ($mrConfig[ 'wholeday_booking' ] == '1') {
			$arrivalText = jr_gettext('_CASTOR_COM_MR_VIEWBOOKINGS_ARRIVAL_WHOLEDAY', '_CASTOR_COM_MR_VIEWBOOKINGS_ARRIVAL_WHOLEDAY');
			$departureText = jr_gettext('_CASTOR_COM_MR_VIEWBOOKINGS_DEPARTURE_WHOLEDAY', '_CASTOR_COM_MR_VIEWBOOKINGS_DEPARTURE_WHOLEDAY');
		} else {
			$arrivalText = jr_gettext('_CASTOR_COM_MR_VIEWBOOKINGS_ARRIVAL', '_CASTOR_COM_MR_VIEWBOOKINGS_ARRIVAL');
			$departureText = jr_gettext('_CASTOR_COM_MR_VIEWBOOKINGS_DEPARTURE', '_CASTOR_COM_MR_VIEWBOOKINGS_DEPARTURE');
		}

		$output[ 'ARRIVALTEXT' ] = $arrivalText;
		$output[ 'BOOKING_ARRIVAL' ] = outputDate($current_contract_details->contract[$contract_uid]['contractdeets']['arrival']);

		$output[ 'DEPARTURETEXT' ] = $departureText;
		$output[ 'BOOKING_DEPARTURE' ] = outputDate($current_contract_details->contract[$contract_uid]['contractdeets']['departure']);

		$nights = $mrConfig[ 'wholeday_booking' ] == '1' ? jr_gettext('_CASTOR_COM_MR_QUICKRES_STEP4_STAYDAYS_WHOLEDAY', '_CASTOR_COM_MR_QUICKRES_STEP4_STAYDAYS_WHOLEDAY', false, false) : jr_gettext('_CASTOR_COM_MR_QUICKRES_STEP4_STAYDAYS', '_CASTOR_COM_MR_QUICKRES_STEP4_STAYDAYS', false, false);

		if (get_showtime('include_room_booking_functionality')) { // Jintour property bookings will probably not want to show this information, so we won't add it
			$output[ 'HNIGHTS' ] = $nights;
			$output[ 'NUM_NIGHTS' ] = count(explode(',', $current_contract_details->contract[$contract_uid]['contractdeets']['date_range_string']));
		}

		if (isset($current_contract_details->contract[$contract_uid]['roomdeets']) && (int)$current_contract_details->contract[$contract_uid]['contractdeets']['approved'] == 1) {
			$roomBooking_black_booking = 0;
			$roomBooking_reception_booking = 0;
			foreach ($current_contract_details->contract[$contract_uid]['roomdeets'] as $rd) {
				if (isset($rd['black_booking']) && isset($rd['reception_booking'])) {
					$roomBooking_black_booking = $rd['black_booking'];
					$roomBooking_reception_booking = $rd['reception_booking'];
				}
			}

			if ((int) $roomBooking_black_booking == 1) {
				$bookingType = jr_gettext('_CASTOR_COM_MR_EB_ROOM_BOOKINGTYPE_BLACK', '_CASTOR_COM_MR_EB_ROOM_BOOKINGTYPE_BLACK');
			} elseif ((int) $roomBooking_reception_booking == 1) {
				$bookingType = jr_gettext('_CASTOR_COM_MR_EB_ROOM_BOOKINGTYPE_RECEPTION', '_CASTOR_COM_MR_EB_ROOM_BOOKINGTYPE_RECEPTION');
			} else {
				$bookingType = jr_gettext('_CASTOR_COM_MR_EB_ROOM_BOOKINGTYPE_INTERNET', '_CASTOR_COM_MR_EB_ROOM_BOOKINGTYPE_INTERNET');
			}
		} else {
			$bookingType = '';
		}

		$output[ '_CASTOR_COM_MR_EB_ROOM_BOOKINGTYPE_EXPL' ] = jr_gettext('_CASTOR_COM_MR_EB_ROOM_BOOKINGTYPE_EXPL', '_CASTOR_COM_MR_EB_ROOM_BOOKINGTYPE_EXPL');
		$output[ 'BOOKINGTYPE' ] = $bookingType;
		$output[ '_CASTOR_COM_MR_ASSIGNUSER_USERNAME' ] = jr_gettext('_CASTOR_COM_MR_ASSIGNUSER_USERNAME', '_CASTOR_COM_MR_ASSIGNUSER_USERNAME');
		$output[ 'BOOKERSUSERNAME' ] = $current_contract_details->contract[$contract_uid]['contractdeets']['username'];

		$output[ '_CASTOR_COM_MR_EB_ROOM_BOOKINGSPECIALREQ' ] = jr_gettext('_CASTOR_COM_MR_EB_ROOM_BOOKINGSPECIALREQ', '_CASTOR_COM_MR_EB_ROOM_BOOKINGSPECIALREQ');
		$output[ 'SPECIALREQS' ] = castor_decode($current_contract_details->contract[$contract_uid]['contractdeets']['special_reqs']);

		$pageoutput[ ] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_BACKEND);
		$tmpl->readTemplatesFromInput('edit_booking_tabcontents_arrdep.html');
		$tmpl->addRows('pageoutput', $pageoutput);
		$arrdep_template = $tmpl->getParsedTemplate();

		//guest tab
		$output = array();
		$pageoutput = array();

		$guest_type_rows = array();
		if (get_showtime('include_room_booking_functionality')) {
			$output[ '_CASTOR_CONFIG_VARIANCES_CUSTOMERTYPES' ] = jr_gettext('_CASTOR_CONFIG_VARIANCES_CUSTOMERTYPES', '_CASTOR_CONFIG_VARIANCES_CUSTOMERTYPES');

			foreach ($current_contract_details->contract[$contract_uid]['guesttype'] as $type) {
				$r = array();
				$r[ 'GUEST_TYPE_TITLE' ] = $type[ 'title' ];
				$r[ 'GUEST_TYPE_QTY' ] = $type[ 'qty' ];
				$guest_type_rows[ ] = $r;
			}
		}

		$output[ '_CASTOR_COM_MR_EB_ARRIVALFIRSTNAME_EXPL' ] = jr_gettext('_CASTOR_COM_MR_EB_ARRIVALFIRSTNAME_EXPL', '_CASTOR_COM_MR_EB_ARRIVALFIRSTNAME_EXPL');
		$output[ 'GUEST_FIRSTNAME' ] = $current_contract_details->contract[$contract_uid]['guestdeets']['firstname'];
		$output[ '_CASTOR_COM_MR_EB_ARRIVALSURNAME_EXPL' ] = jr_gettext('_CASTOR_COM_MR_EB_ARRIVALSURNAME_EXPL', '_CASTOR_COM_MR_EB_ARRIVALSURNAME_EXPL');
		$output[ 'GUEST_SURNAME' ] = $current_contract_details->contract[$contract_uid]['guestdeets']['surname'];
		$output[ '_CASTOR_COM_MR_EB_GUEST_CASTOR_HOUSE_EXPL' ] = jr_gettext('_CASTOR_COM_MR_EB_GUEST_CASTOR_HOUSE_EXPL', '_CASTOR_COM_MR_EB_GUEST_CASTOR_HOUSE_EXPL');
		$output[ 'GUEST_HOUSE' ] = $current_contract_details->contract[$contract_uid]['guestdeets']['house'];
		$output[ '_CASTOR_COM_MR_EB_GUEST_CASTOR_STREET_EXPL' ] = jr_gettext('_CASTOR_COM_MR_EB_GUEST_CASTOR_STREET_EXPL', '_CASTOR_COM_MR_EB_GUEST_CASTOR_STREET_EXPL');
		$output[ 'GUEST_STREET' ] = $current_contract_details->contract[$contract_uid]['guestdeets']['street'];
		$output[ '_CASTOR_COM_MR_EB_GUEST_CASTOR_TOWN_EXPL' ] = jr_gettext('_CASTOR_COM_MR_EB_GUEST_CASTOR_TOWN_EXPL', '_CASTOR_COM_MR_EB_GUEST_CASTOR_TOWN_EXPL');
		$output[ 'GUEST_TOWN' ] = $current_contract_details->contract[$contract_uid]['guestdeets']['town'];
		$output[ '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_REGION' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_REGION', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_REGION');
		$output[ 'GUEST_REGION' ] = $current_contract_details->contract[$contract_uid]['guestdeets']['county'];
		$output[ '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_COUNTRY' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_COUNTRY', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_COUNTRY');
		$output[ 'GUEST_COUNTRY' ] = $current_contract_details->contract[$contract_uid]['guestdeets']['country'];
		$output[ '_CASTOR_COM_MR_EB_GUEST_CASTOR_POSTCODE_EXPL' ] = jr_gettext('_CASTOR_COM_MR_EB_GUEST_CASTOR_POSTCODE_EXPL', '_CASTOR_COM_MR_EB_GUEST_CASTOR_POSTCODE_EXPL');
		$output[ 'GUEST_POSTCODE' ] = $current_contract_details->contract[$contract_uid]['guestdeets']['postcode'];
		$output[ '_CASTOR_COM_MR_EB_GUEST_CASTOR_LANDLINE_EXPL' ] = jr_gettext('_CASTOR_COM_MR_EB_GUEST_CASTOR_LANDLINE_EXPL', '_CASTOR_COM_MR_EB_GUEST_CASTOR_LANDLINE_EXPL');
		$output[ 'GUEST_TEL_LANDLINE' ] = $current_contract_details->contract[$contract_uid]['guestdeets']['tel_landline'];
		$output[ '_CASTOR_COM_MR_EB_GUEST_CASTOR_MOBILE_EXPL' ] = jr_gettext('_CASTOR_COM_MR_EB_GUEST_CASTOR_MOBILE_EXPL', '_CASTOR_COM_MR_EB_GUEST_CASTOR_MOBILE_EXPL');
		$output[ 'GUEST_TEL_MOBILE' ] = $current_contract_details->contract[$contract_uid]['guestdeets']['tel_mobile'];
		$output[ 'HGUEST_VAT_NUMBER' ] = jr_gettext('_CASTOR_COM_YOURBUSINESS_VATNO', '_CASTOR_COM_YOURBUSINESS_VATNO');
		$output[ 'GUEST_VAT_NUMBER' ] = $current_contract_details->contract[$contract_uid]['guestdeets']['vat_number'];
		$output[ '_CASTOR_COM_MR_EB_GUEST_CASTOR_EMAIL_EXPL' ] = jr_gettext('_CASTOR_COM_MR_EB_GUEST_CASTOR_EMAIL_EXPL', '_CASTOR_COM_MR_EB_GUEST_CASTOR_EMAIL_EXPL');

		$output[ 'EMAIL_LINK' ] = 'mailto:'
								.restore_task_specific_email_address($current_contract_details->contract[$contract_uid]['guestdeets']['email'])
								.'?subject='.jr_gettext('_CASTOR_BOOKING_NUMBER', '_CASTOR_BOOKING_NUMBER', false)
								.' '
								.$current_contract_details->contract[$contract_uid]['contractdeets']['tag']
								.' @ '
								.$current_property_details->property_name
								.'&body='.jr_gettext('_CASTOR_COM_CONFIRMATION_DEAR', '_CASTOR_COM_CONFIRMATION_DEAR', false)
								.ucfirst($current_contract_details->contract[$contract_uid]['guestdeets']['firstname'])
								.' '
								.ucfirst($current_contract_details->contract[$contract_uid]['guestdeets']['surname'])
								.' RE '
								.jr_gettext('_CASTOR_BOOKING_NUMBER', '_CASTOR_BOOKING_NUMBER', false)
								.' '
								.$current_contract_details->contract[$contract_uid]['contractdeets']['tag'];

		$output[ 'EMAIL_ADDRESS' ] = restore_task_specific_email_address($current_contract_details->contract[$contract_uid]['guestdeets']['email']);
		$output[ 'GUEST_IMAGE' ] = $current_contract_details->contract[$contract_uid]['guestdeets']['image'];
		
	
		$guest_uid = $current_contract_details->contract[$contract_uid]['contractdeets']['guest_uid'];
		
		jr_import('jrportal_guests');
		$jrportal_guests = new jrportal_guests();
		$jrportal_guests->id = $guest_uid;
		$jrportal_guests->property_uid = $defaultProperty;
		$jrportal_guests->get_guest();

		$output['GUEST_PROFILE'] = $MiniComponents->specificEvent('06000', 'show_user_profile', array('output_now' => false , 'cms_user_id' => $jrportal_guests->cms_user_id ));

		$pageoutput[ ] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_BACKEND);
		$tmpl->readTemplatesFromInput('edit_booking_tabcontents_guest.html');
		$tmpl->addRows('pageoutput', $pageoutput);
		$tmpl->addRows('guest_type_rows', $guest_type_rows);
		$guest_template = $tmpl->getParsedTemplate();

		//rooms tab
		$output = array();
		$pageoutput = array();

		$rooms_tab_replacement = get_showtime('rooms_tab_replacement');

		if (is_null($rooms_tab_replacement)) {
			$room_tab_name = '';
			$room_template = '';

			//only display the rooms tab if the booking is approved
			if ((int)$current_contract_details->contract[$contract_uid]['contractdeets']['approved'] == 1) {
				$rows = array();
				foreach ($current_contract_details->contract[$contract_uid]['roomdeets'] as $rd) {
					$r = array();
					
					$r[ '_CASTOR_COM_MR_EB_ROOM_NAME' ] = jr_gettext('_CASTOR_COM_MR_EB_ROOM_NAME', '_CASTOR_COM_MR_EB_ROOM_NAME');
					
					$r[ '_CASTOR_COM_MR_LISTTARIFF_RATETITLE' ] = jr_gettext('_CASTOR_COM_MR_LISTTARIFF_RATETITLE', '_CASTOR_COM_MR_LISTTARIFF_RATETITLE');
					$r[ 'RINFO_TARIFF' ] = $rd[ 'rate_title' ];
						
					if (isset($rd[ 'room_name' ])) {
						$r[ 'RINFO_NAME' ] = $rd[ 'room_name' ];
					}
					
					if (isset($rd[ 'room_number' ])) {
						$r[ '_CASTOR_COM_MR_EB_ROOM_NUMBER' ] = jr_gettext('_CASTOR_COM_MR_EB_ROOM_NUMBER', '_CASTOR_COM_MR_EB_ROOM_NUMBER');
						$r[ 'RINFO_NUMBER' ] = $rd[ 'room_number' ];
					}
					
					if (isset($rd[ 'room_floor' ])) {
						$r[ '_CASTOR_COM_MR_EB_ROOM_FLOOR' ] = jr_gettext('_CASTOR_COM_MR_EB_ROOM_FLOOR', '_CASTOR_COM_MR_EB_ROOM_FLOOR');
						$r[ 'RINFO_ROOM_FLOOR' ] = $rd[ 'room_floor' ];
					}
					
					if (isset($rd[ 'max_people' ])) {
						$r[ '_CASTOR_COM_MR_EB_ROOM_MAXPEOPLE' ] = jr_gettext('_CASTOR_COM_MR_EB_ROOM_MAXPEOPLE', '_CASTOR_COM_MR_EB_ROOM_MAXPEOPLE');
						$r[ 'RINFO_MAX_PEOPLE' ] = $rd[ 'max_people' ];
					}
					
					
					$r[ '_CASTOR_COM_MR_EB_ROOM_CLASS_ABBV' ] = jr_gettext('_CASTOR_COM_MR_EB_ROOM_CLASS_ABBV', '_CASTOR_COM_MR_EB_ROOM_CLASS_ABBV');
					
					if (isset($rd['room_classes_uid']) && isset($current_property_details->all_room_types[$rd['room_classes_uid']]['room_class_abbv'])) {
						$type = $current_property_details->all_room_types[$rd['room_classes_uid']]['room_class_abbv'];
						$r[ 'TYPE' ] = $type;
					} else { // If a room has been removed ( or the property type changed ) then we don't know anything about the old room.
						$r[ 'TYPE' ] = "Unknown";
					}
					$rows[ ] = $r;
				}

				$pageoutput[ ] = $output;
				$tmpl = new patTemplate();
				$tmpl->setRoot(CASTOR_TEMPLATEPATH_BACKEND);
				$tmpl->readTemplatesFromInput('edit_booking_tabcontents_room.html');
				$tmpl->addRows('pageoutput', $pageoutput);
				$tmpl->addRows('rows', $rows);
				$room_template = $tmpl->getParsedTemplate();
				$room_tab_name = jr_gettext('_CASTOR_COM_MR_EDITBOOKING_TAB_ROOM', '_CASTOR_COM_MR_EDITBOOKING_TAB_ROOM', false);
			}
		} else {
			$room_tab_name = jr_gettext('_JINTOUR_REGPROP_MANAGEMENTPROCESS_TOURS', '_JINTOUR_REGPROP_MANAGEMENTPROCESS_TOURS', false);
			$room_template = $rooms_tab_replacement;
		}

		//payment tab
		$output = array();
		$pageoutput = array();

		$extras_rows = array();

		$taxrate = 0;
		
		$jrportal_taxrate = castor_singleton_abstract::getInstance('jrportal_taxrate');

		if (isset($current_contract_details->contract[$contract_uid]['extradeets'])) {
			foreach ($current_contract_details->contract[$contract_uid]['extradeets'] as $extra) {
				$r = array();
				$quantity = $extra['qty'];
				$price = $extra['price'];
				if ($mrConfig[ 'prices_inclusive' ] == '0') {
					$tax_rate_id = (int) $extra['tax_rate'];
					$jrportal_taxrate->gather_data($tax_rate_id);
					$taxrate = (float) $jrportal_taxrate->rate;
					$tax = ($price / 100) * $taxrate;
					$inc_price = $price + $tax;
				} else {
					$inc_price = $price;
				}

				$extra_tax_output = '';
				if ($taxrate > 0) {
					$extra_tax_output = $taxrate;
				}

				$r[ 'EXTRA_NAME' ] = $extra['name'];
				$r[ 'EXTRA_INCLUSIVE_PRICE' ] = output_price($inc_price);
				$r[ 'EXTRA_TAX' ] = $extra_tax_output;
				$r[ 'EXTRA_QUANTITY' ] = $quantity;
				$extras_rows[ ] = $r;
			}
		}

		$other_services_rows = array();
		$otherServiceTotal = 0.00;
		if (isset($current_contract_details->contract[$contract_uid]['extraservice'])) {
			foreach ($current_contract_details->contract[$contract_uid]['extraservice'] as $e) {
				$service_value = $e['service_value'] * $e['service_qty'];
				$xs_tax = ($service_value / 100) * (float) $e['tax_rate_val'];
				$otherServiceTotal = $otherServiceTotal + ($service_value + $xs_tax);

				$r = array();
				$r[ 'OTHER_SERVICE' ] = $e['service_description'];
				$r[ 'OTHER_SERVICE_VALUE' ] = output_price($service_value + $xs_tax);
				$other_services_rows[ ] = $r;
			}
		}

		if ((int) $current_contract_details->contract[$contract_uid]['contractdeets']['invoice_uid'] > 0) {
			jr_import('jrportal_invoice');
			$invoice = new jrportal_invoice();
			$invoice->id = $current_contract_details->contract[$contract_uid]['contractdeets']['invoice_uid'];
			$remaindertopay = $invoice->get_line_items_balance();
		} else {
			if ((int) $current_contract_details->contract[$contract_uid]['contractdeets']['deposit_paid'] == 1) {
				$remaindertopay = ($otherServiceTotal + $current_contract_details->contract[$contract_uid]['contractdeets']['contract_total']) - $current_contract_details->contract[$contract_uid]['contractdeets']['deposit_required'];
			} else {
				$remaindertopay = $otherServiceTotal + $current_contract_details->contract[$contract_uid]['contractdeets']['contract_total'];
			}
		}

		$output['HROOM_TOTAL'] = jr_gettext('_CASTOR_AJAXFORM_BILLING_ROOM_TOTAL', '_CASTOR_AJAXFORM_BILLING_ROOM_TOTAL');
		$output['ROOM_TOTAL'] = output_price($current_contract_details->contract[$contract_uid]['contractdeets']['room_total']);

		$output['_CASTOR_SEARCH_FORM_ADULTS'] = jr_gettext('_CASTOR_SEARCH_FORM_ADULTS', '_CASTOR_SEARCH_FORM_ADULTS');
		$output['ADULTS'] = $current_contract_details->contract[$contract_uid]['contractdeets']['adults'];
		$output['_CASTOR_SEARCH_FORM_CHILDREN'] = jr_gettext('_CASTOR_SEARCH_FORM_CHILDREN', '_CASTOR_SEARCH_FORM_CHILDREN');
		$output['CHILDREN'] = $current_contract_details->contract[$contract_uid]['contractdeets']['children'];

		if ((int) $current_contract_details->contract[$contract_uid]['contractdeets']['deposit_paid'] == 1) {
			$depositPaid = jr_gettext('_CASTOR_COM_MR_YES', '_CASTOR_COM_MR_YES');
		} else {
			$depositPaid = jr_gettext('_CASTOR_COM_MR_NO', '_CASTOR_COM_MR_NO');
		}

		$output[ '_CASTOR_COM_MR_EB_PAYM_DEPOSIT_PAID' ] = jr_gettext('_CASTOR_COM_MR_EB_PAYM_DEPOSIT_PAID', '_CASTOR_COM_MR_EB_PAYM_DEPOSIT_PAID');
		$output[ 'DEPOSITPAID' ] = $depositPaid;
		$output[ '_CASTOR_COM_MR_EB_PAYM_DEPOSITREQUIRED' ] = jr_gettext('_CASTOR_COM_MR_EB_PAYM_DEPOSITREQUIRED', '_CASTOR_COM_MR_EB_PAYM_DEPOSITREQUIRED');
		$output[ 'BOOKING_DEPOSIT_REQUIRED' ] = output_price($current_contract_details->contract[$contract_uid]['contractdeets']['deposit_required']);
		$output[ '_CASTOR_COM_MR_EB_PAYM_CONTRACT_TOTAL' ] = jr_gettext('_CASTOR_COM_MR_EB_PAYM_CONTRACT_TOTAL', '_CASTOR_COM_MR_EB_PAYM_CONTRACT_TOTAL');
		$output[ 'BOOKING_CONTRACT_TOTAL' ] = output_price($current_contract_details->contract[$contract_uid]['contractdeets']['contract_total']);

		$output[ '_CASTOR_COM_MR_EB_PAYM_DEPOSIT_REF' ] = jr_gettext('_CASTOR_COM_MR_EB_PAYM_DEPOSIT_REF', '_CASTOR_COM_MR_EB_PAYM_DEPOSIT_REF');
		$output[ 'BOOKING_DEPOSIT_REF' ] = $current_contract_details->contract[$contract_uid]['contractdeets']['deposit_ref'];

		if (get_showtime('include_room_booking_functionality')) { // Jintour property bookings will probably not want to show this information, so we won't add it
			$output[ '_CASTOR_COM_A_SUPPLIMENTS_SINGLEPERSON' ] = jr_gettext('_CASTOR_COM_A_SUPPLIMENTS_SINGLEPERSON', '_CASTOR_COM_A_SUPPLIMENTS_SINGLEPERSON');
			$output[ 'SINGLE_PERSON_SUPPLIMENT' ] = output_price($current_contract_details->contract[$contract_uid]['contractdeets']['single_person_suppliment']);
		}

		$output[ '_CASTOR_COM_FRONT_ROOMTAX' ] = jr_gettext('_CASTOR_COM_FRONT_ROOMTAX', '_CASTOR_COM_FRONT_ROOMTAX');
		$output[ 'TAX' ] = output_price($current_contract_details->contract[$contract_uid]['contractdeets']['tax']);

		$output[ '_CASTOR_COM_MR_EXTRA_TITLE' ] = jr_gettext('_CASTOR_COM_MR_EXTRA_TITLE', '_CASTOR_COM_MR_EXTRA_TITLE');
		$output[ '_CASTOR_COM_MR_QUICKRES_STEP4_TOTALINVOICE' ] = jr_gettext('_CASTOR_COM_MR_QUICKRES_STEP4_TOTALINVOICE', '_CASTOR_COM_MR_QUICKRES_STEP4_TOTALINVOICE');
		$output[ 'EXTRASOPTIONSVALUE' ] = output_price($current_contract_details->contract[$contract_uid]['contractdeets']['extrasvalue']);
		$output[ '_CASTOR_COM_ADDSERVICE_BOOKINGDESC' ] = jr_gettext('_CASTOR_COM_ADDSERVICE_BOOKINGDESC', '_CASTOR_COM_ADDSERVICE_BOOKINGDESC');
		$output[ '_CASTOR_COM_INVOICE_LETTER_GRANDTOTAL' ] = jr_gettext('_CASTOR_COM_INVOICE_LETTER_GRANDTOTAL', '_CASTOR_COM_INVOICE_LETTER_GRANDTOTAL');
		$output[ 'GRAND_TOTAL' ] = output_price($otherServiceTotal + $current_contract_details->contract[$contract_uid]['contractdeets']['contract_total']);
		$output[ '_CASTOR_COM_MR_EDITBOOKING_REMAINDERTOPAY' ] = jr_gettext('_CASTOR_COM_MR_EDITBOOKING_REMAINDERTOPAY', '_CASTOR_COM_MR_EDITBOOKING_REMAINDERTOPAY');
		$output[ 'REMAINDER_TO_PAY' ] = output_price($remaindertopay);

		$pageoutput[ ] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_BACKEND);
		$tmpl->readTemplatesFromInput('edit_booking_tabcontents_payment.html');
		$tmpl->addRows('pageoutput', $pageoutput);
		$tmpl->addRows('extras_rows', $extras_rows);
		$tmpl->addRows('other_services_rows', $other_services_rows);
		$payment_template = $tmpl->getParsedTemplate();

		//notes tab
		$output = array();
		$pageoutput = array();
		$note_rows = array();

		foreach ($current_contract_details->contract[$contract_uid]['notedeets'] as $n) {
			$r = array();
			$r[ 'NOTE' ] = $n['note'];
			$r[ 'DATETIME' ] = outputDate($n['timestamp']);
			$r[ 'EDITLINK' ] = CASTOR_SITEPAGE_URL_NOSEF.'&task=editnote&note_id='.$n['id'].'&contract_uid='.(int) $contract_uid;
			$r[ 'EDITTEXT' ] = jr_gettext('_JOMCOMP_BOOKINGNOTES_EDIT', '_JOMCOMP_BOOKINGNOTES_EDIT', $editable = false, $isLink = true);
			$r[ 'DELETELINK' ] = CASTOR_SITEPAGE_URL_NOSEF.'&task=deletenote&note_id='.$n['id'].'&contract_uid='.$contract_uid;
			$r[ 'DELETETEXT' ] = jr_gettext('_JOMCOMP_BOOKINGNOTES_DELETE', '_JOMCOMP_BOOKINGNOTES_DELETE', $editable = false, $isLink = true);
			$note_rows[ ] = $r;
		}

		$pageoutput[ ] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_BACKEND);
		$tmpl->readTemplatesFromInput('view_notes.html');
		$tmpl->addRows('pageoutput', $pageoutput);
		$tmpl->addRows('rows', $note_rows);
		$note_template = $tmpl->getParsedTemplate();

		//generate the tabs
		jr_import('castor_content_tabs');
		$contentPanel = new castor_content_tabs();

		$contentPanel->startTabs();
		$contentPanel->startPanel(jr_gettext('_CASTOR_COM_MR_EDITBOOKING_TAB_ARRIVAL', '_CASTOR_COM_MR_EDITBOOKING_TAB_ARRIVAL', false));
		$contentPanel->setcontent($arrdep_template);
		$contentPanel->insertContent();
		$contentPanel->endPanel();

		$contentPanel->startPanel(jr_gettext('_CASTOR_COM_MR_EDITBOOKING_TAB_GUEST', '_CASTOR_COM_MR_EDITBOOKING_TAB_GUEST', false));
		$contentPanel->setcontent($guest_template);
		$contentPanel->insertContent();
		$contentPanel->endPanel();

		if ($room_tab_name != '') {
			$contentPanel->startPanel($room_tab_name);
			$contentPanel->setcontent($room_template);
			$contentPanel->insertContent();
			$contentPanel->endPanel();
		}

		$contentPanel->startPanel(jr_gettext('_CASTOR_COM_MR_EDITBOOKING_TAB_PAYMENT', '_CASTOR_COM_MR_EDITBOOKING_TAB_PAYMENT', false, false));
		$contentPanel->setcontent($payment_template);
		$contentPanel->insertContent();
		$contentPanel->endPanel();

		$contentPanel->startPanel(jr_gettext('_JOMCOMP_BOOKINGNOTES_VIEW', '_JOMCOMP_BOOKINGNOTES_VIEW', false));
		$contentPanel->setcontent($note_template);
		$contentPanel->insertContent();
		$contentPanel->endPanel();

		$contentPanel->endTabs();
	}

	public function touch_template_language()
	{
		$output = array();
		$output[ ] = jr_gettext('_CASTOR_FRONT_MR_MENU_ADMIN_BOOKAGUESTOUT', '_CASTOR_FRONT_MR_MENU_ADMIN_BOOKAGUESTOUT');
		$output[ ] = jr_gettext('_CASTOR_BOOKING_NUMBER', '_CASTOR_BOOKING_NUMBER');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_EDITBOOKING_TAB_ARRIVAL', '_CASTOR_COM_MR_EDITBOOKING_TAB_ARRIVAL');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_EB_ARRIVALFIRSTNAME_EXPL', '_CASTOR_COM_MR_EB_ARRIVALFIRSTNAME_EXPL');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_EB_ARRIVALSURNAME_EXPL', '_CASTOR_COM_MR_EB_ARRIVALSURNAME_EXPL');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_VIEWBOOKINGS_ARRIVAL', '_CASTOR_COM_MR_VIEWBOOKINGS_ARRIVAL');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_VIEWBOOKINGS_DEPARTURE', '_CASTOR_COM_MR_VIEWBOOKINGS_DEPARTURE');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_EB_ROOM_BOOKINGSPECIALREQ', '_CASTOR_COM_MR_EB_ROOM_BOOKINGSPECIALREQ');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_EDITBOOKING_TAB_GUEST', '_CASTOR_COM_MR_EDITBOOKING_TAB_GUEST');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_EB_GUEST_CASTOR_HOUSE_EXPL', '_CASTOR_COM_MR_EB_GUEST_CASTOR_HOUSE_EXPL');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_EB_GUEST_CASTOR_STREET_EXPL', '_CASTOR_COM_MR_EB_GUEST_CASTOR_STREET_EXPL');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_EB_GUEST_CASTOR_TOWN_EXPL', '_CASTOR_COM_MR_EB_GUEST_CASTOR_TOWN_EXPL');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_REGION', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_REGION');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_COUNTRY', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_COUNTRY');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_EB_GUEST_CASTOR_POSTCODE_EXPL', '_CASTOR_COM_MR_EB_GUEST_CASTOR_POSTCODE_EXPL');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_EB_GUEST_CASTOR_LANDLINE_EXPL', '_CASTOR_COM_MR_EB_GUEST_CASTOR_LANDLINE_EXPL');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_EB_GUEST_CASTOR_MOBILE_EXPL', '_CASTOR_COM_MR_EB_GUEST_CASTOR_MOBILE_EXPL');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_EDITBOOKING_TAB_ROOM', '_CASTOR_COM_MR_EDITBOOKING_TAB_ROOM');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_EB_ROOM_BOOKINGTYPE_BLACK', '_CASTOR_COM_MR_EB_ROOM_BOOKINGTYPE_BLACK');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_EB_ROOM_BOOKINGTYPE_RECEPTION', '_CASTOR_COM_MR_EB_ROOM_BOOKINGTYPE_RECEPTION');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_EB_ROOM_BOOKINGTYPE_INTERNET', '_CASTOR_COM_MR_EB_ROOM_BOOKINGTYPE_INTERNET');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_EB_ROOM_BOOKINGTYPE_EXPL', '_CASTOR_COM_MR_EB_ROOM_BOOKINGTYPE_EXPL');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_YES', '_CASTOR_COM_MR_YES');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_NO', '_CASTOR_COM_MR_NO');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_EB_ROOM_NAME', '_CASTOR_COM_MR_EB_ROOM_NAME');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_EB_ROOM_NUMBER', '_CASTOR_COM_MR_EB_ROOM_NUMBER');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_EB_ROOM_FLOOR', '_CASTOR_COM_MR_EB_ROOM_FLOOR');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_EB_ROOM_DISABLED', '_CASTOR_COM_MR_EB_ROOM_DISABLED');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_EB_ROOM_MAXPEOPLE', '_CASTOR_COM_MR_EB_ROOM_MAXPEOPLE');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_EB_ROOM_CLASS_ABBV', '_CASTOR_COM_MR_EB_ROOM_CLASS_ABBV');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_EB_PAYM_DEPOSIT_PAID', '_CASTOR_COM_MR_EB_PAYM_DEPOSIT_PAID');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_EB_PAYM_DEPOSITREQUIRED', '_CASTOR_COM_MR_EB_PAYM_DEPOSITREQUIRED');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_EB_PAYM_CONTRACT_TOTAL', '_CASTOR_COM_MR_EB_PAYM_CONTRACT_TOTAL');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_QUICKRES_STEP4_STAYDAYS', '_CASTOR_COM_MR_QUICKRES_STEP4_STAYDAYS');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_EB_PAYM_DEPOSIT_REF', '_CASTOR_COM_MR_EB_PAYM_DEPOSIT_REF');
		$output[ ] = jr_gettext('_CASTOR_COM_A_SUPPLIMENTS_SINGLEPERSON', '_CASTOR_COM_A_SUPPLIMENTS_SINGLEPERSON');
		$output[ ] = jr_gettext('_CASTOR_COM_FRONT_ROOMTAX', '_CASTOR_COM_FRONT_ROOMTAX');
		$output[ ] = jr_gettext('_CASTOR_CONFIG_VARIANCES_CUSTOMERTYPES', '_CASTOR_CONFIG_VARIANCES_CUSTOMERTYPES');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_EXTRA_TITLE', '_CASTOR_COM_MR_EXTRA_TITLE');
		$output[ ] = jr_gettext('_CASTOR_COM_ADDSERVICE_BOOKINGDESC', '_CASTOR_COM_ADDSERVICE_BOOKINGDESC');
		$output[ ] = jr_gettext('_CASTOR_COM_INVOICE_LETTER_GRANDTOTAL', '_CASTOR_COM_INVOICE_LETTER_GRANDTOTAL');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_EDITBOOKING_REMAINDERTOPAY', '_CASTOR_COM_MR_EDITBOOKING_REMAINDERTOPAY');
		$output[ ] = jr_gettext('_JOMCOMP_BOOKINGNOTES_VIEW', '_JOMCOMP_BOOKINGNOTES_VIEW');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_QUICKRES_STEP4_TOTALINVOICE', '_CASTOR_COM_MR_QUICKRES_STEP4_TOTALINVOICE');

		foreach ($output as $o) {
			echo $o;
			echo '<br/>';
		}
	}

/**
 * Must be included in every mini-component.
 #
 * Returns any settings that the mini-component wants to send back to the calling script. In addition to being returned to the calling script they are put into an array in the mcHandler object as eg. $mcHandler->miniComponentData[$ePoint][$eName]
 */

	public function getRetVals()
	{
		return null;
	}
}

