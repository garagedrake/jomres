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
	 * Loads the appropriate Castor specific css files for the configured version of Bootstrap.
	 *
	 */

class j00021colourscheme
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

		if (!using_bootstrap()) {
			if (!this_cms_is_wordpress()) {
				$css_file = 'castorcss.css';
			} else {
				$css_file = 'castorcss_wordpress.css';
			}
		} else {
			if (castor_cmsspecific_areweinadminarea()) {
				switch (_CASTOR_DETECTED_CMS) {
					case 'joomla4':
						$css_file = 'castorcss_bootstrap5.css';
						break;
					default:
						$css_file = 'castorcss_bootstrap.css';
						break;
				}
			} else {
				switch (castor_bootstrap_version()) {
					case '2':
						$css_file = 'castorcss_bootstrap.css';
						break;
					case '3':
						$css_file = 'castorcss_bootstrap3.css';
						break;
					case '4':
						$css_file = 'castorcss_bootstrap4.css';
						break;
					case '5':
						$css_file = 'castorcss_bootstrap5.css';
						break;
					default:
						$css_file = 'castorcss_bootstrap.css';
						break;
				}
			}
		}

        $override_directory = get_override_directory().'custom_code'.JRDS;

        if (file_exists( $override_directory.JRDS.$css_file)) {
            castor_cmsspecific_addheaddata('css', castor_get_relative_path_to_file($override_directory).'/', $css_file);
        } else {
            castor_cmsspecific_addheaddata('css', CASTOR_CSS_RELPATH, $css_file);
        }
	}

	//Must be included in every mini-component.
	public function getRetVals()
	{
		return null;
	}
}

