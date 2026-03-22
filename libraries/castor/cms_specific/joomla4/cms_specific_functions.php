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
use Joomla\CMS\Factory;
use Joomla\CMS\User\UserFactoryInterface;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Editor\Editor;

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
			$app = JFactory::getApplication();
			$session =  $app->getSession();

			return $session->getId();
		}
	}


/**
 *
 * Date is sent in format YYYY/mm/dd, e.g. 2013/
 * https://docs.joomla.org/API16:JHtml/date
 */
	if (!function_exists('castor_cmsspecific_output_date')) {
		function castor_cmsspecific_output_date($date, $format = false)
		{
			if (!$format) {
				$format = JText::_('DATE_FORMAT_LC');
			}

			$result = JHtml::date($date, $format, $usertimezone = false);

			return $result;
		}
	}
//
//


/**
 *
 *
 *
 */
	if (!function_exists('castor_cmsspecific_getregistrationlink')) {
		function castor_cmsspecific_getregistrationlink()
		{
			return castorURL(get_showtime('live_site').'/index.php?option=com_users&view=registration');
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
			return 'index.php?option=com_users&view=login';
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
			return 'index.php?option=com_users&view=login';
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
			$administrator_area = false;
			if (strpos($_SERVER[ 'SCRIPT_NAME' ], 'administrator')) {
				$administrator_area = true;
			}

			return $administrator_area;
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

			$guest_email = restore_task_specific_email_address($guestDeets[ 'email' ]);

			//If the email address already exists in the system, we'll not bother carrying on, just return this user's "mos_id"
			$query = "SELECT `id` FROM #__users WHERE `email` = '".$guestDeets[ 'email' ]."' LIMIT 1";
			$existing = doSelectSql($query, 1);
			if ($existing) {
				return $existing;
			}
			$name = $guestDeets[ 'firstname' ].' '.$guestDeets[ 'surname' ];
			$usertype = 'Registered';
			$block = '0';

			$clear_password = JUserHelper::genRandomPassword(20);
			$encryptedPassword = JUserHelper::hashPassword($clear_password);

			$query = "INSERT INTO #__users (
		`name`,
		`username`,
		`email`,
		`password`,
		`registerDate`,
		`lastvisitDate`,
		`lastResetTime`,
		`params`
		) VALUES (
		'" .$name."',
		'" .$guest_email."',
		'" .$guest_email."',
		'" .$encryptedPassword."',
		'" .date('Y-m-d H:i:s')."',
		'" .date('Y-m-d H:i:s')."',
		'" .date('Y-m-d H:i:s')."',
		'{}'
		) ";
			$id = doInsertSql($query);

			if (!$id) {
				trigger_error('Failed insert new user '.$query, E_USER_ERROR);
				$insertSuccessful = false;
			} else {
				$webhook_notification							  	= new stdClass();
				$webhook_notification->webhook_event				= 'user_created';
				$webhook_notification->webhook_event_description	= 'Logs when a new user is created.';
				$webhook_notification->webhook_event_plugin		 	= 'core';
				$webhook_notification->data						 	= new stdClass();
				$webhook_notification->data->cms_user_id		   	= $id;
				$webhook_notification->data->name          		   	= $name;
				$webhook_notification->data->password   		   	= $clear_password;
				$webhook_notification->data->username		      	= $guest_email;
				$webhook_notification->data->email      		   	= $guest_email;

				add_webhook_notification($webhook_notification);

				$query = "INSERT INTO #__user_usergroup_map  (
			`user_id`,
			`group_id`
			) VALUES (
			'" .$id."',
			2
			) ";
				doInsertSql($query);

				//$thisJRUser->userIsRegistered=true; // Disabled as this setting would be incorrect during the booking phase. We want newly created users to have their details recorded by the insertGuestDeets function in insertbookings
				$thisJRUser->id = $id;
				$tmpBookingHandler->updateGuestField('mos_userid', $id);

				$subject = jr_gettext('_JRPORTAL_NEWUSER_SUBJECT', '_JRPORTAL_NEWUSER_SUBJECT', false);

				$text = jr_gettext('_JRPORTAL_NEWUSER_DEAR', '_JRPORTAL_NEWUSER_DEAR', false).' '.stripslashes($guestDeets[ 'firstname' ]).' '.stripslashes($guestDeets[ 'surname' ]).'<br />';
				$text .= jr_gettext('_JRPORTAL_NEWUSER_THANKYOU', '_JRPORTAL_NEWUSER_THANKYOU', false).'<br />';
				$text .= jr_gettext('_JRPORTAL_NEWUSER_USERNAME', '_JRPORTAL_NEWUSER_USERNAME', false).' '.$guest_email.'<br />';
				$text .= jr_gettext('_JRPORTAL_NEWUSER_PASSWORD', '_JRPORTAL_NEWUSER_PASSWORD', false).' '.$clear_password.'<br />';
				$text .= jr_gettext('_JRPORTAL_NEWUSER_LOG_IN', '_JRPORTAL_NEWUSER_LOG_IN', false).' '.get_showtime('live_site').'<br />';

				$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
				$jrConfig = $siteConfig->get();
				if ($jrConfig[ 'useNewusers_sendemail' ] == '1') {
					if (!castorMailer(get_showtime('mailfrom'), get_showtime('fromname'), $guest_email, $subject, $text, 1)) {
						error_logging('Failure in sending registration email to guest. Target address: '.get_showtime('mailfrom').' Subject'.$subject);
					}
				}

				if (!$thisJRUser->userIsManager) {
					//JLoader::import('joomla.user.authentication');
					//$credentials = array('username' => $guest_email, 'password' => $password);
					//$app = JFactory::getApplication();
					//$options = array('remember' => true);
					//$authenticate = JAuthentication::getInstance();
					// $result = $authenticate->authenticate($credentials, $options);

					$result_login = JFactory::getApplication()->login(
						[
							'username' => $guest_email,
							'password' => $clear_password
						],
						[
							'remember' => true,
							'silent'   => true
						]
					);
					$container = \Joomla\CMS\Factory::getContainer();
					$userFactory = $container->get(UserFactoryInterface::class);
					$user = $userFactory->loadUserById($id);
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
			$CONFIG = new JConfig();
			$editor_name = $CONFIG->editor;
			$user = Factory::getUser();
			$editor = Editor::getInstance($user->getParam('editor', $editor_name ));
			$ret = $editor->display($name, $content, '', '', '', '', []);
			return $ret;
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
				$app = JFactory::getApplication();
				$user = $app->getIdentity();
				$id = $user->get('id');
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
			$app = JFactory::getApplication();
			$user = $app->getIdentity();
			$username = $user->get('username');
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

			if ($filename == 'jquery.js') {
				return;
			}

			if (defined('API_STARTED')) {
				return;
			}

			if (!defined('API_STARTED') && castor_cmsspecific_areweinadminarea()) {
				HTMLHelper::_('jquery.framework');
				JHtml::_('bootstrap.framework');
			}

			if (castor_cmsspecific_areweinadminarea()) {
				$in_admin_area = true;

				$app = JFactory::getApplication();
				$doc = $app->getDocument();
			} else {
				$in_admin_area = false;

				// \Joomla\CMS\Factory::getApplication()->getDocument();
				$app = Factory::getApplication();
				$app->loadDocument();

				$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
				$wr = $wa->getRegistry();

				// Throws a depreciated warning, but the following code doesn't work either so for now we'll continue to use this
				HTMLHelper::_('bootstrap.framework');


				// https://gist.github.com/dgrammatiko/efb3de4aa7cab4813a244f93f73cc0fd
				// Link says that the following should work, but it throws a depreciated for J5 message for me. I'll leave it here for now, but will probably delete it at some point. Above also throws depreciated, see note.
/*				\Joomla\CMS\HTML\HTMLHelper::_('bootstrap.alert', '.alert');
				\Joomla\CMS\HTML\HTMLHelper::_('bootstrap.button', '.selector');
				\Joomla\CMS\HTML\HTMLHelper::_('bootstrap.carousel', '.selector', []);
				\Joomla\CMS\HTML\HTMLHelper::_('bootstrap.collapse', '.selector', []);
				\Joomla\CMS\HTML\HTMLHelper::_('bootstrap.dropdown', '.selector', []);
				\Joomla\CMS\HTML\HTMLHelper::_('bootstrap.modal', '.selector', []);
				\Joomla\CMS\HTML\HTMLHelper::_('bootstrap.offcanvas', '.btn', []);
				\Joomla\CMS\HTML\HTMLHelper::_('bootstrap.popover', '.btn', []);
				\Joomla\CMS\HTML\HTMLHelper::_('bootstrap.popover', 'a', []);
				\Joomla\CMS\HTML\HTMLHelper::_('bootstrap.scrollspy', '.selector', []);
				\Joomla\CMS\HTML\HTMLHelper::_('bootstrap.tab', '.selector', []);
				\Joomla\CMS\HTML\HTMLHelper::_('bootstrap.tooltip', '.btn', []);
				\Joomla\CMS\HTML\HTMLHelper::_('bootstrap.tooltip', 'a', []);
				\Joomla\CMS\HTML\HTMLHelper::_('bootstrap.toast', '.toast', []);

				or https://gist.github.com/dgrammatiko/efb3de4aa7cab4813a244f93f73cc0fd?permalink_comment_id=4101117#gistcomment-4101117

				Factory::getApplication()->getDocument()->getWebAssetManager()->useScript('bootstrap.alert');
				Factory::getApplication()->getDocument()->getWebAssetManager()->useScript('bootstrap.button');
				Factory::getApplication()->getDocument()->getWebAssetManager()->useScript('bootstrap.carousel');
				Factory::getApplication()->getDocument()->getWebAssetManager()->useScript('bootstrap.collapse');
				Factory::getApplication()->getDocument()->getWebAssetManager()->useScript('bootstrap.dropdown');
				Factory::getApplication()->getDocument()->getWebAssetManager()->useScript('bootstrap.modal');
				Factory::getApplication()->getDocument()->getWebAssetManager()->useScript('bootstrap.offcanvas');
				Factory::getApplication()->getDocument()->getWebAssetManager()->useScript('bootstrap.popover');
				Factory::getApplication()->getDocument()->getWebAssetManager()->useScript('bootstrap.scrollspy');
				Factory::getApplication()->getDocument()->getWebAssetManager()->useScript('bootstrap.tabs');
				Factory::getApplication()->getDocument()->getWebAssetManager()->useScript('bootstrap.tooltip');
				Factory::getApplication()->getDocument()->getWebAssetManager()->useScript('bootstrap.toast');

				*/
			}



			$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
			$jrConfig = $siteConfig->get();

			$includeVersion ? $version = '?v='.$jrConfig['update_time'] : $version = '';

			if (strpos($path, 'http') === false) {
				$data = JURI::base(true).'/'.$path.$filename.$version;
				if ($in_admin_area) {
					$data = str_replace('/administrator/', '/', $data);
				}
			} else {
				$data = $path.$filename.$version;
			}

			switch ($type) {
				case 'javascript':
					//JHTML::script( $path . $filename, false ); // If we want to include version numbers in script filenames, we can't use this. Instead we need to directly access JFactory as below

					if ($in_admin_area) {
						if ($async) {
							$doc->addScript($data, "text/javascript", false, true);
						} else {
							$doc->addScript($data);
						}
					} else {
						$dependency = 'jquery';

/*						if ($filename == 'jquery-ui.min.js') {
							$dependency = 'jquery';
						}

						if ($filename == 'castor.js' || $filename == 'no-conflict.js') {
							$dependency = 'jquery';
						}
						if (strstr($filename, 'datepicker-')) {
							$dependency = 'bootstrap.es5';
						}*/

						if (strstr($filename, 'galleria.classic.min.js')) {
							$dependency = 'galleria.min.js';
						}
						$wa->registerAndUseScript($filename, $data, [], [], [$dependency]);
					}

					break;
				case 'css':
					//JHTML::stylesheet( $path . $filename, array (), false, false ); // If we want to include version numbers in script filenames, we can't use this. Instead we need to directly access JFactory as below

					$dependency = 'template.active';

					if ($in_admin_area) {
						$doc->addStyleSheet($data);
					} else {
						$wa->registerAndUseStyle($filename, $data, [], [], [$dependency]);
					}

					break;
				default:
					break;
			}
		}
	}


