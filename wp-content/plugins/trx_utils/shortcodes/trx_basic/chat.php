<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('poolservices_sc_chat_theme_setup')) {
	add_action( 'poolservices_action_before_init_theme', 'poolservices_sc_chat_theme_setup' );
	function poolservices_sc_chat_theme_setup() {
		add_action('poolservices_action_shortcodes_list', 		'poolservices_sc_chat_reg_shortcodes');
		if (function_exists('poolservices_exists_visual_composer') && poolservices_exists_visual_composer())
			add_action('poolservices_action_shortcodes_list_vc','poolservices_sc_chat_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_chat id="unique_id" link="url" title=""]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_chat]
[trx_chat id="unique_id" link="url" title=""]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_chat]
...
*/

if (!function_exists('poolservices_sc_chat')) {	
	function poolservices_sc_chat($atts, $content=null){	
		if (poolservices_in_shortcode_blogger()) return '';
		extract(poolservices_html_decode(shortcode_atts(array(
			// Individual params
			"photo" => "",
			"title" => "",
			"link" => "",
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
		$css .= poolservices_get_css_dimensions_from_values($width, $height);
		$title = $title=='' ? $link : $title;
		if (!empty($photo)) {
			if ($photo > 0) {
				$attach = wp_get_attachment_image_src( $photo, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$photo = $attach[0];
			}
			$photo = poolservices_get_resized_image_tag($photo, 75, 75);
		}
		$content = do_shortcode($content);
		if (poolservices_substr($content, 0, 2)!='<p') $content = '<p>' . ($content) . '</p>';
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_chat' . (!empty($class) ? ' '.esc_attr($class) : '') . '"' 
				. (!poolservices_param_is_off($animation) ? ' data-animation="'.esc_attr(poolservices_get_animation_classes($animation)).'"' : '')
				. ($css ? ' style="'.esc_attr($css).'"' : '') 
				. '>'
					. '<div class="sc_chat_inner">'
						. ($photo ? '<div class="sc_chat_avatar">'.($photo).'</div>' : '')
						. ($title == '' ? '' : ('<div class="sc_chat_title">' . ($link!='' ? '<a href="'.esc_url($link).'">' : '') . ($title) . ($link!='' ? '</a>' : '') . '</div>'))
						. '<div class="sc_chat_content">'.($content).'</div>'
					. '</div>'
				. '</div>';
		return apply_filters('poolservices_shortcode_output', $output, 'trx_chat', $atts, $content);
	}
	poolservices_require_shortcode('trx_chat', 'poolservices_sc_chat');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'poolservices_sc_chat_reg_shortcodes' ) ) {
	//add_action('poolservices_action_shortcodes_list', 'poolservices_sc_chat_reg_shortcodes');
	function poolservices_sc_chat_reg_shortcodes() {
	
		poolservices_sc_map("trx_chat", array(
			"title" => esc_html__("Chat", 'poolservices'),
			"desc" => wp_kses_data( __("Chat message", 'poolservices') ),
			"decorate" => true,
			"container" => true,
			"params" => array(
				"title" => array(
					"title" => esc_html__("Item title", 'poolservices'),
					"desc" => wp_kses_data( __("Chat item title", 'poolservices') ),
					"value" => "",
					"type" => "text"
				),
				"photo" => array(
					"title" => esc_html__("Item photo", 'poolservices'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site for the item photo (avatar)", 'poolservices') ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"link" => array(
					"title" => esc_html__("Item link", 'poolservices'),
					"desc" => wp_kses_data( __("Chat item link", 'poolservices') ),
					"value" => "",
					"type" => "text"
				),
				"_content_" => array(
					"title" => esc_html__("Chat item content", 'poolservices'),
					"desc" => wp_kses_data( __("Current chat item content", 'poolservices') ),
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
if ( !function_exists( 'poolservices_sc_chat_reg_shortcodes_vc' ) ) {
	//add_action('poolservices_action_shortcodes_list_vc', 'poolservices_sc_chat_reg_shortcodes_vc');
	function poolservices_sc_chat_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_chat",
			"name" => esc_html__("Chat", 'poolservices'),
			"description" => wp_kses_data( __("Chat message", 'poolservices') ),
			"category" => esc_html__('Content', 'poolservices'),
			'icon' => 'icon_trx_chat',
			"class" => "trx_sc_container trx_sc_chat",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "title",
					"heading" => esc_html__("Item title", 'poolservices'),
					"description" => wp_kses_data( __("Title for current chat item", 'poolservices') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "photo",
					"heading" => esc_html__("Item photo", 'poolservices'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for the item photo (avatar)", 'poolservices') ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'poolservices'),
					"description" => wp_kses_data( __("URL for the link on chat title click", 'poolservices') ),
					"class" => "",
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
			'js_view' => 'VcTrxTextContainerView'
		
		) );
		
		class WPBakeryShortCode_Trx_Chat extends POOLSERVICES_VC_ShortCodeContainer {}
	}
}
?>