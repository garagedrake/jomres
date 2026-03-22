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

class j11020towns
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

    function __construct($componentArgs)
    {
        // Must be in all minicomponents. Minicomponents with templates that can contain editable text should run $this->template_touch() else just return
        $MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
        if ($MiniComponents->template_touch) {
            $this->template_touchable = false;
            return;
        }
        $thisJRUser = castor_singleton_abstract::getInstance('jr_user');
        if (!$thisJRUser->userIsManager) {
            return;
        }

        $dropdown = '';

        //big results list on big sites...we need a better way, because grouping by property town won`t work if mysql mode is set to ONLY_FULL_GROUP_BY
        $query = "SELECT `propertys_uid`, `property_town` FROM #__castor_propertys WHERE `property_town` != ''";
        $townsList = doSelectSql($query);

        if (!empty($townsList)) {
            foreach ($townsList as $t) {
                $towns[$t->propertys_uid] = $t->property_town;
            }

            $towns = array_unique($towns);

            $resource_options = array();
            foreach ($towns as $k => $v) {
                set_showtime('property_uid', $k);
                $resource_options[ ] = castorHTML::makeOption(castor_cmsspecific_stringURLSafe($v), jr_gettext('_CASTOR_CUSTOMTEXT_PROPERTY_TOWN', castor_decode($v), ENT_QUOTES));
            }
            $use_bootstrap_radios = false;
            $dropdown = castorHTML::selectList($resource_options, 'resource_id', ' autocomplete="off" ', 'value', 'text', '', $use_bootstrap_radios);
        }
        $this->ret_vals = $dropdown;
    }


    function getRetVals()
    {
        return $this->ret_vals;
    }
}

