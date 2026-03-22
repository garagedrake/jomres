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

class j10501file_uploads
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

		if (!isset($jrConfig[ 'admin_options_level' ])) {
			$jrConfig[ 'admin_options_level' ] = 0;
		}

		if ($jrConfig[ 'admin_options_level' ] < 1) {
			return;
		}

		$configurationPanel = $componentArgs[ 'configurationPanel' ];
		$lists = $componentArgs[ 'lists' ];

		$configurationPanel->startPanel(jr_gettext('_CASTOR_COM_A_CASTOR_FILE_UPLOADS', '_CASTOR_COM_A_CASTOR_FILE_UPLOADS', false));

		$configurationPanel->setleft(jr_gettext('_CASTOR_COM_THUMBNAIL_SMALL_WIDTH', '_CASTOR_COM_THUMBNAIL_SMALL_WIDTH', false));
		$configurationPanel->setmiddle('<input type="text" class="input-large" name="cfg_thumbnail_property_list_max_width" value="'.$jrConfig[ 'thumbnail_property_list_max_width' ].'">');
		$configurationPanel->setright();
		$configurationPanel->insertSetting();

		/*$configurationPanel->setleft(jr_gettext('_CASTOR_COM_THUMBNAIL_SMALL_HEIGHT', '_CASTOR_COM_THUMBNAIL_SMALL_HEIGHT', false));
		$configurationPanel->setmiddle('<input type="text" class="input-large" name="cfg_thumbnail_property_list_max_height" value="'.$jrConfig[ 'thumbnail_property_list_max_height' ].'">');
		$configurationPanel->setright();
		$configurationPanel->insertSetting();*/

		$configurationPanel->setleft(jr_gettext('_CASTOR_COM_THUMBNAIL_MED_WIDTH', '_CASTOR_COM_THUMBNAIL_MED_WIDTH', false));
		$configurationPanel->setmiddle('<input type="text" class="input-large" name="cfg_thumbnail_property_header_max_width" value="'.$jrConfig[ 'thumbnail_property_header_max_width' ].'">');
		$configurationPanel->setright();
		$configurationPanel->insertSetting();

		/*$configurationPanel->setleft(jr_gettext('_CASTOR_COM_THUMBNAIL_MED_HEIGHT', '_CASTOR_COM_THUMBNAIL_MED_HEIGHT', false));
		$configurationPanel->setmiddle('<input type="text" class="input-large" name="cfg_thumbnail_property_header_max_height" value="'.$jrConfig[ 'thumbnail_property_header_max_height' ].'">');
		$configurationPanel->setright();
		$configurationPanel->insertSetting();*/

		$configurationPanel->setleft(jr_gettext('_CASTOR_COM_A_UPLOADS_IMAGES_WIDTH_LARGE', '_CASTOR_COM_A_UPLOADS_IMAGES_WIDTH_LARGE', false));
		$configurationPanel->setmiddle('<input type="text" class="input-large" name="cfg_maxwidth" value="'.$jrConfig[ 'maxwidth' ].'">');
		$configurationPanel->setright(jr_gettext('_CASTOR_COM_A_UPLOADS_IMAGES_WIDTH_LARGE_DESC', '_CASTOR_COM_A_UPLOADS_IMAGES_WIDTH_LARGE_DESC', false));
		$configurationPanel->insertSetting();

		$configurationPanel->setleft(jr_gettext('_CASTOR_COM_A_UPLOADS_FILESIZE', '_CASTOR_COM_A_UPLOADS_FILESIZE', false));
		$configurationPanel->setmiddle('<input type="text" class="input-large" name="cfg_fileSize" value="'.$jrConfig[ 'fileSize' ].'">');
		$configurationPanel->setright(jr_gettext('_CASTOR_COM_A_UPLOADS_FILESIZE_DESC', '_CASTOR_COM_A_UPLOADS_FILESIZE_DESC', false));
		$configurationPanel->insertSetting();
		
		/* $configurationPanel->setleft(jr_gettext('_CASTOR_MEDIA_CENTRE_OPTIMIZE_IMAGES', '_CASTOR_MEDIA_CENTRE_OPTIMIZE_IMAGES', false));
		$configurationPanel->setmiddle($lists['optimize_images']);
		$configurationPanel->setright(jr_gettext('_CASTOR_MEDIA_CENTRE_OPTIMIZE_IMAGES_DESC', '_CASTOR_MEDIA_CENTRE_OPTIMIZE_IMAGES_DESC', false));
		$configurationPanel->insertSetting(); */
		
		//plugins can add options to this tab
		$MiniComponents->triggerEvent('10524', $componentArgs);

		$configurationPanel->endPanel();
	}


	public function getRetVals()
	{
		return null;
	}
}

