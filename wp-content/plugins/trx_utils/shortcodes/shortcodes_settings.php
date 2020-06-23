<?php

// Check if shortcodes settings are now used
if ( !function_exists( 'poolservices_shortcodes_is_used' ) ) {
	function poolservices_shortcodes_is_used() {
		return poolservices_options_is_used() 															// All modes when Theme Options are used
			|| (is_admin() && isset($_POST['action']) 
					&& in_array($_POST['action'], array('vc_edit_form', 'wpb_show_edit_form')))		// AJAX query when save post/page
			|| (is_admin() && !empty($_REQUEST['page']) && $_REQUEST['page']=='vc-roles')			// VC Role Manager
			|| (function_exists('poolservices_vc_is_frontend') && poolservices_vc_is_frontend());			// VC Frontend editor mode
	}
}

// Width and height params
if ( !function_exists( 'poolservices_shortcodes_width' ) ) {
	function poolservices_shortcodes_width($w="") {
		return array(
			"title" => esc_html__("Width", 'poolservices'),
			"divider" => true,
			"value" => $w,
			"type" => "text"
		);
	}
}
if ( !function_exists( 'poolservices_shortcodes_height' ) ) {
	function poolservices_shortcodes_height($h='') {
		return array(
			"title" => esc_html__("Height", 'poolservices'),
			"desc" => wp_kses_data( __("Width and height of the element", 'poolservices') ),
			"value" => $h,
			"type" => "text"
		);
	}
}

// Return sc_param value
if ( !function_exists( 'poolservices_get_sc_param' ) ) {
	function poolservices_get_sc_param($prm) {
		return poolservices_storage_get_array('sc_params', $prm);
	}
}

// Set sc_param value
if ( !function_exists( 'poolservices_set_sc_param' ) ) {
	function poolservices_set_sc_param($prm, $val) {
		poolservices_storage_set_array('sc_params', $prm, $val);
	}
}

// Add sc settings in the sc list
if ( !function_exists( 'poolservices_sc_map' ) ) {
	function poolservices_sc_map($sc_name, $sc_settings) {
		poolservices_storage_set_array('shortcodes', $sc_name, $sc_settings);
	}
}

// Add sc settings in the sc list after the key
if ( !function_exists( 'poolservices_sc_map_after' ) ) {
	function poolservices_sc_map_after($after, $sc_name, $sc_settings='') {
		poolservices_storage_set_array_after('shortcodes', $after, $sc_name, $sc_settings);
	}
}

// Add sc settings in the sc list before the key
if ( !function_exists( 'poolservices_sc_map_before' ) ) {
	function poolservices_sc_map_before($before, $sc_name, $sc_settings='') {
		poolservices_storage_set_array_before('shortcodes', $before, $sc_name, $sc_settings);
	}
}

// Compare two shortcodes by title
if ( !function_exists( 'poolservices_compare_sc_title' ) ) {
	function poolservices_compare_sc_title($a, $b) {
		return strcmp($a['title'], $b['title']);
	}
}



