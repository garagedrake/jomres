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
class castor_error_handler extends Exception
{

	/**
	 *
	 *
	 *
	 */

	public function __construct()
	{
		$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
		$jrConfig = $siteConfig->get();
		$this->debugging = false;
		if ($jrConfig['development_production'] == 'development') {
			$this->debugging = true;
		}
	}

	public function output_error($e, $extra_info)
	{
		if ($this->debugging) {
			$this->output_dev_mode_debugging($e, $extra_info);
		} else {
			$this->output_production_mode_debugging($e, $extra_info);
		}
	}

	private function output_dev_mode_debugging($e, $extra_info)
	{
		$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
		$jrConfig = $siteConfig->get();

		$cms_files_we_are_not_interested_in = castor_cmsspecific_error_logging_cms_files_to_not_backtrace();

		if (is_int($e)) {
			$backtrace = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS , 10 );
			$rows=array();
			$found = false;
			foreach ($backtrace as $trace) {
				$r = array();
				if (isset($trace[ 'file' ]) && $found ) {
					$file = $trace[ 'file' ];
					$bang = explode(JRDS, $file);

					$r['FILES'] = sprintf("\n%s:%s %s::%s", $trace['file'], $trace['line'], $trace['class'], $trace['function']).'<br/>';
					$rows[] = $r;
				}
				if (isset($trace['function']) && $trace['function'] === 'output_fatal_error' ) {
					$found = true;
				}
			}
			unset($backtrace);

		//	var_dump($rows);
		//	exit;


			$link = getCurrentUrl();
			//$link =  "//$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$cleaned_link = castor_sanitise_string($link);

			$output = array('MESSAGE' => $extra_info);

			$output['IP_NUMBER'] = castor_get_client_ip();

			$output['DATETIME'] = date('Y-m-d H:i:s');

			if (!defined('CASTOR_TEMPLATEPATH_FRONTEND'))  {
				define('CASTOR_TEMPLATEPATH_FRONTEND', CASTORPATH_BASE.JRDS.'assets'.JRDS.'templates'.JRDS.'bootstrap5'.JRDS.'frontend');
			}

			$pageoutput[] = $output;
			$tmpl = new patTemplate();
			$tmpl->setRoot(CASTOR_TEMPLATEPATH_FRONTEND);

			$tmpl->readTemplatesFromInput('error_developer.html');
			$tmpl->addRows('rows', $rows);
			$tmpl->addRows('pageoutput', $pageoutput);
			$error_html = $tmpl->getParsedTemplate();

			if (!is_dir(CASTOR_SYSTEMLOG_PATH)) {
				mkdir(CASTOR_SYSTEMLOG_PATH);
			}

			$filename = generateCastorRandomString(30).'.html';

			file_put_contents(CASTOR_SYSTEMLOG_PATH.$filename, $error_html);

			if ($jrConfig['development_production'] == 'development') {
				echo $error_html;
			} else {
				$pageoutput = array(array('_CASTOR_ERROR' => jr_gettext('_CASTOR_ERROR', '_CASTOR_ERROR', false), '_CASTOR_ERROR_MESSAGE' =>  $e->getMessage() ));
				$tmpl = new patTemplate();
				$tmpl->setRoot(CASTOR_TEMPLATEPATH_FRONTEND);
				$tmpl->readTemplatesFromInput('error_production.html');
				$tmpl->addRows('pageoutput', $pageoutput);
				echo $tmpl->getParsedTemplate();
			}

			$url = '<a href = "'.CASTOR_SITEPAGE_URL_NOSEF.'&task=show_logfile&logfile='.$filename.'"> Logfile </a>'.
				$error_html
			;

			if ($jrConfig[ 'sendErrorEmails' ] == '1') {
				sendAdminEmail('Error logged '.$output['MESSAGE'], $url);
			}

			logging::log_message('Error logged '.$output['MESSAGE'].' '.$url, 'Core', 'ERROR');

			//debug_print_backtrace();
		} else {
			if (is_string($e)) {
				$response = $e;
			} else {
				if ($e->getMessage() != '' ) {
					$response = $e->getMessage();

					$response .= "Exception: " . $response;
					$response .= "The exception was created in file: " . $e->getFile();
					$response .= "The exception was created on line: " . $e->getLine();
				}
			}
		}
	}

	private function output_production_mode_debugging($e, $extra_info)
	{
		$link = getCurrentUrl();
		$cleaned_link = castor_sanitise_string($link);

		if (is_object($e)) {
			$output = array(
				'URL' => $cleaned_link,
				'MESSAGE' => $e->getMessage(),
				'EXTRA_INFO' => $extra_info,
				'FILE' => $e->getFile(),
				'LINE' => $e->getLine(),
				'TRACE' => nl2br($e->getTraceAsString()),
				'_CASTOR_ERROR_DEBUGGING_MESSAGE' => jr_gettext('_CASTOR_ERROR_DEBUGGING_MESSAGE', '_CASTOR_ERROR_DEBUGGING_MESSAGE', false),
				'_CASTOR_ERROR_DEBUGGING_FILE' => jr_gettext('_CASTOR_ERROR_DEBUGGING_FILE', '_CASTOR_ERROR_DEBUGGING_FILE', false),
				'_CASTOR_ERROR_DEBUGGING_LINE' => jr_gettext('_CASTOR_ERROR_DEBUGGING_LINE', '_CASTOR_ERROR_DEBUGGING_LINE', false),
				'_CASTOR_ERROR_DEBUGGING_TRACE' => jr_gettext('_CASTOR_ERROR_DEBUGGING_TRACE', '_CASTOR_ERROR_DEBUGGING_TRACE', false),
			);
		} else {
			$output = array('MESSAGE' => $extra_info);
		}

		$pageoutput = array(array('_CASTOR_ERROR' => jr_gettext('_CASTOR_ERROR', '_CASTOR_ERROR', false), '_CASTOR_ERROR_MESSAGE' => $output['MESSAGE'] ));
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_FRONTEND);
		$tmpl->readTemplatesFromInput('error_production.html');
		$tmpl->addRows('pageoutput', $pageoutput);
		echo $tmpl->getParsedTemplate();
	}


	private function callStack($stacktrace) {
		print str_repeat("=", 50) ."\n";
		$i = 1;
		foreach($stacktrace as $node) {
			print "$i. ".basename($node['file']) .":" .$node['function'] ."(" .$node['line'].")\n";
			$i++;
		}
	}

}

