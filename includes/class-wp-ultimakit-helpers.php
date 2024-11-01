<?php

class UltimaKit_Helpers {
    public function __construct() {
    }

    public function ultimakit_asset_condition() {
        if ( isset( $_GET['page'] ) && 'wp-ultimakit-dashboard' === $_GET['page'] ) {
            return true;
        }
    }

    /**
     * Retrieves or displays the custom header content for the theme.
     *
     * This function is a custom implementation for fetching or rendering the header content.
     * It can be used to include custom header templates or dynamic header elements specific
     * to the theme or plugin. The function can be tailored to support different header styles
     * or configurations based on context or preferences set in the theme options.
     */
    public function ultimakit_get_header() {
        ?>
		<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #6610F2;">
			<div class="container-fluid p-2">
				<a class="navbar-brand" href="#">
					<img src="<?php 
        echo esc_url( ULTIMAKIT_FOR_WP_LOGO );
        ?>"  class="d-inline-block align-top" alt="ultimakit-for-wp-logo" width="175px">
					<div class="wpuk-version-info"><?php 
        echo esc_html_e( 'Current version:', 'ultimakit-for-wp' );
        echo esc_html_e( ULTIMAKIT_FOR_WP_VERSION );
        ?></div>
				</a>
				
				<div class="navbar-nav ml-auto">
					<?php 
        ?>
				        	<a class="nav-item nav-link get-pro" target="_blank" href="https://wpultimakit.com/pricing" style="margin-right: 20px"><?php 
        echo esc_html_e( 'Get Pro', 'ultimakit-for-wp' );
        ?></a>
				        	<?php 
        ?>
					<a class="nav-item nav-link" target="_blank" href="https://wordpress.org/support/plugin/ultimakit-for-wp/reviews/#new-post" style="color: #ffffff; margin-right: 20px"><?php 
        echo esc_html_e( 'Leave Feedback', 'ultimakit-for-wp' );
        ?></a>
				</div>
			</div>
		</nav>
		<?php 
    }

    public function ultimakit_generate_form( $args = array(), $modal_type = '' ) {
        $modal_title = sanitize_text_field( $args['title'] );
        $fields = $args['fields'];
        ?>
		<!-- Modal -->
		<div class="wpuk_modal " id="<?php 
        echo esc_attr( $this->ID );
        ?>_modal" tabindex="-1" aria-labelledby="<?php 
        echo esc_attr( $this->ID );
        ?>_modal" aria-hidden="true">
			<div class="<?php 
        echo esc_attr( $modal_type );
        ?>">
				<div class="">
					<div class="modal-body">
						<form id="<?php 
        echo esc_attr( $args['ID'] );
        ?>_form" class="module_settings" method="post">
							<input type="hidden" id="module_id" value="<?php 
        echo esc_attr( $args['ID'] );
        ?>">
						<?php 
        $this->ultimakit_generate_fields( $fields );
        ?>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php 
        esc_html_e( 'Reset', 'ultimakit-for-wp' );
        ?></button>
						<button type="submit" class="btn btn-primary wpuk_save_module_settings" id="<?php 
        echo esc_attr( $this->ID );
        ?>_form"><?php 
        esc_html_e( 'Save changes', 'ultimakit-for-wp' );
        ?></button>

					</div>
					</form>
				</div>
			</div>
		</div>
		<?php 
    }

    public function ultimakit_generate_modal( $args = array(), $modal_type = '' ) {
        $modal_title = sanitize_text_field( $args['title'] );
        $fields = $args['fields'];
        if ( !$this->ultimakit_asset_condition() ) {
            return;
        }
        ?>
		<!-- Modal -->
		<div class="wpuk_modal modal fade" id="<?php 
        echo esc_attr( $this->ID );
        ?>_modal" tabindex="-1" aria-labelledby="<?php 
        echo esc_attr( $this->ID );
        ?>_modal" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered <?php 
        echo esc_html_e( $modal_type );
        ?>">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title"><?php 
        echo esc_html( $modal_title );
        ?></h5>
					</div>
					<div class="modal-body">
						<form id="<?php 
        echo esc_attr( $args['ID'] );
        ?>_form" class="module_settings" method="post">
							<input type="hidden" id="module_id" value="<?php 
        echo esc_attr( $args['ID'] );
        ?>">
							<?php 
        $this->ultimakit_generate_fields( $fields );
        ?>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php 
        esc_html_e( 'Close', 'ultimakit-for-wp' );
        ?></button>
						<button type="submit" class="btn btn-primary wpuk_save_module_settings" id="<?php 
        echo esc_attr( $this->ID );
        ?>_form"><?php 
        esc_html_e( 'Save changes', 'ultimakit-for-wp' );
        ?></button>

					</div>
					</form>
				</div>
			</div>
		</div>
		<?php 
    }

