<?PHP
// ################################################################
	defined( '_CASTOR_INITCHECK' ) or die( '' );
// ################################################################

	#[AllowDynamicProperties]

	/**
	 * Core file
	 *
	 * @author Vince Wooll <sales@castor.net>
	 *  @version Castor 10.7.2
	 * @package Castor
	 * @copyright	2005-2023 Vince Wooll
	 * Castor (tm) PHP, CSS & Javascript files are released under both MIT and GPL2 licenses. This means that you can choose the license that best suits your project, and use it accordingly.
	 **/

	/**
	 * patTemplate Reader that reads from a database using Castor
	 */

	class patTemplate_Reader_Castor extends patTemplate_Reader
	{
		var $_name = 'Castor';

		function readTemplates( $templatename = '', $options = array() )
		{

			$template_packages = get_showtime('template_packages');


			if (!empty($template_packages)) { // There are some override packages installed, we can go ahead and check for overrides, which requires an extra query.
				// An alternative method of providing template overrides through plugins
				$overrides_class = castor_singleton_abstract::getInstance('template_overrides');
				$ptype_id = (int)get_showtime('ptype_id');
			}

			// Allows shortcodes to set their own alt template name
			if (isset($_REQUEST[$templatename]) && $_REQUEST[$templatename] != '') {
				$temp_template = castorGetParam($_REQUEST, $templatename, '');
				$temp_template = str_replace( '&#34;' , '' , $temp_template );
				$alt_template_path = get_override_directory();
				if (file_exists($alt_template_path . JRDS . $temp_template)) {
					$content = file_get_contents($alt_template_path . JRDS . $temp_template);
				}
			}

			if (!isset($content) || is_null($content)) {
				if (isset($overrides_class->template_overrides[$templatename])) { // Template overrides are available
					if (
						(int)$ptype_id >0 && // Property type id is set and greater than 0
						file_exists(CASTORPATH_BASE.$overrides_class->template_overrides[$templatename]['path'] .$ptype_id.JRDS. $templatename) // And a template of the required name exists in the property type template directory with a sub directory of the property type id
					) {
						$content = file_get_contents( CASTORPATH_BASE.$overrides_class->template_overrides[$templatename]['path'] .$ptype_id.JRDS. $templatename );
					} else {
						$content = file_get_contents( CASTORPATH_BASE.$overrides_class->template_overrides[$templatename]['path'] . $templatename );
					}
				} else {
					$override_template = false;
					if ( !isset( $_REQUEST[ 'nocustomtemplate' ] ) && !defined('API_STARTED') )
						$override_template = $this->get_cms_template_override( $templatename);

					if ( !$override_template )
					{
						$custom_paths = get_showtime( 'custom_paths' );

						if ( is_array($custom_paths) && array_key_exists( $templatename, $custom_paths ) )
						{
							$default_root = $custom_paths[ $templatename ];
						}
						else
						{
							$default_root = $this->_options[ 'root' ][ '__default' ];
						}

						if ( !file_exists($default_root . JRDS . $templatename)) {
							$siteConfig		= castor_singleton_abstract::getInstance( 'castor_config_site_singleton' );
							$jrConfig		  = $siteConfig->get();
							$post_error_message = '';
							if ($jrConfig['development_production'] != 'development') {
								$post_error_message = ' Please check the administrator > castor > tools > log files area for more information. ';
							}

							throw new Exception("Error: the file ".$default_root . JRDS . $templatename. " does not exist. ".$post_error_message );
						}
						$content = file_get_contents( $default_root . JRDS . $templatename );
					}
					else
					{
						$content = $override_template;
					}
				}
			}

			$templates = $this->parseString( $content );

			return $templates;
		}

		function get_cms_template_override($castor_template_name)
		{
			$override_path = false;

			$ptype_id = (int)get_showtime('ptype_id');

			if (this_cms_is_joomla())
			{
				$app = JFactory::getApplication();
				$joomla_templateName = $app->getTemplate('template')->template;

				if (castor_cmsspecific_areweinadminarea())
				{
					$path_to_template = CASTORCONFIG_ABSOLUTE_PATH . CASTOR_ADMINISTRATORDIRECTORY . JRDS . "templates" .JRDS. $joomla_templateName ;

				}
				else
				{
					$path_to_template = CASTORCONFIG_ABSOLUTE_PATH . "templates" .JRDS. $joomla_templateName ;
				}

				$override_path = $path_to_template .JRDS . 'html' . JRDS . 'com_castor';

				//ptype specific override_path
				if ( $ptype_id > 0 )
				{
					if ( file_exists($override_path . JRDS . $ptype_id . JRDS . $castor_template_name) )
						$override_path = $override_path . JRDS . $ptype_id;
				}

				//jomsearch modules overrides
				if (strpos($castor_template_name,'mod_jomsearch_m') !== false)
				{
					$arr = explode(".", $castor_template_name);
					$name = $arr[0];

					if ( file_exists(CASTORCONFIG_ABSOLUTE_PATH . "templates" .JRDS. $joomla_templateName .JRDS . 'html' . JRDS . $name . JRDS . $castor_template_name) )
						$override_path = CASTORCONFIG_ABSOLUTE_PATH . "templates" .JRDS. $joomla_templateName .JRDS . 'html' . JRDS . $name;
				}
			}
			elseif (this_cms_is_wordpress()) {
				$path_to_template =  get_theme_file_path();
				$override_path = $path_to_template . JRDS . 'html' . JRDS . 'com_castor';

				//ptype specific override_path
				if ( $ptype_id > 0 ) {
					if ( file_exists($override_path . JRDS . $ptype_id . JRDS . $castor_template_name) )
						$override_path = $override_path . JRDS . $ptype_id;
				}
			}

			if ( get_showtime('task') != '' && file_exists( $path_to_template . JRDS . 'html' . JRDS . get_showtime('task'). JRDS . $castor_template_name ) )  {
				$override_path = $path_to_template. JRDS . 'html' . JRDS . get_showtime('task');
			}

			if ($override_path != '' && is_dir($override_path))
			{

				if (is_file( $override_path . JRDS . $castor_template_name ) )
				{
					set_showtime('override_path_'.$castor_template_name, $override_path);
					return file_get_contents( $override_path . JRDS . $castor_template_name );
				}
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}

			return false;
		}

	}