/**
 *
 *
 *
 */
	if (!function_exists('castor_cmsspecific_setmetadata')) {
		function castor_cmsspecific_setmetadata($meta, $data)
		{
			$data = castor_decode($data);
			$app = JFactory::getApplication();
			$document = $app->getDocument();

			switch ($meta) {
				case 'title':
					$data = str_replace('&#39;', "'", $data);
					$document->setTitle($data);
					break;
				case 'description':
					$document->setDescription($data);
					break;
				case 'keywords':
					$document->setMetaData('keywords', $data);
					break;
				default:
					$document->setMetaData($meta, $data);
					break;
			}
		}
	}
// set our meta data


/**
 *
 *
 *
 */
	if (!function_exists('castor_cmsspecific_getCMS_users_frontend_userdetails_by_id')) {
		function castor_cmsspecific_getCMS_users_frontend_userdetails_by_id($id)
		{
			$user = array();
			$query = 'SELECT id,name,username,email FROM #__users WHERE id='.(int) $id;
			$userList = doSelectSql($query);
			if (!empty($userList)) {
				foreach ($userList as $u) {
					$user[ $id ] = array('id' => $u->id, 'name' => $u->name, 'username' => $u->username, 'email' => $u->email);
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
			$query = "SELECT id,username FROM #__users WHERE username='".(string) $username."'";
			$userList = doSelectSql($query);
			if (!empty($userList)) {
				foreach ($userList as $u) {
					$user[ $u->id ] = array('id' => $u->id, 'username' => $u->username, 'email' => $u->username);
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
			$query = 'SELECT id,username,email FROM #__users WHERE id='.(int) $id;
			$userList = doSelectSql($query);
			if (!empty($userList)) {
				foreach ($userList as $u) {
					$user[ $id ] = array('id' => $u->id, 'username' => $u->username, 'email' => $u->email);
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
			$db = Factory::getContainer()->get('DatabaseDriver');
			$query = $db->getQuery(true);
			$query
				->select($db->quoteName(array('a.id', 'a.username', 'a.email')))
				->from($db->quoteName('#__users', 'a'))
				->join('LEFT', $db->quoteName('#__user_usergroup_map', 'b').' ON ('.$db->quoteName('a.id').' = '.$db->quoteName('b.user_id').')')
				->where($db->quoteName('b.group_id').'=8');
			// Number 8 represent ID of a "group_id" from "_user_usergroup_map" table
			$db->setQuery($query);
			// load results from query
			$ids = $db->loadObjectList();

			$users = array();
			if (!empty($ids)) {
				foreach ($ids as $u) {
					$users[ $u->id ] = array('id' => $u->id, 'username' => $u->username, 'email' => $u->email);
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
					$query = "SELECT params FROM #__modules WHERE module = '$moduleName'";
					$p = doSelectSql($query, 1);

					$vals = array();
					$arr = explode(',', $p);
					if (!empty($arr)) {
						foreach ($arr as $str) {
							$dat = explode(':', $str);

							$key = $dat[ 0 ];
							$val = $dat[ 1 ];
							if (strlen($key) > 0) {
								$k = str_replace('"', '', $key);
								$k = str_replace('{', '', $k);
								$k = str_replace('}', '', $k);
								$v = str_replace('"', '', $val);
								$v = str_replace('{', '', $v);
								$v = str_replace('}', '', $v);

								$vals[ $k ] = $v;
							}
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

			$query = 'SELECT `id`,`name`,`username`,`email` FROM #__users '.$clause;
			$userList = doSelectSql($query);

			if (!empty($userList)) {
				foreach ($userList as $u) {
					$users[ $u->id ] = array('id' => $u->id, 'username' => $u->username, 'email' => $u->email);
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
			if (!castor_cmsspecific_areweinadminarea() &&  strstr($link, '&Itemid=0')) {
				throw new \Exception("Error. Castor has detected that Itemid in the url is set to 0 (zero). The most likely cause is that you have not added a link to Castor in your main menu. Joomla will throw an uncaught error without it. Add Castor to your main menu before attempting to use Castor again.", 500);
			}
			jimport('joomla.application.helper');
			if (class_exists('JRoute')) {
				$link = JRoute::_($link, $xhtml = true);
			}
			$link = castor_decode($link);

			return stripslashes($link);
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
			$limitstart = 0;
			$params = '';
			$app = JFactory::getApplication();
			JPluginHelper::importPlugin('content');
			$obj = new stdClass();
			$obj->text = $str;
			$output = $app->triggerEvent('onContentPrepare', array('castor.default', &$obj, &$params, $limitstart));
			$output = $obj->text;

			return $output;
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
			if (!defined('AUTO_UPGRADE')) {
				$config = JFactory::getConfig();
				if ($config->get('unicodeslugs') == '1') {
					$str = JFilterOutput::stringURLUnicodeSlug($str);
				} else {
					$str = JFilterOutput::stringURLSafe($str);
				}

				return $str;
			} else {
				return null;
			}
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
			$document = JFactory::getDocument();
			if ($document->getType() === 'html') {
				$document->addCustomTag($data);
			}
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
			return JURI::current();
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
			$app = JFactory::getApplication();
			$templateName = $app->getTemplate('template')->template;
			$tmplcomponent = get_showtime('tmplcomponent');
			$tmplcomponent_source = get_showtime('tmplcomponent_source');

			if (castor_cmsspecific_areweinadminarea()) {
				if ($force || !file_exists(CASTORCONFIG_ABSOLUTE_PATH.JRDS.'administrator'.JRDS.'templates'.JRDS.$templateName.JRDS.$tmplcomponent.'.php')) {
					if (!copy($tmplcomponent_source, CASTORCONFIG_ABSOLUTE_PATH.JRDS.'administrator'.JRDS.'templates'.JRDS.$templateName.JRDS.$tmplcomponent.'.php')) {
						echo '<p class="alert alert-error">Error, unable to copy '.$tmplcomponent_source.' to '.CASTORCONFIG_ABSOLUTE_PATH.JRDS.'administrator'.JRDS.'templates'.JRDS.$templateName.JRDS.$tmplcomponent.'.php automatically, please do this manually through FTP</p><br/>';
					}

					return true;
				} elseif (file_exists(CASTORCONFIG_ABSOLUTE_PATH.JRDS.'administrator'.JRDS.'templates'.JRDS.$templateName.JRDS.$tmplcomponent.'.php')) {
					if (filemtime($tmplcomponent_source) > filemtime(CASTORCONFIG_ABSOLUTE_PATH.JRDS.'administrator'.JRDS.'templates'.JRDS.$templateName.JRDS.$tmplcomponent.'.php')) {
						if (!copy($tmplcomponent_source, CASTORCONFIG_ABSOLUTE_PATH.JRDS.'administrator'.JRDS.'templates'.JRDS.$templateName.JRDS.$tmplcomponent.'.php')) {
							echo '<p class="alert alert-error">Error, unable to copy '.$tmplcomponent_source.' to '.CASTORCONFIG_ABSOLUTE_PATH.JRDS.'administrator'.JRDS.'templates'.JRDS.$templateName.JRDS.$tmplcomponent.'.php automatically, please do this manually through FTP</p><br/>';
						}

						return true;
					}
				}
			} else {
				if ($force || !file_exists(CASTORCONFIG_ABSOLUTE_PATH.JRDS.'templates'.JRDS.$templateName.JRDS.$tmplcomponent.'.php')) {
					if (!copy($tmplcomponent_source, CASTORCONFIG_ABSOLUTE_PATH.JRDS.'templates'.JRDS.$templateName.JRDS.$tmplcomponent.'.php')) {
						echo '<p class="alert alert-error">Error, unable to copy '.$tmplcomponent_source.' to '.CASTORCONFIG_ABSOLUTE_PATH.JRDS.'templates'.JRDS.$templateName.JRDS.$tmplcomponent.'.php automatically, please do this manually through FTP</p><br/>';
					}

					return true;
				} elseif (file_exists(CASTORCONFIG_ABSOLUTE_PATH.JRDS.'templates'.JRDS.$templateName.JRDS.$tmplcomponent.'.php')) {
					if (filemtime($tmplcomponent_source) > filemtime(CASTORCONFIG_ABSOLUTE_PATH.JRDS.'templates'.JRDS.$templateName.JRDS.$tmplcomponent.'.php')) {
						if (!copy($tmplcomponent_source, CASTORCONFIG_ABSOLUTE_PATH.JRDS.'templates'.JRDS.$templateName.JRDS.$tmplcomponent.'.php')) {
							echo '<p class="alert alert-error">Error, unable to copy '.$tmplcomponent_source.' to '.CASTORCONFIG_ABSOLUTE_PATH.JRDS.'templates'.JRDS.$templateName.JRDS.$tmplcomponent.'.php automatically, please do this manually through FTP</p><br/>';
						}

						return true;
					}
				}
			}

			return false;
		}
	}


/**
 *
 *Get the cms language
 *
 */
	if (!function_exists('castor_cmsspecific_getcmslang')) {
		function castor_cmsspecific_getcmslang()
		{
			$currentLanguage = JFactory::getLanguage()->getTag();
			if (isset($currentLanguage) && trim($currentLanguage) != '') {
				return $currentLanguage;
			}
			$app = JFactory::getApplication();
			if (method_exists($app , 'getTag')) {
				return $app->getLanguage()->getTag();
			} else {
				return 'en-GB';
			}
		}
	}
//


/**
 *
 *
 *
 */
	if (!function_exists('castor_cmsspecific_find_cms_users')) {
		function castor_cmsspecific_find_cms_users($search_term = '')
		{
			$clause = '';
			$users = array();

			if ($search_term != '') {
				$clause = "WHERE LOWER(`username`) LIKE '%".mb_strtolower($search_term)."%'";
			}

			$query = 'SELECT `id`, `name`, `username`, `email` FROM #__users '.$clause;
			$userList = doSelectSql($query);

			if (!empty($userList)) {
				foreach ($userList as $u) {
					$users[ $u->id ] = array('id' => $u->id, 'username' => $u->username, 'email' => $u->email);
				}
			}

			return $users;
		}
	}
// Returns an indexed array of the CMS's users where username matches a searched string


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

			$query = 'SELECT `username` FROM #__users WHERE `id` = '.(int)$user_id.' LIMIT 1';
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

			$url = JURI::base().'index.php?option=com_users&task=user.edit&id='.$cms_user_id;
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
			$language = JFactory::getLanguage();
			$isRtl = $language->isRtl();

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
			$user = JFactory::getUser();

			if ($user->authorise('core.admin')) {
				return true;
			}

			return false;
		}
	}


