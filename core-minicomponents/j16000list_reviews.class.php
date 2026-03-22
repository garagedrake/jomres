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

class j16000list_reviews
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
		$rows = array();

		//get all properties in system
		$all_properties_in_system = get_showtime('all_properties_in_system');

		//get all property names
		$basic_property_details = castor_singleton_abstract::getInstance('basic_property_details');
		$basic_property_details->get_property_name_multi($all_properties_in_system);

		$output[ '_CASTOR_REVIEWS_ADMIN_PROPERTYLISTINFO' ] = jr_gettext('_CASTOR_REVIEWS_ADMIN_PROPERTYLISTINFO', '_CASTOR_REVIEWS_ADMIN_PROPERTYLISTINFO', false);
		$output[ '_CASTOR_REVIEWS_ADMIN_NUMBERUNPUBLISHED' ] = jr_gettext('_CASTOR_REVIEWS_ADMIN_NUMBERUNPUBLISHED', '_CASTOR_REVIEWS_ADMIN_NUMBERUNPUBLISHED', false);
		$output[ '_CASTOR_REVIEWS_ADMIN_NUMBERTOTAL' ] = jr_gettext('_CASTOR_REVIEWS_ADMIN_NUMBERTOTAL', '_CASTOR_REVIEWS_ADMIN_NUMBERTOTAL', false);
		$output[ '_CASTOR_REVIEWS_REPORT_REVIEW_TITLE' ] = jr_gettext('_CASTOR_REVIEWS_REPORT_REVIEW_TITLE', '_CASTOR_REVIEWS_REPORT_REVIEW_TITLE', false);
		$output[ '_CASTOR_REVIEWS' ] = jr_gettext('_CASTOR_REVIEWS', '_CASTOR_REVIEWS', false);
		$output[ 'HPROPERTYNAME' ] = jr_gettext('_CASTOR_COM_A_INTEGRATEDSEARCH_PROPERTYNAME', '_CASTOR_COM_A_INTEGRATEDSEARCH_PROPERTYNAME', false);

		$editIcon = '<img src="'.CASTOR_IMAGES_RELPATH.'castorimages/small/EditItem.png" border="0" alt="editicon" />';

		jr_import('castor_reviews');
		$Reviews = new castor_reviews();
		$all_reviews = $Reviews->get_all_reviews_index_by_property_uid();
		$all_reports = $Reviews->get_all_reports_index_by_rating_id();
		$total_number_of_reports = count($all_reports);

		foreach ($all_properties_in_system as $property_uid) {
			$r = array();
			$r[ 'PROPERTYNAME' ] = $basic_property_details->property_names[$property_uid];

			if (!using_bootstrap()) {
				$r[ 'VIEWLINK' ] = '<a href="'.CASTOR_SITEPAGE_URL_ADMIN.'&task=view_property_reviews&property_uid='.(int) $property_uid.'">'.$editIcon.'</a>';
			} else {
				$toolbar = castor_singleton_abstract::getInstance('castorItemToolbar');
				$toolbar->newToolbar();
				$toolbar->addItem('fa fa-list', 'btn btn-info', '', castorURL(CASTOR_SITEPAGE_URL_ADMIN.'&task=view_property_reviews&property_uid='.$property_uid), jr_gettext('_CASTOR_REVIEWS_CLICKTOSHOW', '_CASTOR_REVIEWS_CLICKTOSHOW', false));

				$r['VIEWLINK'] = $toolbar->getToolbar();
			}

			$review_count = 0;
			$unpublished_count = 0;
			$report_count = 0;
			foreach ($all_reviews as $property_reviews) {
				foreach ($property_reviews as $review) {
					$rating_id = $review[ 'rating_id' ];
					if ($review[ 'property_uid' ] == $property_uid) {
						if (isset($all_reports[ $rating_id ])) {
							$report_count = count($all_reports[ $rating_id ]);
						}
						++$review_count;
						if ($review[ 'published' ] == 0) {
							$unpublished_count++;
						}
					}
				}
			}

			$r[ 'row_class' ] = '';
			if (!using_bootstrap()) {
				if ($review_count > 0) {
					$r[ 'row_class' ] = 'ui-state-highlight';
				}
				if ($unpublished_count > 0) {
					$r[ 'row_class' ] = 'ui-state-error';
				}
			} else {
				if ($review_count > 0) {
					$r[ 'row_class' ] = 'alert alert-info';
				}
				if ($unpublished_count > 0) {
					$r[ 'row_class' ] = 'alert';
				}
			}

			$r[ 'NUMBERUNPUBLISHED' ] = $unpublished_count;
			$r[ 'NUMBERTOTAL' ] = $review_count;
			$r[ 'REPORTTOTAL' ] = $report_count;

			$rows[ ] = $r;
		}

		$pageoutput[ ] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_ADMINISTRATOR);
		$tmpl->readTemplatesFromInput('list_reviews_propertys.html');
		$tmpl->addRows('pageoutput', $pageoutput);
		$tmpl->addRows('rows', $rows);
		$tmpl->displayParsedTemplate();
	}


	public function getRetVals()
	{
		return null;
	}
}

