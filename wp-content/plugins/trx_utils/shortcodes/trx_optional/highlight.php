<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('poolservices_sc_highlight_theme_setup')) {
	add_action( 'poolservices_action_before_init_theme', 'poolservices_sc_highlight_theme_setup' );
	function poolservices_sc_highlight_theme_setup() {
		add_action('poolservices_action_shortcodes_list', 		'poolservices_sc_highlight_reg_shortcodes');
		if (function_exists('poolservices_exists_visual_composer') && poolservices_exists_visual_composer())
			add_action('poolservices_action_shortcodes_list_vc','poolservices_sc_highlight_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_highlight id="unique_id" color="fore_color's_name_or_#rrggbb" backcolor="back_color's_name_or_#rrggbb" style="custom_style"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_highlight]
*/

if (!function_exists('poolservices_sc_highlight')) {	
	function poolservices_sc_highlight($atts, $content=null){	
		if (poolservices_in_shortcode_blogger()) return '';
		extract(poolservices_html_decode(shortcode_atts(array(
			// Individual params
			"color" => "",
			"bg_color" => "",
			"font_size" => "",
			"type" => "1",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		$css .= ($color != '' ? 'color:' . esc_attr($color) . ';' : '')
			.($bg_color != '' ? 'background-color:' . esc_attr($bg_color) . ';' : '')
			.($font_size != '' ? 'font-size:' . esc_attr(poolservices_prepare_css_value($font_size)) . '; line-height: 1em;' : '');
		$output = '<span' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_highlight'.($type>0 ? ' sc_highlight_style_'.esc_attr($type) : ''). (!empty($class) ? ' '.esc_attr($class) : '').'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>' 
				. do_shortcode($content) 
				. '</span>';
		return apply_filters('poolservices_shortcode_output', $output, 'trx_highlight', $atts, $content);
	}
	poolservices_require_shortcode('trx_highlight', 'poolservices_sc_highlight');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'poolservices_sc_highlight_reg_shortcodes' ) ) {
	//add_action('poolservices_action_shortcodes_list', 'poolservices_sc_highlight_reg_shortcodes');
	function poolservices_sc_highlight_reg_shortcodes() {
	
		poolservices_sc_map("trx_highlight", array(
			"title" => esc_html__("Highlight text", 'poolservices'),
			"desc" => wp_kses_data( __("Highlight text with selected color, background color and other styles", 'poolservices') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"type" => array(
					"title" => esc_html__("Type", 'poolservices'),
					"desc" => wp_kses_data( __("Highlight type", 'poolservices') ),
					"value" => "1",
					"type" => "checklist",
					"options" => array(
						0 => esc_html__('Custom', 'poolservices'),
						1 => esc_html__('Type 1', 'poolservices'),
						2 => esc_html__('Type 2', 'poolservices'),
						3 => esc_html__('Type 3', 'poolservices')
					)
				),
				"color" => array(
					"title" => esc_html__("Color", 'poolservices'),
					"desc" => wp_kses_data( __("Color for the highlighted text", 'poolservices') ),
					"divider" => true,
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", 'poolservices'),
					"desc" => wp_kses_data( __("Background color for the highlighted text", 'poolservices') ),
					"value" => "",
					"type" => "color"
				),
				"font_size" => array(
					"title" => esc_html__("Font size", 'poolservices'),
					"desc" => wp_kses_data( __("Font size of the highlighted text (default - in pixels, allows any CSS units of measure)", 'poolservices') ),
					"value" => "",
					"type" => "text"
				),
				"_content_" => array(
					"title" => esc_html__("Highlighting content", 'poolservices'),
					"desc" => wp_kses_data( __("Content for highlight", 'poolservices') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"id" => poolservices_get_sc_param('id'),
				"class" => poolservices_get_sc_param('class'),
				"css" => poolservices_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'poolservices_sc_highlight_reg_shortcodes_vc' ) ) {
	//add_action('poolservices_action_shortcodes_list_vc', 'poolservices_sc_highlight_reg_shortcodes_vc');
	function poolservices_sc_highlight_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_highlight",
			"name" => esc_html__("Highlight text", 'poolservices'),
			"description" => wp_kses_data( __("Highlight text with selected color, background color and other styles", 'poolservices') ),
			"category" => esc_html__('Content', 'poolservices'),
			'icon' => 'icon_trx_highlight',
			"class" => "trx_sc_single trx_sc_highlight",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "type",
					"heading" => esc_html__("Type", 'poolservices'),
					"description" => wp_kses_data( __("Highlight type", 'poolservices') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
							esc_html__('Custom', 'poolservices') => 0,
							esc_html__('Type 1', 'poolservices') => 1,
							esc_html__('Type 2', 'poolservices') => 2,
							esc_html__('Type 3', 'poolservices') => 3
						),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", 'poolservices'),
					"description" => wp_kses_data( __("Color for the highlighted text", 'poolservices') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'poolservices'),
					"description" => wp_kses_data( __("Background color for the highlighted text", 'poolservices') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'poolservices'),
					"description" => wp_kses_data( __("Font size for the highlighted text (default - in pixels, allows any CSS units of measure)", 'poolservices') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "content",
					"heading" => esc_html__("Highlight text", 'poolservices'),
					"description" => wp_kses_data( __("Content for highlight", 'poolservices') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				poolservices_get_vc_param('id'),
				poolservices_get_vc_param('class'),
				poolservices_get_vc_param('css')
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Highlight extends POOLSERVICES_VC_ShortCodeSingle {}
	}
}
?>