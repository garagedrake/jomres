<?php
/**
 * Core file.
 *
 * @author Vince Wooll <sales@castor.net>
 *
 *  @version Castor 10.7.2
 *
 * @copyright	2005-2023 Vince Wooll
 * Castor is currently available for use in all personal or commercial projects under both MIT and GPL2 licenses. This means that you can choose the license that best suits your project, and use it accordingly
 **/

// ################################################################
defined('_CASTOR_INITCHECK') or die('Direct Access to this file is not allowed.');
// ################################################################
	
	/**
	 *
	 * @package Castor\Core\CMS_Specific
	 *
	 */
	if (!function_exists('castor_cmsspecific_error_logging_cms_files_to_not_backtrace')) {
		function castor_cmsspecific_error_logging_cms_files_to_not_backtrace()
		{
			return array('application.php', 'mcHandler.class.php', 'site.php', 'cms.php', 'helper.php');
		}
	}

	
	/**
	 *
	 *
	 *
	 */


	if (!function_exists('castor_cmsspecific_getsessionid')) {
		function castor_cmsspecific_getsessionid()
		{
			if (!isset($_SESSION)) {
				@session_start();
			}

			if (isset($_SESSION['castor_wp_session']['id']) && $_SESSION['castor_wp_session']['id'] != '') {
				$session_id = $_SESSION['castor_wp_session']['id'];
			} else {
				$session_id = generateCastorRandomString();
				$_SESSION['castor_wp_session'] = array();
				$_SESSION['castor_wp_session']['id'] = $session_id;
			}

			return $session_id;
		}
	}

	
	/**
	 *
	 *
	 *
	 */

