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

class j11010property_types
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
	 
	function __construct($componentArgs)
	{
		// Must be in all minicomponents. Minicomponents with templates that can contain editable text should run $this->template_touch() else just return
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = false;
			return;
		}
		
		$this->ret_vals = array (
								"resource_type" => "property_types" ,
								"resource_id_required" => true ,
								"name" => jr_gettext('_CASTOR_MEDIA_CENTRE_UPLOAD_CONTEXT_PROPERTY_TYPE_IMAGES', '_CASTOR_MEDIA_CENTRE_UPLOAD_CONTEXT_PROPERTY_TYPE_IMAGES', false),
								"upload_root_abs_path" => CASTOR_IMAGELOCATION_ABSPATH,
								"upload_root_rel_path" => CASTOR_IMAGELOCATION_RELPATH,
								"notes" => ''
								);
		
		$task = get_showtime('task');
		if ( $task != '' && strpos($task, "media_centre") === false
			) {
			return;
		}

		$thisJRUser = castor_singleton_abstract::getInstance('jr_user');
		
		if ($thisJRUser->userIsManager) {
			if (!AJAXCALL && !defined("MEDIACENTRE_ROOMJS") && !defined('CASTOR_API_CMS_ROOT')) {
				define("MEDIACENTRE_ROOMJS", 1);
				echo '
				<script>
				document.addEventListener(\'DOMContentLoaded\', function() {
					castorJquery("#resource_id_dropdown").change(function () {
						get_existing_images(); 
						});
					});
				</script>
				';
			}
		}
	}


	function getRetVals()
	{
		return $this->ret_vals;
	}
}

