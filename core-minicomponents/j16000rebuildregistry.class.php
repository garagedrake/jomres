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

class j16000rebuildregistry
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

		$registry = castor_singleton_abstract::getInstance('minicomponent_registry');
		$registry->regenerate_registry();

		if (!using_bootstrap()) {
			if ($registry->error_detected) {
				echo jr_gettext('_CASTOR_REGISTRYREBUILD_FAILURE', '_CASTOR_REGISTRYREBUILD_FAILURE', false);
			} else {
				echo jr_gettext('_CASTOR_REGISTRYREBUILD_SUCCESS', '_CASTOR_REGISTRYREBUILD_SUCCESS', false);
			}
			echo '<br />';
			echo jr_gettext('_CASTOR_REGISTRYREBUILD_NOTES', '_CASTOR_REGISTRYREBUILD_NOTES', false);
		} else {
			if ($registry->error_detected) {
				echo '
				<div class="alert alert-block alert-error">
					<h4 class="alert-heading">' .jr_gettext('_CASTOR_REGISTRYREBUILD_FAILURE', '_CASTOR_REGISTRYREBUILD_FAILURE', false).'</h4>
					<p>' .jr_gettext('_CASTOR_REGISTRYREBUILD_NOTES', '_CASTOR_REGISTRYREBUILD_NOTES', false).'</p>
				</div>
				';
			} else {
				echo '
				<div class="alert alert-block alert-success">
					<h4 class="alert-heading">' .jr_gettext('_CASTOR_REGISTRYREBUILD_SUCCESS', '_CASTOR_REGISTRYREBUILD_SUCCESS', false).'</h4>
					<p>' .jr_gettext('_CASTOR_REGISTRYREBUILD_NOTES', '_CASTOR_REGISTRYREBUILD_NOTES', false).'</p>
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

