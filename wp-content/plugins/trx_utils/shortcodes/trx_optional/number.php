<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('poolservices_sc_number_theme_setup')) {
	add_action( 'poolservices_action_before_init_theme', 'poolservices_sc_number_theme_setup' );
	function poolservices_sc_number_theme_setup() {
		add_action('poolservices_action_shortcodes_list', 		'poolservices_sc_number_reg_shortcodes');
		if (function_exists('poolservices_exists_visual_composer') && poolservices_exists_visual_composer())
			add_action('poolservices_action_shortcodes_list_vc','poolservices_sc_number_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_number id="unique_id" value="400"]
*/

if (!function_exists('poolservices_sc_number')) {	
	function poolservices_sc_number($atts, $content=null){	
		if (poolservices_in_shortcode_blogger()) return '';
		extract(poolservices_html_decode(shortcode_atts(array(
			// Individual params
			"value" => "",
			"align" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . poolservices_get_css_position_as_classes($top, $right, $bottom, $left);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_number' 
					. (!empty($align) ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. '"'
				. (!poolservices_param_is_off($animation) ? ' data-animation="'.esc_attr(poolservices_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>';
		for ($i=0; $i < poolservices_strlen($value); $i++) {
			$output .= '<span class="sc_number_item">' . trim(poolservices_substr($value, $i, 1)) . '</span>';
		}
		$output .= '</div>';
		return apply_filters('poolservices_shortcode_output', $output, 'trx_number', $atts, $content);
	}
	poolservices_require_shortcode('trx_number', 'poolservices_sc_number');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'poolservices_sc_number_reg_shortcodes' ) ) {
	//add_action('poolservices_action_shortcodes_list', 'poolservices_sc_number_reg_shortcodes');
	function poolservices_sc_number_reg_shortcodes() {
	
		poolservices_sc_map("trx_number", array(
			"title" => esc_html__("Number", 'poolservices'),
			"desc" => wp_kses_data( __("Insert number or any word as set separate characters", 'poolservices') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"value" => array(
					"title" => esc_html__("Value", 'poolservices'),
					"desc" => wp_kses_data( __("Number or any word", 'poolservices') ),
					"value" => "",
					"type" => "text"
				),
				"align" => array(
					"title" => esc_html__("Align", 'poolservices'),
					"desc" => wp_kses_data( __("Select block alignment", 'poolservices') ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => poolservices_get_sc_param('align')
				),
				"top" => poolservices_get_sc_param('top'),
				"bottom" => poolservices_get_sc_param('bottom'),
				"left" => poolservices_get_sc_param('left'),
				"right" => poolservices_get_sc_param('right'),
				"id" => poolservices_get_sc_param('id'),
				"class" => poolservices_get_sc_param('class'),
				"animation" => poolservices_get_sc_param('animation'),
				"css" => poolservices_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'poolservices_sc_number_reg_shortcodes_vc' ) ) {
	//add_action('poolservices_action_shortcodes_list_vc', 'poolservices_sc_number_reg_shortcodes_vc');
	function poolservices_sc_number_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_number",
			"name" => esc_html__("Number", 'poolservices'),
			"description" => wp_kses_data( __("Insert number or any word as set of separated characters", 'poolservices') ),
			"category" => esc_html__('Content', 'poolservices'),
			"class" => "trx_sc_single trx_sc_number",
			'icon' => 'icon_trx_number',
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "value",
					"heading" => esc_html__("Value", 'poolservices'),
					"description" => wp_kses_data( __("Number or any word to separate", 'poolservices') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'poolservices'),
					"description" => wp_kses_data( __("Select block alignment", 'poolservices') ),
					"class" => "",
					"value" => array_flip(poolservices_get_sc_param('align')),
					"type" => "dropdown"
				),
				poolservices_get_vc_param('id'),
				poolservices_get_vc_param('class'),
				poolservices_get_vc_param('animation'),
				poolservices_get_vc_param('css'),
				poolservices_get_vc_param('margin_top'),
				poolservices_get_vc_param('margin_bottom'),
				poolservices_get_vc_param('margin_left'),
				poolservices_get_vc_param('margin_right')
			)
		) );
		
		class WPBakeryShortCode_Trx_Number extends POOLSERVICES_VC_ShortCodeSingle {}
	}
}
?>