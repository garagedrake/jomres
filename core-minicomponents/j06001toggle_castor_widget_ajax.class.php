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

class j06001toggle_castor_widget_ajax
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

		$property_uid = getDefaultProperty();
		
		$thisJRUser = castor_singleton_abstract::getInstance('jr_user');
		if (!in_array($property_uid, $thisJRUser->authorisedProperties)) {
			return;
		}
		
		$response = array();
		$content = '';

		$jr_widget = castorGetParam($_GET, 'jr_widget', '');
		$jr_widget_enabled = (int)castorGetParam($_GET, 'jr_widget_enabled', 0);
		$jr_widget_column = (int)castorGetParam($_GET, 'jr_widget_column', 1);
		$jr_widget_position = (int)castorGetParam($_GET, 'jr_widget_position', 0);

		$order = array();
		$jr_widget_order = castorGetParam($_GET, 'jr_widget_order', '');
		$bang = explode(",", $jr_widget_order);
		if (!empty($bang)) {
			for ($i=0; $i<=count($bang); $i++) {
				if (isset($bang[$i]) && trim($bang[$i]) != '') {
					$order[] = castorGetParam($bang, $i, '');
				}
			}
		}

		$castor_widgets = castor_singleton_abstract::getInstance('castor_widgets');
		$castor_widgets->property_uid = $property_uid; //we need to set this so we`ll be sure we`ll get/set just the enabled widgets for this property uid. Other properties may have other widgets enabled

		if (!isset($castor_widgets->widgets[$jr_widget])) {
			return;
		}

		//get all enabled widgets
		if (!$castor_widgets->get_widgets($property_uid)) {
			return;
		}
		
		//save user widgets params
		if (!$castor_widgets->toggle_widget($jr_widget, $jr_widget_enabled, $jr_widget_column, $order)) {
			return;
		}

		$componentArgs = array(
			'output_now' => false,
			'is_widget' => true
		);
		
		if ($jr_widget_enabled == 1) {
			$widget = array();
				
			$widget['JR_WIDGET_TASK'] = $jr_widget;

			//$widget['WIDGET_SHORTCODE'] = '{castor_shortcode '.$jr_widget.'}';
			$widget['WIDGET_SHORTCODE'] = $MiniComponents->specificEvent($castor_widgets->widgets[$jr_widget]['eventPoint'], $jr_widget, $componentArgs);
			
			$widget['WIDGET_TITLE'] = $castor_widgets->widgets[$jr_widget]['title'];
			
			$pageoutput = array();
			$pageoutput[] = $widget;
			$tmpl = new patTemplate();
			$tmpl->setRoot(CASTOR_TEMPLATEPATH_BACKEND);
			$tmpl->addRows('pageoutput', $pageoutput);
			$tmpl->readTemplatesFromInput('widget.html');
			
			$content = $tmpl->getParsedTemplate(null, true);
		}
		
		//set ajax response
		$response = array(
			'widget' => $jr_widget,
			'enabled' => $jr_widget_enabled,
			'column' => $jr_widget_column,
			'position' => $jr_widget_position,
			'content' => $content
		);

		echo json_encode($response);
		exit;
	}


	public function getRetVals()
	{
		return null;
	}
}

