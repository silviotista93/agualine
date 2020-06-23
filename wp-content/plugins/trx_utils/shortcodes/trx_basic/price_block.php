<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('poolservices_sc_price_block_theme_setup')) {
	add_action( 'poolservices_action_before_init_theme', 'poolservices_sc_price_block_theme_setup' );
	function poolservices_sc_price_block_theme_setup() {
		add_action('poolservices_action_shortcodes_list', 		'poolservices_sc_price_block_reg_shortcodes');
		if (function_exists('poolservices_exists_visual_composer') && poolservices_exists_visual_composer())
			add_action('poolservices_action_shortcodes_list_vc','poolservices_sc_price_block_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

if (!function_exists('poolservices_sc_price_block')) {	
	function poolservices_sc_price_block($atts, $content=null){	
		if (poolservices_in_shortcode_blogger()) return '';
		extract(poolservices_html_decode(shortcode_atts(array(
			// Individual params
			"style" => 1,
			"title" => "",
			"link" => "",
			"link_text" => "",
			"icon" => "",
			"money" => "",
			"currency" => "$",
			"period" => "",
			"align" => "",
			"scheme" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$output = '';
		$class .= ($class ? ' ' : '') . poolservices_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= poolservices_get_css_dimensions_from_values($width, $height);
		if ($money) $money = do_shortcode('[trx_price money="'.esc_attr($money).'" period="'.esc_attr($period).'"'.($currency ? ' currency="'.esc_attr($currency).'"' : '').']');
		$content = do_shortcode(poolservices_sc_clear_around($content));
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_price_block sc_price_block_style_'.max(1, min(3, $style))
						. (!empty($class) ? ' '.esc_attr($class) : '')
						. ($scheme && !poolservices_param_is_off($scheme) && !poolservices_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
						. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
						. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!poolservices_param_is_off($animation) ? ' data-animation="'.esc_attr(poolservices_get_animation_classes($animation)).'"' : '')
					. '>'
				. (!empty($title) ? '<div class="sc_price_block_title"><span>'.($title).'</span></div>' : '')
				. '<div class="sc_price_block_money">'
					. (!empty($icon) ? '<div class="sc_price_block_icon '.esc_attr($icon).'"></div>' : '')
					. ($money)
				. '</div>'
				. (!empty($content) ? '<div class="sc_price_block_description">'.($content).'</div>' : '')
				. (!empty($link_text) ? '<div class="sc_price_block_link">'.do_shortcode('[trx_button link="'.($link ? esc_url($link) : '#').'" size="large"]'.($link_text).'[/trx_button]').'</div>' : '')
			. '</div>';
		return apply_filters('poolservices_shortcode_output', $output, 'trx_price_block', $atts, $content);
	}
	poolservices_require_shortcode('trx_price_block', 'poolservices_sc_price_block');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'poolservices_sc_price_block_reg_shortcodes' ) ) {
	//add_action('poolservices_action_shortcodes_list', 'poolservices_sc_price_block_reg_shortcodes');
	function poolservices_sc_price_block_reg_shortcodes() {
	
		poolservices_sc_map("trx_price_block", array(
			"title" => esc_html__("Price block", 'poolservices'),
			"desc" => wp_kses_data( __("Insert price block with title, price and description", 'poolservices') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Block style", 'poolservices'),
					"desc" => wp_kses_data( __("Select style for this price block", 'poolservices') ),
					"value" => 1,
					"options" => poolservices_get_list_styles(1, 3),
					"type" => "checklist"
				),
				"title" => array(
					"title" => esc_html__("Title", 'poolservices'),
					"desc" => wp_kses_data( __("Block title", 'poolservices') ),
					"value" => "",
					"type" => "text"
				),
				"link" => array(
					"title" => esc_html__("Link URL", 'poolservices'),
					"desc" => wp_kses_data( __("URL for link from button (at bottom of the block)", 'poolservices') ),
					"value" => "",
					"type" => "text"
				),
				"link_text" => array(
					"title" => esc_html__("Link text", 'poolservices'),
					"desc" => wp_kses_data( __("Text (caption) for the link button (at bottom of the block). If empty - button not showed", 'poolservices') ),
					"value" => "",
					"type" => "text"
				),
				"icon" => array(
					"title" => esc_html__("Icon",  'poolservices'),
					"desc" => wp_kses_data( __('Select icon from Fontello icons set (placed before/instead price)',  'poolservices') ),
					"value" => "",
					"type" => "icons",
					"options" => poolservices_get_sc_param('icons')
				),
				"money" => array(
					"title" => esc_html__("Money", 'poolservices'),
					"desc" => wp_kses_data( __("Money value (dot or comma separated)", 'poolservices') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"currency" => array(
					"title" => esc_html__("Currency", 'poolservices'),
					"desc" => wp_kses_data( __("Currency character", 'poolservices') ),
					"value" => "$",
					"type" => "text"
				),
				"period" => array(
					"title" => esc_html__("Period", 'poolservices'),
					"desc" => wp_kses_data( __("Period text (if need). For example: monthly, daily, etc.", 'poolservices') ),
					"value" => "",
					"type" => "text"
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'poolservices'),
					"desc" => wp_kses_data( __("Select color scheme for this block", 'poolservices') ),
					"value" => "",
					"type" => "checklist",
					"options" => poolservices_get_sc_param('schemes')
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'poolservices'),
					"desc" => wp_kses_data( __("Align price to left or right side", 'poolservices') ),
					"divider" => true,
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => poolservices_get_sc_param('float')
				), 
				"_content_" => array(
					"title" => esc_html__("Description", 'poolservices'),
					"desc" => wp_kses_data( __("Description for this price block", 'poolservices') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
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
if ( !function_exists( 'poolservices_sc_price_block_reg_shortcodes_vc' ) ) {
	//add_action('poolservices_action_shortcodes_list_vc', 'poolservices_sc_price_block_reg_shortcodes_vc');
	function poolservices_sc_price_block_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_price_block",
			"name" => esc_html__("Price block", 'poolservices'),
			"description" => wp_kses_data( __("Insert price block with title, price and description", 'poolservices') ),
			"category" => esc_html__('Content', 'poolservices'),
			'icon' => 'icon_trx_price_block',
			"class" => "trx_sc_single trx_sc_price_block",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Block style", 'poolservices'),
					"desc" => wp_kses_data( __("Select style of this price block", 'poolservices') ),
					"admin_label" => true,
					"class" => "",
					"std" => 1,
					"value" => array_flip(poolservices_get_list_styles(1, 3)),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'poolservices'),
					"description" => wp_kses_data( __("Block title", 'poolservices') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'poolservices'),
					"description" => wp_kses_data( __("URL for link from button (at bottom of the block)", 'poolservices') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link_text",
					"heading" => esc_html__("Link text", 'poolservices'),
					"description" => wp_kses_data( __("Text (caption) for the link button (at bottom of the block). If empty - button not showed", 'poolservices') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Icon", 'poolservices'),
					"description" => wp_kses_data( __("Select icon from Fontello icons set (placed before/instead price)", 'poolservices') ),
					"class" => "",
					"value" => poolservices_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "money",
					"heading" => esc_html__("Money", 'poolservices'),
					"description" => wp_kses_data( __("Money value (dot or comma separated)", 'poolservices') ),
					"admin_label" => true,
					"group" => esc_html__('Money', 'poolservices'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "currency",
					"heading" => esc_html__("Currency symbol", 'poolservices'),
					"description" => wp_kses_data( __("Currency character", 'poolservices') ),
					"admin_label" => true,
					"group" => esc_html__('Money', 'poolservices'),
					"class" => "",
					"value" => "$",
					"type" => "textfield"
				),
				array(
					"param_name" => "period",
					"heading" => esc_html__("Period", 'poolservices'),
					"description" => wp_kses_data( __("Period text (if need). For example: monthly, daily, etc.", 'poolservices') ),
					"admin_label" => true,
					"group" => esc_html__('Money', 'poolservices'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'poolservices'),
					"description" => wp_kses_data( __("Select color scheme for this block", 'poolservices') ),
					"group" => esc_html__('Colors and Images', 'poolservices'),
					"class" => "",
					"value" => array_flip(poolservices_get_sc_param('schemes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'poolservices'),
					"description" => wp_kses_data( __("Align price to left or right side", 'poolservices') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(poolservices_get_sc_param('float')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "content",
					"heading" => esc_html__("Description", 'poolservices'),
					"description" => wp_kses_data( __("Description for this price block", 'poolservices') ),
					"class" => "",
					"value" => "",
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
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_PriceBlock extends POOLSERVICES_VC_ShortCodeSingle {}
	}
}
?>