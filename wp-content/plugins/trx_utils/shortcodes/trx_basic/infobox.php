<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('poolservices_sc_infobox_theme_setup')) {
	add_action( 'poolservices_action_before_init_theme', 'poolservices_sc_infobox_theme_setup' );
	function poolservices_sc_infobox_theme_setup() {
		add_action('poolservices_action_shortcodes_list', 		'poolservices_sc_infobox_reg_shortcodes');
		if (function_exists('poolservices_exists_visual_composer') && poolservices_exists_visual_composer())
			add_action('poolservices_action_shortcodes_list_vc','poolservices_sc_infobox_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_infobox id="unique_id" style="regular|info|success|error|result" static="0|1"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_infobox]
*/

if (!function_exists('poolservices_sc_infobox')) {	
	function poolservices_sc_infobox($atts, $content=null){	
		if (poolservices_in_shortcode_blogger()) return '';
		extract(poolservices_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "regular",
			"closeable" => "no",
			"icon" => "",
			"color" => "",
			"bg_color" => "",
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
		$css .= ($color !== '' ? 'color:' . esc_attr($color) .';' : '')
			. ($bg_color !== '' ? 'background-color:' . esc_attr($bg_color) .';' : '');
		if (empty($icon)) {
			if ($style=='regular')
				$icon = 'icon-cog';
			else if ($style=='success')
				$icon = 'icon-check';
			else if ($style=='error')
				$icon = 'icon-attention';
			else if ($style=='info')
				$icon = 'icon-info';
		} else if ($icon=='none')
			$icon = '';

		$content = do_shortcode($content);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_infobox sc_infobox_style_' . esc_attr($style) 
					. (poolservices_param_is_on($closeable) ? ' sc_infobox_closeable' : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. ($icon!='' && !poolservices_param_is_inherit($icon) ? ' sc_infobox_iconed '. esc_attr($icon) : '') 
					. '"'
				. (!poolservices_param_is_off($animation) ? ' data-animation="'.esc_attr(poolservices_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>'
				. trim($content)
				. '</div>';
		return apply_filters('poolservices_shortcode_output', $output, 'trx_infobox', $atts, $content);
	}
	poolservices_require_shortcode('trx_infobox', 'poolservices_sc_infobox');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'poolservices_sc_infobox_reg_shortcodes' ) ) {
	//add_action('poolservices_action_shortcodes_list', 'poolservices_sc_infobox_reg_shortcodes');
	function poolservices_sc_infobox_reg_shortcodes() {
	
		poolservices_sc_map("trx_infobox", array(
			"title" => esc_html__("Infobox", 'poolservices'),
			"desc" => wp_kses_data( __("Insert infobox into your post (page)", 'poolservices') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", 'poolservices'),
					"desc" => wp_kses_data( __("Infobox style", 'poolservices') ),
					"value" => "regular",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => array(
						'regular' => esc_html__('Regular', 'poolservices'),
						'info' => esc_html__('Info', 'poolservices'),
						'success' => esc_html__('Success', 'poolservices'),
						'error' => esc_html__('Error', 'poolservices')
					)
				),
				"closeable" => array(
					"title" => esc_html__("Closeable box", 'poolservices'),
					"desc" => wp_kses_data( __("Create closeable box (with close button)", 'poolservices') ),
					"value" => "no",
					"type" => "switch",
					"options" => poolservices_get_sc_param('yes_no')
				),
				"icon" => array(
					"title" => esc_html__("Custom icon",  'poolservices'),
					"desc" => wp_kses_data( __('Select icon for the infobox from Fontello icons set. If empty - use default icon',  'poolservices') ),
					"value" => "",
					"type" => "icons",
					"options" => poolservices_get_sc_param('icons')
				),
				"color" => array(
					"title" => esc_html__("Text color", 'poolservices'),
					"desc" => wp_kses_data( __("Any color for text and headers", 'poolservices') ),
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", 'poolservices'),
					"desc" => wp_kses_data( __("Any background color for this infobox", 'poolservices') ),
					"value" => "",
					"type" => "color"
				),
				"_content_" => array(
					"title" => esc_html__("Infobox content", 'poolservices'),
					"desc" => wp_kses_data( __("Content for infobox", 'poolservices') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
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
if ( !function_exists( 'poolservices_sc_infobox_reg_shortcodes_vc' ) ) {
	//add_action('poolservices_action_shortcodes_list_vc', 'poolservices_sc_infobox_reg_shortcodes_vc');
	function poolservices_sc_infobox_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_infobox",
			"name" => esc_html__("Infobox", 'poolservices'),
			"description" => wp_kses_data( __("Box with info or error message", 'poolservices') ),
			"category" => esc_html__('Content', 'poolservices'),
			'icon' => 'icon_trx_infobox',
			"class" => "trx_sc_container trx_sc_infobox",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'poolservices'),
					"description" => wp_kses_data( __("Infobox style", 'poolservices') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
							esc_html__('Regular', 'poolservices') => 'regular',
							esc_html__('Info', 'poolservices') => 'info',
							esc_html__('Success', 'poolservices') => 'success',
							esc_html__('Error', 'poolservices') => 'error',
							esc_html__('Result', 'poolservices') => 'result'
						),
					"type" => "dropdown"
				),
				array(
					"param_name" => "closeable",
					"heading" => esc_html__("Closeable", 'poolservices'),
					"description" => wp_kses_data( __("Create closeable box (with close button)", 'poolservices') ),
					"class" => "",
					"value" => array(esc_html__('Close button', 'poolservices') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Custom icon", 'poolservices'),
					"description" => wp_kses_data( __("Select icon for the infobox from Fontello icons set. If empty - use default icon", 'poolservices') ),
					"class" => "",
					"value" => poolservices_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", 'poolservices'),
					"description" => wp_kses_data( __("Any color for the text and headers", 'poolservices') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'poolservices'),
					"description" => wp_kses_data( __("Any background color for this infobox", 'poolservices') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				poolservices_get_vc_param('id'),
				poolservices_get_vc_param('class'),
				poolservices_get_vc_param('animation'),
				poolservices_get_vc_param('css'),
				poolservices_get_vc_param('margin_top'),
				poolservices_get_vc_param('margin_bottom'),
				poolservices_get_vc_param('margin_left'),
				poolservices_get_vc_param('margin_right')
			),
			'js_view' => 'VcTrxTextContainerView'
		) );
		
		class WPBakeryShortCode_Trx_Infobox extends POOLSERVICES_VC_ShortCodeContainer {}
	}
}
?>