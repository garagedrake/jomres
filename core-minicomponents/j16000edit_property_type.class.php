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

class j16000edit_property_type
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

        $id = intval(castorGetParam($_GET, 'id', 0));

        $siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
        $jrConfig = $siteConfig->get();

        $output = array();

        $output[ 'PAGETITLE' ] = jr_gettext('_CASTOR_COM_PTYPES_LIST_TITLE_EDIT', '_CASTOR_COM_PTYPES_LIST_TITLE_EDIT', false);
        $output[ 'HPTYPE' ] = jr_gettext('_CASTOR_COM_PTYPES_PTYPE', '_CASTOR_COM_PTYPES_PTYPE', false);
        $output[ 'HPTYPE_DESC' ] = jr_gettext('_CASTOR_COM_LANGUAGE_CONTEXT', '_CASTOR_COM_LANGUAGE_CONTEXT', false);
        $output[ 'HPUBLISHED' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PUBLISHED', '_CASTOR_COM_MR_VRCT_PUBLISHED', false);
        $output[ 'FURTHER' ] = jr_gettext('_CASTOR_COM_PTYPES_PTYPE_DESC_FURTHER', '_CASTOR_COM_PTYPES_PTYPE_DESC_FURTHER', false);
        $output[ 'CASTOR_SITEPAGE_URL_ADMIN' ] = jr_gettext('CASTOR_SITEPAGE_URL_ADMIN', 'CASTOR_SITEPAGE_URL_ADMIN', false);
        $output[ '_CASTOR_PROPERTYTYPE_FLAG' ] = jr_gettext('_CASTOR_PROPERTYTYPE_FLAG', '_CASTOR_PROPERTYTYPE_FLAG', false);
        $output[ '_CASTOR_PROPERTYTYPE_FLAG_DESC' ] = jr_gettext('_CASTOR_PROPERTYTYPE_FLAG_DESC', '_CASTOR_PROPERTYTYPE_FLAG_DESC', false);
        $output[ '_CASTOR_PROPERTYTYPE_MARKER' ] = jr_gettext('_CASTOR_PROPERTYTYPE_MARKER', '_CASTOR_PROPERTYTYPE_MARKER', false);
        $output[ 'HAS_STARS_TITLE' ] = jr_gettext('HAS_STARS_TITLE', 'HAS_STARS_TITLE', false);

        //get property type details by id
        $castor_property_types = castor_singleton_abstract::getInstance('castor_property_types');

		if ($id >0) {
			$castor_property_types->get_property_type($id);
		} else {
			$castor_property_types->property_type['id']             = 0;
			$castor_property_types->property_type['ptype']          = '';
			$castor_property_types->property_type['ptype_desc']     = '';
			$castor_property_types->property_type['published']      = 1;
			$castor_property_types->property_type['order']          = 0;
			$castor_property_types->property_type['mrp_srp_flag']   = 0;
			$castor_property_types->property_type['marker']         = '';
			$castor_property_types->property_type['has_stars']      = 1;
		}



        if (!is_array($castor_property_types->property_type)) {
            return;
        }

        //room type icons
        $images = $castor_property_types->get_all_property_type_images();

        $markers = array();

        foreach ($images as $i) {
            $i[ 'ISCHECKED' ] = '';

            if ($i[ 'IMAGE_FILENAME' ] == $castor_property_types->property_type['marker']) {
                $i[ 'ISCHECKED' ] = 'checked';
            }

            $markers[] = $i;
        }

        $output[ 'PTYPE' ] = $castor_property_types->property_type['ptype'];
        $output[ 'PTYPE_DESC' ] = $castor_property_types->property_type['ptype_desc'];

        // mrp_srp_flag:
        // 0 - hotel
        // 1 - villa/apartment
        // 2 - both - BC, resets to 0
        // 3 - tours
        // 4 - real estate
        if ($castor_property_types->property_type['mrp_srp_flag'] == 2) {
            $mrp_srp_flag = 0;
        } else {
            $mrp_srp_flag = $castor_property_types->property_type['mrp_srp_flag'];
        }

        $mrp_srp_flag_options = array();
        $mrp_srp_flag_options[ ] = castorHTML::makeOption('0', jr_gettext('_CASTOR_PROPERTYTYPE_FLAG_HOTEL', '_CASTOR_PROPERTYTYPE_FLAG_HOTEL', false));
        $mrp_srp_flag_options[ ] = castorHTML::makeOption('1', jr_gettext('_CASTOR_PROPERTYTYPE_FLAG_VILLA', '_CASTOR_PROPERTYTYPE_FLAG_VILLA', false));
        $mrp_srp_flag_options[ ] = castorHTML::makeOption('3', jr_gettext('_CASTOR_PROPERTYTYPE_FLAG_TOURS', '_CASTOR_PROPERTYTYPE_FLAG_TOURS', false));
        $mrp_srp_flag_options[ ] = castorHTML::makeOption('4', jr_gettext('_CASTOR_PROPERTYTYPE_FLAG_REALESTATE', '_CASTOR_PROPERTYTYPE_FLAG_REALESTATE', false));
        $mrp_srp_flag_options[ ] = castorHTML::makeOption('5', jr_gettext('_CASTOR_PROPERTYTYPE_FLAG_HIRE', '_CASTOR_PROPERTYTYPE_FLAG_HIRE', false));


        $output[ '_CASTOR_PROPERTYTYPE_FLAG_DROPDOWN' ] = castorHTML::selectList($mrp_srp_flag_options, 'mrp_srp_flag', '', 'value', 'text', $mrp_srp_flag);

        $has_stars_options = array();
        $has_stars_options[ ] = castorHTML::makeOption('0', jr_gettext('_CASTOR_COM_MR_NO', '_CASTOR_COM_MR_NO', false));
        $has_stars_options[ ] = castorHTML::makeOption('1', jr_gettext('_CASTOR_COM_MR_YES', '_CASTOR_COM_MR_YES', false));
        $output[ 'HAS_STARS' ] = castorHTML::selectList($has_stars_options, 'has_stars', '', 'value', 'text', $castor_property_types->property_type['has_stars']);

        $output[ 'ID' ] = $id;

        $jrtbar = castor_singleton_abstract::getInstance('castor_toolbar');
        $jrtb = $jrtbar->startTable();
        $image = $jrtbar->makeImageValid(CASTOR_IMAGES_RELPATH.'castorimages/small/Save.png');
        $link = CASTOR_SITEPAGE_URL_ADMIN;
        $jrtb .= $jrtbar->toolbarItem('cancel', CASTOR_SITEPAGE_URL_ADMIN.'&task=list_property_types', '');
        $jrtb .= $jrtbar->customToolbarItem('save_property_type', $link, jr_gettext('_CASTOR_COM_MR_SAVE', '_CASTOR_COM_MR_SAVE', false), $submitOnClick = true, $submitTask = 'save_property_type', $image);
        $jrtb .= $jrtbar->endTable();

        $output[ 'CASTORTOOLBAR' ] = $jrtb;

        $pageoutput[] = $output;
        $tmpl = new patTemplate();
        $tmpl->setRoot(CASTOR_TEMPLATEPATH_ADMINISTRATOR);
        $tmpl->readTemplatesFromInput('edit_property_type.html');
        $tmpl->addRows('pageoutput', $pageoutput);
        $tmpl->addRows('markers', $markers);
        $tmpl->displayParsedTemplate();
    }


    public function getRetVals()
    {
        return null;
    }
}

