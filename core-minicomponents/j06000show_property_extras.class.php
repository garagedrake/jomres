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

class j06000show_property_extras
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
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = true;
			$this->shortcode_data = array(
				'task' => 'show_property_extras',
				'info' => '_CASTOR_SHORTCODES_06000SHOW_PROPERTY_EXTRAS',
				'arguments' => array(0 => array(
						'argument' => 'property_uid',
						'arg_info' => '_CASTOR_SHORTCODES_06000SHOW_PROPERTY_EXTRAS_ARG_PROPERTY_UID',
						'arg_example' => '1',
						),
					),
				);

			return;
		}
		$this->retVals = null;

		if (isset($componentArgs[ 'property_uid' ])) {
			$property_uid = (int)$componentArgs[ 'property_uid' ];
		} else {
			$property_uid = (int)castorGetParam($_REQUEST, 'property_uid', 0);
		}
		
		if ($property_uid == 0) {
			return;
		}

		if (!user_can_view_this_property($property_uid)) {
			return;
		}

		if (isset($componentArgs['output_now'])) {
			$output_now = $componentArgs['output_now'];
		} else {
			$output_now = true;
		}

		$mrConfig = getPropertySpecificSettings($property_uid);

		if ($mrConfig[ 'showExtras' ] == '1') {
			$castor_media_centre_images = castor_singleton_abstract::getInstance('castor_media_centre_images');
			$castor_media_centre_images->get_images($property_uid, array('extras'));

			$jrportal_taxrate = castor_singleton_abstract::getInstance('jrportal_taxrate');

			$extra_details = array();

			$query = 'SELECT `uid`,`name`,`desc`,`maxquantity`,`price`,`auto_select`,`tax_rate`,`chargabledaily`,`property_uid`,`published`,`validfrom`,`validto` FROM `#__castor_extras` WHERE `property_uid` = '.$property_uid.' AND `published` = 1 AND `include_in_property_lists` = 1 ORDER BY `name` ';
			$exList = doSelectSql($query);

			if (!empty($exList)) {
				foreach ($exList as $ex) {
					$price = $ex->price;
					$jrportal_taxrate->gather_data($ex->tax_rate);
					$rate = (float) $jrportal_taxrate->rate;
					if ($mrConfig[ 'prices_inclusive' ] == 1) {
						$divisor = ($rate / 100) + 1;
						$price = $price / $divisor;
					}
					$tax = ($price / 100) * $rate;
					$inc_price = $price + $tax;

					$extra_deets = array();

					$extra_deets[ 'UID' ] = $ex->uid;

					$query = 'SELECT `force`,`model` FROM #__jomcomp_extrasmodels_models WHERE extra_id = '.$ex->uid;
					$model = doSelectSql($query, 2);
					switch ($model[ 'model' ]) {
						case '1': // Per week
							$model_text = jr_gettext('_CASTOR_CUSTOMTEXT_EXTRAMODEL_PERWEEK', '_CASTOR_CUSTOMTEXT_EXTRAMODEL_PERWEEK');
							break;
						case '2': // per days
							$model_text = jr_gettext('_CASTOR_CUSTOMTEXT_EXTRAMODEL_PERDAYS', '_CASTOR_CUSTOMTEXT_EXTRAMODEL_PERDAYS');
							break;
						case '3': // per booking
							$model_text = jr_gettext('_CASTOR_CUSTOMTEXT_EXTRAMODEL_PERBOOKING', '_CASTOR_CUSTOMTEXT_EXTRAMODEL_PERBOOKING');
							break;
						case '4': // per person per booking
							$model_text = jr_gettext('_CASTOR_CUSTOMTEXT_EXTRAMODEL_PERPERSONPERBOOKING', '_CASTOR_CUSTOMTEXT_EXTRAMODEL_PERPERSONPERBOOKING');
							break;
						case '5': // per person per day
							$model_text = jr_gettext('_CASTOR_CUSTOMTEXT_EXTRAMODEL_PERPERSONPERDAY', '_CASTOR_CUSTOMTEXT_EXTRAMODEL_PERPERSONPERDAY');
							break;
						case '6': // per person per week
							$model_text = jr_gettext('_CASTOR_CUSTOMTEXT_EXTRAMODEL_PERPERSONPERWEEK', '_CASTOR_CUSTOMTEXT_EXTRAMODEL_PERPERSONPERWEEK');
							break;
						case '7': // per person per days min days
							$model_text = jr_gettext('_CASTOR_CUSTOMTEXT_EXTRAMODEL_PERDAYSMINDAYS', '_CASTOR_CUSTOMTEXT_EXTRAMODEL_PERDAYSMINDAYS');
							break;
						case '8': // per days per room
							$model_text = jr_gettext('_CASTOR_CUSTOMTEXT_EXTRAMODEL_PERDAYSPERROOM', '_CASTOR_CUSTOMTEXT_EXTRAMODEL_PERDAYSPERROOM');
							break;
						case '9': // per room
							$model_text = jr_gettext('_CASTOR_CUSTOMTEXT_EXTRAMODEL_PERROOMPERBOOKING', '_CASTOR_CUSTOMTEXT_EXTRAMODEL_PERROOMPERBOOKING');
							break;
						case '100': // Commission
							$model_text = jr_gettext('_CASTOR_COMMISSION', '_CASTOR_COMMISSION');
							break;
					}
					$tax_output = '';
					if ($rate > 0) {
						$tax_output = ' ('.$rate.'%)';
					}
					$extra_deets[ 'NAME' ] = jr_gettext('_CASTOR_CUSTOMTEXT_EXTRANAME'.$ex->uid, castor_decode($ex->name));
					$extra_deets[ 'MODELTEXT' ] = $tax_output.' ( '.$model_text.' )';
					
					if ($model[ 'model' ] == '100') {
						$extra_deets[ 'PRICE' ] = $inc_price.'%';
					} else {
						$extra_deets[ 'PRICE' ] = output_price($inc_price);
					}

					$extra_deets[ 'EXTRA_IMAGE' ] = $castor_media_centre_images->multi_query_images['noimage-small'];
					if (isset($castor_media_centre_images->images['extras'][$ex->uid][0]['small'])) {
						$extra_deets[ 'EXTRA_IMAGE' ] = $castor_media_centre_images->images['extras'][$ex->uid][0]['small'];
					}

					if ($mrConfig[ 'wholeday_booking' ] == '1') {
						if ($ex->chargabledaily == '1') {
							$extra_deets[ 'PERNIGHT' ] = jr_gettext('_CASTOR_FRONT_TARIFFS_PN_DAY_WHOLEDAY', '_CASTOR_FRONT_TARIFFS_PN_DAY_WHOLEDAY', false, true);
						} else {
							$extra_deets[ 'PERNIGHT' ] = '';
						}
					} else {
						if ($ex->chargabledaily == '1') {
							$extra_deets[ 'PERNIGHT' ] = jr_gettext('_CASTOR_COM_PERDAY', '_CASTOR_COM_PERDAY', false, true);
						} else {
							$extra_deets[ 'PERNIGHT' ] = '';
						}
					}
					$extra_deets[ 'DESCRIPTION' ] = jr_gettext('_CASTOR_CUSTOMTEXT_EXTRADESC'.$ex->uid, castor_decode($ex->desc));

					$extra_details[ ] = $extra_deets;
				}

				castor_set_page_title( $property_uid ,  jr_gettext('_CASTOR_COM_MR_EXTRA_TITLE', '_CASTOR_COM_MR_EXTRA_TITLE', false) );

				$output = array();
				$output ['_CASTOR_EXTRAS_TEMPLATE'] = jr_gettext('_CASTOR_EXTRAS_TEMPLATE', '_CASTOR_EXTRAS_TEMPLATE');

				$pageoutput[] = $output;
				$tmpl = new patTemplate();
				$tmpl->setRoot(CASTOR_TEMPLATEPATH_FRONTEND);

				$tmpl->addRows('pageoutput', $pageoutput);
				$tmpl->addRows('extras', $extra_details);
				$tmpl->readTemplatesFromInput('show_property_extras.html');
				$extras_template = $tmpl->getParsedTemplate();
				if ($output_now) {
					echo $extras_template;
				} else {
					$this->retVals = $extras_template;
				}
			}
		}
	}

	public function touch_template_language()
	{
		$output = array();

		$output[ ] = jr_gettext('_CASTOR_EXTRAS_TEMPLATE', '_CASTOR_EXTRAS_TEMPLATE');

		foreach ($output as $o) {
			echo $o;
			echo '<br/>';
		}
	}

	public function getRetVals()
	{
		return $this->retVals;
	}
}