// Date is sent in format YYYY/mm/dd, e.g. 2013/
	if (!function_exists('castor_cmsspecific_output_date')) {
		function castor_cmsspecific_output_date($date, $format = false)
		{
			if (!$format) {
				$format = get_option('date_format');
			}

			$result = date_i18n($format, strtotime($date));

			return $result;
		}
	}

	
	/**
	 *
	 *
	 *
	 */

	if (!function_exists('castor_cmsspecific_getregistrationlink')) {
		function castor_cmsspecific_getregistrationlink()
		{
			return castorURL(get_showtime('live_site').'/wp-login.php?action=register');
		}
	}

	
	/**
	 *
	 *
	 *
	 */

	if (!function_exists('castor_cmsspecific_getlogout_task')) {
		function castor_cmsspecific_getlogout_task()
		{
			return 'wp-login.php?action=logout';
		}
	}

	
	/**
	 *
	 *
	 *
	 */
	if (!function_exists('castor_cmsspecific_getlogin_task')) {
		function castor_cmsspecific_getlogin_task()
		{
			if (function_exists('custom_castor_wordpress_login_link')) {
				return custom_castor_wordpress_login_link();
			}
			return 'wp-login.php?action=login';
		}
	}

	
	/**
	 *
	 *
	 *
	 */
	if (!function_exists('castor_cmsspecific_areweinadminarea')) {
		function castor_cmsspecific_areweinadminarea()
		{
			if (strpos($_SERVER['SCRIPT_NAME'], 'wp-admin')) {
				return true;
			} elseif (strpos($_SERVER['SCRIPT_NAME'], 'ajax.php')) {
				return false;
			} else {
				return is_admin();
			}
		}
	}

	
	/**
	 *
	 *
	 *
	 */
	if (!function_exists('castor_cmsspecific_createNewUser')) {
		function castor_cmsspecific_createNewUser($email_address = '')
		{
			if ($email_address == '') {
				throw new Exception('Cannot create a new cms user without an email address');
			}

			$thisJRUser = castor_singleton_abstract::getInstance('jr_user');
			$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
			$jrConfig = $siteConfig->get();
			$tmpBookingHandler = castor_singleton_abstract::getInstance('castor_temp_booking_handler');

			$id = $thisJRUser->id;

			$guestDeets = $tmpBookingHandler->getGuestData();

			//If the email address already exists in the system, we'll not bother carrying on, just return this user's "mos_id"
			$query = "SELECT id FROM #__users WHERE user_email = '".$guestDeets[ 'email' ]."' LIMIT 1";
			$existing = doSelectSql($query, 1);
			if ($existing) {
				return $existing;
			}

			/* 		$valid = false;
					while ( !$valid )
						{
						$username = $guestDeets[ 'firstname' ] . "_" . $guestDeets[ 'surname' ] . rand( 0, 1000 );
						$username = strtolower( preg_replace( '/[^A-Za-z0-9_-]+/', "", $username ) );
						$query	= "SELECT FROM #__users WHERE user_login = '" . $username . "'";
						$users	= doSelectSql( $query );
						if ( count( $users ) == 0 )
							{
							$valid = true;
							}
						} */

			$name = $guestDeets[ 'firstname' ].' '.$guestDeets[ 'surname' ];

			$password = generateCastorRandomString(10);

			$userdata = array(
				'user_login' => $guestDeets[ 'email' ],
				'user_pass' => $password,
				'user_email' => $guestDeets[ 'email' ],
				'user_nicename' => $name,
			);

			$id = wp_insert_user($userdata);

			//On success
			if (!is_wp_error($id)) {
				$webhook_notification							  	= new stdClass();
				$webhook_notification->webhook_event				= 'user_created';
				$webhook_notification->webhook_event_description	= 'Logs when a new user is created.';
				$webhook_notification->webhook_event_plugin		 	= 'core';
				$webhook_notification->data						 	= new stdClass();
				$webhook_notification->data->cms_user_id		   	= $id;
				$webhook_notification->data->name          		   	= $name;
				$webhook_notification->data->password   		   	= $password;
				$webhook_notification->data->username		      	= $guestDeets[ 'email' ];
				$webhook_notification->data->email      		   	= $guestDeets[ 'email' ];

				add_webhook_notification($webhook_notification);

				//$thisJRUser->userIsRegistered=true; // Disabled as this setting would be incorrect during the booking phase. We want newly created users to have their details recorded by the insertGuestDeets function in insertbookings
				$thisJRUser->id = $id;
				$tmpBookingHandler->updateGuestField('mos_userid', $id);

				$subject = jr_gettext('_JRPORTAL_NEWUSER_SUBJECT', '_JRPORTAL_NEWUSER_SUBJECT', false);

				$text = jr_gettext('_JRPORTAL_NEWUSER_DEAR', '_JRPORTAL_NEWUSER_DEAR', false).' '.stripslashes($guestDeets[ 'firstname' ]).' '.stripslashes($guestDeets[ 'surname' ]).'<br />';
				$text .= jr_gettext('_JRPORTAL_NEWUSER_THANKYOU', '_JRPORTAL_NEWUSER_THANKYOU', false).'<br />';
				$text .= jr_gettext('_JRPORTAL_NEWUSER_USERNAME', '_JRPORTAL_NEWUSER_USERNAME', false).' '.$guestDeets[ 'email' ].'<br />';
				$text .= jr_gettext('_JRPORTAL_NEWUSER_PASSWORD', '_JRPORTAL_NEWUSER_PASSWORD', false).' '.$userdata['user_pass'].'<br />';
				$text .= jr_gettext('_JRPORTAL_NEWUSER_LOG_IN', '_JRPORTAL_NEWUSER_LOG_IN', false).' '.get_showtime('live_site').'<br />';

				$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
				$jrConfig = $siteConfig->get();
				if ($jrConfig[ 'useNewusers_sendemail' ] == '1') {
					if (!castorMailer(get_showtime('mailfrom'), get_showtime('fromname'), $guestDeets[ 'email' ], $subject, $text, $mode = 1)) {
						error_logging('Failure in sending registration email to guest. Target address: '.$hotelemail.' Subject'.$subject);
					}
				}

				if (!$thisJRUser->userIsManager) {
					wp_set_current_user($id); // set the current wp user
					wp_set_auth_cookie($id); // start the cookie for the current registered user
				}
			}


			return $id;
		}
	}

	
	/**
	 *
	 *
	 *
	 */
	if (!function_exists('castor_cmsspecific_getRegistrationURL')) {
		function castor_cmsspecific_getRegistrationURL()
		{
		}
	}

	
	/**
	 *
	 *
	 *
	 */
	if (!function_exists('castor_cmsspecific_getTextEditor')) {
		function castor_cmsspecific_getTextEditor($name, $content, $hiddenField, $width, $height, $col, $row)
		{
			ob_start();  // The wp_editor function will output the editor immediately. We don't want that to happen so...we'll buffer the function's response and dump it into a variable for return.
			wp_editor($content, $name);
			$contents = ob_get_contents();
			ob_end_clean();

			return $contents;
		}
	}

	
	/**
	 *
	 *
	 *
	 */
	if (!function_exists('castor_cmsspecific_getcurrentusers_id')) {
		function castor_cmsspecific_getcurrentusers_id()
		{
			$id = 0;

			if (!defined('AUTO_UPGRADE')) {
				$user = wp_get_current_user();
				$id = $user->get('ID');
			}

			return $id;
		}
	}

	
	/**
	 *
	 *
	 *
	 */
	if (!function_exists('castor_cmsspecific_getcurrentusers_username')) {
		function castor_cmsspecific_getcurrentusers_username()
		{
			$username = '';
			$user = wp_get_current_user();
			$username = $user->get('user_login');

			return $username;
		}
	}

	
	/**
	 *
	 *
	 *
	 */
	if (!function_exists('castor_cmsspecific_addheaddata')) {
		function castor_cmsspecific_addheaddata($type, $path = '', $filename = '', $includeVersion = true, $async = false)
		{
			if ($filename == '') {
				return;
			}
			if (!class_exists('WP_Castor')) {
				return;
			}
			$wp_castor = WP_Castor::getInstance();

			$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
			$jrConfig = $siteConfig->get();

			$includeVersion ? $version = $jrConfig['update_time'] : $version = '';

			if (strpos($path, 'http') === false) {
				$url = get_showtime('live_site').'/'.$path.$filename;
			} else {
				$url = $path.$filename;
			}

			switch ($type) {
				case 'javascript':
					$wp_castor->add_js($filename, $url, $version);
					break;
				case 'css':
					$wp_castor->add_css($filename, $url, $version);
					break;
				default:
					break;
			}
		}
	}

	
	/**
	 *
	 * set our meta data
	 *
	 */
	if (!function_exists('castor_cmsspecific_setmetadata')) {
		function castor_cmsspecific_setmetadata($meta, $data)
		{
			$data = castor_decode($data);

			$wp_castor = WP_Castor::getInstance();

			switch ($meta) {
				case 'title':
					$wp_castor->set_meta_title($data);
					break;
				case 'description':
					//$document->setDescription( $data );
					break;
				case 'keywords':
					//$document->setMetaData( 'keywords', $data );
					break;
				default:
					//$document->setMetaData( $meta, $data );
					break;
			}
		}
	}
