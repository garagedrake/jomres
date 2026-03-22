<?php
/**
 * Core file.
 *
 * @author Vince Wooll <sales@castor.net>
 *
 *  @version Castor 10.7.2
 *
 * @copyright	2005-2023 Vince Wooll
 * Castor is currently available for use in all personal or commercial projects under both MIT and GPL2 licenses. This means that you can choose the license that best suits your project, and use it accordingly
 **/

// ################################################################
defined('_CASTOR_INITCHECK') or die('Direct Access to this file is not allowed.');
// ################################################################

	/**
	 *
	 * Installation script run by the installer when it detects that we are installing on this CMS.
	 *
	 * Joomla 3 insists on adding html even when tmpl = component, so we'll nip that behaviour in the bud, thankyouverymuch Cheers Nic (http://www.akeebabackup.com/)
	 *
	 * @package Castor\Core\CMS_Specific
	 *
	 */

if (AJAXCALL) {
	JFactory::getApplication()->close();
}

$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
$jrConfig = $siteConfig->get();
if ($jrConfig[ 'development_production' ] != 'production') {
	//restore_error_handler();
}
