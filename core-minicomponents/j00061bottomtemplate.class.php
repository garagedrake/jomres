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
	 * Includes one of two possible template files, plus a little javascript for slider radio buttons that were originally included as part of the Joomla 3 Bootstrap 2 implementation.,
	 *
	 */

	class j00061bottomtemplate
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

				return;
			}
			if (AJAXCALL) {
				return;
			}

			if (get_showtime('menuoff') == true) {
				echo '</div>'; // echo '<div id="castor_content_area">'; // closed in j00061bottomtemplate.class.php
				return;
			}

			$castor_tooltips = castor_singleton_abstract::getInstance('castor_tooltips');

			$management_view = castorGetParam($_REQUEST, 'tmpl', false);

			$output = array();

			if (using_bootstrap()) {
				$output[ 'RADIO_BUTTON_JAVASCRIPT' ] = '
			<!-- Joomla 3 frontend doesn\'t yet have this, and Castor needs it for the property config (among others). Put in for now, will see if we need to remove it when Alpha 2 is released -->
			<script>
				(function($){
					// Turn radios into btn-group
					$(\'.radio.btn-group label\').addClass(\'btn btn-default\')
					$(".btn-group label:not(.active)").click(function(){
						var label = $(this);
						var input = $(\'#\' + label.attr(\'for\'));

						if (!input.prop(\'checked\')){
							label.closest(\'.btn-group\').find("label").removeClass(\'active btn-primary\');
							label.addClass(\'active btn-primary\');
							input.prop(\'checked\', true);
						}
					});
					$(".btn-group input[checked=checked]").each(function(){
						$("label[for=" + $(this).attr(\'id\') + "]").addClass(\'active btn-primary\');
					});
				})(jQuery);
			</script>';
			}

			if (!defined('CASTOR_TEMPLATEPATH_FRONTEND'))  {
				define('CASTOR_TEMPLATEPATH_FRONTEND', CASTORPATH_BASE.JRDS.'assets'.JRDS.'templates'.JRDS.'bootstrap5'.JRDS.'frontend');
			}

			$pageoutput[ ] = $output;

			$tmpl = new patTemplate();
			$tmpl->setRoot(CASTOR_TEMPLATEPATH_FRONTEND);
			if ($management_view) {
				$tmpl->readTemplatesFromInput('management_bottom.html');
			} else {
				$tmpl->readTemplatesFromInput('bottom.html');
			}
			$tmpl->addRows('pageoutput', $pageoutput);
			$tmpl->displayParsedTemplate();
		}


		public function getRetVals()
		{
			return null;
		}
	}