    public function ultimakit_generate_fields( $fields ) {
        if ( !empty( $fields ) ) {
            echo '<ul>';
            foreach ( $fields as $key => $value ) {
                echo '<li>';
                switch ( $value['type'] ) {
                    case 'text':
                        echo '<label for="' . esc_attr( $key ) . '">' . esc_html( $value['label'] ) . '</label><br />';
                        echo '<input type="text" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value['value'] ) . '">';
                        if ( !empty( $value['desc'] ) ) {
                            echo '<br /><small>' . esc_html( $value['desc'] ) . '</small>';
                        }
                        break;
                    case 'color':
                        echo '<label for="' . esc_attr( $key ) . '">' . esc_html( $value['label'] ) . '</label><br />';
                        echo '<input type="text" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" class="ultimakit-color-picker" value="' . esc_attr( $value['value'] ) . '">';
                        if ( !empty( $value['desc'] ) ) {
                            echo '<br /><small>' . esc_html( $value['desc'] ) . '</small>';
                        }
                        break;
                    case 'hidden':
                        echo '<input type="hidden" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value['value'] ) . '">';
                        break;
                    case 'textarea':
                        echo '<label for="' . esc_attr( $key ) . '">' . esc_html( $value['label'] ) . '</label><br />';
                        echo '<textarea rows="5" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '">' . esc_attr( $value['value'] ) . '</textarea>';
                        if ( !empty( $value['desc'] ) ) {
                            echo '<br /><small>' . esc_html( $value['desc'] ) . '</small>';
                        }
                        break;
                    case 'password':
                        echo '<label for="' . esc_attr( $key ) . '">' . esc_html( $value['label'] ) . '</label><br />';
                        echo '<input type="password" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value['value'] ) . '">';
                        if ( !empty( $value['desc'] ) ) {
                            echo '<br /><small>' . esc_html( $value['desc'] ) . '</small>';
                        }
                        break;
                    case 'checkbox':
                        $checked = ( 'on' === $value['value'] ? 'checked' : '' );
                        echo '<input ' . esc_attr( $checked ) . ' type="checkbox" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value['value'] ) . '"> <label for="' . esc_attr( $key ) . '">' . esc_html( $value['label'] ) . '</label>';
                        break;
                    case 'switch':
                        $checked = ( 'on' === $value['value'] ? 'checked' : '' );
                        echo '<div class="form-check form-switch module-switch"><input ' . esc_attr( $checked ) . ' class="form-check-input" type="checkbox" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value['value'] ) . '"> <label class="form-check-label switch-label" for="' . esc_attr( $key ) . '">toggle me</label>' . esc_html( $value['label'] ) . '</div>';
                        break;
                    case 'radio':
                        $checked = ( 'on' === $value['value'] ? 'checked="checked"' : '' );
                        echo '<input ' . esc_attr( $checked ) . ' type="radio" name="' . esc_attr( $key ) . '"> <label for="' . esc_attr( $key ) . '"> ' . esc_html( $value['label'] ) . '</label>';
                        break;
                    case 'select':
                        echo '<label for="' . esc_attr( $key ) . '">' . esc_html( $value['label'] ) . '</label><br/>';
                        echo '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '">';
                        if ( !empty( $value['options'] ) ) {
                            foreach ( $value['options'] as $op_key => $op_value ) {
                                $selected = selected( $op_key, $value['default'], false );
                                echo '<option ' . esc_attr( $selected ) . ' value="' . esc_attr( $op_key ) . '">' . esc_html( $op_value ) . '</option>';
                            }
                        }
                        echo '</select>';
                        if ( !empty( $value['desc'] ) ) {
                            echo '<br /><small>' . esc_html( $value['desc'] ) . '</small>';
                        }
                        break;
                    case 'html':
                        echo $value['value'];
                        break;
                }
                echo '</li>';
            }
            echo '</ul>';
        }
    }

    public function show_featured_image_column() {
        $post_types = get_post_types( array(
            'public' => true,
        ), 'names' );
        foreach ( $post_types as $post_type_key => $post_type_name ) {
            if ( post_type_supports( $post_type_key, 'thumbnail' ) ) {
                add_filter( "manage_{$post_type_name}_posts_columns", array($this, 'add_featured_image_column'), 999 );
                add_action(
                    "manage_{$post_type_name}_posts_custom_column",
                    array($this, 'add_featured_image'),
                    10,
                    2
                );
            }
        }
    }

