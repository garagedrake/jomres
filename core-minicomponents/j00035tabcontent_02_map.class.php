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
	 * Used by j06000viewproperty.class.php to build tabs in the property details page. Builds map template output.
	 *
	 */

class j00035tabcontent_02_map
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
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = false;

			return;
		}

		$this->retVals = '';
		$property_uid = (int) $componentArgs[ 'property_uid' ];
		$mrConfig = getPropertySpecificSettings($property_uid);

		$map = $MiniComponents->specificEvent('06000', 'show_property_map', array('output_now' => false, 'property_uid' => $property_uid));
		$map_title = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_MAPPINGLINK', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_MAPPINGLINK', false);

		if (strlen($map) > 0) {
			$tab_id = 'mapTab';
			$anchor = castor_generate_tab_anchor($map_title); ?>
				<script type="text/javascript">
					castorJquery(document).ready(function () {
						castorJquery('#pdetails_tabs').bind('tabsshow', function (event, ui) {
							if (ui.panel.id == "<?php echo $anchor; ?>") {
								<?php echo 'init_map_'.get_showtime('current_map_identifier'); ?>();
							}
						});
					});
				</script>
			<?php
			$tab = array('TAB_ANCHOR' => $anchor, 'TAB_ID' => $tab_id, 'TAB_TITLE' => $map_title, 'TAB_CONTENT' => $map);
			$this->retVals = $tab;
		}
	}


	public function getRetVals()
	{
		return $this->retVals;
	}
}

