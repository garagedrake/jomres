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

class j16000list_users
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
	 
	function __construct()
	{
		// Must be in all minicomponents. Minicomponents with templates that can contain editable text should run $this->template_touch() else just return
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = false;
			return;
		}
		
		$output	 = array ();
		$rows	   = array ();
		$pageoutput = array ();

		$output[ 'HLEGEND' ] 					   						= jr_gettext('_CASTOR_HLEGEND', '_CASTOR_HLEGEND', false);
		$output[ '_CASTOR_MANAGER_CHOOSE_EXISTINGTITLE' ]				= jr_gettext('_CASTOR_MANAGER_CHOOSE_EXISTINGTITLE', '_CASTOR_MANAGER_CHOOSE_EXISTINGTITLE', false);
		$output[ '_CASTOR_CHOOSEMANAGER_NUMBEROFPROPERTIES_ASSIGNED' ]	= jr_gettext('_CASTOR_CHOOSEMANAGER_NUMBEROFPROPERTIES_ASSIGNED', '_CASTOR_CHOOSEMANAGER_NUMBEROFPROPERTIES_ASSIGNED', false);
		$output[ 'HACCESSLEVEL' ]	   									= jr_gettext('_CASTOR_COM_MR_ASSIGNUSER_AUTHORISEDACCESSLEVEL', '_CASTOR_COM_MR_ASSIGNUSER_AUTHORISEDACCESSLEVEL', false);
		$output[ 'HUSERNAME' ]		   									= jr_gettext('_CASTOR_COM_MR_ASSIGNUSER_USERNAME', '_CASTOR_COM_MR_ASSIGNUSER_USERNAME', false);

		$output[ '_LIST_USERS_LEGEND_NOROLE' ]		   								= jr_gettext('_LIST_USERS_LEGEND_NOROLE', '_LIST_USERS_LEGEND_NOROLE', false);
		$output[ '_LIST_USERS_LEGEND_RECEPTIONIST' ]		   						= jr_gettext('_LIST_USERS_LEGEND_RECEPTIONIST', '_LIST_USERS_LEGEND_RECEPTIONIST', false);
		$output[ '_LIST_USERS_LEGEND_PROPERTYMANAGER' ]		   						= jr_gettext('_LIST_USERS_LEGEND_PROPERTYMANAGER', '_LIST_USERS_LEGEND_PROPERTYMANAGER', false);
		$output[ '_LIST_USERS_LEGEND_SUPERPROPERTYMANAGER' ]		   				= jr_gettext('_LIST_USERS_LEGEND_SUPERPROPERTYMANAGER', '_LIST_USERS_LEGEND_SUPERPROPERTYMANAGER', false);
		$output[ '_LIST_USERS_LEGEND_SUSPENDED' ]		   							= jr_gettext('_LIST_USERS_LEGEND_SUSPENDED', '_LIST_USERS_LEGEND_SUSPENDED', false);
		$output[ '_LIST_USERS_LEGEND_DELETEDFROMCMS' ]		   						= jr_gettext('_LIST_USERS_LEGEND_DELETEDFROMCMS', '_LIST_USERS_LEGEND_DELETEDFROMCMS', false);
		
		$castor_users = castor_singleton_abstract::getInstance('castor_users');
		$castor_users->get_users();
		
		foreach ($castor_users->users as $u) {
			$r = array ();

			switch ($u['access_level']) {
				case 50: //receptionist
					$r[ 'LABEL_CLASS' ] = 'label-teal';
					$r[ 'ACCESSLEVEL' ] = 'Receptionist';
					break;
				case 70: //manager
					$r[ 'LABEL_CLASS' ] = 'label-blue';
					$r[ 'ACCESSLEVEL' ] = 'Property Manager';
					break;
				case 90: //super property manager
					$r[ 'LABEL_CLASS' ] = 'label-purple';
					$r[ 'ACCESSLEVEL' ] = 'Super Property Manager';
					break;
				default:
					$r[ 'LABEL_CLASS' ] = 'label-grey';
					$r[ 'ACCESSLEVEL' ] = 'No management access';
					break;
			}
			
			if ($u['suspended'] == 1) {
				$r[ 'LABEL_CLASS' ] = 'label-red';
			}
			
			if ($u['username'] == '') {
				$r[ 'LABEL_CLASS' ] = 'label-orange';
			}
			
			if ($u['access_level'] > 0) {
				$toolbar = castor_singleton_abstract::getInstance('castorItemToolbar');
				$toolbar->newToolbar();
				$toolbar->addItem('fa fa-pencil-square-o', 'btn btn-info', '', castorURL(CASTOR_SITEPAGE_URL_ADMIN . '&task=edit_user&cms_user_id=' . $u['cms_user_id']), jr_gettext('COMMON_EDIT', 'COMMON_EDIT', false));
				
				if ($u['access_level'] < 90) {
					if ($u['suspended'] == 1) {
						$toolbar->addSecondaryItem('fa fa-ban', '', '', castorURL(CASTOR_SITEPAGE_URL_ADMIN . '&task=unsuspend_user&cms_user_id=' . $u['cms_user_id']), 'Unsuspend');
					} else {
						$toolbar->addSecondaryItem('fa fa-ban', '', '', castorURL(CASTOR_SITEPAGE_URL_ADMIN . '&task=suspend_user&cms_user_id=' . $u['cms_user_id']), 'Suspend');
					}
				}
				
				$toolbar->addSecondaryItem('fa fa-trash-o', '', '', castorURL(CASTOR_SITEPAGE_URL_ADMIN . '&task=delete_user&cms_user_id=' . $u['cms_user_id']), jr_gettext('COMMON_DELETE', 'COMMON_DELETE', false));
				
				$r['LINKTEXT'] = $toolbar->getToolbar();
			} else {
				$r[ 'LINKTEXT' ] = "";
			}

			if ($u['username'] != '') {
				$r[ 'USERNAME' ]	   = $u['username'];
			} else {
				$r[ 'USERNAME' ]	   = '-';
			}
			
			$r[ 'NUMBEROFPROPERTIES' ] = count($u['authorised_properties']);
			$r[ 'API_KEY' ] 		   = $u['apikey'];
			
			$rows[] = $r;
		}
		
		$jrtbar = castor_singleton_abstract::getInstance('castor_toolbar');
		$jrtb  = $jrtbar->startTable();
		
		$image = $jrtbar->makeImageValid(CASTOR_IMAGES_RELPATH.'castorimages/small/AddItem.png');
		
		$jrtb .= $jrtbar->customToolbarItem('edit', CASTOR_SITEPAGE_URL_ADMIN, $text=jr_gettext('COMMON_NEW', 'COMMON_NEW', false), $submitOnClick=true, $submitTask="edit_user", $image);
		$jrtb .= $jrtbar->toolbarItem('cancel', CASTOR_SITEPAGE_URL_ADMIN, jr_gettext("COMMON_CANCEL", 'COMMON_CANCEL', false));
		$jrtb .= $jrtbar->endTable();
		$output['CASTORTOOLBAR']=$jrtb;

		$pageoutput[] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_ADMINISTRATOR);
		$tmpl->readTemplatesFromInput('list_users.html');
		$tmpl->addRows('pageoutput', $pageoutput);
		$tmpl->addRows('rows', $rows);
		$tmpl->displayParsedTemplate();
	}


	function getRetVals()
	{
		return null;
	}
}

