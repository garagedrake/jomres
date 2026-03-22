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
class castor_suspensions
{

	/**
	 *
	 *
	 *
	 */

	public function __construct()
	{
		$this->cms_user_id = 0;				   // This is the userid in #__castor_managers. It corresponds with the CMS user's id
		$this->id = 0;							// This is the manager_uid in #__castor_managers
		$this->authorised_properties = array();   // properties that this manager has access to
		$this->accesslevel = 0;					  // access level

		$this->suspended_manager_denied_tasks = array(
													'publish_property',
													'dobooking',
													'list_bookings',
													'edit_booking',
													);  // blocked tasks for suspended managers
	}
	
	/**
	 *
	 *
	 *
	 */

	//gets the userid(cms user id) and sets the id (manager_uid)
	public function set_manager_id($cms_user_id = 0)
	{
		if ($cms_user_id == 0) {
			return false;
		}

		$this->cms_user_id = (int) $cms_user_id;

		$castor_users = castor_singleton_abstract::getInstance('castor_users');

		if ($castor_users->get_user($this->cms_user_id)) {
			$this->id = $castor_users->id;
			$this->authorised_properties = $castor_users->authorised_properties;
			$this->accesslevel = $castor_users->access_level;
		}
	}
	
	/**
	 *
	 *
	 *
	 */

	//suspend the user
	public function suspend_manager()
	{
		if ($this->id == 0) {
			return false;
		}

		$query = 'UPDATE #__castor_managers SET `suspended` = 1 WHERE `manager_uid` = '.$this->id;
		doInsertSql($query, '');

		$this->email_suspension_to_manager();

		return true;
	}
	
	/**
	 *
	 *
	 *
	 */

	//unsuspend the user
	public function unsuspend_manager()
	{
		if ($this->id == 0) {
			return false;
		}

		$query = 'UPDATE #__castor_managers SET `suspended` = 0 WHERE `manager_uid` = '.$this->id;
		doInsertSql($query, '');

		$this->email_unsuspension_to_manager();

		return true;
	}
	
	/**
	 *
	 *
	 *
	 */

	//unpublish the suspended user properties
	public function unpublish_managers_properties()
	{
		if ($this->id == 0) {
			return false;
		}
		
		if ($this->accesslevel < 50 || $this->accesslevel >= 90) {
			return false;
		}

		if (!empty($this->authorised_properties)) {
			$query = 'UPDATE #__castor_propertys SET `published` = 0 WHERE `propertys_uid` IN ('.castor_implode($this->authorised_properties).') AND `published` = 1 ';
			doInsertSql($query, '');

			foreach ($this->authorised_properties as $property_uid) {
				$webhook_notification							   = new stdClass();
				$webhook_notification->webhook_event				= 'property_unpublished';
				$webhook_notification->webhook_event_description	= 'Logs when a property is unpublished.';
				$webhook_notification->webhook_event_plugin		 = 'core';
				$webhook_notification->data						 = new stdClass();
				$webhook_notification->data->property_uid		   = $property_uid;
				add_webhook_notification($webhook_notification);
			}
		}
	}
	
	/**
	 *
	 *
	 *
	 */

	//publish the unsuspended user properties
	//maybe this shouldn`t be done automatically, as this will also publish whatever test properties the manager may have..
	public function publish_managers_properties()
	{
		if ($this->id == 0) {
			return false;
		}
		
		if ($this->accesslevel < 50 || $this->accesslevel >= 90) {
			return false;
		}

		if (!empty($this->authorised_properties)) {
			$query = 'UPDATE #__castor_propertys SET `published` = 1 WHERE `propertys_uid` IN ('.castor_implode($this->authorised_properties).') AND `published` = 0 ';
			doInsertSql($query, '');

			foreach ($this->authorised_properties as $property_uid) {
				$webhook_notification							   = new stdClass();
				$webhook_notification->webhook_event				= 'property_published';
				$webhook_notification->webhook_event_description	= 'Logs when a property is published.';
				$webhook_notification->webhook_event_plugin		 = 'core';
				$webhook_notification->data						 = new stdClass();
				$webhook_notification->data->property_uid		   = $property_uid;
				add_webhook_notification($webhook_notification);
			}
		}
	}
	
	/**
	 *
	 *
	 *
	 */

	//send email to user that his account has been suspended
	public function email_suspension_to_manager()
	{
		$castorConfig_mailfrom = get_showtime('mailfrom');
		$castorConfig_fromname = get_showtime('fromname');

		$user_deets = castor_cmsspecific_getCMS_users_frontend_userdetails_by_id($this->cms_user_id);
		$email = $user_deets[ $this->cms_user_id ][ 'email' ];
		$output = array();
		$pageoutput = array();

		$subject = jr_gettext('_CASTOR_SUSPENSIONS_SUSPENDED_EMAIL_TITLE', '_CASTOR_SUSPENSIONS_SUSPENDED_EMAIL_TITLE');
		$body = jr_gettext('_CASTOR_COM_CONFIRMATION_DEAR', '_CASTOR_COM_CONFIRMATION_DEAR').$user_deets[ $this->cms_user_id ][ 'name' ].".\r\n
		" .jr_gettext('_CASTOR_SUSPENSIONS_MANAGER_SUSPENDED_EMAIL', '_CASTOR_SUSPENSIONS_MANAGER_SUSPENDED_EMAIL');

		if (!castorMailer($castorConfig_mailfrom, $castorConfig_fromname, $email, $subject, $body, $mode = 0)) {
			error_logging('Failure in sending suspension email to user. Target address: '.$email.' Subject'.$subject);
		}

		sendAdminEmail($subject, $body);
	}
	
	/**
	 *
	 *
	 *
	 */

	//send email to user that his account has been unsuspended
	public function email_unsuspension_to_manager()
	{
		$castorConfig_mailfrom = get_showtime('mailfrom');
		$castorConfig_fromname = get_showtime('fromname');

		$user_deets = castor_cmsspecific_getCMS_users_frontend_userdetails_by_id($this->cms_user_id);
		$email = $user_deets[ $this->cms_user_id ][ 'email' ];
		$output = array();
		$pageoutput = array();

		$subject = jr_gettext('_CASTOR_SUSPENSIONS_UNSUSPENDED_EMAIL_TITLE', '_CASTOR_SUSPENSIONS_UNSUSPENDED_EMAIL_TITLE');
		$body = jr_gettext('_CASTOR_COM_CONFIRMATION_DEAR', '_CASTOR_COM_CONFIRMATION_DEAR').$user_deets[ $this->cms_user_id ][ 'name' ].".\r\n
		" .jr_gettext('_CASTOR_SUSPENSIONS_MANAGER_UNSUSPENDED_EMAIL', '_CASTOR_SUSPENSIONS_MANAGER_UNSUSPENDED_EMAIL');

		if (!castorMailer($castorConfig_mailfrom, $castorConfig_fromname, $email, $subject, $body, $mode = 0)) {
			error_logging('Failure in sending unsuspension email to user. Target address: '.$email.' Subject'.$subject);
		}

		sendAdminEmail($subject, $body);
	}
	
	/**
	 *
	 *
	 *
	 */

	//checks if this suspended user has has the rights to access this task
	public function suspended_manager_denied_task($task = '')
	{
		if ($task != '') {
			if (in_array($task, $this->suspended_manager_denied_tasks)) {
				return true;
			}
		}

		return false;
	}
}

