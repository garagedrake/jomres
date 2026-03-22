<?php
/**
 *
 * Database querying functions
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
 * @package Castor\Core\Functions
*
* Performs SELECT queries
*
* If passed, mode 1 expects 1 row with 1 element in it. Returns a string. mode 2 The calling function expects 1 row with elements in it. Returns an associative array
*
*/
	if (!function_exists('doSelectSql')) {
		function doSelectSql($query, $mode = false)
		{
			$castor_db = castor_singleton_abstract::getInstance('castor_database');
			$castor_db->setQuery($query);
			$castor_db->loadObjectList();

			$num = count($castor_db->result);

			switch ($mode) {
				case 1:
					// Mode 1. The calling function expects 1 row with 1 element in it. Returns a string
					if ($num == 1) {
						foreach ($castor_db->result[0] as $r) {
							$result = $r;
						}

						return $result;
					} else {
						return false;
					}
					break;
				case 2:
					// Mode 2. The calling function expects 1 row with elements in it. Returns an associative array
					if ($num > 1) {
						throw new Exception('Database error more than one result returned. One expected. Stop.');
					}

					if ($num == 1) {
						if (empty($castor_db->result[0])) {
							return false;
						} else {
							foreach ($castor_db->result[0] as $k => $v) {
								$result[ $k ] = $v;
							}

							return $result;
						}
					} else {
						return false;
					}
					break;
				default:
					return $castor_db->result;
					break;
			}
		}
	}


/**
 *
 * @package Castor\Core\Functions
*
* Performs INSERT/UPDATE/DELETE queries
*
* Called doInsertSql, the title is not quite correct as this function also handles updates and deletes.
* We'll use the lack of text in $op as a way of indicating that we don't want this operation logged.
* This way we can call the audit directly from the insert internet booking function rather than logging EVERYTHING that's done by the function
*
*/
	if (!function_exists('doInsertSql')) {
		function doInsertSql($query, $op = '', $ignoreErrors = false)
		{
			$castor_db = castor_singleton_abstract::getInstance('castor_database');
			$castor_db->setQuery($query);

			if (!$castor_db->query()) {
				if (!$ignoreErrors) {
					if (is_array($castor_db->error)) {
						$castor_db->error = serialize($castor_db->error);
					}
					error_logging('Do insert failed :: '.$castor_db->error.' '.$query);
				}

				return false;
			} else {
				$thisID = $castor_db->last_id;

				if ($op != '') {
					castor_audit($query, $op);
				}

				if ($thisID) {
					return $thisID;
				} else {
					return true;
				}
			}
		}
	}


/**
 *
 * @package Castor\Core\Functions
 *
 * Closes the database connection
 *
 */
	if (!function_exists('doDBClose')) {
		function doDBClose()
		{
			$castor_db = castor_singleton_abstract::getInstance('castor_database');
			$castor_db->close();
		}
	}


