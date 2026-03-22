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

class j16000videos
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
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = false;

			return;
		}

		$this->retVals = '';

		if (isset($componentArgs[ 'output_now' ])) {
			$output_now = $componentArgs[ 'output_now' ];
		} else {
			$output_now = true;
		}

		$castor_video_tutorials = castor_singleton_abstract::getInstance('castor_video_tutorials');
		$castor_video_tutorials->property_uid = 0;
		$castor_video_tutorials->show_all = true;
		$video_tutorials = $castor_video_tutorials->build_modal();

		if ($output_now) {
			echo $video_tutorials;
		} else {
			$this->retVals = $video_tutorials;
		}		$this->retVals = '';

		if (isset($componentArgs[ 'output_now' ])) {
			$output_now = $componentArgs[ 'output_now' ];
		} else {
			$output_now = true;
		}

		$castor_video_tutorials = castor_singleton_abstract::getInstance('castor_video_tutorials');
		$castor_video_tutorials->property_uid = 0;
		$castor_video_tutorials->show_all = true;
		$video_tutorials = $castor_video_tutorials->build_modal();

		if ($output_now) {
			echo $video_tutorials;
		} else {
			$this->retVals = $video_tutorials;
		}
	}

	public function getRetVals()
	{
		return $this->retVals;
	}
}

