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

class j16000media_centre_s3import
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
		
		$force = (int)castorGetParam($_REQUEST, 'force', 0);
		
		//preliminary checks
		if ($jrConfig['images_imported_to_db'] == '0' ||
			$jrConfig['amazon_s3_active'] != '1' ||
			$jrConfig['amazon_s3_bucket'] == '' ||
			$jrConfig['amazon_s3_key'] == '' ||
			$jrConfig['amazon_s3_secret'] == ''
			) {
			castorRedirect(castorURL(CASTOR_SITEPAGE_URL_ADMIN), '');
		}

		if ($jrConfig['images_imported_to_s3'] != '0' && !$force) {
			castorRedirect(castorURL(CASTOR_SITEPAGE_URL_ADMIN), '');
		}
		
		//set max execution time. If not possible, don`t run the import because most probably it will time out
		try {
			ini_set('max_execution_time', '0');
		} catch (Exception $e) {
			echo '
			<div class="alert alert-error alert-danger">
				<h4 class="alert-heading">ERROR</h4>
				<p>Error: Can`t set max_execution_time to 0, importing existing images will probably time out. Please import them manually by copying the entire /castor/uploadedimages dir to your Amazon S3 bucket.</p>
			</div>
			';
			return;
		}
		
		jr_import('castor_media_centre_images_s3import');
		$castor_media_centre_images_s3import = new castor_media_centre_images_s3import();

		if (!using_bootstrap()) {
			if (!$castor_media_centre_images_s3import->run()) {
				echo 'Error: Could not import images to Aamazon S3 bucket.';
			} else {
				echo 'Images imported successfully to Amazon S3 bucket.';
			}
		} else {
			if (!$castor_media_centre_images_s3import->run()) {
				echo '
				<div class="alert alert-error alert-danger">
					<h4 class="alert-heading">ERROR</h4>
					<p>Error: Could not import images to Aamazon S3 bucket.</p>
				</div>
				';
			} else {
				//mark as imported
				$siteConfig->update_setting('images_imported_to_s3', '1');

				echo '
				<div class="alert alert-success">
					<h4 class="alert-heading">Congratulations!</h4>
					<p>Images imported successfully to Amazon S3 bucket.</p>
				</div>
				';
			}
		}
	}


	public function getRetVals()
	{
		return null;
	}
}

