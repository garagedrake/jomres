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

	
	/**
	 *
	 * @package Castor\Core\Classes
	 *
	 */

	#[AllowDynamicProperties]

class booking_engine_children_dropdown
{

	/**
	 *
	 * Constructor
	 *
	 */

	public function __construct($bkg)
	{
		if (isset($bkg->available_rooms_for_selected_dates) && !empty($bkg->available_rooms_for_selected_dates)) {
			$this->available_rooms	= $bkg->available_rooms_for_selected_dates;
		} else {
			$this->available_rooms	= [];
		}

		$this->property_uid		= $bkg->property_uid;
		$this->child_numbers	= $bkg->child_numbers;
		$this->cfg_perPersonPerNight = $bkg->cfg_perPersonPerNight;
	}
	
	/**
	 *
	 *	Builds the child dropdowns for the old booking engine (not NBE)
	 *
	 */

	public function build_children_dropdowns()
	{
		$child_dropdowns = array();

		$basic_room_details = castor_singleton_abstract::getInstance('basic_room_details');
		$basic_room_details->get_all_rooms($this->property_uid);

		$total_child_slots_available_these_dates = 0;

		foreach ($this->available_rooms as $room_id) {
			if (isset($basic_room_details->rooms[$room_id])) {
				$total_child_slots_available_these_dates = $total_child_slots_available_these_dates + $basic_room_details->rooms[$room_id]['max_children'];
			}
		}

		$total_slots_already_selected = 0;
		if (!empty($this->child_numbers)) {
			foreach ($this->child_numbers as $child_selection) {
				$total_slots_already_selected = $total_slots_already_selected  + $child_selection;
			}
		}

		$remaining_slots_not_selected = $total_child_slots_available_these_dates - $total_slots_already_selected;
		if ($remaining_slots_not_selected < 0) {
			$remaining_slots_not_selected = 0;
		}

		jr_import('castor_child_rates');
		$castor_child_rates = new castor_child_rates($this->property_uid);

		jr_import('castor_child_policies');
		$castor_child_policies = new castor_child_policies($this->property_uid);

		if (!empty($castor_child_rates->child_rates)) {
			$slots_remaining = $total_child_slots_available_these_dates;
			foreach ($castor_child_rates->child_rates as $id => $rate) {
				if ($rate['age_from'] >= $castor_child_policies->child_policies['child_min_age']) {
					if (isset($this->child_numbers[$id])) { // Some child numbers have already been selected. Because we need to adjust the other child numbers to ensure that too many kids aren't chosen during booking time we need to adjust the remaining numbers
						$selected = $this->child_numbers[$id];
					} else {
						$selected = 0;
					}

					$slots_remaining = $slots_remaining - $selected;

					$guests_dropdown = castorHTML::integerSelectList(0, $remaining_slots_not_selected + $selected, 1, 'child_dropdown['.$id.']', 'size="1" class="input-mini form-select"  autocomplete="off" onchange="getResponse_children('.$id.');"', $selected, '%02d', $use_bootstrap_radios = false);

					if( $this->cfg_perPersonPerNight == '1' ) {
						if ($rate['model'] == 'per_stay') {
							$price_text = output_price($rate['price'])." ".jr_gettext('CASTOR_POLICIES_CHILDREN_CHARGE_MODEL_PER_STAY', 'CASTOR_POLICIES_CHILDREN_CHARGE_MODEL_PER_STAY');
						} else {
							$price_text = output_price($rate['price'])." ".jr_gettext('CASTOR_POLICIES_CHILDREN_CHARGE_MODEL_PER_NIGHT', 'CASTOR_POLICIES_CHILDREN_CHARGE_MODEL_PER_NIGHT');
						}
					} else {
						$price_text = '';
					}

					$child_dropdowns[] = array (
						"CHILD_DROPDOWN" => $guests_dropdown ,
						'TEXT' => jr_gettext('CASTOR_BOOKING_FORM_CHILDREN_AGE_DD', 'CASTOR_BOOKING_FORM_CHILDREN_AGE_DD') ." ".$rate["age_from"]." - ".$rate["age_to"]  ,
						'INFO' => $price_text
					);
				}
			}
		}

		return $child_dropdowns;
	}
}

