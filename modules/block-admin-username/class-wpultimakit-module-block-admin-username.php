<?php

/**
 * Class UltimaKit_Module_Block_Admin_Username
 *
 * 
 * Provides methods to control the display of dashboard widgets based on user preferences.
 * Allows users to selectively hide certain dashboard widgets to streamline their WordPress dashboard experience and improve usability.
 *
 * @since 1.0.0
 */
class UltimaKit_Module_Block_Admin_Username extends UltimaKit_Module_Manager {
    /**
     * Unique identifier for the module.
     *
     * @var string
     */
    protected $ID = 'ultimakit_block_admin_username';

    /**
     * The name of the module.
     *
     * @var string
     */
    protected $name;

    /**
     * A brief description of what the module does.
     *
     * @var string
     */
    protected $description;

    /**
     * The pricing plan associated with the module.
     *
     * @var string
     */
    protected $plan = 'free';

    /**
     * The category of functionality the module falls under.
     *
     * @var string
     */
    protected $category = 'Security';

    /**
     * The type of module, indicating its platform or use case.
     *
     * @var string
     */
    protected $type = 'WordPress';

    /**
     * Flag indicating whether the module is active.
     *
     * @var bool
     */
    protected $is_active;

    /**
     * URL providing more detailed information about the module.
     *
     * @var string
     */
    protected $read_more_link = 'block-admin-username-in-wordpress';

    /**
     * The settings associated with the module, if any.
     *
     * @var array
     */
    protected $settings;

    /**
     * Constructs the Hide Admin Bar module instance.
     *
     * Initializes the module with default values for properties and prepares
     * any necessary setup or hooks into WordPress.
     */
    public function __construct() {
        $this->name = __('Block "Admin" Username', 'ultimakit-for-wp');
        $this->description = __('Verify if the primary user account is still named "admin." This makes it easier for malicious users to target the account with the highest privileges.', 'ultimakit-for-wp');
        $this->is_active = $this->isModuleActive($this->ID);
        $this->initializeModule();
    }

    /**
     * Initializes the specific module within the application.
     */
    protected function initializeModule() {
        if ($this->is_active) {
            add_filter('registration_errors', array($this, 'ultimakit_block_the_username_admin'), 10, 3);
            add_action('admin_init', array($this, 'ultimakit_check_the_current_username'));
            add_action('wp_ajax_ultimakit_change_admin_name', array($this, 'ultimakit_change_admin_name'));
            
            if ( true === $this->ultimakit_asset_condition() ) {
                add_action('admin_enqueue_scripts', array($this, 'ultimakit_tidy_nav_admin_scripts'), 110);
            }
        }
    }

    public function ultimakit_block_the_username_admin($errors, $sanitized_user_login, $user_email) {
        if ($sanitized_user_login == 'admin') {
            $errors->add('username_unavailable', __('Sorry, that username is not allowed.', 'ultimakit-for-wp'));
        }
        return $errors;
    }

    public function ultimakit_check_the_current_username() {
        $admin_user = get_user_by('login', 'admin');
        if ($admin_user) {
            add_action('admin_notices', array($this, 'ultimakit_admin_notice'));
        }
    }

    public function ultimakit_admin_notice() {
        $class = 'notice notice-error';
        $message = esc_html("detected username as admin. We recommend changing username for security purposes.", 'ultimakit-for-wp');
        printf('<div class="%1$s ultimakit_change_admin"><p><strong>' . esc_html('Ultimakit For WP', 'ultimakit-for-wp') . '</strong> %2$s</p><input type="text" name="change_username" id="change_username" class="form-control" placeholder="' . esc_html('Enter new username', 'ultimakit-for-wp') . '"><input type="submit" name="change_user" id="change_user" value="' . esc_html('Change', 'ultimakit-for-wp') . '" class="button button-primary"><p class="user_validation"></p></div>', esc_attr($class), esc_html($message));
    }

    public static function ultimakit_tidy_nav_admin_scripts() {
        wp_enqueue_script(
            'ultimakit-admin-admin-name',
            plugins_url("module-script.js", __FILE__),
            array(), // Dependencies
            filemtime(plugin_dir_path(__FILE__) . "module-script.js"), // Version
            true // In footer
        );

        $rename_admin = array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'ajax_nonce' => wp_create_nonce('ultimakit-nonce'),
        );

        wp_localize_script('ultimakit-admin-admin-name', 'change_ajax_obj', $rename_admin);
    }

    public function ultimakit_change_admin_name() {
        global $wpdb;
        if (!wp_verify_nonce(sanitize_text_field($_REQUEST['nonce']), 'ultimakit-nonce')) {
            $result = array(
                'status' => false,
                'error' => __('Invalid nonce!', 'ultimakit-for-wp')
            );
            wp_send_json($result);
            die();
        }
        $new_username = sanitize_text_field($_POST['username']);
        $admin_user = get_user_by('login', 'admin');
        if (isset($new_username) && $new_username !== '') {

            $user_not_exist = __('No user has the username "admin". Nothing to update.', 'ultimakit-for-wp');
            $user_exist = __('The new username "' . $new_username . '" already exists. Please choose a different one.', 'ultimakit-for-wp');
            $user_name_changed = __('Username changed successfully. Please logout and login with new username.', 'ultimakit-for-wp');
            $failed_change = __('Username change failed.', 'ultimakit-for-wp');

            if (!$admin_user) {
                // Assuming $user_not_exist is retrieved from user input or similar
                $user_not_exist_san = sanitize_text_field($user_not_exist);
                wp_send_json(array('usertext' => 'admin', 'message' => $user_not_exist_san));
                return;
            }
            if (username_exists($new_username)) {
                // Again, assuming $user_exist is derived from user input or needs to be sanitized
                $user_exist_san = sanitize_text_field($user_exist);
                wp_send_json(array('usertext' => 'admin', 'message' => $user_exist_san));
                return;
            }

            $wpdb->update($wpdb->users, array('user_login' => $new_username), array('ID' => $admin_user->ID));
            if (get_user_by('login', $new_username)) {
                // Assuming $user_name_changed is supposed to be plain text
                $user_name_changed_sanitized = sanitize_text_field($user_name_changed);

                // Send JSON response
                wp_send_json(array('usertext' => 'admin', 'message' => $user_name_changed_sanitized));

                return;
            } else {
                // Sanitize the message to ensure it's safe to output
                $sanitized_message = sanitize_text_field($failed_change);

                // Send the sanitized data as a JSON response
                wp_send_json(array('usertext' => 'admin', 'message' => $sanitized_message));

                return;
            }
        }
        die;
    }
}
