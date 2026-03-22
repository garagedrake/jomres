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
	 * Used by the media centre to configure media uploading options for the slideshow images
	 *
	 */

class j03379slideshow
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

		if (!isset($componentArgs['property_uid'])) {
			$property_uid = getDefaultProperty();
		} else {
			$property_uid = (int)$componentArgs['property_uid'];
		}
		
		$preview_link = CASTOR_SITEPAGE_URL_AJAX.'&task=show_property_slideshow&property_uid='.$property_uid;
		
		$this->ret_vals = array(
								'resource_type' => 'slideshow',
								'resource_id_required' => true,
								'name' => jr_gettext('_CASTOR_MEDIA_CENTRE_RESOURCE_TYPES_SLIDESHOW', '_CASTOR_MEDIA_CENTRE_RESOURCE_TYPES_SLIDESHOW', false),
								'upload_root_abs_path' => CASTOR_IMAGELOCATION_ABSPATH.$property_uid.JRDS,
								'upload_root_rel_path' => CASTOR_IMAGELOCATION_RELPATH.$property_uid.'/',
								'notes' => '',
								'preview_link'=>$preview_link
								);
	}


	/**
	 * @return array
	 */
	public function getRetVals()
	{
		return $this->ret_vals;
	}
}