/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'poolservices_shortcodes_settings_theme_setup' ) ) {
	if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') || (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline') )
		add_action( 'poolservices_action_before_init_theme', 'poolservices_shortcodes_settings_theme_setup', 20 );
	else
		add_action( 'poolservices_action_after_init_theme', 'poolservices_shortcodes_settings_theme_setup' );
	function poolservices_shortcodes_settings_theme_setup() {
		if (poolservices_shortcodes_is_used()) {

			// Sort templates alphabetically
			$tmp = poolservices_storage_get('registered_templates');
			ksort($tmp);
			poolservices_storage_set('registered_templates', $tmp);

			// Prepare arrays 
			poolservices_storage_set('sc_params', array(
			
				// Current element id
				'id' => array(
					"title" => esc_html__("Element ID", 'poolservices'),
					"desc" => wp_kses_data( __("ID for current element", 'poolservices') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
			
				// Current element class
				'class' => array(
					"title" => esc_html__("Element CSS class", 'poolservices'),
					"desc" => wp_kses_data( __("CSS class for current element (optional)", 'poolservices') ),
					"value" => "",
					"type" => "text"
				),
			
				// Current element style
				'css' => array(
					"title" => esc_html__("CSS styles", 'poolservices'),
					"desc" => wp_kses_data( __("Any additional CSS rules (if need)", 'poolservices') ),
					"value" => "",
					"type" => "text"
				),
			
			
				// Switcher choises
				'list_styles' => array(
					'ul'	=> esc_html__('Unordered', 'poolservices'),
					'ol'	=> esc_html__('Ordered', 'poolservices'),
					'iconed'=> esc_html__('Iconed', 'poolservices')
				),

				'yes_no'	=> poolservices_get_list_yesno(),
				'on_off'	=> poolservices_get_list_onoff(),
				'dir' 		=> poolservices_get_list_directions(),
				'align'		=> poolservices_get_list_alignments(),
				'float'		=> poolservices_get_list_floats(),
				'hpos'		=> poolservices_get_list_hpos(),
				'show_hide'	=> poolservices_get_list_showhide(),
				'sorting' 	=> poolservices_get_list_sortings(),
				'ordering' 	=> poolservices_get_list_orderings(),
				'shapes'	=> poolservices_get_list_shapes(),
				'sizes'		=> poolservices_get_list_sizes(),
				'sliders'	=> poolservices_get_list_sliders(),
				'controls'	=> poolservices_get_list_controls(),
                    'categories'=> is_admin() && poolservices_get_value_gp('action')=='vc_edit_form' && substr(poolservices_get_value_gp('tag'), 0, 4)=='trx_' && isset($_POST['params']['post_type']) && $_POST['params']['post_type']!='post'
                        ? poolservices_get_list_terms(false, poolservices_get_taxonomy_categories_by_post_type($_POST['params']['post_type']))
                        : poolservices_get_list_categories(),
				'columns'	=> poolservices_get_list_columns(),
				'images'	=> array_merge(array('none'=>"none"), poolservices_get_list_images("images/icons", "png")),
				'icons'		=> array_merge(array("inherit", "none"), poolservices_get_list_icons()),
				'locations'	=> poolservices_get_list_dedicated_locations(),
				'filters'	=> poolservices_get_list_portfolio_filters(),
				'formats'	=> poolservices_get_list_post_formats_filters(),
				'hovers'	=> poolservices_get_list_hovers(true),
				'hovers_dir'=> poolservices_get_list_hovers_directions(true),
				'schemes'	=> poolservices_get_list_color_schemes(true),
				'animations'		=> poolservices_get_list_animations_in(),
				'margins' 			=> poolservices_get_list_margins(true),
				'blogger_styles'	=> poolservices_get_list_templates_blogger(),
				'forms'				=> poolservices_get_list_templates_forms(),
				'posts_types'		=> poolservices_get_list_posts_types(),
				'googlemap_styles'	=> poolservices_get_list_googlemap_styles(),
				'field_types'		=> poolservices_get_list_field_types(),
				'label_positions'	=> poolservices_get_list_label_positions()
				)
			);

			// Common params
			poolservices_set_sc_param('animation', array(
				"title" => esc_html__("Animation",  'poolservices'),
				"desc" => wp_kses_data( __('Select animation while object enter in the visible area of page',  'poolservices') ),
				"value" => "none",
				"type" => "select",
				"options" => poolservices_get_sc_param('animations')
				)
			);
			poolservices_set_sc_param('top', array(
				"title" => esc_html__("Top margin",  'poolservices'),
				"divider" => true,
				"value" => "inherit",
				"type" => "select",
				"options" => poolservices_get_sc_param('margins')
				)
			);
			poolservices_set_sc_param('bottom', array(
				"title" => esc_html__("Bottom margin",  'poolservices'),
				"value" => "inherit",
				"type" => "select",
				"options" => poolservices_get_sc_param('margins')
				)
			);
			poolservices_set_sc_param('left', array(
				"title" => esc_html__("Left margin",  'poolservices'),
				"value" => "inherit",
				"type" => "select",
				"options" => poolservices_get_sc_param('margins')
				)
			);
			poolservices_set_sc_param('right', array(
				"title" => esc_html__("Right margin",  'poolservices'),
				"desc" => wp_kses_data( __("Margins around this shortcode", 'poolservices') ),
				"value" => "inherit",
				"type" => "select",
				"options" => poolservices_get_sc_param('margins')
				)
			);

			poolservices_storage_set('sc_params', apply_filters('poolservices_filter_shortcodes_params', poolservices_storage_get('sc_params')));

			// Shortcodes list
			//------------------------------------------------------------------
			poolservices_storage_set('shortcodes', array());
			
			// Register shortcodes
			do_action('poolservices_action_shortcodes_list');

			// Sort shortcodes list
			$tmp = poolservices_storage_get('shortcodes');
			uasort($tmp, 'poolservices_compare_sc_title');
			poolservices_storage_set('shortcodes', $tmp);
		}
	}
}
?>