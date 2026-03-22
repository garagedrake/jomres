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

class j06000cron_gdpr_cleanup
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

			
			$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
			$jrConfig = $siteConfig->get();
			
			jr_import('castor_gdpr_cleanup');
			$castor_gdpr_cleanup = new castor_gdpr_cleanup();
			
			$query = "SELECT contract_uid , invoice_uid FROM #__castor_contracts WHERE `departure` <= DATE_SUB(CURRENT_TIMESTAMP(), INTERVAL ".(int)$jrConfig[ 'gdpr_booking_retention_period' ]." DAY) ";
			$result = doSelectSql($query);

		if (!empty($result)) {
			foreach ($result as $r) {
				if ($r->contract_uid > 0 && $r->invoice_uid > 0) {
					$castor_gdpr_cleanup->cleanup_booking($r->contract_uid, $r->invoice_uid);
				}
			}
		}
			
			$query = "SELECT id FROM #__castorportal_invoices WHERE `due_date` <= DATE_SUB(CURRENT_TIMESTAMP(), INTERVAL ".(int)$jrConfig[ 'gdpr_other_invoice_retention_period' ]." DAY) ";
			$result = doSelectSql($query);

		if (!empty($result)) {
			foreach ($result as $r) {
				if ($r->id > 0) {
					$castor_gdpr_cleanup->cleanup_invoice($r->id);
				}
			}
		}
	}


	public function getRetVals()
	{
		return null;
	}
}

