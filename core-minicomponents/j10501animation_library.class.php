<?php
/**
 * Core file.
 *
 * @author Vince Wooll <sales@castor.net>
 *
 *  @version Castor 10.4.0 (Platty Joobs edition)
 *
 * @copyright	2005-2023 Vince Wooll
 * Castor (tm) PHP, CSS & Javascript files are released under both MIT and GPL2 licenses. This means that you can choose the license that best suits your project, and use it accordingly
 **/

// ################################################################
defined('_CASTOR_INITCHECK') or die('');
// ################################################################
	
	/**
	 * @package Castor\Core\Minicomponents
	 *
	 * 
	 */

class j10501animation_library
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

		$configurationPanel = $componentArgs[ 'configurationPanel' ];

		$animation = array();
		$animation[] = castorHTML::makeOption( 'fade', 'fade');
		$animation[] = castorHTML::makeOption( 'fade-up', 'fade-up');
		$animation[] = castorHTML::makeOption( 'fade-down', 'fade-down');
		$animation[] = castorHTML::makeOption( 'fade-left', 'fade-left');
		$animation[] = castorHTML::makeOption( 'fade-right', 'fade-right');
		$animation[] = castorHTML::makeOption( 'fade-up-right', 'fade-up-right');
		$animation[] = castorHTML::makeOption( 'fade-up-left', 'fade-up-left');
		$animation[] = castorHTML::makeOption( 'fade-down-right', 'fade-down-right');
		$animation[] = castorHTML::makeOption( 'fade-down-left', 'fade-down-left');
		$animation[] = castorHTML::makeOption( 'flip-up', 'flip-up');
		$animation[] = castorHTML::makeOption( 'flip-down', 'flip-down');
		$animation[] = castorHTML::makeOption( 'flip-left', 'flip-left');
		$animation[] = castorHTML::makeOption( 'flip-right', 'flip-right');
		$animation[] = castorHTML::makeOption( 'slide-up', 'slide-up');
		$animation[] = castorHTML::makeOption( 'slide-down', 'slide-down');
		$animation[] = castorHTML::makeOption( 'slide-left', 'slide-left');
		$animation[] = castorHTML::makeOption( 'slide-right', 'slide-right');
		$animation[] = castorHTML::makeOption( 'zoom-in', 'zoom-in');
		$animation[] = castorHTML::makeOption( 'zoom-in-up', 'zoom-in-up');
		$animation[] = castorHTML::makeOption( 'zoom-in-down', 'zoom-in-down');
		$animation[] = castorHTML::makeOption( 'zoom-in-left', 'zoom-in-left');
		$animation[] = castorHTML::makeOption( 'zoom-in-right', 'zoom-in-right');
		$animation[] = castorHTML::makeOption( 'zoom-out', 'zoom-out');
		$animation[] = castorHTML::makeOption( 'zoom-out-up', 'zoom-out-up');
		$animation[] = castorHTML::makeOption( 'zoom-out-down', 'zoom-out-down');
		$animation[] = castorHTML::makeOption( 'zoom-out-left', 'zoom-out-left');
		$animation[] = castorHTML::makeOption( 'zoom-out-right', 'zoom-out-right');


		$easing = array();
		$easing[] = castorHTML::makeOption( 'linear', 'linear');
		$easing[] = castorHTML::makeOption( 'ease', 'ease');
		$easing[] = castorHTML::makeOption( 'ease-in', 'ease-in');
		$easing[] = castorHTML::makeOption( 'ease-out', 'ease-out');
		$easing[] = castorHTML::makeOption( 'ease-in-out', 'ease-in-out');
		$easing[] = castorHTML::makeOption( 'ease-in-back', 'ease-in-back');
		$easing[] = castorHTML::makeOption( 'ease-out-back', 'ease-out-back');
		$easing[] = castorHTML::makeOption( 'ease-in-out-back', 'ease-in-out-back');
		$easing[] = castorHTML::makeOption( 'ease-in-sine', 'ease-in-sine');
		$easing[] = castorHTML::makeOption( 'ease-out-sine', 'ease-out-sine');
		$easing[] = castorHTML::makeOption( 'ease-in-out-sine', 'ease-in-out-sine');
		$easing[] = castorHTML::makeOption( 'ease-in-quad', 'ease-in-quad');
		$easing[] = castorHTML::makeOption( 'ease-out-quad', 'ease-out-quad');
		$easing[] = castorHTML::makeOption( 'ease-in-out-quad', 'ease-in-out-quad');
		$easing[] = castorHTML::makeOption( 'ease-in-cubic', 'ease-in-cubic');
		$easing[] = castorHTML::makeOption( 'ease-out-cubic', 'ease-out-cubic');
		$easing[] = castorHTML::makeOption( 'ease-in-out-cubic', 'ease-in-out-cubic');
		$easing[] = castorHTML::makeOption( 'ease-in-quart', 'ease-in-quart');
		$easing[] = castorHTML::makeOption( 'ease-out-quart', 'ease-out-quart');
		$easing[] = castorHTML::makeOption( 'ease-in-out-quart', 'ease-in-out-quart');

		$duration = array();
		$duration[] = castorHTML::makeOption( '1000', '1000');
		$duration[] = castorHTML::makeOption( '2000', '2000');
		$duration[] = castorHTML::makeOption( '3000', '3000');
		$duration[] = castorHTML::makeOption( '4000', '4000');
		$duration[] = castorHTML::makeOption( '5000', '5000');
		$duration[] = castorHTML::makeOption( '6000', '6000');

		$delay = array();
		$delay[] = castorHTML::makeOption( '50', '50');
		$delay[] = castorHTML::makeOption( '2000', '2000');
		$delay[] = castorHTML::makeOption( '3000', '3000');
		$delay[] = castorHTML::makeOption( '4000', '4000');
		$delay[] = castorHTML::makeOption( '5000', '5000');
		$delay[] = castorHTML::makeOption( '6000', '6000');

		if (!isset($jrConfig['animation_library_enabled'])) {
			$jrConfig['animation_library_enabled'] = 1;
		}

		if (!isset($jrConfig['animation_library_animation'])) {
			$jrConfig['animation_library_animation'] = 'fade';
		}

		if (!isset($jrConfig['animation_library_delay'])) {
			$jrConfig['animation_library_delay'] = "0";
		}

		if (!isset($jrConfig['animation_library_duration'])) {
			$jrConfig['animation_library_duration'] = "3000";
		}

		if (!isset($jrConfig['animation_library_easing'])) {
			$jrConfig['animation_library_easing'] = "ease-in-out";
		}

		$yesno = array();
		$yesno[] = castorHTML::makeOption( '0', jr_gettext("_CASTOR_COM_MR_NO",'_CASTOR_COM_MR_NO',false) );
		$yesno[] = castorHTML::makeOption( '1', jr_gettext("_CASTOR_COM_MR_YES",'_CASTOR_COM_MR_YES',false) );


		$configurationPanel->startPanel('Animation Library');

		$configurationPanel->setleft('Enabled?');
		$configurationPanel->setmiddle( castorHTML::selectList( $yesno, 'cfg_animation_library_enabled', '', 'value', 'text', $jrConfig['animation_library_enabled'] ) );
		$configurationPanel->setright('');
		$configurationPanel->insertSetting();


		$configurationPanel->setleft('Animation');
		$configurationPanel->setmiddle( castorHTML::selectList( $animation, 'cfg_animation_library_animation', '', 'value', 'text', $jrConfig['animation_library_animation'] ) );
		$configurationPanel->setright('');
		$configurationPanel->insertSetting();

		$configurationPanel->setleft('Duration');
		$configurationPanel->setmiddle( castorHTML::selectList( $duration, 'cfg_animation_library_duration', '', 'value', 'text', $jrConfig['animation_library_duration'] ) );
		$configurationPanel->setright('');
		$configurationPanel->insertSetting();

		$configurationPanel->setleft('Easing');
		$configurationPanel->setmiddle( castorHTML::selectList( $easing, 'cfg_animation_library_easing', '', 'value', 'text', $jrConfig['animation_library_easing'] ) );
		$configurationPanel->setright('');
		$configurationPanel->insertSetting();
		$configurationPanel->endPanel();
	}


	public function getRetVals()
	{
		return null;
	}
}

