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

/**
 *
 * @package Castor\Core\Classes
 *
 */
#[AllowDynamicProperties]
class castor_config_property_singleton
{

    /**
     *
     *
     *
     */

    public function __construct()
    {
        $this->property_uid = 0;

        $this->default_config = array();
        $this->property_config = array();
        $this->all_property_settings = array();

        //get default property settings
        $this->get_default_property_config();

        //get property specific settings
        $this->get_property_settings();
    }

    /**
     *
     *
     *
     */

    public function set($config)
    {
        $this->property_config = $config;
    }

    /**
     *
     *
     *
     */

    public function get()
    {
        return $this->property_config;
    }

    /**
     *
     *
     *
     */

    public function set_setting($setting, $value)
    {
        $this->property_config[ $setting ] = $value;
    }

    /**
     *
     *
     *
     */

    public function get_setting($setting)
    {
        return $this->property_config[ $setting ];
    }

    /**
     *
     *
     *
     */

    public function init($property_uid = null, $force_reload = false)
    {
        return $this->load_property_config($property_uid, $force_reload);
    }

    /**
     *
     *
     *
     */

    //load property config for current property uid
    public function load_property_config($property_uid = null, $force_reload = false)
    {

        if (!is_null($property_uid)) {
            $this->property_uid = (int)$property_uid;
        }

        if ($force_reload == true) {
            if (isset($this->all_property_settings[$this->property_uid])) {
                unset($this->all_property_settings[$this->property_uid]);
            }
        }

        if ($this->property_uid > 0) {
            $siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
            $jrConfig = $siteConfig->get();

            $temp_config = $this->default_config;

            if (!isset($this->all_property_settings[$this->property_uid])) {
                $this->get_property_settings(array($this->property_uid));
            }

            $temp_config = array_merge($temp_config, $this->all_property_settings[$this->property_uid]);

            if ($jrConfig[ 'useGlobalCurrency' ] == '1') {
                $temp_config[ 'currencyCode' ] = $jrConfig[ 'globalCurrencyCode' ];
            }

            $this->property_config = $temp_config;
        } else {
            $this->property_config = $this->default_config;
        }

        $this->property_config['item_hire_property'] = false;

        if ($property_uid >0) {
            $query = "SELECT ptype_id FROM #__castor_propertys WHERE propertys_uid = ".(int)$this->property_uid;
            $property_type = doSelectSql($query,1);
            $castor_property_types = castor_singleton_abstract::getInstance('castor_property_types');
            $castor_property_types->get_property_type($property_type);

            if ($castor_property_types->property_type['mrp_srp_flag'] == 5) {
                $this->property_config['singleRoomProperty'] = false;
                $this->property_config['item_hire_property'] = true;
            }
        }

        return $this->property_config;
    }

    /**
     *
     *
     *
     */

    //get property configs multi
    public function get_property_settings($property_uids = array())
    {
        if (empty($property_uids)) {
            return true;
        }

        foreach ($property_uids as $k => $uid) {
            if ($uid == 0) {
                unset($property_uids[$k]);
            }

            if (isset($this->all_property_settings[$uid])) {
                unset($property_uids[$k]);
            } else {
                $this->all_property_settings[$uid] = array();
            }
        }

        if (empty($property_uids)) {
            return true; //we already have all settings we need
        }

        $query = 'SELECT `property_uid`, `akey`, `value` FROM #__castor_settings WHERE `property_uid` IN ('.castor_implode($property_uids).')';
        $result = doSelectSql($query);

        if (!empty($result)) {
            foreach ($result as $setting) {
                $this->all_property_settings[ $setting->property_uid ][ $setting->akey ] = $setting->value;
            }
        }
    }

    /**
     *
     *
     *
     */

    //get default property config
    private function get_default_property_config()
    {
        if (!empty($this->default_config)) {
            return true;
        }

        $siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
        $jrConfig = $siteConfig->get();

        //no more missing settings
        if (file_exists(CASTORCONFIG_ABSOLUTE_PATH.JRDS.CASTOR_ROOT_DIRECTORY.JRDS.'castor_config.php')) {
            include CASTORCONFIG_ABSOLUTE_PATH.JRDS.CASTOR_ROOT_DIRECTORY.JRDS.'castor_config.php';
            $this->default_config = $mrConfig;
        }

        $query = 'SELECT `property_uid`, `akey`, `value` FROM #__castor_settings WHERE `property_uid` = 0';
        $result = doSelectSql($query);

        if (!empty($result)) {
            foreach ($result as $setting) {
                $this->default_config[ $setting->akey ] = $setting->value;
            }

            if ($jrConfig[ 'useGlobalCurrency' ] == '1') {
                $this->default_config[ 'currencyCode' ] = $jrConfig[ 'globalCurrencyCode' ];
            }
        }
    }
}

