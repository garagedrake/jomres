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

if (!using_bootstrap()) {
	jr_import('castor_toolbar_normal');
	
	/**
	 *
	 *
	 *
	 */
	#[AllowDynamicProperties]
	class castor_toolbar extends castor_toolbar_normal
	{

	/**
	 *
	 *
	 *
	 */

		public function __construct()
		{
			$mrConfig = getPropertySpecificSettings();
			$this->livesite = get_showtime('live_site');
			$this->standardActivityImages = $this->getStandardActivityImagesArray();
			$this->menubarImagesArray = $this->getMenubarImagesArray();
			$this->imageSize = 'small';
			if (isset($mrConfig[ 'editiconsize' ])) {
				$this->imageSize = $mrConfig[ 'editiconsize' ];
			}
			$this->imageExtension = 'png';
		}
	}
} else {
	$the_toolbar_class_filename = 'castor_toolbar_bootstrap';
	
	$bs_version = castor_bootstrap_version();
	
	if ($bs_version == '2' || castor_cmsspecific_areweinadminarea()) {
		$the_toolbar_class_filename = 'castor_toolbar_bootstrap';
	} elseif ($bs_version == '3') {
		$the_toolbar_class_filename = 'castor_toolbar_bootstrap3';
	} elseif ($bs_version == '4') {
		$the_toolbar_class_filename = 'castor_toolbar_bootstrap4';
	} elseif ($bs_version == '5') {
		$the_toolbar_class_filename = 'castor_toolbar_bootstrap5';
	}

	jr_import($the_toolbar_class_filename);
	jr_import('castorItemToolbar');
	
	/**
	 *
	 *
	 *
	 */

	class castor_toolbar extends castor_toolbar_bootstrap
	{

	/**
	 *
	 *
	 *
	 */

		public function __construct()
		{
			$mrConfig = getPropertySpecificSettings();
			$this->livesite = get_showtime('live_site');
			$this->standardActivityImages = $this->getStandardActivityImagesArray();
			$this->menubarImagesArray = $this->getMenubarImagesArray();
			$this->imageSize = 'small';
			if (isset($mrConfig[ 'editiconsize' ])) {
				$this->imageSize = $mrConfig[ 'editiconsize' ];
			}
			$this->imageExtension = 'png';
		}
	}
}

