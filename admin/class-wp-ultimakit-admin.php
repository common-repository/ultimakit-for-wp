<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    UltimaKit
 * @link       https://wpankit.com
 * @since      1.0.0
 *
 * @subpackage UltimaKit/admin
 */
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    UltimaKit
 * @subpackage UltimaKit/admin
 * @author     Ankit Panchal <ankitpanchalweb7@gmail.com>
 */
class UltimaKit_Admin extends UltimaKit_Module_Manager {
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name       The name of this plugin.
     * @param      string $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in UltimaKit_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The UltimaKit_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        if ( strpos( ULTIMAKIT_FOR_WP_CURRENT_PAGE, 'ultimakit' ) > 0 ) {
            wp_enqueue_style(
                'ultimakit_bootstrap_main',
                plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css',
                array(),
                $this->version,
                'all'
            );
            wp_enqueue_style(
                'ultimakit_bootstrap_rtl',
                plugin_dir_url( __FILE__ ) . 'css/bootstrap.rtl.min.css',
                array(),
                $this->version,
                'all'
            );
            // Enqueue toastr CSS.
            wp_enqueue_style(
                'toastr-css',
                plugin_dir_url( __FILE__ ) . 'css/toastr.min.css',
                array(),
                $this->version,
                'all'
            );
            wp_enqueue_style(
                $this->plugin_name,
                plugin_dir_url( __FILE__ ) . 'css/wp-ultimakit-admin.css',
                array(),
                $this->version,
                'all'
            );
        }
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in UltimaKit_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The UltimaKit_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        if ( strpos( ULTIMAKIT_FOR_WP_CURRENT_PAGE, 'ultimakit' ) > 0 ) {
            wp_enqueue_script( 'jquery' );
            wp_enqueue_script(
                'ultimakit_bootstrap_bundle',
                plugin_dir_url( __FILE__ ) . 'js/bootstrap.bundle.min.js',
                array('jquery'),
                $this->version,
                false
            );
            // Enqueue toastr.js.
            wp_enqueue_script(
                'toastr-js',
                plugin_dir_url( __FILE__ ) . 'js/toastr.min.js',
                array('jquery'),
                $this->version,
                true
            );
            wp_enqueue_script(
                $this->plugin_name,
                plugin_dir_url( __FILE__ ) . 'js/wp-ultimakit-admin.js',
                array('jquery'),
                $this->version,
                false
            );
            wp_localize_script( $this->plugin_name, 'ultimakit_ajax', array(
                'url'               => admin_url( 'admin-ajax.php' ),
                'rest_settings_url' => esc_url_raw( rest_url( 'ultimakit/v1/settings' ) ),
                'nonce'             => wp_create_nonce( 'ultimakit_nonce' ),
                'positionClass'     => 'toast-top-right',
                'timeOut'           => 3000,
            ) );
        }
    }

    /**
     * Adds an admin menu page for WP UltimaKit.
     *
     * This function registers a new menu page in the WordPress dashboard
     * under the specified menu title, position, and with the specified capabilities.
     *
     * @return void
     */
    public function ultimakit_admin_menu() {
        // Ensure the current user has the 'manage_options' capability.
        if ( !current_user_can( 'manage_options' ) ) {
            return;
        }
        // Parameters are: page_title, menu_title, capability, menu_slug, function, icon_url, position.
        add_menu_page(
            __( 'UltimaKit Dashboard', 'ultimakit-for-wp' ),
            // Page Title.
            __( 'UltimaKit For WP', 'ultimakit-for-wp' ),
            // Menu Title.
            'manage_options',
            // Capability (only admins can access).
            'wp-ultimakit-dashboard',
            // Menu Slug.
            array($this, 'ultimakit_render_dashboard_page'),
            // The function to render the page content.
            'dashicons-superhero',
            100
        );
        // Add submenu page
        add_submenu_page(
            'wp-ultimakit-dashboard',
            // Parent slug
            __( 'Settings', 'ultimakit-for-wp' ),
            // Page title
            __( 'Settings', 'ultimakit-for-wp' ),
            // Menu title
            'manage_options',
            // Capability
            'wp-ultimakit-settings',
            // Menu slug
            array($this, 'ultimakit_render_settings_page')
        );
    }

    public function ultimakit_render_settings_page() {
        ?>
		<div class="wrap">
			<?php 
        $this->ultimakit_get_header();
        ?>
			<?php 
        $this->ultimakit_get_settings();
        ?>
		</div>
		<?php 
    }

    /**
     * Renders the settings page for the plugin/theme.
     *
     * This function outputs the HTML for the settings page of the plugin or theme.
     * It should be hooked into the WordPress admin menu system via add_options_page()
     * or a similar function. The function checks for user permissions, outputs the
     * settings form, and handles the submission of form data for updating plugin/theme
     * settings.
     */
    public function ultimakit_render_dashboard_page() {
        ?>
		<div class="wrap">
			<?php 
        $this->ultimakit_get_header();
        ?>
			<?php 
        $this->ultimakit_get_modules();
        ?>
		</div>
		<?php 
    }

    public function ultimakit_get_settings() {
        ?>
		<div class="container-fluid module-container">
			<div class="row">
				<div class="col-6">
					<form id="ultimakit_form" method="post" enctype="multipart/form-data">
						<?php 
        $uninstall_status = get_option( 'ultimakit_uninstall_settings', true );
        ?>
						<div class="mb-3 form-check form-switch p-0">
							<label><?php 
        echo esc_html_e( 'Remove all plugin data upon uninstallation.', 'ultimakit-for-wp' );
        ?></label>
						  <input class="form-check-input ultimakit_settings_action" type="checkbox" id="ultimakit_uninstall_settings" <?php 
        if ( 'on' === $uninstall_status ) {
            echo 'checked';
        }
        ?> >
						  <label class="form-check-label switch-label" for="ultimakit_uninstall_settings">Toggle me</label>
						</div>

						<div class="mb-3">
						  <label for="formFile" class="form-label"><?php 
        echo esc_html_e( 'Import Settings', 'ultimakit-for-wp' );
        ?></label>
						  <input class="form-control" type="file" name="ultimakit_import_settings" id="ultimakit_import_settings" accept=".json">
						  <small><?php 
        echo esc_html_e( 'Only valid JSON is accepted.', 'ultimakit-for-wp' );
        ?></small>
						</div>

						<div class="mb-5">
						  <button class="btn btn-primary" id="ultimakit_export_settings"><?php 
        echo esc_html_e( 'Export Settings', 'ultimakit-for-wp' );
        ?></button>
						</div>
					
					</form>	

				</div>
			</div>
		</div>
		<?php 
    }

    /**
     * Retrieves a list of available modules/components for the theme or plugin.
     *
     * This function compiles and returns an array of modules or components that are
     * available within the theme or plugin. These could be features, extensions,
     * widgets, or any other type of modular functionality that can be dynamically
     * managed or utilized within the project. The function can be used to check
     * for the availability of certain modules, to dynamically include them in the
     * project, or to provide options in the admin settings for enabling/disabling
     * specific modules.
     */
    public function ultimakit_get_modules() {
        $admin = new UltimaKit_Module_Manager();
        ?>
		<div class="wrap">
			<div class="container-fluid module-container">
				<div class="row">
					<div class="filters">
				        <div class="filter_container">
						    <h4><?php 
        echo esc_html_e( 'Filters', 'ultimakit-for-wp' );
        ?></h4>
						    <form id="moduleFilterForm" style="display: flex; align-items: center;">
						        <div style="margin-right: 10px;">
						            <label for="category" style="margin-right: 5px;"><?php 
        echo esc_html_e( 'Category:', 'ultimakit-for-wp' );
        ?></label>
						            <select id="ultimakit_category">
						            	<option value="all">All</option>
						                <?php 
        $admin->getAllCategories();
        ?>
						            </select>
						        </div>
						        <div style="margin-right: 10px;">
						            <label for="status" style="margin-right: 5px;"><?php 
        echo esc_html_e( 'Status:', 'ultimakit-for-wp' );
        ?></label>
						            <select id="ultimakit_status">
						                <option value="all">All</option>
						                <option value="active"><?php 
        echo esc_html_e( 'Active', 'ultimakit-for-wp' );
        ?></option>
						                <option value="inactive"><?php 
        echo esc_html_e( 'Inactive', 'ultimakit-for-wp' );
        ?></option>
						            </select>
						        </div>
						    </form>
						</div>

					</div>
					<!-- Nav tabs -->
					<ul class="nav nav-tabs" id="wpukTabs" role="tablist">
						<li class="nav-item" role="presentation">
							<a class="nav-link active" id="modules-tab" data-bs-toggle="tab" href="#free-modules" role="tab" aria-controls="free-modules" aria-selected="true"><?php 
        echo esc_html_e( 'Free Modules', 'ultimakit-for-wp' );
        ?></a>
						</li>
						<li class="nav-item" role="presentation">
							<a class="nav-link " id="modules-tab" data-bs-toggle="tab" href="#pro-modules" role="tab" aria-controls="pro-modules" aria-selected="true"><?php 
        echo esc_html_e( 'Pro Modules', 'ultimakit-for-wp' );
        ?></a>
						</li>
						<li class="nav-item" role="presentation">
							<a class="nav-link" href="https://wpultimakit.com" target="_blank"><?php 
        echo esc_html_e( 'Help', 'ultimakit-for-wp' );
        ?></a>
						</li>
						
					</ul>

					<!-- Tab panes -->
					<div class="tab-content" id="wpukTabsContent">
						<div class="tab-pane fade show active" id="free-modules" role="tabpanel" aria-labelledby="modules-tab">
							<!-- Your modules content here -->
							<div class="row">

								<!-- Your module listing below... -->
								<?php 
        $all_modules = $admin->getAllModules( 'free' );
        usort( $all_modules, function ( $a, $b ) {
            return $b['is_active'] - $a['is_active'];
        } );
        if ( !empty( $all_modules ) ) {
            foreach ( $all_modules as $module ) {
                ?>
											<div class="module-block col-sm-4 col-md-3 p-0 m-0 <?php 
                echo esc_attr( $module['category'] );
                ?> <?php 
                echo ( true === $module['is_active'] ? 'active' : 'inactive' );
                ?>">
												<div class="module-box <?php 
                echo esc_attr( $module['plan'] );
                ?>-plan">
													<!-- Module Title (Top-Left) -->
													<h5 class="module-title"><?php 
                echo esc_html( $module['name'] );
                ?></h5>

													<!-- Module Description (Below Title) -->
													<p class="module-description"><?php 
                echo esc_html( $module['description'] );
                ?></p>

													<div class="form-check form-switch module-switch">
														<input type="checkbox" class="form-check-input ultimakit_module_action" id="<?php 
                echo esc_attr( $module['id'] );
                ?>" <?php 
                if ( true === $module['is_active'] ) {
                    echo 'checked';
                }
                ?> >
														<label class="form-check-label switch-label" for="<?php 
                echo esc_attr( $module['id'] );
                ?>">Toggle me</label>
													</div>

													<!-- Learn More Link (Bottom-Left) -->
												<?php 
                if ( isset( $module['settings'] ) && 'yes' == $module['settings'] ) {
                    ?>
														<a href="javascript:void()" class="
														<?php 
                    if ( !$module['is_active'] ) {
                        echo 'ultimakit_hide_settings ';
                    }
                    ?>
														learn-more-link <?php 
                    echo esc_attr( $module['id'] );
                    ?>"><?php 
                    echo esc_html( __( 'Settings', 'ultimakit-for-wp' ) );
                    ?></a>
													<?php 
                }
                ?>
													<span class="plugin-badge"><?php 
                echo esc_html( $module['type'] );
                ?></span>

												<?php 
                echo '<span class="doc-badge"><a href="' . esc_url( ULTIMAKIT_WEB_URL . $module['link'] ) . '" target="_blank"><span class="dashicons dashicons-external"></span></a></span>';
                if ( isset( $module['plan'] ) && 'pro' === $module['plan'] ) {
                    echo '<span class="pro-badge">' . esc_html__( 'PRO', 'ultimakit-for-wp' ) . '</span>';
                } else {
                    echo '<span class="free-badge">' . esc_html__( 'FREE', 'ultimakit-for-wp' ) . '</span>';
                }
                ?>
												</div>
											</div>
											<?php 
            }
        }
        ?>
							</div>
						</div>

						<div class="tab-pane fade " id="pro-modules" role="tabpanel" aria-labelledby="modules-tab">
							<!-- Your modules content here -->
							<div class="row">

								<!-- Your module listing below... -->
								<?php 
        $admin = new UltimaKit_Module_Manager();
        $all_modules = $admin->getAllModules( 'json' );
        usort( $all_modules, function ( $a, $b ) {
            return $b['is_active'] - $a['is_active'];
        } );
        if ( !empty( $all_modules ) ) {
            foreach ( $all_modules as $module ) {
                ?>
											<div class="module-block col-sm-4 col-md-3 p-0 m-0 <?php 
                echo esc_attr( $module['category'] );
                ?> <?php 
                echo ( true === $module['is_active'] ? 'active' : 'inactive' );
                ?> <?php 
                echo 'not_paying';
                ?>">
												<div class="module-box <?php 
                echo esc_attr( $module['plan'] );
                ?>-plan">
													<!-- Module Title (Top-Left) -->
													<h5 class="module-title"><?php 
                echo esc_html( $module['name'] );
                ?></h5>

													<!-- Module Description (Below Title) -->
													<p class="module-description"><?php 
                echo esc_html( $module['description'] );
                ?></p>

													<div class="form-check form-switch module-switch">
														<?php 
                ?>
													</div>

													<!-- Learn More Link (Bottom-Left) -->
												<?php 
                if ( isset( $module['settings'] ) && 'yes' == $module['settings'] ) {
                    ?>
														<a href="javascript:void()" class="
														<?php 
                    if ( !$module['is_active'] ) {
                        echo 'ultimakit_hide_settings ';
                    }
                    ?>
														learn-more-link <?php 
                    echo esc_attr( $module['id'] );
                    ?>"><?php 
                    echo esc_html( __( 'Settings', 'ultimakit-for-wp' ) );
                    ?></a>
													<?php 
                }
                ?>
													<span class="plugin-badge"><?php 
                echo esc_html( $module['type'] );
                ?></span>

												<?php 
                echo '<span class="doc-badge"><a href="' . esc_url( ULTIMAKIT_WEB_URL . $module['link'] ) . '" target="_blank"><span class="dashicons dashicons-external"></span></a></span>';
                if ( isset( $module['plan'] ) && 'pro' === $module['plan'] ) {
                    echo '<span class="pro-badge">' . esc_html__( 'PRO', 'ultimakit-for-wp' ) . '</span>';
                } else {
                    echo '<span class="free-badge">' . esc_html__( 'FREE', 'ultimakit-for-wp' ) . '</span>';
                }
                ?>
												</div>
											</div>
											<?php 
            }
        }
        ?>
							</div>
						</div>

					</div>

					<!-- Duplicate the above block for each module you have -->
				</div>
			</div>
		</div>
		<?php 
    }

}
