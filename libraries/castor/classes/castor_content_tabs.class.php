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


$bs_version = castor_bootstrap_version();
if ($bs_version == '2') {
	jr_import('castor_content_tabs_bootstrap');

	class castor_content_tabs extends castor_content_tabs_bootstrap
	{
	}
} elseif ($bs_version == '5') {
	if (this_cms_is_wordpress()) {
		jr_import('castor_content_tabs_bootstrap5_wordpress');
		class castor_content_tabs extends castor_content_tabs_bootstrap5_wordpress
		{
		}
	} else {
		jr_import('castor_content_tabs_bootstrap5');
		class castor_content_tabs extends castor_content_tabs_bootstrap5
		{
		}
	}
} else { // BS4
	jr_import('castor_content_tabs_bootstrap4');
	class castor_content_tabs extends castor_content_tabs_bootstrap4
	{
	}
}

