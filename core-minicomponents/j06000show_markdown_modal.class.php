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

class j06000show_markdown_modal
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
		
		$output = array();
		$this->retVals = '';

		if (isset($componentArgs[ 'output_now' ])) {
			$output_now = $componentArgs[ 'output_now' ];
		} else {
			$output_now = true;
		}

		$output[ '_CASTOR_MARKDOWN_TITLE' ] = jr_gettext('_CASTOR_MARKDOWN_TITLE', '_CASTOR_MARKDOWN_TITLE', false);
		$output[ '_CASTOR_MARKDOWN_DESC' ] = jr_gettext('_CASTOR_MARKDOWN_DESC', '_CASTOR_MARKDOWN_DESC', false);
		$output[ '_CASTOR_MARKDOWN_EMPHASIS' ] = jr_gettext('_CASTOR_MARKDOWN_EMPHASIS', '_CASTOR_MARKDOWN_EMPHASIS', false);
		$output[ '_CASTOR_MARKDOWN_BOLD' ] = jr_gettext('_CASTOR_MARKDOWN_BOLD', '_CASTOR_MARKDOWN_BOLD', false);
		$output[ '_CASTOR_MARKDOWN_ITALICS' ] = jr_gettext('_CASTOR_MARKDOWN_ITALICS', '_CASTOR_MARKDOWN_ITALICS', false);
		$output[ '_CASTOR_MARKDOWN_STRIKETHROUGH' ] = jr_gettext('_CASTOR_MARKDOWN_STRIKETHROUGH', '_CASTOR_MARKDOWN_STRIKETHROUGH', false);
		$output[ '_CASTOR_MARKDOWN_HEADERS' ] = jr_gettext('_CASTOR_MARKDOWN_HEADERS', '_CASTOR_MARKDOWN_HEADERS', false);
		$output[ '_CASTOR_MARKDOWN_BIGHEADER' ] = jr_gettext('_CASTOR_MARKDOWN_BIGHEADER', '_CASTOR_MARKDOWN_BIGHEADER', false);
		$output[ '_CASTOR_MARKDOWN_MEDIUMHEADER' ] = jr_gettext('_CASTOR_MARKDOWN_MEDIUMHEADER', '_CASTOR_MARKDOWN_MEDIUMHEADER', false);
		$output[ '_CASTOR_MARKDOWN_SMALLHEADER' ] = jr_gettext('_CASTOR_MARKDOWN_SMALLHEADER', '_CASTOR_MARKDOWN_SMALLHEADER', false);
		$output[ '_CASTOR_MARKDOWN_TINYHEADER' ] = jr_gettext('_CASTOR_MARKDOWN_TINYHEADER', '_CASTOR_MARKDOWN_TINYHEADER', false);
		$output[ '_CASTOR_MARKDOWN_LISTS' ] = jr_gettext('_CASTOR_MARKDOWN_LISTS', '_CASTOR_MARKDOWN_LISTS', false);
		$output[ '_CASTOR_MARKDOWN_GENERICLISTITEM' ] = jr_gettext('_CASTOR_MARKDOWN_GENERICLISTITEM', '_CASTOR_MARKDOWN_GENERICLISTITEM', false);
		$output[ '_CASTOR_MARKDOWN_NUMBEREDLISTITEM' ] = jr_gettext('_CASTOR_MARKDOWN_NUMBEREDLISTITEM', '_CASTOR_MARKDOWN_NUMBEREDLISTITEM', false);
		$output[ '_CASTOR_MARKDOWN_LINKS' ] = jr_gettext('_CASTOR_MARKDOWN_LINKS', '_CASTOR_MARKDOWN_LINKS', false);
		$output[ '_CASTOR_MARKDOWN_LINKSTEXT' ] = jr_gettext('_CASTOR_MARKDOWN_LINKSTEXT', '_CASTOR_MARKDOWN_LINKSTEXT', false);
		$output[ '_CASTOR_MARKDOWN_IMAGES' ] = jr_gettext('_CASTOR_MARKDOWN_IMAGES', '_CASTOR_MARKDOWN_IMAGES', false);
		$output[ '_CASTOR_MARKDOWN_TABLE' ] = jr_gettext('_CASTOR_MARKDOWN_TABLE', '_CASTOR_MARKDOWN_TABLE', false);
		$output[ '_CASTOR_MARKDOWN_COLUMN' ] = jr_gettext('_CASTOR_MARKDOWN_COLUMN', '_CASTOR_MARKDOWN_COLUMN', false);

		$pageoutput = array();
		$pageoutput[] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_BACKEND);
		$tmpl->addRows('pageoutput', $pageoutput);
		$tmpl->readTemplatesFromInput('markdown.html');
		$template = $tmpl->getParsedTemplate();
		if ($output_now) {
			echo $template;
		} else {
			$this->retVals = $template;
		}
	}


	public function getRetVals()
	{
		return $this->retVals;
	}
}

