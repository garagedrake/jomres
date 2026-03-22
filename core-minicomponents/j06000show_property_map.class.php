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

class j06000show_property_map
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
            $this->shortcode_data = array(
                'task' => 'show_property_map',
                'info' => '_CASTOR_SHORTCODES_06000SHOW_PROPERTY_MAP',
                'arguments' => array(0 => array(
                    'argument' => 'property_uid',
                    'arg_info' => '_CASTOR_SHORTCODES_06000SHOW_PROPERTY_MAP_ARG_PROPERTY_UID',
                    'arg_example' => '1',
                ),
                    1 => array(
                        'argument' => 'map_zoom',
                        'arg_info' => '_CASTOR_SHORTCODES_06005PROPERTY_MAP_ZOOM',
                        'arg_example' => '10',
                    ),
                ),
            );

            return;
        }

        $this->retVals = '';

        $siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
        $jrConfig = $siteConfig->get();

        if (!isset($jrConfig["google_maps_api_key"]) || trim($jrConfig["google_maps_api_key"]) == '') {
            echo "<div class='alert alert-danger'>Google maps api key is not set. Go to Administrator > Castor > Settings > Site Configuration > Integrations tab and save your Google maps keys there.</div>";
            return;
        }

        if (isset($componentArgs[ 'property_uid' ])) {
            $property_uid = (int)$componentArgs[ 'property_uid' ];
        } else {
            $property_uid = (int)castorGetParam($_REQUEST, 'property_uid', 0);
        }

        if ($property_uid == 0) {
            return;
        }

        if (!user_can_view_this_property($property_uid)) {
            return;
        }

        if (isset($componentArgs[ 'output_now' ])) {
            $output_now = $componentArgs[ 'output_now' ];
        } else {
            $output_now = true;
        }

        $mrConfig = getPropertySpecificSettings($property_uid);

        castor_set_page_title( $property_uid ,  jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_MAPPINGLINK', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_MAPPINGLINK', false) );

        $mw = 300;
        $mh = $jrConfig['map_height'];
        if ((int)castorGetParam($_REQUEST, 'property_uid', 0) > 0) {
            if (isset($_REQUEST[ 'mw' ])) {
                $mw = (int) $_REQUEST[ 'mw' ];
            }
            if (isset($_REQUEST[ 'mh' ])) {
                $mh = (int) $_REQUEST[ 'mh' ];
            }
            if (isset($_REQUEST[ 'output_now' ])) {
                $output_now = (bool) castorGetParam($_REQUEST, 'output_now', 1);
            }
        } else {
            if (isset($componentArgs[ 'mw' ])) {
                $mw = (int) $componentArgs[ 'mw' ];
            }
            if (isset($componentArgs[ 'mh' ])) {
                $mh = (int) $componentArgs[ 'mh' ];
            }
            if (isset($componentArgs[ 'output_now' ])) {
                $output_now = (bool) $componentArgs[ 'output_now' ];
            }
        }

        $componentArgs = array('property_uid' => $property_uid, 'width' => $mw, 'height' => $mh);
        $MiniComponents->specificEvent('01050', 'x_geocoder', $componentArgs);

        if ($output_now) {
            echo $MiniComponents->miniComponentData[ '01050' ][ 'x_geocoder' ];
        } else {
            $this->retVals = $MiniComponents->miniComponentData[ '01050' ][ 'x_geocoder' ];
        }
    }


    public function getRetVals()
    {
        return $this->retVals;
    }
}

