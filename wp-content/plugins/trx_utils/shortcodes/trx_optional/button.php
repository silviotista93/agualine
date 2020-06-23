<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('poolservices_sc_button_theme_setup')) {
	add_action( 'poolservices_action_before_init_theme', 'poolservices_sc_button_theme_setup' );
	function poolservices_sc_button_theme_setup() {
		add_action('poolservices_action_shortcodes_list', 		'poolservices_sc_button_reg_shortcodes');
		if (function_exists('poolservices_exists_visual_composer') && poolservices_exists_visual_composer())
			add_action('poolservices_action_shortcodes_list_vc','poolservices_sc_button_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_button id="unique_id" type="square|round" fullsize="0|1" style="global|light|dark" size="mini|medium|big|huge|banner" icon="icon-name" link='#' target='']Button caption[/trx_button]
*/

if (!function_exists('poolservices_sc_button')) {	
	function poolservices_sc_button($atts, $content=null){	
		if (poolservices_in_shortcode_blogger()) return '';
		extract(poolservices_html_decode(shortcode_atts(array(
			// Individual params
			"type" => "square",
			"style" => "filled",
			"size" => "small",
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			"link" => "",
			"target" => "",
			"align" => "",
			"rel" => "",
			"popup" => "no",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . poolservices_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= poolservices_get_css_dimensions_from_values($width, $height)
			. ($color !== '' ? 'color:' . esc_attr($color) .';' : '')
			. ($bg_color !== '' ? 'background-color:' . esc_attr($bg_color) . '; border-color:'. esc_attr($bg_color) .';' : '');
		if (poolservices_param_is_on($popup)) poolservices_enqueue_popup('magnific');
		$output = '<a href="' . (empty($link) ? '#' : $link) . '"'
			. (!empty($target) ? ' target="'.esc_attr($target).'"' : '')
			. (!empty($rel) ? ' rel="'.esc_attr($rel).'"' : '')
			. (!poolservices_param_is_off($animation) ? ' data-animation="'.esc_attr(poolservices_get_animation_classes($animation)).'"' : '')
			. ' class="sc_button sc_button_' . esc_attr($type) 
					. ' sc_button_style_' . esc_attr($style) 
					. ' sc_button_size_' . esc_attr($size)
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. ($icon!='' ? '  sc_button_iconed '. esc_attr($icon) : '') 
					. (poolservices_param_is_on($popup) ? ' sc_popup_link' : '') 
					. '"'
			. ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
			. '>'
			. do_shortcode($content)
			. '</a>';
		return apply_filters('poolservices_shortcode_output', $output, 'trx_button', $atts, $content);
	}
	poolservices_require_shortcode('trx_button', 'poolservices_sc_button');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'poolservices_sc_button_reg_shortcodes' ) ) {
	//add_action('poolservices_action_shortcodes_list', 'poolservices_sc_button_reg_shortcodes');
	function poolservices_sc_button_reg_shortcodes() {
	
		poolservices_sc_map("trx_button", array(
			"title" => esc_html__("Button", 'poolservices'),
			"desc" => wp_kses_data( __("Button with link", 'poolservices') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Caption", 'poolservices'),
					"desc" => wp_kses_data( __("Button caption", 'poolservices') ),
					"value" => "",
					"type" => "text"
				),
				"type" => array(
					"title" => esc_html__("Button's shape", 'poolservices'),
					"desc" => wp_kses_data( __("Select button's shape", 'poolservices') ),
					"value" => "square",
					"size" => "medium",
					"options" => array(
						'square' => esc_html__('Square', 'poolservices'),
						'round' => esc_html__('Round', 'poolservices')
					),
					"type" => "switch"
				), 
				"style" => array(
					"title" => esc_html__("Button's style", 'poolservices'),
					"desc" => wp_kses_data( __("Select button's style", 'poolservices') ),
					"value" => "default",
					"dir" => "horizontal",
					"options" => array(
						'filled' => esc_html__('Filled', 'poolservices'),
						'border' => esc_html__('Border', 'poolservices')
					),
					"type" => "checklist"
				), 
				"size" => array(
					"title" => esc_html__("Button's size", 'poolservices'),
					"desc" => wp_kses_data( __("Select button's size", 'poolservices') ),
					"value" => "small",
					"dir" => "horizontal",
					"options" => array(
						'small' => esc_html__('Small', 'poolservices'),
						'medium' => esc_html__('Medium', 'poolservices'),
						'large' => esc_html__('Large', 'poolservices')
					),
					"type" => "checklist"
				), 
				"icon" => array(
					"title" => esc_html__("Button's icon",  'poolservices'),
					"desc" => wp_kses_data( __('Select icon for the title from Fontello icons set',  'poolservices') ),
					"value" => "",
					"type" => "icons",
					"options" => poolservices_get_sc_param('icons')
				),
				"color" => array(
					"title" => esc_html__("Button's text color", 'poolservices'),
					"desc" => wp_kses_data( __("Any color for button's caption", 'poolservices') ),
					"std" => "",
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Button's backcolor", 'poolservices'),
					"desc" => wp_kses_data( __("Any color for button's background", 'poolservices') ),
					"value" => "",
					"type" => "color"
				),
				"align" => array(
					"title" => esc_html__("Button's alignment", 'poolservices'),
					"desc" => wp_kses_data( __("Align button to left, center or right", 'poolservices') ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => poolservices_get_sc_param('align')
				), 
				"link" => array(
					"title" => esc_html__("Link URL", 'poolservices'),
					"desc" => wp_kses_data( __("URL for link on button click", 'poolservices') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"target" => array(
					"title" => esc_html__("Link target", 'poolservices'),
					"desc" => wp_kses_data( __("Target for link on button click", 'poolservices') ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"popup" => array(
					"title" => esc_html__("Open link in popup", 'poolservices'),
					"desc" => wp_kses_data( __("Open link target in popup window", 'poolservices') ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "no",
					"type" => "switch",
					"options" => poolservices_get_sc_param('yes_no')
				), 
				"rel" => array(
					"title" => esc_html__("Rel attribute", 'poolservices'),
					"desc" => wp_kses_data( __("Rel attribute for button's link (if need)", 'poolservices') ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"width" => poolservices_shortcodes_width(),
				"height" => poolservices_shortcodes_height(),
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
if ( !function_exists( 'poolservices_sc_button_reg_shortcodes_vc' ) ) {
	//add_action('poolservices_action_shortcodes_list_vc', 'poolservices_sc_button_reg_shortcodes_vc');
	function poolservices_sc_button_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_button",
			"name" => esc_html__("Button", 'poolservices'),
			"description" => wp_kses_data( __("Button with link", 'poolservices') ),
			"category" => esc_html__('Content', 'poolservices'),
			'icon' => 'icon_trx_button',
			"class" => "trx_sc_single trx_sc_button",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "content",
					"heading" => esc_html__("Caption", 'poolservices'),
					"description" => wp_kses_data( __("Button caption", 'poolservices') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "type",
					"heading" => esc_html__("Button's shape", 'poolservices'),
					"description" => wp_kses_data( __("Select button's shape", 'poolservices') ),
					"class" => "",
					"value" => array(
						esc_html__('Square', 'poolservices') => 'square',
						esc_html__('Round', 'poolservices') => 'round'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Button's style", 'poolservices'),
					"description" => wp_kses_data( __("Select button's style", 'poolservices') ),
					"class" => "",
					"value" => array(
						esc_html__('Filled', 'poolservices') => 'filled',
						esc_html__('Border', 'poolservices') => 'border'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "size",
					"heading" => esc_html__("Button's size", 'poolservices'),
					"description" => wp_kses_data( __("Select button's size", 'poolservices') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Small', 'poolservices') => 'small',
						esc_html__('Medium', 'poolservices') => 'medium',
						esc_html__('Large', 'poolservices') => 'large'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Button's icon", 'poolservices'),
					"description" => wp_kses_data( __("Select icon for the title from Fontello icons set", 'poolservices') ),
					"class" => "",
					"value" => poolservices_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Button's text color", 'poolservices'),
					"description" => wp_kses_data( __("Any color for button's caption", 'poolservices') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Button's backcolor", 'poolservices'),
					"description" => wp_kses_data( __("Any color for button's background", 'poolservices') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Button's alignment", 'poolservices'),
					"description" => wp_kses_data( __("Align button to left, center or right", 'poolservices') ),
					"class" => "",
					"value" => array_flip(poolservices_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'poolservices'),
					"description" => wp_kses_data( __("URL for the link on button click", 'poolservices') ),
					"class" => "",
					"group" => esc_html__('Link', 'poolservices'),
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "target",
					"heading" => esc_html__("Link target", 'poolservices'),
					"description" => wp_kses_data( __("Target for the link on button click", 'poolservices') ),
					"class" => "",
					"group" => esc_html__('Link', 'poolservices'),
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "popup",
					"heading" => esc_html__("Open link in popup", 'poolservices'),
					"description" => wp_kses_data( __("Open link target in popup window", 'poolservices') ),
					"class" => "",
					"group" => esc_html__('Link', 'poolservices'),
					"value" => array(esc_html__('Open in popup', 'poolservices') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "rel",
					"heading" => esc_html__("Rel attribute", 'poolservices'),
					"description" => wp_kses_data( __("Rel attribute for the button's link (if need", 'poolservices') ),
					"class" => "",
					"group" => esc_html__('Link', 'poolservices'),
					"value" => "",
					"type" => "textfield"
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
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Button extends POOLSERVICES_VC_ShortCodeSingle {}
	}
}
?>