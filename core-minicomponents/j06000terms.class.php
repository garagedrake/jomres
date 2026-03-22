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

class j06000terms
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
			$this->shortcode_data = array(
				'task' => 'terms',
				'info' => '_CASTOR_SHORTCODES_06000TERMS',
				'arguments' => array(0 => array(
					'argument' => 'property_uid',
					'arg_info' => '_CASTOR_SHORTCODES_06000TERMS_ARG_PROPERTY_UID',
					'arg_example' => '1',
				),
				),
			);

			return;
		}

		$pdf_test_mode = false;

		if (isset($componentArgs['property_uid'])) {
			$property_uid = (int)$componentArgs['property_uid'];
		} else {
			$property_uid = intval(castorGetParam($_REQUEST, 'property_uid', 0));
		}

		if (isset($componentArgs['as_pdf'])) {
			$as_pdf = (bool)$componentArgs['as_pdf'];
		} elseif (isset($_REQUEST['as_pdf'])) {
			$as_pdf = (bool)castorGetParam($_REQUEST, 'as_pdf', false);
		} else {
			$as_pdf = false;
		}

		if ($pdf_test_mode == true) {
			$as_pdf = true;
		}

		if (isset($componentArgs['output_now'])) {
			$output_now = (bool)$componentArgs['output_now'];
		} elseif (isset($_REQUEST['output_now'])) {
			$output_now = (bool)castorGetParam($_REQUEST, 'output_now', false);
		} else {
			$output_now = false;
		}

		$this->retVals = '';

		jr_import('castor_markdown');
		$castor_markdown = new castor_markdown();

		$query = "SELECT property_policies_disclaimers FROM #__castor_propertys WHERE propertys_uid = '".$property_uid."' LIMIT 1";
		$property_policiesdisclaimers = doSelectSql($query, 1);

		$property_policiesdisclaimers = castor_cmsspecific_parseByBots($castor_markdown->get_markdown(jr_gettext('_CASTOR_CUSTOMTEXT_ROOMTYPE_DISCLAIMERS_'.$property_uid, $property_policiesdisclaimers, false, false)));

		$property = array();
		$property[ 'LIVESITE' ] = get_showtime('live_site');
		$property[ 'HPROPERTYNAME' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_NAME', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_NAME');
		$property[ 'HPOLICIESDISCLAIMERS' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_POLICIESDISCLAIMERS', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_POLICIESDISCLAIMERS');

		$property[ 'POLICIESDISCLAIMERS' ] = $property_policiesdisclaimers;
		if (empty($property[ 'POLICIESDISCLAIMERS' ])) {
			$property[ 'HPOLICIESDISCLAIMERS' ] = '';
		}

		$property_deets[ ] = $property;

		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_FRONTEND);
		$tmpl->addRows('property_deets', $property_deets);

		if ($pdf_test_mode == true) {
			$tmpl->readTemplatesFromInput('terms_pdf.html');
			$pdf = output_pdf($tmpl->getParsedTemplate(), $property[ 'HPOLICIESDISCLAIMERS' ], true);
			header("Content-type:application/pdf");
			echo $pdf;
			exit;
		}

		if ($as_pdf) {
			$tmpl->readTemplatesFromInput('terms_pdf.html');
			$pdf = output_pdf($tmpl->getParsedTemplate(), $property[ 'HPOLICIESDISCLAIMERS' ], true);
			$this->retVals = $pdf;
		} else {
			$tmpl->readTemplatesFromInput('terms.html');
			if (!isset($componentArgs['output_now']) || $componentArgs['output_now'] == true) {
				$tmpl->displayParsedTemplate();
			} else {
				$this->retVals = $tmpl->getParsedTemplate();
			}
		}
	}

	/**
	 * Must be included in every mini-component.
	#
	 * Returns any settings that the mini-component wants to send back to the calling script. In addition to being returned to the calling script they are put into an array in the mcHandler object as eg. $mcHandler->miniComponentData[$ePoint][$eName]
	 */

	public function getRetVals()
	{
		return $this->retVals;
	}
}

