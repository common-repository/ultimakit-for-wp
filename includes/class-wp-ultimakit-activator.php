<?php

/**
 * Fired during plugin activation
 *
 * @link       https://wpankit.com
 * @since      1.0.0
 *
 * @package    UltimaKit
 * @subpackage UltimaKit/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    UltimaKit
 * @subpackage UltimaKit/includes
 * @author     Ankit Panchal <ankitpanchalweb7@gmail.com>
 */
class UltimaKit_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		// Check if the options array already exists
		if ( false === get_option( 'ultimakit_options', false ) ) {

			$admin                  = new UltimaKit_Module_Manager();
			$all_modules            = $admin->getAllModules();
			$default_module_options = array();
			foreach ( $all_modules as $module ) {
				if ( false === $admin->isModuleActive( $module['id'] ) ) {
					$default_module_options[ $module['id'] ] = array( 'enabled' => false );
				}
			}

			// Options array doesn't exist, so insert the default settings
			update_option( 'ultimakit_options', $default_module_options, 'no' );
			update_option( 'ultimakit_uninstall_settings', 'off', 'no' );
		}
	}
}
