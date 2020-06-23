<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('poolservices_sc_googlemap_theme_setup')) {
	add_action( 'poolservices_action_before_init_theme', 'poolservices_sc_googlemap_theme_setup' );
	function poolservices_sc_googlemap_theme_setup() {
		add_action('poolservices_action_shortcodes_list', 		'poolservices_sc_googlemap_reg_shortcodes');
		if (function_exists('poolservices_exists_visual_composer') && poolservices_exists_visual_composer())
			add_action('poolservices_action_shortcodes_list_vc','poolservices_sc_googlemap_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_googlemap id="unique_id" width="width_in_pixels_or_percent" height="height_in_pixels"]
//	[trx_googlemap_marker address="your_address"]
//[/trx_googlemap]

if (!function_exists('poolservices_sc_googlemap')) {	
	function poolservices_sc_googlemap($atts, $content = null) {
		if (poolservices_in_shortcode_blogger()) return '';
		extract(poolservices_html_decode(shortcode_atts(array(
			// Individual params
			"zoom" => 16,
			"style" => 'default',
			"scheme" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "100%",
			"height" => "400",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . poolservices_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= poolservices_get_css_dimensions_from_values($width, $height);
		if (empty($id)) $id = 'sc_googlemap_'.str_replace('.', '', mt_rand());
		if (empty($style)) $style = poolservices_get_custom_option('googlemap_style');
		$api_key = poolservices_get_theme_option('api_google');
		wp_enqueue_script( 'googlemap', poolservices_get_protocol().'://maps.google.com/maps/api/js'.($api_key ? '?key='.$api_key : ''), array(), null, true );
		wp_enqueue_script( 'poolservices-googlemap-script', poolservices_get_file_url('js/core.googlemap.js'), array(), null, true );
		poolservices_storage_set('sc_googlemap_markers', array());
		$content = do_shortcode($content);
		$output = '';
		$markers = poolservices_storage_get('sc_googlemap_markers');
		if (count($markers) == 0) {
			$markers[] = array(
				'title' => poolservices_get_custom_option('googlemap_title'),
				'description' => poolservices_strmacros(poolservices_get_custom_option('googlemap_description')),
				'latlng' => poolservices_get_custom_option('googlemap_latlng'),
				'address' => poolservices_get_custom_option('googlemap_address'),
				'point' => poolservices_get_custom_option('googlemap_marker')
			);
		}
		$output .= 
			($content ? '<div id="'.esc_attr($id).'_wrap" class="sc_googlemap_wrap'
					. ($scheme && !poolservices_param_is_off($scheme) && !poolservices_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
					. '">' : '')
			. '<div id="'.esc_attr($id).'"'
				. ' class="sc_googlemap'. (!empty($class) ? ' '.esc_attr($class) : '').'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. (!poolservices_param_is_off($animation) ? ' data-animation="'.esc_attr(poolservices_get_animation_classes($animation)).'"' : '')
				. ' data-zoom="'.esc_attr($zoom).'"'
				. ' data-style="'.esc_attr($style).'"'
				. '>';
		$cnt = 0;
		foreach ($markers as $marker) {
			$cnt++;
			if (empty($marker['id'])) $marker['id'] = $id.'_'.intval($cnt);
			$output .= '<div id="'.esc_attr($marker['id']).'" class="sc_googlemap_marker"'
				. ' data-title="'.esc_attr($marker['title']).'"'
				. ' data-description="'.esc_attr(poolservices_strmacros($marker['description'])).'"'
				. ' data-address="'.esc_attr($marker['address']).'"'
				. ' data-latlng="'.esc_attr($marker['latlng']).'"'
				. ' data-point="'.esc_attr($marker['point']).'"'
				. '></div>';
		}
		$output .= '</div>'
			. ($content ? '<div class="sc_googlemap_content">' . trim($content) . '</div></div>' : '');
			
		return apply_filters('poolservices_shortcode_output', $output, 'trx_googlemap', $atts, $content);
	}
	poolservices_require_shortcode("trx_googlemap", "poolservices_sc_googlemap");
}


if (!function_exists('poolservices_sc_googlemap_marker')) {	
	function poolservices_sc_googlemap_marker($atts, $content = null) {
		if (poolservices_in_shortcode_blogger()) return '';
		extract(poolservices_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"address" => "",
			"latlng" => "",
			"point" => "",
			// Common params
			"id" => ""
		), $atts)));
		if (!empty($point)) {
			if ($point > 0) {
				$attach = wp_get_attachment_image_src( $point, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$point = $attach[0];
			}
		}
		$content = do_shortcode($content);
		poolservices_storage_set_array('sc_googlemap_markers', '', array(
			'id' => $id,
			'title' => $title,
			'description' => !empty($content) ? $content : $address,
			'latlng' => $latlng,
			'address' => $address,
			'point' => $point ? $point : poolservices_get_custom_option('googlemap_marker')
			)
		);
		return '';
	}
	poolservices_require_shortcode("trx_googlemap_marker", "poolservices_sc_googlemap_marker");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'poolservices_sc_googlemap_reg_shortcodes' ) ) {
	//add_action('poolservices_action_shortcodes_list', 'poolservices_sc_googlemap_reg_shortcodes');
	function poolservices_sc_googlemap_reg_shortcodes() {
	
		poolservices_sc_map("trx_googlemap", array(
			"title" => esc_html__("Google map", 'poolservices'),
			"desc" => wp_kses_data( __("Insert Google map with specified markers", 'poolservices') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"zoom" => array(
					"title" => esc_html__("Zoom", 'poolservices'),
					"desc" => wp_kses_data( __("Map zoom factor", 'poolservices') ),
					"divider" => true,
					"value" => 16,
					"min" => 1,
					"max" => 20,
					"type" => "spinner"
				),
				"style" => array(
					"title" => esc_html__("Map style", 'poolservices'),
					"desc" => wp_kses_data( __("Select map style", 'poolservices') ),
					"value" => "default",
					"type" => "checklist",
					"options" => poolservices_get_sc_param('googlemap_styles')
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'poolservices'),
					"desc" => wp_kses_data( __("Select color scheme for this block", 'poolservices') ),
					"value" => "",
					"type" => "checklist",
					"options" => poolservices_get_sc_param('schemes')
				),
				"width" => poolservices_shortcodes_width('100%'),
				"height" => poolservices_shortcodes_height(240),
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
				"name" => "trx_googlemap_marker",
				"title" => esc_html__("Google map marker", 'poolservices'),
				"desc" => wp_kses_data( __("Google map marker", 'poolservices') ),
				"decorate" => false,
				"container" => true,
				"params" => array(
					"address" => array(
						"title" => esc_html__("Address", 'poolservices'),
						"desc" => wp_kses_data( __("Address of this marker", 'poolservices') ),
						"value" => "",
						"type" => "text"
					),
					"latlng" => array(
						"title" => esc_html__("Latitude and Longitude", 'poolservices'),
						"desc" => wp_kses_data( __("Comma separated marker's coorditanes (instead Address)", 'poolservices') ),
						"value" => "",
						"type" => "text"
					),
					"point" => array(
						"title" => esc_html__("URL for marker image file", 'poolservices'),
						"desc" => wp_kses_data( __("Select or upload image or write URL from other site for this marker. If empty - use default marker", 'poolservices') ),
						"readonly" => false,
						"value" => "",
						"type" => "media"
					),
					"title" => array(
						"title" => esc_html__("Title", 'poolservices'),
						"desc" => wp_kses_data( __("Title for this marker", 'poolservices') ),
						"value" => "",
						"type" => "text"
					),
					"_content_" => array(
						"title" => esc_html__("Description", 'poolservices'),
						"desc" => wp_kses_data( __("Description for this marker", 'poolservices') ),
						"rows" => 4,
						"value" => "",
						"type" => "textarea"
					),
					"id" => poolservices_get_sc_param('id')
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'poolservices_sc_googlemap_reg_shortcodes_vc' ) ) {
	//add_action('poolservices_action_shortcodes_list_vc', 'poolservices_sc_googlemap_reg_shortcodes_vc');
	function poolservices_sc_googlemap_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_googlemap",
			"name" => esc_html__("Google map", 'poolservices'),
			"description" => wp_kses_data( __("Insert Google map with desired address or coordinates", 'poolservices') ),
			"category" => esc_html__('Content', 'poolservices'),
			'icon' => 'icon_trx_googlemap',
			"class" => "trx_sc_collection trx_sc_googlemap",
			"content_element" => true,
			"is_container" => true,
			"as_parent" => array('only' => 'trx_googlemap_marker,trx_form,trx_section,trx_block,trx_promo'),
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "zoom",
					"heading" => esc_html__("Zoom", 'poolservices'),
					"description" => wp_kses_data( __("Map zoom factor", 'poolservices') ),
					"admin_label" => true,
					"class" => "",
					"value" => "16",
					"type" => "textfield"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'poolservices'),
					"description" => wp_kses_data( __("Map custom style", 'poolservices') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(poolservices_get_sc_param('googlemap_styles')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'poolservices'),
					"description" => wp_kses_data( __("Select color scheme for this block", 'poolservices') ),
					"class" => "",
					"value" => array_flip(poolservices_get_sc_param('schemes')),
					"type" => "dropdown"
				),
				poolservices_get_vc_param('id'),
				poolservices_get_vc_param('class'),
				poolservices_get_vc_param('animation'),
				poolservices_get_vc_param('css'),
				poolservices_vc_width('100%'),
				poolservices_vc_height(240),
				poolservices_get_vc_param('margin_top'),
				poolservices_get_vc_param('margin_bottom'),
				poolservices_get_vc_param('margin_left'),
				poolservices_get_vc_param('margin_right')
			)
		) );
		
		vc_map( array(
			"base" => "trx_googlemap_marker",
			"name" => esc_html__("Googlemap marker", 'poolservices'),
			"description" => wp_kses_data( __("Insert new marker into Google map", 'poolservices') ),
			"class" => "trx_sc_collection trx_sc_googlemap_marker",
			'icon' => 'icon_trx_googlemap_marker',
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => true,
			"as_child" => array('only' => 'trx_googlemap'), // Use only|except attributes to limit parent (separate multiple values with comma)
			"params" => array(
				array(
					"param_name" => "address",
					"heading" => esc_html__("Address", 'poolservices'),
					"description" => wp_kses_data( __("Address of this marker", 'poolservices') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "latlng",
					"heading" => esc_html__("Latitude and Longitude", 'poolservices'),
					"description" => wp_kses_data( __("Comma separated marker's coorditanes (instead Address)", 'poolservices') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'poolservices'),
					"description" => wp_kses_data( __("Title for this marker", 'poolservices') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "point",
					"heading" => esc_html__("URL for marker image file", 'poolservices'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for this marker. If empty - use default marker", 'poolservices') ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				poolservices_get_vc_param('id')
			)
		) );
		
		class WPBakeryShortCode_Trx_Googlemap extends POOLSERVICES_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Googlemap_Marker extends POOLSERVICES_VC_ShortCodeCollection {}
	}
}
?>