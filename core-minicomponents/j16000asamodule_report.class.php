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

class j16000asamodule_report
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
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');

		if ($MiniComponents->template_touch) {
			$this->template_touchable = false;

			return;
		}

		$castor_shortcode_parser = castor_singleton_abstract::getInstance('castor_shortcode_parser');
		$castor_shortcode_parser->get_shortcodes();

		$castor_language = castor_singleton_abstract::getInstance('castor_language');
		$castor_language->get_language('shortcodes');

		$output = array();
		$rows = array();
		$pageoutput = array();

		if (is_array($castor_shortcode_parser->shortcodes)) {
			$output[ 'PAGETITLE' ] = jr_gettext('_CASTOR_SHORTCODES', '_CASTOR_SHORTCODES', false);
			$output[ 'SHORTCODE_TRIGGER' ] = jr_gettext('SHORTCODE_TRIGGER', 'SHORTCODE_TRIGGER', false);
			$output[ 'SHORTCODE_TASK' ] = jr_gettext('SHORTCODE_TASK', 'SHORTCODE_TASK', false);
			$output[ 'SHORTCODE_DESCRIPTION' ] = jr_gettext('SHORTCODE_DESCRIPTION', 'SHORTCODE_DESCRIPTION', false);
			$output[ 'SHORTCODE_ARGUMENTS' ] = jr_gettext('SHORTCODE_ARGUMENTS', 'SHORTCODE_ARGUMENTS', false);
			$output[ 'SHORTCODE_EXAMPLE' ] = jr_gettext('SHORTCODE_EXAMPLE', 'SHORTCODE_EXAMPLE', false);

            $output[ 'SHORTCODE_INFO_CASTOR_V_CASTOR_SCRIPT' ] = jr_gettext('SHORTCODE_INFO_CASTOR_V_CASTOR_SCRIPT', 'SHORTCODE_INFO_CASTOR_V_CASTOR_SCRIPT', false);

			if (this_cms_is_wordpress()) {
				$output[ 'INFO' ] = jr_gettext('_CASTOR_SHORTCODES_INFO_WORDPRESS', '_CASTOR_SHORTCODES_INFO_WORDPRESS', false);
			} else {
				$output[ 'INFO' ] = jr_gettext('_CASTOR_SHORTCODES_INFO_JOOMLA', '_CASTOR_SHORTCODES_INFO_JOOMLA', false);
			}

			$rows = array();
			foreach ($castor_shortcode_parser->shortcodes as $key => $trigger) {
				if (!empty($trigger)) {
					foreach ($trigger as $task) {
						$r = array();

						$r['TRIGGER'] = $key;
						$r['TASK'] = $task['task'];
						$r['DESCRIPTION'] = jr_gettext($task['info'], $task['info'], false);

						$r['ARGUMENTS'] = '';
						$arguments = array();

						if (!empty($task['arguments'])) {
							foreach ($task['arguments'] as $arg) {
								$o = array();
								$po = array();

								$o['ARGUMENT'] = $arg['argument'];
								$o['ARG_INFO'] = jr_gettext($arg['arg_info'], $arg['arg_info'], false);
								$o['ARG_EXAMPLE'] = '';
								if (isset($arg['arg_example'])) {
									$arguments[ $arg['argument'] ] = $arg['arg_example'];
								}

								$po[ ] = $o;
								$tmpl = new patTemplate();
								$tmpl->setRoot(CASTOR_TEMPLATEPATH_ADMINISTRATOR);
								$tmpl->readTemplatesFromInput('shortcode_snippet.html');
								$tmpl->addRows('pageoutput', $po);
								$r['ARGUMENTS'] .= $tmpl->getParsedTemplate();
							}
						}

						$arg_str = '';
						if (!empty($arguments)) {
							foreach ($arguments as $parameter => $example) {
								$arg_str .= '&'.$parameter.'='.$example;
							}

							if (this_cms_is_wordpress()) {
								$arg_str = 'params="'.$arg_str.'"';
							}
						}

						if (this_cms_is_wordpress()) {
							$r['EXAMPLE'] = '[castor task="'.$r['TASK'].'" '.$arg_str.']';
						} else {
							$r['EXAMPLE'] = '{castor '.$r['TASK'].' '.$arg_str.'}';
						}
						$r['EXAMPLE'] = str_replace('&', '&amp;', $r['EXAMPLE']);

						$rows[] = $r;
					}
				}
			}

			$jrtbar = castor_singleton_abstract::getInstance('castor_toolbar');
			$jrtb = $jrtbar->startTable();
			$jrtb .= $jrtbar->toolbarItem('cancel', castorURL(CASTOR_SITEPAGE_URL_ADMIN), '');

			$jrtb .= $jrtbar->endTable();
			$output[ 'CASTORTOOLBAR' ] = $jrtb;

			$pageoutput[ ] = $output;

			$tmpl = new patTemplate();
			$tmpl->setRoot(CASTOR_TEMPLATEPATH_ADMINISTRATOR);
			$tmpl->readTemplatesFromInput('list_shortcodes.html');
			$tmpl->addRows('pageoutput', $pageoutput);
			$tmpl->addRows('rows', $rows);
			$tmpl->displayParsedTemplate();
		} else {
			echo 'Error, shortcodes cannot be displayed. Try rebuilding the registry then come back to this page.';
		}
	}


	public function getRetVals()
	{
		return null;
	}
}