    public function add_featured_image_column( $columns ) {
        $new_columns = array();
        foreach ( $columns as $key => $value ) {
            if ( 'title' == $key ) {
                // We add featured image column before the 'title' column
                $new_columns['wpuk-featured-image'] = 'Featured Image';
            }
            if ( 'thumb' == $key ) {
                // For WooCommerce products, we add featured image column before it's native thumbnail column
                $new_columns['wpuk-featured-image'] = 'Product Image';
            }
            $new_columns[$key] = $value;
        }
        // Replace WooCommerce thumbnail column with ASE featured image column
        if ( array_key_exists( 'thumb', $new_columns ) ) {
            unset($new_columns['thumb']);
        }
        return $new_columns;
    }

    public function add_featured_image( $column_name, $id ) {
        if ( 'wpuk-featured-image' === $column_name ) {
            if ( has_post_thumbnail( $id ) ) {
                $size = 'thumbnail';
                echo get_the_post_thumbnail( $id, $size, '' );
            } else {
                echo '<img src="' . esc_url( plugins_url( 'assets/img/default_featured_image.jpg', __DIR__ ) ) . '" />';
            }
        }
    }

    public function show_excerpt_column() {
        $post_types = get_post_types( array(
            'public' => true,
        ), 'names' );
        foreach ( $post_types as $post_type_key => $post_type_name ) {
            if ( post_type_supports( $post_type_key, 'excerpt' ) ) {
                add_filter( "manage_{$post_type_name}_posts_columns", array($this, 'add_excerpt_column') );
                add_action(
                    "manage_{$post_type_name}_posts_custom_column",
                    array($this, 'add_excerpt'),
                    10,
                    2
                );
            }
        }
    }

    public function add_excerpt_column( $columns ) {
        $new_columns = array();
        foreach ( $columns as $key => $value ) {
            $new_columns[$key] = $value;
            if ( $key == 'title' ) {
                $new_columns['wpuk-excerpt'] = 'Excerpt';
            }
        }
        return $new_columns;
    }

    public function add_excerpt( $column_name, $id ) {
        if ( 'wpuk-excerpt' === $column_name ) {
            $excerpt = get_the_excerpt( $id );
            // about 310 characters
            $excerpt = substr( $excerpt, 0, 160 );
            // truncate to 160 characters
            $short_excerpt = substr( $excerpt, 0, strrpos( $excerpt, ' ' ) );
            echo wp_kses_post( $short_excerpt );
        }
    }

    public function show_id_column() {
        // For pages and hierarchical post types list table
        add_filter( 'manage_pages_columns', array($this, 'add_id_column') );
        add_action(
            'manage_pages_custom_column',
            array($this, 'add_id_echo_value'),
            10,
            2
        );
        // For posts and non-hierarchical custom posts list table
        add_filter( 'manage_posts_columns', array($this, 'add_id_column') );
        add_action(
            'manage_posts_custom_column',
            array($this, 'add_id_echo_value'),
            10,
            2
        );
        // For media list table
        add_filter( 'manage_media_columns', array($this, 'add_id_column') );
        add_action(
            'manage_media_custom_column',
            array($this, 'add_id_echo_value'),
            10,
            2
        );
        // For list table of all taxonomies
        $taxonomies = get_taxonomies( array(
            'public' => true,
        ), 'names' );
        foreach ( $taxonomies as $taxonomy ) {
            add_filter( 'manage_edit-' . $taxonomy . '_columns', array($this, 'add_id_column') );
            add_action(
                'manage_' . $taxonomy . '_custom_column',
                array($this, 'add_id_return_value'),
                10,
                3
            );
        }
        // For users list table
        add_filter( 'manage_users_columns', array($this, 'add_id_column') );
        add_action(
            'manage_users_custom_column',
            array($this, 'add_id_return_value'),
            10,
            3
        );
        // For comments list table
        add_filter( 'manage_edit-comments_columns', array($this, 'add_id_column') );
        add_action(
            'manage_comments_custom_column',
            array($this, 'add_id_echo_value'),
            10,
            3
        );
    }

    public function add_id_column( $columns ) {
        $columns['wpuk-id'] = 'ID';
        return $columns;
    }

    public function add_id_echo_value( $column_name, $id ) {
        if ( 'wpuk-id' === $column_name ) {
            echo esc_html( $id );
        }
    }

    public function show_id_in_action_row() {
        add_filter(
            'page_row_actions',
            array($this, 'add_id_in_action_row'),
            10,
            2
        );
        add_filter(
            'post_row_actions',
            array($this, 'add_id_in_action_row'),
            10,
            2
        );
        add_filter(
            'cat_row_actions',
            array($this, 'add_id_in_action_row'),
            10,
            2
        );
        add_filter(
            'tag_row_actions',
            array($this, 'add_id_in_action_row'),
            10,
            2
        );
        add_filter(
            'media_row_actions',
            array($this, 'add_id_in_action_row'),
            10,
            2
        );
        add_filter(
            'comment_row_actions',
            array($this, 'add_id_in_action_row'),
            10,
            2
        );
        add_filter(
            'user_row_actions',
            array($this, 'add_id_in_action_row'),
            10,
            2
        );
    }

