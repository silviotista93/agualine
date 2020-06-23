<?php
/**
 * PoolServices Framework: Theme options custom fields
 *
 * @package	poolservices
 * @since	poolservices 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'poolservices_options_custom_theme_setup' ) ) {
	add_action( 'poolservices_action_before_init_theme', 'poolservices_options_custom_theme_setup' );
	function poolservices_options_custom_theme_setup() {

		if ( is_admin() ) {
			add_action("admin_enqueue_scripts",	'poolservices_options_custom_load_scripts');
		}
		
	}
}

// Load required styles and scripts for custom options fields
if ( !function_exists( 'poolservices_options_custom_load_scripts' ) ) {
	function poolservices_options_custom_load_scripts() {
		wp_enqueue_script( 'poolservices-options-custom-script',	poolservices_get_file_url('core/core.options/js/core.options-custom.js'), array(), null, true );	
	}
}


// Show theme specific fields in Post (and Page) options
if ( !function_exists( 'poolservices_show_custom_field' ) ) {
	function poolservices_show_custom_field($id, $field, $value) {
		$output = '';
		switch ($field['type']) {
			case 'reviews':
				$output .= '<div class="reviews_block">' . trim(poolservices_reviews_get_markup($field, $value, true)) . '</div>';
				break;
	
			case 'mediamanager':
				wp_enqueue_media( );
				$output .= '<a id="'.esc_attr($id).'" class="button mediamanager poolservices_media_selector"
					data-param="' . esc_attr($id) . '"
					data-choose="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Choose Images', 'poolservices') : esc_html__( 'Choose Image', 'poolservices')).'"
					data-update="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Add to Gallery', 'poolservices') : esc_html__( 'Choose Image', 'poolservices')).'"
					data-multiple="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? 'true' : 'false').'"
					data-linked-field="'.esc_attr($field['media_field_id']).'"
					>' . (isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Choose Images', 'poolservices') : esc_html__( 'Choose Image', 'poolservices')) . '</a>';
				break;
		}
		return apply_filters('poolservices_filter_show_custom_field', $output, $id, $field, $value);
	}
}
?>