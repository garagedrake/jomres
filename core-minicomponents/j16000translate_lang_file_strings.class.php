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

class j16000translate_lang_file_strings
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
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = false;

			return;
		}
		if (!translation_user_check()) {
			return;
		}
		
		$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
		$jrConfig = $siteConfig->get();
		
		if ($jrConfig['language_context'] == '') {
			$jrConfig['language_context'] = '0';
		}
		
		$language_context = castorGetParam($_GET, 'language_context', $jrConfig['language_context']);
		set_showtime('property_type', $language_context);

		$castor_property_types = castor_singleton_abstract::getInstance('castor_property_types');
		
		$castor_language_definitions = castor_singleton_abstract::getInstance('castor_language_definitions');

		$castor_language = castor_singleton_abstract::getInstance('castor_language');
		$castor_language->get_language('faq');
		$castor_language->get_language('shotcodes');
		$castor_language->get_language('video_tutorials');

		echo '<script type="text/javascript">
			var castor_target_language = "'.get_showtime('lang').'"
			</script>';

		$javascript = 'onchange="switch_language_context(this.value);"';

		echo '<h2 class="page-header">'.jr_gettext('_CASTOR_TOUCHTEMPLATES', '_CASTOR_TOUCHTEMPLATES', false).' - '.get_showtime('lang').'</h2>';
		
		echo '<p>'.jr_gettext('_CASTOR_COM_LANGUAGE_CONTEXT', '_CASTOR_COM_LANGUAGE_CONTEXT', false) . ' ' . $castor_property_types->getPropertyTypeDescDropdown($language_context, 'language_context', $javascript).'</p>';

		echo simple_template_output(CASTOR_TEMPLATEPATH_ADMINISTRATOR, $template = 'translate_lang_file_strings_header.html', jr_gettext( '_CASTOR_COM_TRANSLATE_LANGUAGEFILES_INFO', '_CASTOR_COM_TRANSLATE_LANGUAGEFILES_INFO' , false ));


		$output = array();

        foreach ($castor_language_definitions->definitions[$jrConfig['language_context']] as $const => $def) {
            if ( $const != '_CASTOR_COM_MR_YES ' && $const != '_CASTOR_COM_MR_NO ' && $const != '_CASTOR_COM_TRANSLATE_LANGUAGEFILES_INFO ') {
                $output[$const] = $const." <br/><br/>".jr_gettext($const, $def)."<br/>";
            }
        }

        foreach ($castor_language_definitions->definitions[$language_context] as $const => $def) {
            if ( $const != '_CASTOR_COM_MR_YES ' && $const != '_CASTOR_COM_MR_NO ' && $const != '_CASTOR_COM_TRANSLATE_LANGUAGEFILES_INFO ') {
                $output[$const] = $const." <br/><br/>".jr_gettext($const, $def)."<br/>";
            }
        }

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

