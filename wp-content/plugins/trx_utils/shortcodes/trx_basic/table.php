<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('poolservices_sc_table_theme_setup')) {
	add_action( 'poolservices_action_before_init_theme', 'poolservices_sc_table_theme_setup' );
	function poolservices_sc_table_theme_setup() {
		add_action('poolservices_action_shortcodes_list', 		'poolservices_sc_table_reg_shortcodes');
		if (function_exists('poolservices_exists_visual_composer') && poolservices_exists_visual_composer())
			add_action('poolservices_action_shortcodes_list_vc','poolservices_sc_table_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_table id="unique_id" style="1"]
Table content, generated on one of many public internet resources, for example: http://www.impressivewebs.com/html-table-code-generator/
[/trx_table]
*/

if (!function_exists('poolservices_sc_table')) {	
	function poolservices_sc_table($atts, $content=null){	
		if (poolservices_in_shortcode_blogger()) return '';
		extract(poolservices_html_decode(shortcode_atts(array(
			// Individual params
			"align" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => "",
			"width" => "100%"
		), $atts)));
		$class .= ($class ? ' ' : '') . poolservices_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= poolservices_get_css_dimensions_from_values($width);
		$content = str_replace(
					array('<p><table', 'table></p>', '><br />'),
					array('<table', 'table>', '>'),
					html_entity_decode($content, ENT_COMPAT, 'UTF-8'));
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_table' 
					. (!empty($align) && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. '"'
				. (!poolservices_param_is_off($animation) ? ' data-animation="'.esc_attr(poolservices_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				.'>' 
				. do_shortcode($content) 
				. '</div>';
		return apply_filters('poolservices_shortcode_output', $output, 'trx_table', $atts, $content);
	}
	poolservices_require_shortcode('trx_table', 'poolservices_sc_table');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'poolservices_sc_table_reg_shortcodes' ) ) {
	//add_action('poolservices_action_shortcodes_list', 'poolservices_sc_table_reg_shortcodes');
	function poolservices_sc_table_reg_shortcodes() {
	
		poolservices_sc_map("trx_table", array(
			"title" => esc_html__("Table", 'poolservices'),
			"desc" => wp_kses_data( __("Insert a table into post (page). ", 'poolservices') ),
			"decorate" => true,
			"container" => true,
			"params" => array(
				"align" => array(
					"title" => esc_html__("Content alignment", 'poolservices'),
					"desc" => wp_kses_data( __("Select alignment for each table cell", 'poolservices') ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => poolservices_get_sc_param('align')
				),
				"_content_" => array(
					"title" => esc_html__("Table content", 'poolservices'),
					"desc" => wp_kses_data( __("Content, created with any table-generator", 'poolservices') ),
					"divider" => true,
					"rows" => 8,
					"value" => "Paste here table content, generated on one of many public internet resources, for example: http://www.impressivewebs.com/html-table-code-generator/ or http://html-tables.com/",
					"type" => "textarea"
				),
				"width" => poolservices_shortcodes_width(),
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
if ( !function_exists( 'poolservices_sc_table_reg_shortcodes_vc' ) ) {
	//add_action('poolservices_action_shortcodes_list_vc', 'poolservices_sc_table_reg_shortcodes_vc');
	function poolservices_sc_table_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_table",
			"name" => esc_html__("Table", 'poolservices'),
			"description" => wp_kses_data( __("Insert a table", 'poolservices') ),
			"category" => esc_html__('Content', 'poolservices'),
			'icon' => 'icon_trx_table',
			"class" => "trx_sc_container trx_sc_table",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "align",
					"heading" => esc_html__("Cells content alignment", 'poolservices'),
					"description" => wp_kses_data( __("Select alignment for each table cell", 'poolservices') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(poolservices_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "content",
					"heading" => esc_html__("Table content", 'poolservices'),
					"description" => wp_kses_data( __("Content, created with any table-generator", 'poolservices') ),
					"class" => "",
					"value" => esc_html__("Paste here table content, generated on one of many public internet resources, for example: http://www.impressivewebs.com/html-table-code-generator/ or http://html-tables.com/", 'poolservices'),
					"type" => "textarea_html"
				),
				poolservices_get_vc_param('id'),
				poolservices_get_vc_param('class'),
				poolservices_get_vc_param('animation'),
				poolservices_get_vc_param('css'),
				poolservices_vc_width(),
				poolservices_vc_height(),
				poolservices_get_vc_param('margin_top'),
				poolservices_get_vc_param('margin_bottom'),
				poolservices_get_vc_param('margin_left'),
				poolservices_get_vc_param('margin_right')
			),
			'js_view' => 'VcTrxTextContainerView'
		) );
		
		class WPBakeryShortCode_Trx_Table extends POOLSERVICES_VC_ShortCodeContainer {}
	}
}
?>