//

	
	/**
	 *
	 *
	 *
	 */
	if (!function_exists('castor_cmsspecific_getCMS_users_frontend_userdetails_by_id')) {
		function castor_cmsspecific_getCMS_users_frontend_userdetails_by_id($id)
		{
			$user = array();
			$query = 'SELECT id,user_nicename,user_login,user_email FROM #__users WHERE id='.(int) $id;
			$userList = doSelectSql($query);
			if (!empty($userList)) {
				foreach ($userList as $u) {
					$user[ $id ] = array('id' => $u->id, 'name' => $u->user_nicename, 'username' => $u->user_login, 'email' => $u->user_email);
				}
			}

			return $user;
		}
	}
// As per the function name

	
	/**
	 *
	 *
	 *
	 */
	if (!function_exists('castor_cmsspecific_getCMS_users_frontend_userdetails_by_username')) {
		function castor_cmsspecific_getCMS_users_frontend_userdetails_by_username($username)
		{
			$user = array();
			$query = "SELECT id,user_login FROM #__users WHERE user_login='".(string) $username."'";
			$userList = doSelectSql($query);
			if (!empty($userList)) {
				foreach ($userList as $u) {
					$user[ $u->id ] = array('id' => $u->id, 'username' => $u->user_login, 'email' => $u->user_email);
				}
			}

			return $user;
		}
	}
