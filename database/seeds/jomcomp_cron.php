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
 * @package Castor\Core\Database
 *
 * Seed data for various tables
 *
 **/
$query = "TRUNCATE TABLE `#__jomcomp_cron`;";

if (!doInsertSql($query)) {
	$this->setMessage('Error, unable to truncate #__jomcomp_cron table', 'danger');
	
	return;
}

$castor_cron = castor_singleton_abstract::getInstance('castor_cron');

$castor_cron->addJob('session_files_cleanup', 'H', '');
$castor_cron->addJob('error_logs_cleanup', 'D', '');
$castor_cron->addJob('geolocation_cleanup', 'D', '');
$castor_cron->addJob("api_tokens_cleanup","D","");
$castor_cron->addJob('version_check', 'D', '');
$castor_cron->addJob('booking_data_archive_cleanup', 'D', '');
$castor_cron->addJob('gdpr_cleanup', 'D', '');
$castor_cron->addJob('syndication_get_syndicate_domains', 'D', '');
$castor_cron->addJob('syndication_get_syndicate_properties', 'M', '');
$castor_cron->addJob('syndication_check_syndicate_domains', 'QH', '');
$castor_cron->addJob('syndication_check_syndicate_properties', 'QH', '');


