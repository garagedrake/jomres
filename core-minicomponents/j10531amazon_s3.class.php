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

class j10531amazon_s3
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

		$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
		$jrConfig = $siteConfig->get();

		$configurationPanel = $componentArgs[ 'configurationPanel' ];
		
		$options = array();
		$options[] = castorHTML::makeOption('us-east-1', 'US East (N. Virginia)');
		$options[] = castorHTML::makeOption('us-east-2', 'US East (Ohio)');
		$options[] = castorHTML::makeOption('us-west-1', 'US West (N. California)');
		$options[] = castorHTML::makeOption('us-west-2', 'US West (Oregon)');
		$options[] = castorHTML::makeOption('ca-central-1', 'Canada (Central)');
		$options[] = castorHTML::makeOption('ap-south-1', 'Asia Pacific (Mumbai)');
		$options[] = castorHTML::makeOption('ap-northeast-2', 'Asia Pacific (Seoul)');
		$options[] = castorHTML::makeOption('ap-southeast-1', 'Asia Pacific (Singapore)');
		$options[] = castorHTML::makeOption('ap-southeast-2', 'Asia Pacific (Sydney)');
		$options[] = castorHTML::makeOption('ap-northeast-1', 'Asia Pacific (Tokyo)');
		$options[] = castorHTML::makeOption('eu-central-1', 'EU (Frankfurt)');
		$options[] = castorHTML::makeOption('eu-west-1', 'EU (Ireland)');
		$options[] = castorHTML::makeOption('eu-west-2', 'EU (London)');
		$options[] = castorHTML::makeOption('sa-east-1', 'South America (São Paulo)');
		$s3_region = castorHTML::selectList($options, 'cfg_amazon_s3_region', '', 'value', 'text', $jrConfig[ 'amazon_s3_region' ]);
		
		$yesno = array();
		$yesno[] = castorHTML::makeOption('0', jr_gettext("_CASTOR_COM_MR_NO", '_CASTOR_COM_MR_NO', false));
		$yesno[] = castorHTML::makeOption('1', jr_gettext("_CASTOR_COM_MR_YES", '_CASTOR_COM_MR_YES', false));
		
		$active = castorHTML::selectList($yesno, 'cfg_amazon_s3_active', '', 'value', 'text', $jrConfig[ 'amazon_s3_active' ]);
		$remove_local_copies = castorHTML::selectList($yesno, 'cfg_amazon_s3_remove_local_copies', '', 'value', 'text', $jrConfig[ 'amazon_s3_remove_local_copies' ]);
		$use_tls = castorHTML::selectList($yesno, 'cfg_amazon_s3_use_tls', '', 'value', 'text', $jrConfig[ 'amazon_s3_use_tls' ]);

		$configurationPanel->insertHeading('Amazon S3');
		
		$configurationPanel->setleft(jr_gettext('_CASTOR_STATUS_ACTIVE', '_CASTOR_STATUS_ACTIVE', false));
		$configurationPanel->setmiddle($active);
		$configurationPanel->setright(jr_gettext('_CASTOR_S3_ACTIVE_DESC', '_CASTOR_S3_ACTIVE_DESC', false));
		$configurationPanel->insertSetting();

		$configurationPanel->setleft('Key');
		$configurationPanel->setmiddle('<input type="text" class="input-large" name="cfg_amazon_s3_key" value="'.$jrConfig[ 'amazon_s3_key' ].'" />');
		$configurationPanel->setright();
		$configurationPanel->insertSetting();
		
		$configurationPanel->setleft('Secret');
		$configurationPanel->setmiddle('<input type="text" class="input-large" name="cfg_amazon_s3_secret" value="'.$jrConfig[ 'amazon_s3_secret' ].'" />');
		$configurationPanel->setright();
		$configurationPanel->insertSetting();
		
		$configurationPanel->setleft('Region');
		$configurationPanel->setmiddle($s3_region);
		$configurationPanel->setright();
		$configurationPanel->insertSetting();
		
		$configurationPanel->setleft('Bucket');
		$configurationPanel->setmiddle('<input type="text" class="input-large" name="cfg_amazon_s3_bucket" value="'.$jrConfig[ 'amazon_s3_bucket' ].'" />');
		$configurationPanel->setright();
		$configurationPanel->insertSetting();
		
		$configurationPanel->setleft(jr_gettext('_CASTOR_CLOUDFRONT_DMAIN', '_CASTOR_CLOUDFRONT_DMAIN', false));
		$configurationPanel->setmiddle('https://<input type="text" class="input-large" name="cfg_amazon_cloudfront_domain" value="'.$jrConfig[ 'amazon_cloudfront_domain' ].'" />');
		$configurationPanel->setright(jr_gettext('_CASTOR_CLOUDFRONT_DMAIN_DESC', '_CASTOR_CLOUDFRONT_DMAIN_DESC', false));
		$configurationPanel->insertSetting();
		
		$configurationPanel->setleft('SSL/TLS');
		$configurationPanel->setmiddle($use_tls);
		$configurationPanel->setright(jr_gettext('_CASTOR_S3_SSLTLS_DESC', '_CASTOR_S3_SSLTLS_DESC', false));
		$configurationPanel->insertSetting();
		
		/* $configurationPanel->setleft('Remove files from server?');
		$configurationPanel->setmiddle($remove_local_copies);
		$configurationPanel->setright('Once a file has been copied to Amazon S3, remove it from the local server');
		$configurationPanel->insertSetting(); */
	}


	public function getRetVals()
	{
		return null;
	}
}

