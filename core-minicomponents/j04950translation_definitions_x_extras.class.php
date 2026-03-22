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
defined('_CASTOR_INITCHECK') or die('Direct Access to this file is not allowed.');
// ################################################################
	#[AllowDynamicProperties]
	/**
	 * @package Castor\Core\Minicomponents
	 *
	 * Sends the new property welcome email
	 *
	 */

class j04950translation_definitions_x_extras
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
		$property_uid = getDefaultProperty();
		$mrConfig = getPropertySpecificSettings($property_uid);
		if ($mrConfig[ 'singleRoomProperty' ] == 1) {
			$this->retVals= [];
			return;
		}

		$definitions = array();
		$section_name = jr_gettext('_CASTOR_COM_MR_EXTRA_TITLE', '_CASTOR_COM_MR_EXTRA_TITLE', false);

		$query="SELECT `uid`,`name`,`desc`,`price`,`property_uid`,`published` FROM `#__castor_extras` WHERE `property_uid` = ".(int)$property_uid." ORDER BY `name` ";
		$exList =doSelectSql($query);

		if (!empty($exList)) {
			foreach ($exList as $ex) {
					$subtitle = jr_gettext('_CASTOR_CUSTOMTEXT_EXTRANAME'.$ex->uid, castor_decode($ex->name), false);
					$definitions[$section_name][$subtitle][] = [
						'definition' => jr_gettext('_CASTOR_CUSTOMTEXT_EXTRANAME'.$ex->uid, castor_decode($ex->name)),
						'label' => '_CASTOR_COM_MR_EXTRA_NAME',
						'translate_label' => true
						];

					$definitions[$section_name][$subtitle][] = [
						'definition' => jr_gettext('_CASTOR_CUSTOMTEXT_EXTRADESC'.$ex->uid, castor_decode($ex->desc)),
						'label' => '_CASTOR_COM_MR_EXTRA_DESC',
						'translate_label' => true
						];
			}
		}


		$this->retVals = $definitions;
	}

	public function getRetVals()
	{
		return $this->retVals;
	}
}

