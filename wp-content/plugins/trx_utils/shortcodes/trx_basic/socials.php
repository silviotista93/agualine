<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('poolservices_sc_socials_theme_setup')) {
	add_action( 'poolservices_action_before_init_theme', 'poolservices_sc_socials_theme_setup' );
	function poolservices_sc_socials_theme_setup() {
		add_action('poolservices_action_shortcodes_list', 		'poolservices_sc_socials_reg_shortcodes');
		if (function_exists('poolservices_exists_visual_composer') && poolservices_exists_visual_composer())
			add_action('poolservices_action_shortcodes_list_vc','poolservices_sc_socials_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_socials id="unique_id" size="small"]
	[trx_social_item name="facebook" url="profile url" icon="path for the icon"]
	[trx_social_item name="twitter" url="profile url"]
[/trx_socials]
*/

if (!function_exists('poolservices_sc_socials')) {	
	function poolservices_sc_socials($atts, $content=null){	
		if (poolservices_in_shortcode_blogger()) return '';
		extract(poolservices_html_decode(shortcode_atts(array(
			// Individual params
			"size" => "small",		// tiny | small | medium | large
			"shape" => "square",	// round | square
			"type" => poolservices_get_theme_setting('socials_type'),	// icons | images
			"socials" => "",
			"custom" => "no",
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
		poolservices_storage_set('sc_social_data', array(
			'icons' => false,
            'type' => $type
            )
        );
		if (!empty($socials)) {
			$allowed = explode('|', $socials);
			$list = array();
			for ($i=0; $i<count($allowed); $i++) {
				$s = explode('=', $allowed[$i]);
				if (!empty($s[1])) {
					$list[] = array(
						'icon'	=> $type=='images' ? poolservices_get_socials_url($s[0]) : 'icon-'.trim($s[0]),
						'url'	=> $s[1]
						);
				}
			}
			if (count($list) > 0) poolservices_storage_set_array('sc_social_data', 'icons', $list);
		} else if (poolservices_param_is_on($custom))
			$content = do_shortcode($content);
		if (poolservices_storage_get_array('sc_social_data', 'icons')===false) poolservices_storage_set_array('sc_social_data', 'icons', poolservices_get_custom_option('social_icons'));
		$output = poolservices_prepare_socials(poolservices_storage_get_array('sc_social_data', 'icons'));
		$output = $output
			? '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_socials sc_socials_type_' . esc_attr($type) . ' sc_socials_shape_' . esc_attr($shape) . ' sc_socials_size_' . esc_attr($size) . (!empty($class) ? ' '.esc_attr($class) : '') . '"' 
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. (!poolservices_param_is_off($animation) ? ' data-animation="'.esc_attr(poolservices_get_animation_classes($animation)).'"' : '')
				. '>' 
				. ($output)
				. '</div>'
			: '';
		return apply_filters('poolservices_shortcode_output', $output, 'trx_socials', $atts, $content);
	}
	poolservices_require_shortcode('trx_socials', 'poolservices_sc_socials');
}


if (!function_exists('poolservices_sc_social_item')) {	
	function poolservices_sc_social_item($atts, $content=null){	
		if (poolservices_in_shortcode_blogger()) return '';
		extract(poolservices_html_decode(shortcode_atts(array(
			// Individual params
			"name" => "",
			"url" => "",
			"icon" => ""
		), $atts)));
		if (empty($icon)) {
			if (!empty($name)) {
				$type = poolservices_storage_get_array('sc_social_data', 'type');
				if ($type=='images') {
					if (file_exists(poolservices_get_socials_dir($name.'.png')))
						$icon = poolservices_get_socials_url($name.'.png');
				} else
					$icon = 'icon-'.esc_attr($name);
			}
		} else if ((int) $icon > 0) {
			$attach = wp_get_attachment_image_src( $icon, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$icon = $attach[0];
		}
		if (!empty($icon) && !empty($url)) {
			if (poolservices_storage_get_array('sc_social_data', 'icons')===false) poolservices_storage_set_array('sc_social_data', 'icons', array());
			poolservices_storage_set_array2('sc_social_data', 'icons', '', array(
				'icon' => $icon,
				'url' => $url
				)
			);
		}
		return '';
	}
	poolservices_require_shortcode('trx_social_item', 'poolservices_sc_social_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'poolservices_sc_socials_reg_shortcodes' ) ) {
	//add_action('poolservices_action_shortcodes_list', 'poolservices_sc_socials_reg_shortcodes');
	function poolservices_sc_socials_reg_shortcodes() {
	
		poolservices_sc_map("trx_socials", array(
			"title" => esc_html__("Social icons", 'poolservices'),
			"desc" => wp_kses_data( __("List of social icons (with hovers)", 'poolservices') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"type" => array(
					"title" => esc_html__("Icon's type", 'poolservices'),
					"desc" => wp_kses_data( __("Type of the icons - images or font icons", 'poolservices') ),
					"value" => poolservices_get_theme_setting('socials_type'),
					"options" => array(
						'icons' => esc_html__('Icons', 'poolservices'),
						'images' => esc_html__('Images', 'poolservices')
					),
					"type" => "checklist"
				), 
				"size" => array(
					"title" => esc_html__("Icon's size", 'poolservices'),
					"desc" => wp_kses_data( __("Size of the icons", 'poolservices') ),
					"value" => "small",
					"options" => poolservices_get_sc_param('sizes'),
					"type" => "checklist"
				), 
				"shape" => array(
					"title" => esc_html__("Icon's shape", 'poolservices'),
					"desc" => wp_kses_data( __("Shape of the icons", 'poolservices') ),
					"value" => "square",
					"options" => poolservices_get_sc_param('shapes'),
					"type" => "checklist"
				), 
				"socials" => array(
					"title" => esc_html__("Manual socials list", 'poolservices'),
					"desc" => wp_kses_data( __("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebook.com/my_profile. If empty - use socials from Theme options.", 'poolservices') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"custom" => array(
					"title" => esc_html__("Custom socials", 'poolservices'),
					"desc" => wp_kses_data( __("Make custom icons from inner shortcodes (prepare it on tabs)", 'poolservices') ),
					"divider" => true,
					"value" => "no",
					"options" => poolservices_get_sc_param('yes_no'),
					"type" => "switch"
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
				"name" => "trx_social_item",
				"title" => esc_html__("Custom social item", 'poolservices'),
				"desc" => wp_kses_data( __("Custom social item: name, profile url and icon url", 'poolservices') ),
				"decorate" => false,
				"container" => false,
				"params" => array(
					"name" => array(
						"title" => esc_html__("Social name", 'poolservices'),
						"desc" => wp_kses_data( __("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", 'poolservices') ),
						"value" => "",
						"type" => "text"
					),
					"url" => array(
						"title" => esc_html__("Your profile URL", 'poolservices'),
						"desc" => wp_kses_data( __("URL of your profile in specified social network", 'poolservices') ),
						"value" => "",
						"type" => "text"
					),
					"icon" => array(
						"title" => esc_html__("URL (source) for icon file", 'poolservices'),
						"desc" => wp_kses_data( __("Select or upload image or write URL from other site for the current social icon", 'poolservices') ),
						"readonly" => false,
						"value" => "",
						"type" => "media"
					)
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'poolservices_sc_socials_reg_shortcodes_vc' ) ) {
	//add_action('poolservices_action_shortcodes_list_vc', 'poolservices_sc_socials_reg_shortcodes_vc');
	function poolservices_sc_socials_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_socials",
			"name" => esc_html__("Social icons", 'poolservices'),
			"description" => wp_kses_data( __("Custom social icons", 'poolservices') ),
			"category" => esc_html__('Content', 'poolservices'),
			'icon' => 'icon_trx_socials',
			"class" => "trx_sc_collection trx_sc_socials",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"as_parent" => array('only' => 'trx_social_item'),
			"params" => array_merge(array(
				array(
					"param_name" => "type",
					"heading" => esc_html__("Icon's type", 'poolservices'),
					"description" => wp_kses_data( __("Type of the icons - images or font icons", 'poolservices') ),
					"class" => "",
					"std" => poolservices_get_theme_setting('socials_type'),
					"value" => array(
						esc_html__('Icons', 'poolservices') => 'icons',
						esc_html__('Images', 'poolservices') => 'images'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "size",
					"heading" => esc_html__("Icon's size", 'poolservices'),
					"description" => wp_kses_data( __("Size of the icons", 'poolservices') ),
					"class" => "",
					"std" => "small",
					"value" => array_flip(poolservices_get_sc_param('sizes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "shape",
					"heading" => esc_html__("Icon's shape", 'poolservices'),
					"description" => wp_kses_data( __("Shape of the icons", 'poolservices') ),
					"class" => "",
					"std" => "square",
					"value" => array_flip(poolservices_get_sc_param('shapes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "socials",
					"heading" => esc_html__("Manual socials list", 'poolservices'),
					"description" => wp_kses_data( __("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebook.com/my_profile. If empty - use socials from Theme options.", 'poolservices') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "custom",
					"heading" => esc_html__("Custom socials", 'poolservices'),
					"description" => wp_kses_data( __("Make custom icons from inner shortcodes (prepare it on tabs)", 'poolservices') ),
					"class" => "",
					"value" => array(esc_html__('Custom socials', 'poolservices') => 'yes'),
					"type" => "checkbox"
				),
				poolservices_get_vc_param('id'),
				poolservices_get_vc_param('class'),
				poolservices_get_vc_param('animation'),
				poolservices_get_vc_param('css'),
				poolservices_get_vc_param('margin_top'),
				poolservices_get_vc_param('margin_bottom'),
				poolservices_get_vc_param('margin_left'),
				poolservices_get_vc_param('margin_right')
			))
		) );
		
		
		vc_map( array(
			"base" => "trx_social_item",
			"name" => esc_html__("Custom social item", 'poolservices'),
			"description" => wp_kses_data( __("Custom social item: name, profile url and icon url", 'poolservices') ),
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => false,
			'icon' => 'icon_trx_social_item',
			"class" => "trx_sc_single trx_sc_social_item",
			"as_child" => array('only' => 'trx_socials'),
			"as_parent" => array('except' => 'trx_socials'),
			"params" => array(
				array(
					"param_name" => "name",
					"heading" => esc_html__("Social name", 'poolservices'),
					"description" => wp_kses_data( __("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", 'poolservices') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "url",
					"heading" => esc_html__("Your profile URL", 'poolservices'),
					"description" => wp_kses_data( __("URL of your profile in specified social network", 'poolservices') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("URL (source) for icon file", 'poolservices'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for the current social icon", 'poolservices') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				)
			)
		) );
		
		class WPBakeryShortCode_Trx_Socials extends POOLSERVICES_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Social_Item extends POOLSERVICES_VC_ShortCodeSingle {}
	}
}
?>