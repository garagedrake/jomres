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

class j16000edit_property_category
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
		
		$output = array();
		$pageoutput = array();
		
		$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
		$jrConfig = $siteConfig->get();
		
		$castor_property_categories = castor_singleton_abstract::getInstance('castor_property_categories');
		
		$id = (int)castorGetParam($_REQUEST, 'id', 0);

		if ($id > 0) {
			$castor_property_categories->get_property_category($id);
		}
		
		$output[ 'ID' ] = $castor_property_categories->id;
		$output[ 'TITLE' ] = $castor_property_categories->title;
		
		if ($jrConfig['allowHTMLeditor'] == "1") {
			$width="95%";
			$height="350";
			$col="20";
			$row="10";
			$output['DESCRIPTION']=editorAreaText('description', $castor_property_categories->description, 'description', $width, $height, $col, $row);
		} else {
			$output['DESCRIPTION']='<textarea class="inputbox" cols="60" rows="6" name="description">'.$castor_property_categories->description.'</textarea>';
		}

		$output[ 'PAGETITLE' ] = jr_gettext('_CASTOR_PROPERTY_HCATEGORIES_HEDIT', '_CASTOR_PROPERTY_HCATEGORIES_HEDIT', false);
		$output[ 'HTITLE' ] = jr_gettext('_JRPORTAL_CRATE_TITLE', '_JRPORTAL_CRATE_TITLE', false);
		$output[ 'HDESCRIPTION' ] = jr_gettext('_CASTOR_COM_MR_EXTRA_DESC', '_CASTOR_COM_MR_EXTRA_DESC', false);

		$jrtbar = castor_singleton_abstract::getInstance('castor_toolbar');
		$jrtb = $jrtbar->startTable();
		$image = $jrtbar->makeImageValid(CASTOR_IMAGES_RELPATH.'castorimages/small/Save.png');
		$link = CASTOR_SITEPAGE_URL_ADMIN;

		$jrtb .= $jrtbar->toolbarItem('cancel', CASTOR_SITEPAGE_URL_ADMIN.'&task=list_property_categories', '');
		$jrtb .= $jrtbar->customToolbarItem('save_property_category', $link, jr_gettext('_CASTOR_COM_MR_SAVE', '_CASTOR_COM_MR_SAVE', false), $submitOnClick = true, $submitTask = 'save_property_category', $image);
		$jrtb .= $jrtbar->endTable();
		$output[ 'CASTORTOOLBAR' ] = $jrtb;

		$pageoutput[ ] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_ADMINISTRATOR);
		$tmpl->readTemplatesFromInput('edit_property_category.html');
		$tmpl->addRows('pageoutput', $pageoutput);
		$tmpl->displayParsedTemplate();
	}


	public function getRetVals()
	{
		return null;
	}
}

