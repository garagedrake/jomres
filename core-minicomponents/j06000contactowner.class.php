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

class j06000contactowner
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
			$this->template_touchable = true;
			$this->shortcode_data = array(
				'task' => 'contactowner',
				'info' => '_CASTOR_SHORTCODES_06000CONTACTOWNER',
				'arguments' => array(0 => array(
					'argument' => 'property_uid',
					'arg_info' => '_CASTOR_SHORTCODES_06000CONTACTOWNER_ARG_PROPERTY_UID',
					'arg_example' => '18',
				),
				),
			);

			return;
		}

		$this->ret_vals = '';

		$castor_gdpr_optin_consent = new castor_gdpr_optin_consent();
		if (!$castor_gdpr_optin_consent->user_consents_to_storage()&& !isset($_REQUEST['skip_consent_form'])) {
			//castorRedirect(castorURL(CASTOR_SITEPAGE_URL.'&task=opted_out&jr_redirect_url='.getCurrentUrl()), '');
			$consent_form = $MiniComponents->specificEvent('06000', 'show_consent_form', array ('output_now' => false));
			if (isset($componentArgs[ 'noshownow' ])) {
				$this->ret_vals = $consent_form;
			} else {
				echo $consent_form;
			}
		} else {
			$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
			$jrConfig = $siteConfig->get();

			$use_recaptcha = false;
			if ($jrConfig[ 'recaptcha_public_key' ] != '' && $jrConfig[ 'recaptcha_private_key' ] != '') {
				$use_recaptcha = true;
			}

			$tmpBookingHandler = castor_singleton_abstract::getInstance('castor_temp_booking_handler');

			if ($use_recaptcha) {
				$recaptcha = new \ReCaptcha\ReCaptcha(trim($jrConfig[ 'recaptcha_private_key' ]), new \ReCaptcha\RequestMethod\CurlPost());
			}

			$property_uid = (int)castorGetParam($_REQUEST, 'property_uid', 0);
			$selectedProperty = (int)castorGetParam($_REQUEST, 'selectedProperty', 0);

			if (isset($componentArgs['property_uid'])) {
				$property_uid = $componentArgs['property_uid'];
			}

			if ($property_uid == 0) {
				if (isset($componentArgs[ 'property_uid' ])) {
					$property_uid = intval($componentArgs[ 'property_uid' ]);
				} else {
					$property_uid = $selectedProperty;
					castor_cmsspecific_setmetadata('robots', 'noindex,nofollow');
				}
			}

			if (isset($_POST[ 'property_uid' ])) {
				$property_uid = (int)castorGetParam($_POST, 'property_uid', 0);
			}

			if (isset($componentArgs[ 'property_uid' ])) {
				$property_uid = intval($componentArgs[ 'property_uid' ]);
			}

			if ($property_uid > 0) {
				set_showtime('last_viewed_property_uid', $property_uid);
				if (!user_can_view_this_property($property_uid)) {
					return;
				} else {
					if (!isset($componentArgs[ 'noshownow' ])) {
						/*property_header($property_uid);*/
					}
				}
			}

			$no_title = false;
			if (isset($componentArgs[ 'no_title' ])) {
				$no_title = (bool)$componentArgs[ 'no_title' ];
			}

			$output = array();

			if ($property_uid > 0) {
				$current_property_details = castor_singleton_abstract::getInstance('basic_property_details');
				$current_property_details->gather_data($property_uid);

				$target = $current_property_details->property_name;
				$output[ 'PAGETITLE' ] = jr_gettext('_CASTOR_FRONT_MR_MENU_CONTACTHOTEL', '_CASTOR_FRONT_MR_MENU_CONTACTHOTEL');
			} else {
				$target = $jrConfig['business_name'];
				$output[ 'PAGETITLE' ] = jr_gettext('_CASTOR_CONTANT_US', '_CASTOR_CONTANT_US');
			}

			if ($no_title) {
				$output[ 'PAGETITLE' ] = '';
			}
			$output[ 'SUBJECT' ] = jr_gettext('_CASTOR_FRONT_MR_MENU_CONTACTHOTEL_TITLE', '_CASTOR_FRONT_MR_MENU_CONTACTHOTEL_TITLE').' '.$target;
			$output[ 'ENQUIRY' ] = castorGetParam($_REQUEST, 'enquiry', jr_gettext('_CASTOR_FRONT_MR_MENU_CONTACTHOTEL_YOUR_ENQUIRY', '_CASTOR_FRONT_MR_MENU_CONTACTHOTEL_YOUR_ENQUIRY', false));

			$output[ 'GUEST_NAME' ] = castorGetParam($_REQUEST, 'guest_name', '');
			$output[ 'PROPERTY_UID' ] = $property_uid;

			$guest_email = castorGetParam($_REQUEST, 'guest_email', '');

			if ($guest_email != '') {
				$output[ 'GUEST_EMAIL' ] = $guest_email;
			} elseif (isset($tmpBookingHandler->tmpguest[ 'email' ])) {
				$output[ 'GUEST_EMAIL' ] = $tmpBookingHandler->tmpguest[ 'email' ];
			} else {
				$output[ 'GUEST_EMAIL' ] = '';
			}

			$guest_phone = castorGetParam($_REQUEST, 'guest_phone', '');

			if ($guest_phone != '') {
				$output[ 'GUEST_PHONE' ] = $guest_phone;
			} elseif (isset($tmpBookingHandler->tmpguest[ 'tel_mobile' ])) {
				$output[ 'GUEST_PHONE' ] = $tmpBookingHandler->tmpguest[ 'tel_mobile' ];
			} else {
				$output[ 'GUEST_PHONE' ] = '';
			}

			$output[ 'HEMAIL' ] = jr_gettext('_CASTOR_COM_MR_EB_GUEST_CASTOR_EMAIL_EXPL', '_CASTOR_COM_MR_EB_GUEST_CASTOR_EMAIL_EXPL');
			$output[ 'HNAME' ] = jr_gettext('_CASTOR_FRONT_MR_EMAIL_TEXT_NAME', '_CASTOR_FRONT_MR_EMAIL_TEXT_NAME');
			$output[ 'HENQUIRY' ] = jr_gettext('_CASTOR_FRONT_MR_MENU_CONTACTHOTEL_ENQUIRY', '_CASTOR_FRONT_MR_MENU_CONTACTHOTEL_ENQUIRY');
			$output[ 'PLACEHOLDER_ENQUIRY' ] = jr_gettext('_CASTOR_FRONT_MR_MENU_CONTACTHOTEL_ENQUIRY', '_CASTOR_FRONT_MR_MENU_CONTACTHOTEL_ENQUIRY', false);
			$output[ 'HPHONE' ] = jr_gettext('_CASTOR_COM_MR_EB_GUEST_CASTOR_MOBILE_EXPL', '_CASTOR_COM_MR_EB_GUEST_CASTOR_MOBILE_EXPL');
			$output[ 'PLACEHOLDER_PHONE' ] = jr_gettext('_CASTOR_COM_MR_EB_GUEST_CASTOR_MOBILE_EXPL', '_CASTOR_COM_MR_EB_GUEST_CASTOR_MOBILE_EXPL', false);

			$output[ 'CASTOR_REGISTRATION_INSTRUCTIONS_STEP2_2' ] = jr_gettext('_CASTOR_REGISTRATION_INSTRUCTIONS_STEP2_2', '_CASTOR_REGISTRATION_INSTRUCTIONS_STEP2_2', false, false);
			$output[ 'CASTOR_REGISTRATION_INSTRUCTIONS_STEP2_2_BUTTON' ] = jr_gettext('_CASTOR_REGISTRATION_INSTRUCTIONS_STEP2_2', '_CASTOR_REGISTRATION_INSTRUCTIONS_STEP2_2', false, false);

			$output[ 'CASTOR_RECAPTCHA_INSTRUCTIONS_VISUAL' ] = jr_gettext('CASTOR_RECAPTCHA_INSTRUCTIONS_VISUAL', 'CASTOR_RECAPTCHA_INSTRUCTIONS_VISUAL', false, false);
			$output[ 'CASTOR_RECAPTCHA_INSTRUCTIONS_AUDIO' ] = jr_gettext('CASTOR_RECAPTCHA_INSTRUCTIONS_AUDIO', 'CASTOR_RECAPTCHA_INSTRUCTIONS_AUDIO', false, false);
			$output[ 'CASTOR_RECAPTCHA_PLAY_AGAIN' ] = jr_gettext('CASTOR_RECAPTCHA_PLAY_AGAIN', 'CASTOR_RECAPTCHA_PLAY_AGAIN', false, false);
			$output[ 'CASTOR_RECAPTCHA_CANT_HEAR_THIS' ] = jr_gettext('CASTOR_RECAPTCHA_CANT_HEAR_THIS', 'CASTOR_RECAPTCHA_CANT_HEAR_THIS', false, false);
			$output[ 'CASTOR_RECAPTCHA_VISUAL_CHALLENGE' ] = jr_gettext('CASTOR_RECAPTCHA_VISUAL_CHALLENGE', 'CASTOR_RECAPTCHA_VISUAL_CHALLENGE', false, false);
			$output[ 'CASTOR_RECAPTCHA_AUDIO_CHALLENGE' ] = jr_gettext('CASTOR_RECAPTCHA_AUDIO_CHALLENGE', 'CASTOR_RECAPTCHA_AUDIO_CHALLENGE', false, false);
			$output[ 'CASTOR_RECAPTCHA_REFRESH_BTN' ] = jr_gettext('CASTOR_RECAPTCHA_REFRESH_BTN', 'CASTOR_RECAPTCHA_REFRESH_BTN', false, false);
			$output[ 'CASTOR_RECAPTCHA_HELP_BTN' ] = jr_gettext('CASTOR_RECAPTCHA_HELP_BTN', 'CASTOR_RECAPTCHA_HELP_BTN', false, false);
			$output[ 'CASTOR_RECAPTCHA_INCORRECT_TRY_AGAIN' ] = jr_gettext('CASTOR_RECAPTCHA_INCORRECT_TRY_AGAIN', 'CASTOR_RECAPTCHA_INCORRECT_TRY_AGAIN', false, false);

			$output[ '_CASTOR_REQUIREDFIELDS' ] = jr_gettext('_CASTOR_REQUIREDFIELDS', '_CASTOR_REQUIREDFIELDS', false, false);

			$output[ 'PROPERTY_DETAILS_URL' ] = get_property_details_url(get_showtime('last_viewed_property_uid'));

			if ($use_recaptcha && $output[ 'GUEST_NAME' ] != '' && $output[ 'SUBJECT' ] != '' && $output[ 'GUEST_EMAIL' ] != '') {
				$challenge = '';

				if (isset($_POST[ 'g-recaptcha-response' ])) {
					$challenge = trim($_POST[ 'g-recaptcha-response' ]);
				}

				if ($challenge != '') {
					$resp = $recaptcha->verify(
						$_POST['g-recaptcha-response'],
						$_SERVER['REMOTE_ADDR']
					);
					$resp->is_valid = $resp->isSuccess();
				} else {
					$resp = new stdClass();
					$resp->is_valid = false;
				}
			} else {
				$resp = new stdClass();
				if ($output[ 'SUBJECT' ] != '' && $output[ 'ENQUIRY' ] != '' && $output[ 'GUEST_NAME' ] != '') {
					$resp->is_valid = true;
				} else {
					$resp->is_valid = false;
				}
			}

			$oktosend = false;

			if ($resp->is_valid) {
				$oktosend = true;
				$MiniComponents->triggerEvent('03500'); // Optional, eg for affiliate schemes offering pay-per-lead
				$subject = jr_gettext('_CASTOR_FRONT_MR_MENU_CONTACTHOTEL_SUBJECT', '_CASTOR_FRONT_MR_MENU_CONTACTHOTEL_SUBJECT', false).' '.$output[ 'GUEST_NAME' ].' '.jr_gettext('_CASTOR_FRONT_MR_MENU_CONTACTHOTEL_REGARDING', '_CASTOR_FRONT_MR_MENU_CONTACTHOTEL_REGARDING', false).' '.$current_property_details->property_name;
				$output[ 'THANKS' ] = jr_gettext('_CASTOR_FRONT_MR_MENU_CONTACTHOTEL_THANKS', '_CASTOR_FRONT_MR_MENU_CONTACTHOTEL_THANKS');
				$output[ 'ENQUIRY' ] .= '<br />Email: '.$output[ 'GUEST_EMAIL' ];
				$output[ 'ENQUIRY' ] .= '<br />'.jr_gettext('_CASTOR_COM_MR_EB_GUEST_CASTOR_MOBILE_EXPL', '_CASTOR_COM_MR_EB_GUEST_CASTOR_MOBILE_EXPL').': '.$output[ 'GUEST_PHONE' ];

				if ($property_uid > 0) {
					$target_email = $current_property_details->property_email;
				} else {
					$target_email = $jrConfig['business_email'];
				}

				if ((int) $jrConfig['override_property_contact_details'] == 1) {
					if ($jrConfig['override_property_contact_email'] != '') {
						$target_email = $jrConfig['override_property_contact_email'];
					}
				}

				if (!castorMailer($output[ 'GUEST_EMAIL' ], $current_property_details->property_name, $target_email, $subject, $output[ 'ENQUIRY' ], $mode = 1)) {
					error_logging('Failure in sending enquiry email to hotel. Target address: '.$target_email.' Subject'.$subject);
				}

				if (!castorMailer($target_email, $current_property_details->property_name, $output[ 'GUEST_EMAIL' ], $subject, $output[ 'ENQUIRY' ], $mode = 1)) {
					error_logging('Failure in sending enquiry email to guest. Target address: '.$output[ 'GUEST_EMAIL' ].' Subject'.$subject);
				}

				$output[ 'PROPERTYUID' ] = $property_uid;

				$output[ 'TASK' ] = 'viewproperty&property_uid='.$property_uid;
				$output[ 'BACKTOPROPERTY' ] = jr_gettext('_CASTOR_BACKTOPROPERTYDETAILSLINK', '_CASTOR_BACKTOPROPERTYDETAILSLINK');

				if ($property_uid == 0) {
					$output[ 'TASK' ] = 'search';
					$output[ 'BACKTOPROPERTY' ] = jr_gettext('_CASTOR_SEARCH_BUTTON', '_CASTOR_SEARCH_BUTTON');
				}
			} else {
				$use_ssl = false;
				if (isset($_SERVER['HTTPS'])) {
					$use_ssl = true;
				}

				if ($use_recaptcha) {
					$output[ 'CAPTCHA' ] = '
						<div class="g-recaptcha" data-sitekey="'.trim($jrConfig[ 'recaptcha_public_key' ]).'" data-size="compact"></div>
						<script type="text/javascript"
							src="https://www.google.com/recaptcha/api.js?hl='.get_showtime('lang_shortcode').'">
						</script>
					';
				}
			}

			$pageoutput[ ] = $output;
			$tmpl = new patTemplate();
			if (isset($componentArgs[ 'custom_path_to_template' ])) {
				if (file_exists($componentArgs[ 'custom_path_to_template' ].'contact_owner.html')) {
					$tmpl->setRoot($componentArgs[ 'custom_path_to_template' ]);
				} else {
					$tmpl->setRoot(CASTOR_TEMPLATEPATH_FRONTEND);
				}
			} else {
				$tmpl->setRoot(CASTOR_TEMPLATEPATH_FRONTEND);
			}
			if ($oktosend) {
				$tmpl->readTemplatesFromInput('contact_owner_sent.html');
			} else {
				$tmpl->readTemplatesFromInput('contact_owner.html');
			}
			$tmpl->addRows('pageoutput', $pageoutput);

			if (isset($componentArgs[ 'noshownow' ])) {
				$this->ret_vals = $tmpl->getParsedTemplate();
			} else {
				$tmpl->displayParsedTemplate();
			}
		}
	}

	public function touch_template_language()
	{
		$output = array();

		$output[ ] = jr_gettext('_CASTOR_FRONT_MR_MENU_CONTACTHOTEL_THANKS', '_CASTOR_FRONT_MR_MENU_CONTACTHOTEL_THANKS');
		$output[ ] = jr_gettext('_CASTOR_FRONT_MR_MENU_CONTACTHOTEL_SUBJECT', '_CASTOR_FRONT_MR_MENU_CONTACTHOTEL_SUBJECT');
		$output[ ] = jr_gettext('_CASTOR_FRONT_MR_MENU_CONTACTHOTEL_REGARDING', '_CASTOR_FRONT_MR_MENU_CONTACTHOTEL_REGARDING');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_EB_GUEST_CASTOR_EMAIL_EXPL', '_CASTOR_COM_MR_EB_GUEST_CASTOR_EMAIL_EXPL');
		$output[ ] = jr_gettext('_CASTOR_FRONT_MR_EMAIL_TEXT_NAME', '_CASTOR_FRONT_MR_EMAIL_TEXT_NAME');
		$output[ ] = jr_gettext('_CASTOR_FRONT_MR_MENU_CONTACTHOTEL_ENQUIRY', '_CASTOR_FRONT_MR_MENU_CONTACTHOTEL_ENQUIRY');
		$output[ ] = jr_gettext('_CASTOR_BACKTOPROPERTYDETAILSLINK', '_CASTOR_BACKTOPROPERTYDETAILSLINK');

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
		return $this->ret_vals;
	}
}

