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
	 * Shows the top template, which among other things shows video tutorials, property name, management view switching, editing mode dropdown etc.
	 *
	 */

	class j00060toptemplate
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

			if (get_showtime('topoff')) {
				return;
			}

			$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
			$jrConfig = $siteConfig->get();

			$thisJRUser = castor_singleton_abstract::getInstance('jr_user');

			$management_view = castorGetParam($_REQUEST, 'tmpl', false);
			$popup = intval(castorGetParam($_REQUEST, 'popup', 0));
			//$tz		 	   	= $componentArgs[ 'tz' ];

			$castorlang 		= castor_singleton_abstract::getInstance('castor_language');

			$defaultProperty 	= getDefaultProperty();

			if (AJAXCALL || $popup == 1) {
				return;
			}

			if (get_showtime('menuoff') == true) {
				echo '<div id="castor_content_area">'; // closed in j00061bottomtemplate.class.php
				return;
			}

			$output = array();
			if (class_exists('channelmanagement_framework_properties')) {  // The channel management framework is installed
				$mrConfig = getPropertySpecificSettings($defaultProperty);

				if (isset($mrConfig['allow_channel_property_local_admin']) && $mrConfig['allow_channel_property_local_admin'] != '1') {
					echo '
				<!-- start development mode warning -->
				<div class="isa_warning">
                    <i class="fa fa-info"></i>
                   '.jr_gettext('_CASTOR_CHANNEL_PROPERTY_NO_ADMIN', '_CASTOR_CHANNEL_PROPERTY_NO_ADMIN').'
                    <i class="fa fa-info"></i>
				</div>
				';
				}
			}
			if ($jrConfig[ 'development_production' ] != 'production') {
				echo '
				<!-- start development mode warning -->
				<div class="isa_warning">
                    <i class="fa fa-warning"></i>
                    Development mode enabled
                    <i class="fa fa-warning"></i>
				</div>
				';
			}

			$output[ 'VIDEO_TUTORIALS' ] = '';
			if (using_bootstrap()) {
				$castor_video_tutorials = castor_singleton_abstract::getInstance('castor_video_tutorials');
				$castor_video_tutorials->property_uid = $defaultProperty;
				$output[ 'VIDEO_TUTORIALS' ] = $castor_video_tutorials->build_modal();
			}

			$output[ 'PROPERTYNAME' ] = '';
			$output[ 'HACTIVE_PROPERTY' ] = '';
			$output[ 'MANAGEMENT_VIEW_DROPDOWN' ] = '';
			$output[ 'TIMEZONE_DROPDOWN' ] = '';
			$output[ 'TIMEZONEBLURB' ] = '';
			$output[ 'PROPERTY_SELECTOR_DROPDOWN' ] = '';
			$output[ 'WIDGETS_SELECTOR_DROPDOWN' ] = '';
			$output[ 'CURRENT_PROPERTY_STYLE' ] = '';
			$output['FB_LOCALISE'] = str_replace('-', '_', get_showtime('lang'));

			$result = '';

			if (this_cms_is_joomla() || this_cms_is_wordpress()) {
				if ($thisJRUser->userIsManager) {
					$output[ 'HACTIVE_PROPERTY' ] = jr_gettext('_CASTOR_HSTATUS_CURRENT', '_CASTOR_HSTATUS_CURRENT', false).': ';

					set_showtime('menuitem_propertyname', $output[ 'PROPERTYNAME' ]);

					if ( isset($jrConfig[ 'secret_setting_show_management_view' ]) && $jrConfig[ 'secret_setting_show_management_view' ] == "1" ) {
						jr_import('castor_management_view');
						$castor_management_view = new castor_management_view();
						$management_dropdown = $castor_management_view->get_dropdown();
						$output[ 'MANAGEMENT_VIEW_DROPDOWN' ] = $management_dropdown;
						set_showtime('menuitem_management_view_dropdown', $output[ 'MANAGEMENT_VIEW_DROPDOWN' ]);
					}
				}
			}

			if (using_bootstrap()) {
				if (file_exists(CASTOR_IMAGELOCATION_ABSPATH.'logo.png')) {
					$output[ 'LOGO_RELATIVE_URL' ] = CASTOR_IMAGELOCATION_RELPATH.'/logo.png';
				} elseif (file_exists(CASTOR_IMAGELOCATION_ABSPATH.'logo.jpg')) {
					$output[ 'LOGO_RELATIVE_URL' ] = CASTOR_IMAGELOCATION_RELPATH.'/logo.jpg';
				} else {
					$output[ 'LOGO_RELATIVE_URL' ] = CASTOR_IMAGES_RELPATH.'logo.png';
				}
			} else {
				$output[ 'LOGO_RELATIVE_URL' ] = CASTOR_IMAGES_RELPATH.'jrlogo.png';
			}

			$output[ 'SANITY_CHECKS' ] = get_showtime('sanity_check_warnings');
			$output[ 'NEXT' ] = jr_gettext('_PN_NEXT', '_PN_NEXT', false, false);
			$output[ 'PREVIOUS' ] = jr_gettext('_PN_PREVIOUS', '_PN_PREVIOUS', false, false);

			$output[ 'BACKLINK' ] = '<a href="javascript:history.go(-1)">'.jr_gettext('_CASTOR_COM_MR_BACK', '_CASTOR_COM_MR_BACK', false).'</a>';
			$output[ 'LIVESITE' ] = get_showtime('live_site');
			$output[ 'DATEPICKERLANG' ] = get_showtime('datepicker_lang');
			$output[ 'PROPERTY_UID' ] = $defaultProperty;


			if ($thisJRUser->userIsManager) {
				if (!get_showtime('heavyweight_system') && using_bootstrap()) {
					jr_import('castor_property_selector_dropdown');
					$castor_property_selector_dropdown = new castor_property_selector_dropdown();
					$output['PROPERTY_SELECTOR_DROPDOWN'] = $castor_property_selector_dropdown->get_dropdown(false);
					$output['PROPERTY_SELECTOR_DROPDOWN_TRUNCATED'] = $castor_property_selector_dropdown->get_dropdown(true);
					set_showtime('property_selector_dropdown', $output['PROPERTY_SELECTOR_DROPDOWN']);
				} else {
					$current_property_details = castor_singleton_abstract::getInstance('basic_property_details');
					$current_property_details->gather_data($defaultProperty);
					$output[ 'PROPERTYNAME' ] = jr_gettext('_CASTOR_CUSTOMTEXT_PROPERTY_NAME_'.$defaultProperty, $current_property_details->property_name, false);
				}
			} else {
				$output[ 'CURRENT_PROPERTY_STYLE' ] = 'display:none;';
			}

			//widgets selection dropdown
			$widgets_dropdown = array();
			if ($thisJRUser->userIsManager &&
				!isset($_REQUEST['calledByModule']) &&
				(
					get_showtime('task') == 'cpanel' ||
					get_showtime('task') == ''
				)
			) {
				$castor_widgets = castor_singleton_abstract::getInstance('castor_widgets');

				$widgets_dropdown[]['WIDGETS_DROPDOWN'] = $castor_widgets->get_widgets_dropdown();
			}

			if (using_bootstrap()) {
				$output[ 'USING_BOOTSTRAP' ] = 'true';
			} else {
				$output[ 'USING_BOOTSTRAP' ] = 'false';
			}

			$pageoutput[ ] = $output;
			$tmpl = new patTemplate();
			$tmpl->setRoot(CASTOR_TEMPLATEPATH_FRONTEND);
			if ($management_view) {
				$tmpl->readTemplatesFromInput('management_top.html');
			} else {
				$tmpl->readTemplatesFromInput('top.html');
			}
			$tmpl->addRows('pageoutput', $pageoutput);
			//$tmpl->addRows( 'timezone_dropdown', $timezone_dropdown );
			$tmpl->addRows('widgets_dropdown', $widgets_dropdown);
			$tmpl->displayParsedTemplate();
			$pageoutput = array();
			$output = array();
		}

		public function touch_template_language()
		{
			$output = array();
			$output[ ] = jr_gettext('_PN_NEXT', '_PN_NEXT', false);
			$output[ ] = jr_gettext('_PN_PREVIOUS', '_PN_PREVIOUS', false);
			$output[ ] = jr_gettext('_CASTOR_CONVERSION_DISCLAIMER', '_CASTOR_CONVERSION_DISCLAIMER', false);

			foreach ($output as $o) {
				echo $o;
				echo '<br/>';
			}
		}


		public function getRetVals()
		{
			return null;
		}
	}

