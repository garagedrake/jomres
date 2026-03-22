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

class j16000list_property_types
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
			$this->template_touchable = true;

			return;
		}

		$ptype_data = array();
		$output = array();

		$output[ 'PAGETITLE' ] = jr_gettext('_CASTOR_COM_PTYPES_LIST_TITLE', '_CASTOR_COM_PTYPES_LIST_TITLE', false);
		$output[ 'HPTYPE' ] = jr_gettext('_CASTOR_COM_PTYPES_PTYPE', '_CASTOR_COM_PTYPES_PTYPE', false);
		$output[ 'HPTYPE_DESC' ] = jr_gettext('_CASTOR_COM_LANGUAGE_CONTEXT', '_CASTOR_COM_LANGUAGE_CONTEXT', false);
		$output[ 'HPUBLISHED' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PUBLISHED', '_CASTOR_COM_MR_VRCT_PUBLISHED', false);
		$output[ '_CASTOR_ORDER' ] = jr_gettext('_CASTOR_ORDER', '_CASTOR_ORDER', false);
		$output[ '_CASTOR_PROPERTYTYPE_FLAG_BOTH_COLHEAD' ] = jr_gettext('_CASTOR_PROPERTYTYPE_FLAG_BOTH_COLHEAD', '_CASTOR_PROPERTYTYPE_FLAG_BOTH_COLHEAD', false);
		$output[ '_CASTOR_PROPERTY_MANAGEMENT_PROCESS_ROOM_TYPES' ] = jr_gettext('_CASTOR_PROPERTY_MANAGEMENT_PROCESS_ROOM_TYPES', '_CASTOR_PROPERTY_MANAGEMENT_PROCESS_ROOM_TYPES', false);
		$output[ '_CASTOR_PROPERTYTYPE_MARKER' ] = jr_gettext('_CASTOR_PROPERTYTYPE_MARKER', '_CASTOR_PROPERTYTYPE_MARKER', false);
		$output[ 'HAS_STARS_TITLE' ] = jr_gettext('HAS_STARS_TITLE', 'HAS_STARS_TITLE', false);

		//get all property type details
		$castor_property_types = castor_singleton_abstract::getInstance('castor_property_types');
		$castor_property_types->get_all_property_types();

		$row_info = '';
		$counter = 0;
		foreach ($castor_property_types->property_types as $p) {
			++$counter;

			$r = array();

			$r[ 'COUNTER' ] = $counter;
			$r[ 'ID' ] = $p[ 'id' ];
			$r[ 'EDITURL' ] = castorUrl(CASTOR_SITEPAGE_URL_ADMIN.'&task=edit_property_type&id='.$p[ 'id' ]);
			$r[ 'EDITICON' ] = '<img src="'.CASTOR_IMAGES_RELPATH.'castorimages/small/EditItem.png" border="0" />';
			$r[ 'PTYPE' ] = $p[ 'ptype' ];
			$r[ 'PTYPEDESC' ] = $p[ 'ptype_desc' ];
			$r[ 'PUBLISHURL' ] = castorUrl(CASTOR_SITEPAGE_URL_ADMIN.'&task=publish_property_type&id='.$p[ 'id' ]);
			$r[ 'ORDER' ] = $p['order'];
			if (isset($p['marker_image'])) {
				$r[ 'MARKER_IMAGE' ] = $p['marker_image'];
			} else {
				$r[ 'MARKER_IMAGE' ] = CASTOR_IMAGES_RELPATH.'noimage_small.gif';
			}

			if ($p['published'] == 1) {
				$r[ 'PUBLISHIMAGE' ] = CASTOR_IMAGES_RELPATH.'castorimages/small/Tick.png';
			} else {
				$r[ 'PUBLISHIMAGE' ] = CASTOR_IMAGES_RELPATH.'castorimages/small/Cancel.png';
			}
			
			if ($p['has_stars'] == 1) {
				$r[ 'HAS_STARS' ] = jr_gettext('_CASTOR_COM_MR_YES', '_CASTOR_COM_MR_YES', false);
			} else {
				$r[ 'HAS_STARS' ] = jr_gettext('_CASTOR_COM_MR_NO', '_CASTOR_COM_MR_NO', false);
			}

			switch ($p['mrp_srp_flag']) {
				case 1:
					$r[ 'MRP_SRP_FLAG' ] = jr_gettext('_CASTOR_PROPERTYTYPE_FLAG_VILLA', '_CASTOR_PROPERTYTYPE_FLAG_VILLA', false);
					break;
				case 2:
					$r[ 'MRP_SRP_FLAG' ] = jr_gettext('_CASTOR_PROPERTYTYPE_FLAG_BOTH', '_CASTOR_PROPERTYTYPE_FLAG_BOTH', false);
					break;
				case 3:
					$r[ 'MRP_SRP_FLAG' ] = jr_gettext('_CASTOR_PROPERTYTYPE_FLAG_TOURS', '_CASTOR_PROPERTYTYPE_FLAG_TOURS', false);
					break;
				case 4:
					$r[ 'MRP_SRP_FLAG' ] = jr_gettext('_CASTOR_PROPERTYTYPE_FLAG_REALESTATE', '_CASTOR_PROPERTYTYPE_FLAG_REALESTATE', false);
					break;
                case 5:
                    $r[ 'MRP_SRP_FLAG' ] = jr_gettext('_CASTOR_PROPERTYTYPE_FLAG_HIRE', '_CASTOR_PROPERTYTYPE_FLAG_HIRE', false);
                    break;
				default:
					$r[ 'MRP_SRP_FLAG' ] = jr_gettext('_CASTOR_PROPERTYTYPE_FLAG_HOTEL', '_CASTOR_PROPERTYTYPE_FLAG_HOTEL', false);
					break;
			}

			if (!using_bootstrap()) {
				$r['EDITLINK'] = '';
			} else {
				$toolbar = castor_singleton_abstract::getInstance('castorItemToolbar');
				$toolbar->newToolbar();

				if ($p['published'] == 0) {
					$toolbar->addItem('fa fa-times', 'btn btn-default', '', castorURL(CASTOR_SITEPAGE_URL_ADMIN.'&task=publish_property_type'.'&id='.$p[ 'id' ]), jr_gettext('_CASTOR_COM_MR_VRCT_PUBLISH', '_CASTOR_COM_MR_VRCT_PUBLISH', false));
				} else {
					$toolbar->addItem('fa fa-check', 'btn btn-success', '', castorURL(CASTOR_SITEPAGE_URL_ADMIN.'&task=publish_property_type'.'&id='.$p[ 'id' ]), jr_gettext('_CASTOR_COM_MR_VRCT_UNPUBLISH', '_CASTOR_COM_MR_VRCT_UNPUBLISH', false));
				}

				$toolbar->addSecondaryItem('fa fa-pencil-square-o', '', '', castorURL(CASTOR_SITEPAGE_URL_ADMIN.'&task=edit_property_type&id='.$p[ 'id' ]), jr_gettext('COMMON_EDIT', 'COMMON_EDIT', false));

				$r['EDITLINK'] = $toolbar->getToolbar();
			}

			$rows[ ] = $r;
		}

		$output[ 'COUNTER' ] = count($rows);
		$output[ 'TOTALINLISTPLUSONE' ] = count($rows) + 1;

		$jrtbar = castor_singleton_abstract::getInstance('castor_toolbar');
		$jrtb = $jrtbar->startTable();
		$jrtb .= $jrtbar->toolbarItem('cancel', CASTOR_SITEPAGE_URL_ADMIN, '');
		$image = $jrtbar->makeImageValid(CASTOR_IMAGES_RELPATH.'castorimages/small/AddItem.png');
		$link = CASTOR_SITEPAGE_URL_ADMIN;
		$jrtb .= $jrtbar->customToolbarItem('editPropertyType', $link, jr_gettext('_CASTOR_COM_MR_NEWTARIFF', '_CASTOR_COM_MR_NEWTARIFF', false), $submitOnClick = true, $submitTask = 'edit_property_type', $image);
		$image = $jrtbar->makeImageValid(CASTOR_IMAGES_RELPATH.'castorimages/small/WasteBasket.png');
		$link = CASTOR_SITEPAGE_URL_ADMIN;
		$jrtb .= $jrtbar->customToolbarItem('deletePropertyType', $link, jr_gettext('_CASTOR_COM_MR_ROOM_DELETE', '_CASTOR_COM_MR_ROOM_DELETE', false), $submitOnClick = true, $submitTask = 'delete_property_type', $image);
		$jrtb .= $jrtbar->endTable();

		$output[ 'CASTORTOOLBAR' ] = $jrtb;

		$pageoutput[ ] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_ADMINISTRATOR);
		$tmpl->readTemplatesFromInput('list_ptypes.html');
		$tmpl->addRows('pageoutput', $pageoutput);
		$tmpl->addRows('rows', $rows);
		$tmpl->displayParsedTemplate();
	}

	public function touch_template_language()
	{
		$output = array();

		$castor_property_types = castor_singleton_abstract::getInstance('castor_property_types');
		$castor_property_types->get_all_property_types();

		foreach ($castor_property_types->property_types as $p) {
			$output[ ] = jr_gettext('_CASTOR_CUSTOMTEXT_PROPERTYTYPES'.$p['id'], $p['ptype']);
		}

		foreach ($output as $o) {
			echo $o;
			echo '<br/>';
		}
	}


	public function getRetVals()
	{
		return null;
	}
}

