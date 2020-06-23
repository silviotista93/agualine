<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('poolservices_sc_list_theme_setup')) {
	add_action( 'poolservices_action_before_init_theme', 'poolservices_sc_list_theme_setup' );
	function poolservices_sc_list_theme_setup() {
		add_action('poolservices_action_shortcodes_list', 		'poolservices_sc_list_reg_shortcodes');
		if (function_exists('poolservices_exists_visual_composer') && poolservices_exists_visual_composer())
			add_action('poolservices_action_shortcodes_list_vc','poolservices_sc_list_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_list id="unique_id" style="arrows|iconed|ol|ul"]
	[trx_list_item id="unique_id" title="title_of_element"]Et adipiscing integer.[/trx_list_item]
	[trx_list_item]A pulvinar ut, parturient enim porta ut sed, mus amet nunc, in.[/trx_list_item]
	[trx_list_item]Duis sociis, elit odio dapibus nec, dignissim purus est magna integer.[/trx_list_item]
	[trx_list_item]Nec purus, cras tincidunt rhoncus proin lacus porttitor rhoncus.[/trx_list_item]
[/trx_list]
*/

if (!function_exists('poolservices_sc_list')) {	
	function poolservices_sc_list($atts, $content=null){	
		if (poolservices_in_shortcode_blogger()) return '';
		extract(poolservices_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "ul",
			"icon" => "icon-right",
			"icon_color" => "",
			"color" => "",
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
		$css .= $color !== '' ? 'color:' . esc_attr($color) .';' : '';
		if (trim($style) == '' || (trim($icon) == '' && $style=='iconed')) $style = 'ul';
		poolservices_storage_set('sc_list_data', array(
			'counter' => 0,
            'icon' => empty($icon) || poolservices_param_is_inherit($icon) ? "icon-right" : $icon,
            'icon_color' => $icon_color,
            'style' => $style
            )
        );
		$output = '<' . ($style=='ol' ? 'ol' : 'ul')
				. ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_list sc_list_style_' . esc_attr($style) . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. (!poolservices_param_is_off($animation) ? ' data-animation="'.esc_attr(poolservices_get_animation_classes($animation)).'"' : '')
				. '>'
				. do_shortcode($content)
				. '</' .($style=='ol' ? 'ol' : 'ul') . '>';
		return apply_filters('poolservices_shortcode_output', $output, 'trx_list', $atts, $content);
	}
	poolservices_require_shortcode('trx_list', 'poolservices_sc_list');
}


if (!function_exists('poolservices_sc_list_item')) {	
	function poolservices_sc_list_item($atts, $content=null) {
		if (poolservices_in_shortcode_blogger()) return '';
		extract(poolservices_html_decode(shortcode_atts( array(
			// Individual params
			"color" => "",
			"icon" => "",
			"icon_color" => "",
			"title" => "",
			"link" => "",
			"target" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		poolservices_storage_inc_array('sc_list_data', 'counter');
		$css .= $color !== '' ? 'color:' . esc_attr($color) .';' : '';
		if (trim($icon) == '' || poolservices_param_is_inherit($icon)) $icon = poolservices_storage_get_array('sc_list_data', 'icon');
		if (trim($color) == '' || poolservices_param_is_inherit($icon_color)) $icon_color = poolservices_storage_get_array('sc_list_data', 'icon_color');
		$content = do_shortcode($content);
		if (empty($content)) $content = $title;
		$output = '<li' . ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ' class="sc_list_item' 
			. (!empty($class) ? ' '.esc_attr($class) : '')
			. (poolservices_storage_get_array('sc_list_data', 'counter') % 2 == 1 ? ' odd' : ' even') 
			. (poolservices_storage_get_array('sc_list_data', 'counter') == 1 ? ' first' : '')  
			. '"' 
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. ($title ? ' title="'.esc_attr($title).'"' : '') 
			. '>' 
			. (!empty($link) ? '<a href="'.esc_url($link).'"' . (!empty($target) ? ' target="'.esc_attr($target).'"' : '') . '>' : '')
			. (poolservices_storage_get_array('sc_list_data', 'style')=='iconed' && $icon!='' ? '<span class="sc_list_icon '.esc_attr($icon).'"'.($icon_color !== '' ? ' style="color:'.esc_attr($icon_color).';"' : '').'></span>' : '')
			. trim($content)
			. (!empty($link) ? '</a>': '')
			. '</li>';
		return apply_filters('poolservices_shortcode_output', $output, 'trx_list_item', $atts, $content);
	}
	poolservices_require_shortcode('trx_list_item', 'poolservices_sc_list_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'poolservices_sc_list_reg_shortcodes' ) ) {
	//add_action('poolservices_action_shortcodes_list', 'poolservices_sc_list_reg_shortcodes');
	function poolservices_sc_list_reg_shortcodes() {
	
		poolservices_sc_map("trx_list", array(
			"title" => esc_html__("List", 'poolservices'),
			"desc" => wp_kses_data( __("List items with specific bullets", 'poolservices') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Bullet's style", 'poolservices'),
					"desc" => wp_kses_data( __("Bullet's style for each list item", 'poolservices') ),
					"value" => "ul",
					"type" => "checklist",
					"options" => poolservices_get_sc_param('list_styles')
				), 
				"color" => array(
					"title" => esc_html__("Color", 'poolservices'),
					"desc" => wp_kses_data( __("List items color", 'poolservices') ),
					"value" => "",
					"type" => "color"
				),
				"icon" => array(
					"title" => esc_html__('List icon',  'poolservices'),
					"desc" => wp_kses_data( __("Select list icon from Fontello icons set (only for style=Iconed)",  'poolservices') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "icons",
					"options" => poolservices_get_sc_param('icons')
				),
				"icon_color" => array(
					"title" => esc_html__("Icon color", 'poolservices'),
					"desc" => wp_kses_data( __("List icons color", 'poolservices') ),
					"value" => "",
					"dependency" => array(
						'style' => array('iconed')
					),
					"type" => "color"
				),
				"top" => poolservices_get_sc_param('top'),
				"bottom" => poolservices_get_sc_param('bottom'),
				"left" => poolservices_get_sc_param('left'),
				"right" => poolservices_get_sc_param('right'),
				"id" => poolservices_get_sc_param('id'),
				"class" => poolservices_get_sc_param('class'),
				"animation" => poolservices_get_sc_param('animation'),
				"css" => poolservices_get_sc_param('css')
			),
			"children" => array(
				"name" => "trx_list_item",
				"title" => esc_html__("Item", 'poolservices'),
				"desc" => wp_kses_data( __("List item with specific bullet", 'poolservices') ),
				"decorate" => false,
				"container" => true,
				"params" => array(
					"_content_" => array(
						"title" => esc_html__("List item content", 'poolservices'),
						"desc" => wp_kses_data( __("Current list item content", 'poolservices') ),
						"rows" => 4,
						"value" => "",
						"type" => "textarea"
					),
					"title" => array(
						"title" => esc_html__("List item title", 'poolservices'),
						"desc" => wp_kses_data( __("Current list item title (show it as tooltip)", 'poolservices') ),
						"value" => "",
						"type" => "text"
					),
					"color" => array(
						"title" => esc_html__("Color", 'poolservices'),
						"desc" => wp_kses_data( __("Text color for this item", 'poolservices') ),
						"value" => "",
						"type" => "color"
					),
					"icon" => array(
						"title" => esc_html__('List icon',  'poolservices'),
						"desc" => wp_kses_data( __("Select list item icon from Fontello icons set (only for style=Iconed)",  'poolservices') ),
						"value" => "",
						"type" => "icons",
						"options" => poolservices_get_sc_param('icons')
					),
					"icon_color" => array(
						"title" => esc_html__("Icon color", 'poolservices'),
						"desc" => wp_kses_data( __("Icon color for this item", 'poolservices') ),
						"value" => "",
						"type" => "color"
					),
					"link" => array(
						"title" => esc_html__("Link URL", 'poolservices'),
						"desc" => wp_kses_data( __("Link URL for the current list item", 'poolservices') ),
						"divider" => true,
						"value" => "",
						"type" => "text"
					),
					"target" => array(
						"title" => esc_html__("Link target", 'poolservices'),
						"desc" => wp_kses_data( __("Link target for the current list item", 'poolservices') ),
						"value" => "",
						"type" => "text"
					),
					"id" => poolservices_get_sc_param('id'),
					"class" => poolservices_get_sc_param('class'),
					"css" => poolservices_get_sc_param('css')
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'poolservices_sc_list_reg_shortcodes_vc' ) ) {
	//add_action('poolservices_action_shortcodes_list_vc', 'poolservices_sc_list_reg_shortcodes_vc');
	function poolservices_sc_list_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_list",
			"name" => esc_html__("List", 'poolservices'),
			"description" => wp_kses_data( __("List items with specific bullets", 'poolservices') ),
			"category" => esc_html__('Content', 'poolservices'),
			"class" => "trx_sc_collection trx_sc_list",
			'icon' => 'icon_trx_list',
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => false,
			"as_parent" => array('only' => 'trx_list_item'),
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Bullet's style", 'poolservices'),
					"description" => wp_kses_data( __("Bullet's style for each list item", 'poolservices') ),
					"class" => "",
					"admin_label" => true,
					"value" => array_flip(poolservices_get_sc_param('list_styles')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Color", 'poolservices'),
					"description" => wp_kses_data( __("List items color", 'poolservices') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("List icon", 'poolservices'),
					"description" => wp_kses_data( __("Select list icon from Fontello icons set (only for style=Iconed)", 'poolservices') ),
					"admin_label" => true,
					"class" => "",
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => poolservices_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon_color",
					"heading" => esc_html__("Icon color", 'poolservices'),
					"description" => wp_kses_data( __("List icons color", 'poolservices') ),
					"class" => "",
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
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
			'default_content' => '
				[trx_list_item][/trx_list_item]
				[trx_list_item][/trx_list_item]
			'
		) );
		
		
		vc_map( array(
			"base" => "trx_list_item",
			"name" => esc_html__("List item", 'poolservices'),
			"description" => wp_kses_data( __("List item with specific bullet", 'poolservices') ),
			"class" => "trx_sc_container trx_sc_list_item",
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => true,
			'icon' => 'icon_trx_list_item',
			"as_child" => array('only' => 'trx_list'), // Use only|except attributes to limit parent (separate multiple values with comma)
			"as_parent" => array('except' => 'trx_list'),
			"params" => array(
				array(
					"param_name" => "title",
					"heading" => esc_html__("List item title", 'poolservices'),
					"description" => wp_kses_data( __("Title for the current list item (show it as tooltip)", 'poolservices') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'poolservices'),
					"description" => wp_kses_data( __("Link URL for the current list item", 'poolservices') ),
					"admin_label" => true,
					"group" => esc_html__('Link', 'poolservices'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "target",
					"heading" => esc_html__("Link target", 'poolservices'),
					"description" => wp_kses_data( __("Link target for the current list item", 'poolservices') ),
					"admin_label" => true,
					"group" => esc_html__('Link', 'poolservices'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Color", 'poolservices'),
					"description" => wp_kses_data( __("Text color for this item", 'poolservices') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("List item icon", 'poolservices'),
					"description" => wp_kses_data( __("Select list item icon from Fontello icons set (only for style=Iconed)", 'poolservices') ),
					"admin_label" => true,
					"class" => "",
					"value" => poolservices_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon_color",
					"heading" => esc_html__("Icon color", 'poolservices'),
					"description" => wp_kses_data( __("Icon color for this item", 'poolservices') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				poolservices_get_vc_param('id'),
				poolservices_get_vc_param('class'),
				poolservices_get_vc_param('css')
			)
		
		) );
		
		class WPBakeryShortCode_Trx_List extends POOLSERVICES_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_List_Item extends POOLSERVICES_VC_ShortCodeContainer {}
	}
}
?>