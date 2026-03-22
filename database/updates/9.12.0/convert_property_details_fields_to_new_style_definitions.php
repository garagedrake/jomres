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
 * @package Castor\Core\Database
 *
 * Database modification during updates
 *
 **/
$old_style_property_description_definitions = array (
	'_CASTOR_CUSTOMTEXT_PROPERTY_NAME',
	'_CASTOR_CUSTOMTEXT_PROPERTY_STREET',
	'_CASTOR_CUSTOMTEXT_PROPERTY_TOWN',
	'_CASTOR_CUSTOMTEXT_ROOMTYPE_DESCRIPTION',
	'_CASTOR_CUSTOMTEXT_ROOMTYPE_CHECKINTIMES',
	'_CASTOR_CUSTOMTEXT_ROOMTYPE_AREAACTIVITIES',
	'_CASTOR_CUSTOMTEXT_ROOMTYPE_DIRECTIONS',
	'_CASTOR_CUSTOMTEXT_ROOMTYPE_AIRPORTS',
	'_CASTOR_CUSTOMTEXT_ROOMTYPE_OTHERTRANSPORT',
	'_CASTOR_CUSTOMTEXT_ROOMTYPE_DISCLAIMERS',
	'_CASTOR_CUSTOMTEXT_PROPERTY_METATITLE',
	'_CASTOR_CUSTOMTEXT_PROPERTY_METADESCRIPTION',
	'_CASTOR_CUSTOMTEXT_PROPERTY_METAKEYWORDS'
);

$old_translations = array();

foreach ( $old_style_property_description_definitions as $definition ){
	$query = "SELECT uid , constant , property_uid  FROM #__castor_custom_text WHERE constant = '".$definition."'";
	$old_translations [$definition] = doSelectSql($query);
}

if (!empty($old_translations)) {
	foreach ($old_translations as $key=>$val) {
		if (!empty($val)) {
			foreach ($val as $record ) {
				$new_query = "UPDATE #__castor_custom_text SET 
					`constant` = '".$record->constant."_".$record->property_uid."'
					WHERE
					`uid` = ".$record->uid."
					 ";
				doInsertSql($new_query);
			}
			
		}
	}
}