// As per the function name

	
	/**
	 *
	 *
	 *
	 */
	if (!function_exists('castor_cmsspecific_getCMS_users_admin_userdetails_by_id')) {
		function castor_cmsspecific_getCMS_users_admin_userdetails_by_id($id)
		{
			$user = array();
			$query = 'SELECT id,user_login,user_email FROM #__users WHERE id='.(int) $id;
			$userList = doSelectSql($query);
			if (!empty($userList)) {
				foreach ($userList as $u) {
					$user[ $id ] = array('id' => $u->id, 'username' => $u->user_login, 'email' => $u->user_email);
				}
			}

			return $user;
		}
	}
// As per the function name

	
	/**
	 *
	 *
	 *
	 */
	if (!function_exists('castor_cmsspecific_getCMS_users_admin_getalladmins_ids')) {
		function castor_cmsspecific_getCMS_users_admin_getalladmins_ids()
		{
			$users = array();
			$query = "SELECT a.id, a.user_login, a.user_email FROM #__users a LEFT JOIN #__usermeta b ON a.id = b.user_id WHERE b.meta_key = 'wp_user_level' AND b.meta_value >= 10 ";
			$userList = doSelectSql($query);
			if (!empty($userList)) {
				foreach ($userList as $u) {
					$users[ $u->id ] = array('id' => $u->id, 'username' => $u->user_login, 'email' => $u->user_email);
				}
			}

			return $users;
		}
	}
// As per the function name

	
	/**
	 *
	 *
	 *
	 */
	if (!function_exists('castor_cmsspecific_getSearchModuleParameters')) {
		function castor_cmsspecific_getSearchModuleParameters($moduleName = '')
		{
			if (strlen($moduleName) > 0) {
				if ($moduleName == 'mod_jomsearch_m0') {
					return getIntegratedSearchModuleVals();
				} else {
					$query = "SELECT option_value FROM #__options WHERE option_name = 'widget_".$moduleName."'";
					$p = doSelectSql($query, 1);
					$vals = array();
					$arr = unserialize($p);
					$arr = $arr[2];

					if (!empty($arr)) {
						foreach ($arr as $k => $v) {
							$vals[ $k ] = $v;
						}
					}

					return $vals;
				}
			}
		}
	}

	
	/**
	 *
	 * Returns an indexed array of the CMS's users
	 *
	 */
	if (!function_exists('castor_cmsspecific_getCMSUsers')) {
		function castor_cmsspecific_getCMSUsers($cms_user_id = 0)
		{
			$clause = '';
			$users = array();

			if ((int) $cms_user_id > 0) {
				$clause = 'WHERE `id` = '.(int) $cms_user_id;
			}

			$query = 'SELECT id,user_nicename,user_login,user_email FROM #__users '.$clause;
			$userList = doSelectSql($query);
			if (!empty($userList)) {
				foreach ($userList as $u) {
					$users[ $u->id ] = array('id' => $u->id, 'username' => $u->user_login, 'email' => $u->user_email);
				}
			}

			return $users;
		}
	}
