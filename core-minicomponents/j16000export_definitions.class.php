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

class j16000export_definitions
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
		
		$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
		$jrConfig = $siteConfig->get();
		
		if ($jrConfig['language_context'] == '') {
			$jrConfig['language_context'] = '0';
		}
		
		$language_context = castorGetParam($_GET, 'language_context', $jrConfig['language_context']);
		set_showtime('property_type', $language_context);
		
		$castor_property_types = castor_singleton_abstract::getInstance('castor_property_types');
		
		$castor_language_definitions = castor_singleton_abstract::getInstance('castor_language_definitions');
		
		$javascript = 'onchange="switch_language_context(this.value);"';

		echo '<h2 class="page-header">Export language file definitions - '.get_showtime('lang').'</h2>';
		
		echo '<p>'.jr_gettext('_CASTOR_EXPORT_DEFINITIONS_INFO', '_CASTOR_EXPORT_DEFINITIONS_INFO', false).'</p>';
		
		echo '<p>'.jr_gettext('_CASTOR_COM_LANGUAGE_CONTEXT', '_CASTOR_COM_LANGUAGE_CONTEXT', false) . ' ' . $castor_property_types->getPropertyTypeDescDropdown($language_context, 'language_context', $javascript).'</p>';

		$definitions = array();
		foreach ($castor_language_definitions->definitions[ $jrConfig['language_context'] ] as $const => $def) {
			$definitions[ $const ] = jr_gettext($const, $def, false);
		}

		$output_string = '
<?php
##################################################################
defined( \'_CASTOR_INITCHECK\' ) or die( \'\' );
##################################################################
';
		foreach ($definitions as $const => $string) {
			str_replace("\'", "'", $string);
			$string = filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS);
			$output_string .= 'jr_define("'.$const.'","'.$string.'");
';
		}

		echo '<textarea style="width: 100%;height: 900px;" >'.$output_string.'</textarea>';
	}


	public function getRetVals()
	{
		return null;
	}
}