    public function add_id_in_action_row( $actions, $object ) {
        if ( current_user_can( 'edit_posts' ) ) {
            // For pages, posts, custom post types, media/attachments, users
            if ( property_exists( $object, 'ID' ) ) {
                $id = $object->ID;
            }
            // For taxonomies
            if ( property_exists( $object, 'term_id' ) ) {
                $id = $object->term_id;
            }
            // For comments
            if ( property_exists( $object, 'comment_ID' ) ) {
                $id = $object->comment_ID;
            }
            $actions['wpuk-list-table-item-id'] = '<span class="wpuk-list-table-item-id">ID: ' . $id . '</span>';
        }
        return $actions;
    }

    public function show_custom_taxonomy_filters( $post_type ) {
        $post_taxonomies = get_object_taxonomies( $post_type, 'objects' );
        // Only show custom taxonomy filters for post types other than 'post'
        if ( 'post' != $post_type ) {
            array_walk( $post_taxonomies, array($this, 'output_taxonomy_filter') );
        }
    }

    public function output_taxonomy_filter( $post_taxonomy ) {
        // Only show taxonomy filter when the taxonomy is hierarchical
        if ( true === $post_taxonomy->hierarchical ) {
            $get = ( isset( $_GET[$post_taxonomy->query_var] ) ? $_GET[$post_taxonomy->query_var] : '' );
            wp_dropdown_categories( array(
                'show_option_all' => sprintf( 'All %s', $post_taxonomy->label ),
                'orderby'         => 'name',
                'order'           => 'ASC',
                'hide_empty'      => false,
                'hide_if_empty'   => true,
                'selected'        => sanitize_text_field( $get ),
                'hierarchical'    => true,
                'name'            => $post_taxonomy->query_var,
                'taxonomy'        => $post_taxonomy->name,
                'value_field'     => 'slug',
            ) );
        }
    }

    public function hide_comments_column() {
        $post_types = get_post_types( array(
            'public' => true,
        ), 'names' );
        foreach ( $post_types as $post_type_key => $post_type_name ) {
            if ( post_type_supports( $post_type_key, 'comments' ) ) {
                if ( 'attachment' != $post_type_name ) {
                    // For list tables of pages, posts and other post types
                    add_filter( "manage_{$post_type_name}_posts_columns", array($this, 'remove_comment_column') );
                } else {
                    // For list table of media/attachment
                    add_filter( 'manage_media_columns', array($this, 'remove_comment_column') );
                }
            }
        }
    }

    public function remove_comment_column( $columns ) {
        unset($columns['comments']);
        return $columns;
    }

    public function hide_post_tags_column() {
        $post_types = get_post_types( array(
            'public' => true,
        ), 'names' );
        foreach ( $post_types as $post_type_key => $post_type_name ) {
            if ( $post_type_name == 'post' ) {
                add_filter( 'manage_posts_columns', array($this, 'remove_post_tags_column') );
            }
        }
    }

    public function remove_post_tags_column( $columns ) {
        unset($columns['tags']);
        return $columns;
    }

    public function is_table_exists( $table_name ) {
        global $wpdb;
        $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );
        if ( !$wpdb->get_var( $query ) == $table_name ) {
            return true;
        }
        return false;
    }

    public function ultimakit_get_the_user_ip() {
        $ipaddress = '';
        if ( getenv( 'HTTP_CLIENT_IP' ) ) {
            $ipaddress = getenv( 'HTTP_CLIENT_IP' );
        } else {
            if ( getenv( 'HTTP_X_FORWARDED_FOR' ) ) {
                $ipaddress = getenv( 'HTTP_X_FORWARDED_FOR' );
            } else {
                if ( getenv( 'HTTP_X_FORWARDED' ) ) {
                    $ipaddress = getenv( 'HTTP_X_FORWARDED' );
                } else {
                    if ( getenv( 'HTTP_FORWARDED_FOR' ) ) {
                        $ipaddress = getenv( 'HTTP_FORWARDED_FOR' );
                    } else {
                        if ( getenv( 'HTTP_FORWARDED' ) ) {
                            $ipaddress = getenv( 'HTTP_FORWARDED' );
                        } else {
                            if ( getenv( 'REMOTE_ADDR' ) ) {
                                $ipaddress = getenv( 'REMOTE_ADDR' );
                            } else {
                                $ipaddress = 'UNKNOWN';
                            }
                        }
                    }
                }
            }
        }
        return $ipaddress;
    }

}