//

	
	/**
	 *
	 *
	 *
	 */
	if (!function_exists('castor_cmsspecific_makeSEF_URL')) {
		function castor_cmsspecific_makeSEF_URL($link)
		{
			//for now we don`t have wp router like in joomla to convert a non permalink to permalink url.
			return esc_url_raw($link);
		}
	}

	
	/**
	 *
	 *
	 *
	 */
	if (!function_exists('castor_cmsspecific_parseByBots')) {
		function castor_cmsspecific_parseByBots($str)
		{
			$str = str_replace("&#61;", "=", $str);
			$str = str_replace("&#34;", '"', $str);
			$str = str_replace("&quot;", '"', $str);
			return $str;
		}
	}

	
	/**
	 *
	 *
	 *
	 */
	if (!function_exists('castor_cmsspecific_stringURLSafe')) {
		function castor_cmsspecific_stringURLSafe($str)
		{
			return remove_accents($str);
		}
	}

	
	/**
	 *
	 *
	 *
	 */
	if (!function_exists('castor_cmsspecific_addcustomtag')) {
		function castor_cmsspecific_addcustomtag($data)
		{
			if (trim($data) == '') {
				return;
			}

			if (!class_exists('WP_Castor')) {
				return;
			}

			$wp_castor = WP_Castor::getInstance();

			$wp_castor->add_custom_meta($data);

			return true;
		}
	}

	
	/**
	 *
	 *
	 *
	 */
	if (!function_exists('castor_cmsspecific_currenturl')) {
		function castor_cmsspecific_currenturl()
		{
			return $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		}
	}

	
	/**
	 *
	 *
	 *
	 */
	if (!function_exists('castor_cmsspecific_patchJoomlaTemplate')) {
		function castor_cmsspecific_patchJoomlaTemplate($force = false)
		{
			// Don't need this in WP
			return true;
		}
	}

	
	/**
	 *
	 * Get the cms language
	 *
	 */
	if (!function_exists('castor_cmsspecific_getcmslang')) {
		function castor_cmsspecific_getcmslang()
		{
			return get_bloginfo('language');
		}
	}
//

	
	/**
	 *
	 * Returns an indexed array of the CMS's users where username matches a searched string
	 *
	 */
	if (!function_exists('castor_cmsspecific_find_cms_users')) {
		function castor_cmsspecific_find_cms_users($search_term = '')
		{
			$clause = '';
			$users = array();

			if ($search_term != '') {
				$clause = "WHERE LOWER(`user_login`) LIKE '%".mb_strtolower($search_term)."%'";
			}

			$query = 'SELECT `id`, `user_nicename`, `user_login`, `user_email` FROM #__users '.$clause;
			$userList = doSelectSql($query);

			if (!empty($userList)) {
				foreach ($userList as $u) {
					$users[ $u->id ] = array('id' => $u->id, 'username' => $u->user_login, 'email' => $u->user_email);
				}
			}

			return $users;
		}
	}
//

	
	/**
	 *
	 *
	 *
	 */
	if (!function_exists('castor_cmsspecific_getUsername')) {
		function castor_cmsspecific_getUsername($user_id = 0)
		{
			if ($user_id == 0) {
				return;
			}

			$query = 'SELECT `user_login` FROM #__users WHERE `id` = '.(int)$user_id.' LIMIT 1';
			$result = doSelectSql($query, 1);

			return $result;
		}
	}

	
	/**
	 *
	 *
	 *
	 */
	if (!function_exists('castor_cmsspecific_getCmsUserProfileLink')) {
		function castor_cmsspecific_getCmsUserProfileLink($cms_user_id = 0)
		{
			if ($cms_user_id == 0) {
				return '#';
			}

			$url = get_admin_url('', 'user-edit.php?user_id=' . $cms_user_id, 'admin');

			return $url;
		}
	}

	
	/**
	 *
	 *
	 *
	 */
	if (!function_exists('castor_cmsspecific_isRtl')) {
		function castor_cmsspecific_isRtl($cms_user_id = 0)
		{
			$isRtl = is_rtl();

			return $isRtl;
		}
	}

	
	/**
	 *
	 *
	 *
	 */
	if (!function_exists('castor_cmsspecific_user_is_admin')) {
		function castor_cmsspecific_user_is_admin()
		{
			if (current_user_can('manage_options')) {
				return true;
			}

			return false;
		}
	}


