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

class j06002unpublish_property
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

		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');

		$thisJRUser = castor_singleton_abstract::getInstance('jr_user');

		$defaultProperty = castorGetParam($_REQUEST, 'property_uid', 0);

		if ($defaultProperty == 0) {
			$defaultProperty = getDefaultProperty();
		}

		if ($defaultProperty == 0) {
			return false;
		}

		$current_property_details = castor_singleton_abstract::getInstance('basic_property_details');
		$current_property_details->gather_data($defaultProperty);

		$castor_properties = castor_singleton_abstract::getInstance('castor_properties');
		$castor_properties->propertys_uid = $defaultProperty;

		if (!$current_property_details->approved) {
			castorRedirect(castorURL(CASTOR_SITEPAGE_URL.'&task=cannot_redirect'), '');
		} else {
			$castor_messaging = castor_singleton_abstract::getInstance('castor_messages');

			if (in_array($defaultProperty, $thisJRUser->authorisedProperties)) {
				if ($current_property_details->published) {
					if ($castor_properties->setPublished(0)) {
						$MiniComponents->triggerEvent('02274'); // Optional trigger after property unpublished

						$castor_messaging->set_message(jr_gettext('_CASTOR_MR_AUDIT_UNPUBLISH_PROPERTY', '_CASTOR_MR_AUDIT_UNPUBLISH_PROPERTY', false));
					} else {
						$castor_messaging->set_message('There was a problem unpublishing the property.');
					}
				}
			} else {
				echo "You naughty little tinker, that's not your property";
				return false;
			}

		}
		castorRedirect(castorURL(CASTOR_SITEPAGE_URL.'&task=listyourproperties'), '');


	}


	public function getRetVals()
	{
		return null;
	}
}

