<?php
/**
 * PoolServices Framework: Admin functions
 *
 * @package	poolservices
 * @since	poolservices 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* Admin actions and filters:
------------------------------------------------------------------------ */

if (is_admin()) {

	/* Theme setup section
	-------------------------------------------------------------------- */
	
	if ( !function_exists( 'poolservices_admin_theme_setup' ) ) {
		add_action( 'poolservices_action_before_init_theme', 'poolservices_admin_theme_setup', 11 );
		function poolservices_admin_theme_setup() {
			if ( is_admin() ) {
				add_filter("poolservices_filter_localize_script_admin", 'poolservices_admin_localize_script');
				add_action("admin_enqueue_scripts",	'poolservices_admin_load_scripts');
				add_action("admin_footer",			'poolservices_admin_add_vars', 2);
				add_action('tgmpa_register',		'poolservices_admin_register_plugins');

				// AJAX: Get terms for specified post type
				add_action('wp_ajax_poolservices_admin_change_post_type', 		'poolservices_callback_admin_change_post_type');
				add_action('wp_ajax_nopriv_poolservices_admin_change_post_type','poolservices_callback_admin_change_post_type');
			}
		}
	}
	
	// Load required styles and scripts for admin mode
	if ( !function_exists( 'poolservices_admin_load_scripts' ) ) {
		function poolservices_admin_load_scripts() {
			wp_enqueue_style( 'poolservices-admin-style', poolservices_get_file_url('css/core.admin.css'), array(), null );
			if (poolservices_check_admin_page('widgets.php')) {
				wp_enqueue_style( 'poolservices-fontello-style', poolservices_get_file_url('css/fontello-admin/css/fontello-admin.css'), array(), null );
				wp_enqueue_style( 'poolservices-animations-style', poolservices_get_file_url('css/fontello-admin/css/animation.css'), array(), null );
			}
            if (poolservices_get_theme_option('debug_mode')=='yes') {
                wp_enqueue_script( 'poolservices-debug-script', poolservices_get_file_url('js/core.debug.js'), array('jquery'), null, true );
            }
			wp_enqueue_script( 'poolservices-admin-script', poolservices_get_file_url('js/core.admin.js'), array('jquery'), null, true );
		}
	}
	
	// Prepare required styles and scripts for admin mode
	if ( !function_exists( 'poolservices_admin_localize_script' ) ) {
		function poolservices_admin_localize_script($vars) {
			$vars['admin_mode'] = true;
			$vars['user_logged_in'] = true;
			$vars['ajax_nonce'] = wp_create_nonce(admin_url('admin-ajax.php'));
			$vars['ajax_url'] = esc_url(admin_url('admin-ajax.php'));
			$vars['ajax_error'] = esc_html__('Invalid server answer', 'poolservices');
			$vars['importer_error_msg'] = esc_html__('Errors that occurred during the import process:', 'poolservices');
			return $vars;
		}
	}

	//  Localize scripts in the footer hook
	if ( !function_exists( 'poolservices_admin_add_vars' ) ) {
		function poolservices_admin_add_vars() {
			$vars = apply_filters( 'poolservices_filter_localize_script_admin', poolservices_storage_empty('js_vars') ? array() : poolservices_storage_get('js_vars'));
			if (!empty($vars)) wp_localize_script( 'poolservices-admin-script', 'POOLSERVICES_STORAGE', $vars);
			if (!poolservices_storage_empty('js_code')) {
				$holder = 'script';
				?><<?php poolservices_show_layout($holder); ?>>
					jQuery(document).ready(function() {
						<?php poolservices_show_layout(poolservices_minify_js(poolservices_storage_get('js_code'))); ?>
					}
				</<?php poolservices_show_layout($holder); ?>><?php
			}
		}
	}
	
	// AJAX: Get terms for specified post type
	if ( !function_exists( 'poolservices_callback_admin_change_post_type' ) ) {
		function poolservices_callback_admin_change_post_type() {
			if ( !wp_verify_nonce( poolservices_get_value_gp('nonce'), admin_url('admin-ajax.php') ) )
				die();
			$post_type = $_REQUEST['post_type'];
			$terms = poolservices_get_list_terms(false, poolservices_get_taxonomy_categories_by_post_type($post_type));
			$terms = poolservices_array_merge(array(0 => esc_html__('- Select category -', 'poolservices')), $terms);
			$response = array(
				'error' => '',
				'data' => array(
					'ids' => array_keys($terms),
					'titles' => array_values($terms)
				)
			);
			echo json_encode($response);
			die();
		}
	}

	// Return current post type in dashboard
	if ( !function_exists( 'poolservices_admin_get_current_post_type' ) ) {
		function poolservices_admin_get_current_post_type() {
			global $post, $typenow, $current_screen;
			if ( $post && $post->post_type )							//we have a post so we can just get the post type from that
				return $post->post_type;
			else if ( $typenow )										//check the global $typenow — set in admin.php
				return $typenow;
			else if ( $current_screen && $current_screen->post_type )	//check the global $current_screen object — set in sceen.php
				return $current_screen->post_type;
			else if ( isset( $_REQUEST['post_type'] ) )					//check the post_type querystring
				return sanitize_key( $_REQUEST['post_type'] );
			else if ( isset( $_REQUEST['post'] ) ) {					//lastly check the post id querystring
				$post = get_post( sanitize_key( $_REQUEST['post'] ) );
				return !empty($post->post_type) ? $post->post_type : '';
			} else														//we do not know the post type!
				return '';
		}
	}

	// Add admin menu pages
	if ( !function_exists( 'poolservices_admin_add_menu_item' ) ) {
		function poolservices_admin_add_menu_item($mode, $item, $pos='100') {
			static $shift = 0;
			if ($pos=='100') $pos .= '.'.$shift++;
			$fn = join('_', array('add', $mode, 'page'));
			if (empty($item['parent']))
				$fn($item['page_title'], $item['menu_title'], $item['capability'], $item['menu_slug'], $item['callback'], $item['icon'], $pos);
			else
				$fn($item['parent'], $item['page_title'], $item['menu_title'], $item['capability'], $item['menu_slug'], $item['callback'], $item['icon'], $pos);
		}
	}
	
	// Register optional plugins
	if ( !function_exists( 'poolservices_admin_register_plugins' ) ) {
		function poolservices_admin_register_plugins() {

			$plugins = apply_filters('poolservices_filter_required_plugins', array());
			$config = array(
				'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
				'default_path' => '',                      // Default absolute path to bundled plugins.
				'menu'         => 'tgmpa-install-plugins', // Menu slug.
				'parent_slug'  => 'themes.php',            // Parent menu slug.
				'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
				'has_notices'  => true,                    // Show admin notices or not.
				'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
				'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
				'is_automatic' => true,                    // Automatically activate plugins after installation or not.
				'message'      => ''                       // Message to output right before the plugins table.
			);
	
			tgmpa( $plugins, $config );
		}
	}

	require_once POOLSERVICES_FW_PATH . 'lib/tgm/class-tgm-plugin-activation.php';
}

?>