<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('poolservices_sc_icon_theme_setup')) {
	add_action( 'poolservices_action_before_init_theme', 'poolservices_sc_icon_theme_setup' );
	function poolservices_sc_icon_theme_setup() {
		add_action('poolservices_action_shortcodes_list', 		'poolservices_sc_icon_reg_shortcodes');
		if (function_exists('poolservices_exists_visual_composer') && poolservices_exists_visual_composer())
			add_action('poolservices_action_shortcodes_list_vc','poolservices_sc_icon_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_icon id="unique_id" style='round|square' icon='' color="" bg_color="" size="" weight=""]
*/

if (!function_exists('poolservices_sc_icon')) {	
	function poolservices_sc_icon($atts, $content=null){	
		if (poolservices_in_shortcode_blogger()) return '';
		extract(poolservices_html_decode(shortcode_atts(array(
			// Individual params
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			"bg_shape" => "",
			"font_size" => "",
			"font_weight" => "",
			"align" => "",
			"link" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . poolservices_get_css_position_as_classes($top, $right, $bottom, $left);
		$css2 = ($font_weight != '' && !poolservices_is_inherit_option($font_weight) ? 'font-weight:'. esc_attr($font_weight).';' : '')
			. ($font_size != '' ? 'font-size:' . esc_attr(poolservices_prepare_css_value($font_size)) . '; line-height: ' . (!$bg_shape || poolservices_param_is_inherit($bg_shape) ? '1' : '1.2') . 'em;' : '')
			. ($color != '' ? 'color:'.esc_attr($color).';' : '')
			. ($bg_color != '' ? 'background-color:'.esc_attr($bg_color).';border-color:'.esc_attr($bg_color).';' : '')
		;
		$output = $icon!='' 
			? ($link ? '<a href="'.esc_url($link).'"' : '<span') . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_icon '.esc_attr($icon)
					. ($bg_shape && !poolservices_param_is_inherit($bg_shape) ? ' sc_icon_shape_'.esc_attr($bg_shape) : '')
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '')
				.'"'
				.($css || $css2 ? ' style="'.($class ? 'display:block;' : '') . ($css) . ($css2) . '"' : '')
				.'>'
				.($link ? '</a>' : '</span>')
			: '';
		return apply_filters('poolservices_shortcode_output', $output, 'trx_icon', $atts, $content);
	}
	poolservices_require_shortcode('trx_icon', 'poolservices_sc_icon');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'poolservices_sc_icon_reg_shortcodes' ) ) {
	//add_action('poolservices_action_shortcodes_list', 'poolservices_sc_icon_reg_shortcodes');
	function poolservices_sc_icon_reg_shortcodes() {
	
		poolservices_sc_map("trx_icon", array(
			"title" => esc_html__("Icon", 'poolservices'),
			"desc" => wp_kses_data( __("Insert icon", 'poolservices') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"icon" => array(
					"title" => esc_html__('Icon',  'poolservices'),
					"desc" => wp_kses_data( __('Select font icon from the Fontello icons set',  'poolservices') ),
					"value" => "",
					"type" => "icons",
					"options" => poolservices_get_sc_param('icons')
				),
				"color" => array(
					"title" => esc_html__("Icon's color", 'poolservices'),
					"desc" => wp_kses_data( __("Icon's color", 'poolservices') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "color"
				),
				"bg_shape" => array(
					"title" => esc_html__("Background shape", 'poolservices'),
					"desc" => wp_kses_data( __("Shape of the icon background", 'poolservices') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "none",
					"type" => "radio",
					"options" => array(
						'none' => esc_html__('None', 'poolservices'),
						'round' => esc_html__('Round', 'poolservices'),
						'square' => esc_html__('Square', 'poolservices')
					)
				),
				"bg_color" => array(
					"title" => esc_html__("Icon's background color", 'poolservices'),
					"desc" => wp_kses_data( __("Icon's background color", 'poolservices') ),
					"dependency" => array(
						'icon' => array('not_empty'),
						'background' => array('round','square')
					),
					"value" => "",
					"type" => "color"
				),
				"font_size" => array(
					"title" => esc_html__("Font size", 'poolservices'),
					"desc" => wp_kses_data( __("Icon's font size", 'poolservices') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "spinner",
					"min" => 8,
					"max" => 240
				),
				"font_weight" => array(
					"title" => esc_html__("Font weight", 'poolservices'),
					"desc" => wp_kses_data( __("Icon font weight", 'poolservices') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "select",
					"size" => "medium",
					"options" => array(
						'100' => esc_html__('Thin (100)', 'poolservices'),
						'300' => esc_html__('Light (300)', 'poolservices'),
						'400' => esc_html__('Normal (400)', 'poolservices'),
						'700' => esc_html__('Bold (700)', 'poolservices')
					)
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'poolservices'),
					"desc" => wp_kses_data( __("Icon text alignment", 'poolservices') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => poolservices_get_sc_param('align')
				), 
				"link" => array(
					"title" => esc_html__("Link URL", 'poolservices'),
					"desc" => wp_kses_data( __("Link URL from this icon (if not empty)", 'poolservices') ),
					"value" => "",
					"type" => "text"
				),
				"top" => poolservices_get_sc_param('top'),
				"bottom" => poolservices_get_sc_param('bottom'),
				"left" => poolservices_get_sc_param('left'),
				"right" => poolservices_get_sc_param('right'),
				"id" => poolservices_get_sc_param('id'),
				"class" => poolservices_get_sc_param('class'),
				"css" => poolservices_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'poolservices_sc_icon_reg_shortcodes_vc' ) ) {
	//add_action('poolservices_action_shortcodes_list_vc', 'poolservices_sc_icon_reg_shortcodes_vc');
	function poolservices_sc_icon_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_icon",
			"name" => esc_html__("Icon", 'poolservices'),
			"description" => wp_kses_data( __("Insert the icon", 'poolservices') ),
			"category" => esc_html__('Content', 'poolservices'),
			'icon' => 'icon_trx_icon',
			"class" => "trx_sc_single trx_sc_icon",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Icon", 'poolservices'),
					"description" => wp_kses_data( __("Select icon class from Fontello icons set", 'poolservices') ),
					"admin_label" => true,
					"class" => "",
					"value" => poolservices_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", 'poolservices'),
					"description" => wp_kses_data( __("Icon's color", 'poolservices') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'poolservices'),
					"description" => wp_kses_data( __("Background color for the icon", 'poolservices') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_shape",
					"heading" => esc_html__("Background shape", 'poolservices'),
					"description" => wp_kses_data( __("Shape of the icon background", 'poolservices') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('None', 'poolservices') => 'none',
						esc_html__('Round', 'poolservices') => 'round',
						esc_html__('Square', 'poolservices') => 'square'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'poolservices'),
					"description" => wp_kses_data( __("Icon's font size", 'poolservices') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "font_weight",
					"heading" => esc_html__("Font weight", 'poolservices'),
					"description" => wp_kses_data( __("Icon's font weight", 'poolservices') ),
					"class" => "",
					"value" => array(
						esc_html__('Default', 'poolservices') => 'inherit',
						esc_html__('Thin (100)', 'poolservices') => '100',
						esc_html__('Light (300)', 'poolservices') => '300',
						esc_html__('Normal (400)', 'poolservices') => '400',
						esc_html__('Bold (700)', 'poolservices') => '700'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Icon's alignment", 'poolservices'),
					"description" => wp_kses_data( __("Align icon to left, center or right", 'poolservices') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(poolservices_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'poolservices'),
					"description" => wp_kses_data( __("Link URL from this icon (if not empty)", 'poolservices') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				poolservices_get_vc_param('id'),
				poolservices_get_vc_param('class'),
				poolservices_get_vc_param('css'),
				poolservices_get_vc_param('margin_top'),
				poolservices_get_vc_param('margin_bottom'),
				poolservices_get_vc_param('margin_left'),
				poolservices_get_vc_param('margin_right')
			),
		) );
		
		class WPBakeryShortCode_Trx_Icon extends POOLSERVICES_VC_ShortCodeSingle {}
	}
}
?>