<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @package UltimaKit
 * @link    https://wpankit.com
 * @since   1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:       UltimaKit for WP
 * Plugin URI:        https://wpultimakit.com
 * Description:       <strong>UltimaKit for WP:</strong> The WordPress Toolkit Built With You in Mind. Essential features, ongoing development – shape the future of your WordPress experience.
 * Version:           1.0.1
 * Author:            UltimaKit For WP
 * Author URI:        https://wpultimakit.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ultimakit-for-wp
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}
/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'ULTIMAKIT_FOR_WP_VERSION', '1.0.1' );
define( 'ULTIMAKIT_FOR_WP_LOGO', plugins_url( 'admin/img/wp-ultimakit-logo.svg', __FILE__ ) );
define( 'ULTIMAKIT_FOR_WP_PATH', plugin_dir_path( __FILE__ ) );
define( 'ULTIMAKIT_FOR_WP_DASHBOARD', 'wp-ultimakit-dashboard' );
define( 'ULTIMAKIT_WEB_URL', 'https://wpultimakit.com/' );
if ( isset( $_GET['page'] ) && !empty( sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) ) {
    define( 'ULTIMAKIT_FOR_WP_CURRENT_PAGE', sanitize_text_field( wp_unslash( $_GET['page'] ) ) );
} else {
    define( 'ULTIMAKIT_FOR_WP_CURRENT_PAGE', '/' );
}
define( 'ULTIMAKIT_FOR_WP_ALLOWED_PAGES', apply_filters( 'ultimakit_pages_for_assets', array('wp-ultimakit-dashboard', 'wp-ultimakit-settings', 'wp-ultimakit-dashboard-account') ) );
if ( function_exists( 'ufw_fs' ) ) {
    ufw_fs()->set_basename( false, __FILE__ );
} else {
    if ( !function_exists( 'ufw_fs' ) ) {
        // Create a helper function for easy SDK access.
        function ufw_fs() {
            global $ufw_fs;
            if ( !isset( $ufw_fs ) ) {
                // Activate multisite network integration.
                if ( !defined( 'WP_FS__PRODUCT_15524_MULTISITE' ) ) {
                    define( 'WP_FS__PRODUCT_15524_MULTISITE', true );
                }
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/src/freemius/start.php';
                $ufw_fs = fs_dynamic_init( array(
                    'id'             => '15524',
                    'slug'           => 'ultimakit-for-wp',
                    'premium_slug'   => 'ultimakit-for-wp-pro',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_7242c13a2ba33bb432d938683d41a',
                    'is_premium'     => false,
                    'premium_suffix' => 'Pro',
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'menu'           => array(
                        'slug'       => 'wp-ultimakit-dashboard',
                        'first-path' => 'admin.php?page=wp-ultimakit-dashboard',
                        'support'    => false,
                    ),
                    'is_live'        => true,
                ) );
            }
            return $ufw_fs;
        }

        // Init Freemius.
        ufw_fs();
        // Signal that SDK was initiated.
        do_action( 'ufw_fs_loaded' );
        // Not like register_uninstall_hook(), you do NOT have to use a static function.
        ufw_fs()->add_action( 'after_uninstall', 'ufw_fs_uninstall_cleanup' );
    }
    /**
     * The code that runs during plugin activation.
     * This action is documented in includes/class-wp-ultimakit-activator.php
     */
    function ultimakit_activate_wp_ultimakit() {
        include_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-ultimakit-activator.php';
        UltimaKit_Activator::activate();
    }

    /**
     * The code that runs during plugin deactivation.
     * This action is documented in includes/class-wp-ultimakit-deactivator.php
     */
    function ultimakit_deactivate_wp_ultimakit() {
        include_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-ultimakit-deactivator.php';
        UltimaKit_Deactivator::deactivate();
    }

    register_activation_hook( __FILE__, 'ultimakit_activate_wp_ultimakit' );
    register_deactivation_hook( __FILE__, 'ultimakit_deactivate_wp_ultimakit' );
    /**
     * The core plugin class that is used to define internationalization,
     * admin-specific hooks, and public-facing site hooks.
     */
    require plugin_dir_path( __FILE__ ) . 'includes/class-wp-ultimakit.php';
    require plugin_dir_path( __FILE__ ) . 'includes/class-wp-ultimakit-helpers.php';
    require plugin_dir_path( __FILE__ ) . 'includes/class-wp-ultimakit-manager.php';
    function ufw_fs_uninstall_cleanup() {
        $option_name = 'ultimakit_options';
        // Delete options
        delete_option( $option_name );
        delete_option( 'ultimakit_uninstall_settings' );
        // For Multisite, iterate over all blogs and delete options
        if ( function_exists( 'is_multisite' ) && is_multisite() ) {
            global $wpdb;
            $blog_ids = $wpdb->get_col( "SELECT blog_id FROM {$wpdb->blogs}" );
            foreach ( $blog_ids as $blog_id ) {
                switch_to_blog( $blog_id );
                delete_option( $option_name );
                restore_current_blog();
            }
        }
    }

    function ufw_fs_custom_connect_message_on_update(
        $message,
        $user_first_name,
        $plugin_title,
        $user_login,
        $site_link,
        $freemius_link
    ) {
        return sprintf(
            __( 'Hey %1$s' ) . ',<br>' . __( 'Please help us improve %2$s! If you opt-in, some data about your usage of %2$s will be sent to %5$s. If you skip this, that\'s okay! %2$s will still work just fine.', 'ultimakit-for-wp' ),
            $user_first_name,
            '<b>' . $plugin_title . '</b>',
            '<b>' . $user_login . '</b>',
            $site_link,
            $freemius_link
        );
    }

    ufw_fs()->add_filter(
        'connect_message_on_update',
        'ufw_fs_custom_connect_message_on_update',
        10,
        6
    );
    // $translated_text = 'Congratulations! Your premium version is now fully activated and ready to go.';
    /**
     * Begins execution of the plugin.
     *
     * Since everything within the plugin is registered via hooks,
     * then kicking off the plugin from this point in the file does
     * not affect the page life cycle.
     *
     * @since 1.0.0
     */
    function ultimakit_run_wp_ultimakit() {
        $plugin = new UltimaKit();
        $plugin->run();
    }

    ultimakit_run_wp_ultimakit();
    /**
     * This will initiat all modules from this plugin.
     */
    function ultimakit_init_modules() {
        new UltimaKit_Module_Manager();
    }

    add_action( 'wp_loaded', 'ultimakit_init_modules' );
}