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

class j16000media_centre_dbimport
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
		
		if ($jrConfig['images_imported_to_db'] != '0' && !$force) {
			castorRedirect(castorURL(CASTOR_SITEPAGE_URL_ADMIN), '');
		}
		
		if ($force) {
			//force a new migration, so first empty/truncate the table
			$query = "TRUNCATE TABLE #__castor_images";
			
			if (!doInsertSql($query)) {
				throw new Exception('Could not truncate images table');
			}
			
			$siteConfig->update_setting('images_imported_to_db', '0');
		} elseif ($jrConfig['images_imported_to_db'] == '0') {
			//check if table is empty
			$query = "SELECT `id` FROM #__castor_images LIMIT 1";
			$result = doSelectSql($query);
			
			if (!empty($result)) {
				//table is not empty, so mark as imported
				$siteConfig->update_setting('images_imported_to_db', '1');
				
				//redirect back
				castorRedirect(castorURL(CASTOR_SITEPAGE_URL_ADMIN), '');
			}
		} else {
			//already imported, simply redirect back
			castorRedirect(castorURL(CASTOR_SITEPAGE_URL_ADMIN), '');
		}

		jr_import('castor_media_centre_images_dbimport');
		$castor_media_centre_images_dbimport = new castor_media_centre_images_dbimport(get_showtime('all_properties_in_system'), true);

		if ($force) {
			$castor_media_centre_images_dbimport->use_db = false;
		}

		if (!using_bootstrap()) {
			if (!$castor_media_centre_images_dbimport->run()) {
				echo 'Error: Could not import images to database.';
			} else {
				echo 'Images imported successfully.';
			}
		} else {
			if (!$castor_media_centre_images_dbimport->run()) {
				echo '
				<div class="alert alert-error alert-danger">
					<h4 class="alert-heading">ERROR</h4>
					<p>Error: Could not import images to database.</p>
				</div>
				';
			} else {
				//mark as imported
				$siteConfig->update_setting('images_imported_to_db', '1');

				echo '
				<div class="alert alert-success">
					<h4 class="alert-heading">Congratulations!</h4>
					<p>Image details imported successfully to database.</p>
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

