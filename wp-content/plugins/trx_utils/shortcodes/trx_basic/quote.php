<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('poolservices_sc_quote_theme_setup')) {
	add_action( 'poolservices_action_before_init_theme', 'poolservices_sc_quote_theme_setup' );
	function poolservices_sc_quote_theme_setup() {
		add_action('poolservices_action_shortcodes_list', 		'poolservices_sc_quote_reg_shortcodes');
		if (function_exists('poolservices_exists_visual_composer') && poolservices_exists_visual_composer())
			add_action('poolservices_action_shortcodes_list_vc','poolservices_sc_quote_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_quote id="unique_id" cite="url" title=""]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/quote]
*/

if (!function_exists('poolservices_sc_quote')) {	
	function poolservices_sc_quote($atts, $content=null){	
		if (poolservices_in_shortcode_blogger()) return '';
		extract(poolservices_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"cite" => "",
			"bg_image" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
			), $atts)));
		$class .= ($class ? ' ' : '') . poolservices_get_css_position_as_classes($top, $right, $bottom, $left);
		if ($bg_image > 0) {
			$attach = wp_get_attachment_image_src( $bg_image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$src = $attach[0];
		}
		$css .= poolservices_get_css_dimensions_from_values($width);
		$css .= 'background-image:url('. esc_url($src) . '); ';
		$cite_param = $cite != '' ? ' cite="'.esc_attr($cite).'"' : '';
		$title = $title=='' ? $cite : $title;
		$content = do_shortcode($content);
		$class .= ' ' .($src ? 'with_bg_image' : '');
		if (poolservices_substr($content, 0, 2)!='<p') $content = '<p>' . ($content) . '</p>';
		$output = '<blockquote' 
		. ($id ? ' id="'.esc_attr($id).'"' : '') . ($cite_param) 
		. ' class="sc_quote'. (!empty($class) ? ' '.esc_attr($class) : '').'"' 
		. (!poolservices_param_is_off($animation) ? ' data-animation="'.esc_attr(poolservices_get_animation_classes($animation)).'"' : '')
		. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
		. '>'
		. ($content)
		. ($title == '' ? '' : ('<p class="sc_quote_title">' . ($cite!='' ? '<a href="'.esc_url($cite).'">' : '') . ($title) . ($cite!='' ? '</a>' : '') . '</p>'))
		.'</blockquote>';
		return apply_filters('poolservices_shortcode_output', $output, 'trx_quote', $atts, $content);
	}
	poolservices_require_shortcode('trx_quote', 'poolservices_sc_quote');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'poolservices_sc_quote_reg_shortcodes' ) ) {
	//add_action('poolservices_action_shortcodes_list', 'poolservices_sc_quote_reg_shortcodes');
	function poolservices_sc_quote_reg_shortcodes() {

		poolservices_sc_map("trx_quote", array(
			"title" => esc_html__("Quote", 'poolservices'),
			"desc" => wp_kses_data( __("Quote text", 'poolservices') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"cite" => array(
					"title" => esc_html__("Quote cite", 'poolservices'),
					"desc" => wp_kses_data( __("URL for quote cite", 'poolservices') ),
					"value" => "",
					"type" => "text"
					),
				"bg_image" => array(
					"title" => esc_html__("Add background", 'poolservices'),
					"desc" => wp_kses_data( __("Background image quote", 'poolservices') ),
					"value" => "",
					"type" => "media"
					),
				"title" => array(
					"title" => esc_html__("Title (author)", 'poolservices'),
					"desc" => wp_kses_data( __("Quote title (author name)", 'poolservices') ),
					"value" => "",
					"type" => "text"
					),
				"_content_" => array(
					"title" => esc_html__("Quote content", 'poolservices'),
					"desc" => wp_kses_data( __("Quote content", 'poolservices') ),
					"rows" => 4,
					"value" => "",
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
if ( !function_exists( 'poolservices_sc_quote_reg_shortcodes_vc' ) ) {
	//add_action('poolservices_action_shortcodes_list_vc', 'poolservices_sc_quote_reg_shortcodes_vc');
	function poolservices_sc_quote_reg_shortcodes_vc() {

		vc_map( array(
			"base" => "trx_quote",
			"name" => esc_html__("Quote", 'poolservices'),
			"description" => wp_kses_data( __("Quote text", 'poolservices') ),
			"category" => esc_html__('Content', 'poolservices'),
			'icon' => 'icon_trx_quote',
			"class" => "trx_sc_single trx_sc_quote",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "cite",
					"heading" => esc_html__("Quote cite", 'poolservices'),
					"description" => wp_kses_data( __("URL for the quote cite link", 'poolservices') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
					),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title (author)", 'poolservices'),
					"description" => wp_kses_data( __("Quote title (author name)", 'poolservices') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
					),
				array(
					"param_name" => "bg_image",
					"heading" => esc_html__("Add background", 'poolservices'),
					"description" => wp_kses_data( __("Background quote image", 'poolservices') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "attach_image"
					),
				array(
					"param_name" => "content",
					"heading" => esc_html__("Quote content", 'poolservices'),
					"description" => wp_kses_data( __("Quote content", 'poolservices') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
					),
				poolservices_get_vc_param('id'),
				poolservices_get_vc_param('class'),
				poolservices_get_vc_param('animation'),
				poolservices_get_vc_param('css'),
				poolservices_vc_width(),
				poolservices_get_vc_param('margin_top'),
				poolservices_get_vc_param('margin_bottom'),
				poolservices_get_vc_param('margin_left'),
				poolservices_get_vc_param('margin_right')
				),
			'js_view' => 'VcTrxTextView'
			) );
		
		class WPBakeryShortCode_Trx_Quote extends POOLSERVICES_VC_ShortCodeSingle {}
	}
}
?>