<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('poolservices_sc_intro_theme_setup')) {
	add_action( 'poolservices_action_before_init_theme', 'poolservices_sc_intro_theme_setup' );
	function poolservices_sc_intro_theme_setup() {
		add_action('poolservices_action_shortcodes_list', 		'poolservices_sc_intro_reg_shortcodes');
		if (function_exists('poolservices_exists_visual_composer') && poolservices_exists_visual_composer())
			add_action('poolservices_action_shortcodes_list_vc','poolservices_sc_intro_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

if (!function_exists('poolservices_sc_intro')) {	
	function poolservices_sc_intro($atts, $content=null){	
		if (poolservices_in_shortcode_blogger()) return '';
		extract(poolservices_html_decode(shortcode_atts(array(
			// Individual params
			"style" => 1,
			"align" => "none",
			"image" => "",
			"bg_color" => "",
			"icon" => "",
			"scheme" => "",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link" => '',
			"link_caption" => esc_html__('Read more', 'poolservices'),
			"link2" => '',
			"link2_caption" => '',
			"url" => "",
			"content_position" => "",
			"content_width" => "",
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
	
		if ($image > 0) {
			$attach = wp_get_attachment_image_src($image, 'full');
			if (isset($attach[0]) && $attach[0]!='')
				$image = $attach[0];
		}
		
		$width  = poolservices_prepare_css_value($width);
		$height = poolservices_prepare_css_value($height);
		
		$class .= ($class ? ' ' : '') . poolservices_get_css_position_as_classes($top, $right, $bottom, $left);

		$css .= poolservices_get_css_dimensions_from_values($width,$height);
		$css .= ($image ? 'background: url('.$image.');' : '');
		$css .= ($bg_color ? 'background-color: '.$bg_color.';' : '');
		
		$buttons = (!empty($link) || !empty($link2) 
						? '<div class="sc_intro_buttons sc_item_buttons">'
							. (!empty($link) 
								? '<div class="sc_intro_button sc_item_button">'.do_shortcode('[trx_button link="'.esc_url($link).'" size="medium"]'.esc_html($link_caption).'[/trx_button]').'</div>' 
								: '')
							. (!empty($link2) && $style==2 
								? '<div class="sc_intro_button sc_item_button">'.do_shortcode('[trx_button link="'.esc_url($link2).'" size="medium"]'.esc_html($link2_caption).'[/trx_button]').'</div>' 
								: '')
							. '</div>'
						: '');
						
		$output = '<div '.(!empty($url) ? 'data-href="'.esc_url($url).'"' : '') 
					. ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_intro' 
						. ($class ? ' ' . esc_attr($class) : '') 
						. ($content_position && $style==1 ? ' sc_intro_position_' . esc_attr($content_position) : '') 
						. ($style==5 ? ' small_padding' : '') 
						. ($scheme && !poolservices_param_is_off($scheme) && !poolservices_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
						. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
						. '"'
					. (!poolservices_param_is_off($animation) ? ' data-animation="'.esc_attr(poolservices_get_animation_classes($animation)).'"' : '')
					. ($css ? ' style="'.esc_attr($css).'"' : '')
					.'>' 
					. '<div class="sc_intro_inner '.($style ? ' sc_intro_style_' . esc_attr($style) : '').'"'.(!empty($content_width) ? ' style="width:'.esc_attr($content_width).';"' : '').'>'
						. (!empty($icon) && $style==5 ? '<div class="sc_intro_icon '.esc_attr($icon).'"></div>' : '')
						. '<div class="sc_intro_content">'
							. (!empty($subtitle) && $style!=4 && $style!=5 ? '<h6 class="sc_intro_subtitle">' . trim(poolservices_strmacros($subtitle)) . '</h6>' : '')
							. (!empty($title) ? '<h2 class="sc_intro_title">' . trim(poolservices_strmacros($title)) . '</h2>' : '')
							. (!empty($description) && $style!=1 ? '<div class="sc_intro_descr">' . trim(poolservices_strmacros($description)) . '</div>' : '')
							. ($style==2 || $style==3 ? $buttons : '')
						. '</div>'
					. '</div>'
				.'</div>';
	
	
	
		return apply_filters('poolservices_shortcode_output', $output, 'trx_intro', $atts, $content);
	}
	poolservices_require_shortcode('trx_intro', 'poolservices_sc_intro');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'poolservices_sc_intro_reg_shortcodes' ) ) {
	//add_action('poolservices_action_shortcodes_list', 'poolservices_sc_intro_reg_shortcodes');
	function poolservices_sc_intro_reg_shortcodes() {
	
		poolservices_sc_map("trx_intro", array(
			"title" => esc_html__("Intro", 'poolservices'),
			"desc" => wp_kses_data( __("Insert Intro block in your page (post)", 'poolservices') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", 'poolservices'),
					"desc" => wp_kses_data( __("Select style to display block", 'poolservices') ),
					"value" => "1",
					"type" => "checklist",
					"options" => poolservices_get_list_styles(1, 5)
				),
				"align" => array(
					"title" => esc_html__("Alignment of the intro block", 'poolservices'),
					"desc" => wp_kses_data( __("Align whole intro block to left or right side of the page or parent container", 'poolservices') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => poolservices_get_sc_param('float')
				), 
				"image" => array(
					"title" => esc_html__("Image URL", 'poolservices'),
					"desc" => wp_kses_data( __("Select the intro image from the library for this section", 'poolservices') ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", 'poolservices'),
					"desc" => wp_kses_data( __("Select background color for the intro", 'poolservices') ),
					"value" => "",
					"type" => "color"
				),
				"icon" => array(
					"title" => esc_html__('Icon',  'poolservices'),
					"desc" => wp_kses_data( __("Select icon from Fontello icons set",  'poolservices') ),
					"dependency" => array(
						'style' => array(5)
					),
					"value" => "",
					"type" => "icons",
					"options" => poolservices_get_sc_param('icons')
				),
				"content_position" => array(
					"title" => esc_html__('Content position', 'poolservices'),
					"desc" => wp_kses_data( __("Select content position", 'poolservices') ),
					"dependency" => array(
						'style' => array(1)
					),
					"value" => "top_left",
					"type" => "checklist",
					"options" => array(
						'top_left' => esc_html__('Top Left', 'poolservices'),
						'top_right' => esc_html__('Top Right', 'poolservices'),
						'bottom_right' => esc_html__('Bottom Right', 'poolservices'),
						'bottom_left' => esc_html__('Bottom Left', 'poolservices')
					)
				),
				"content_width" => array(
					"title" => esc_html__('Content width', 'poolservices'),
					"desc" => wp_kses_data( __("Select content width", 'poolservices') ),
					"dependency" => array(
						'style' => array(1)
					),
					"value" => "100%",
					"type" => "checklist",
					"options" => array(
						'100%' => esc_html__('100%', 'poolservices'),
						'90%' => esc_html__('90%', 'poolservices'),
						'80%' => esc_html__('80%', 'poolservices'),
						'70%' => esc_html__('70%', 'poolservices'),
						'60%' => esc_html__('60%', 'poolservices'),
						'50%' => esc_html__('50%', 'poolservices'),
						'40%' => esc_html__('40%', 'poolservices'),
						'30%' => esc_html__('30%', 'poolservices')
					)
				),
				"subtitle" => array(
					"title" => esc_html__("Subtitle", 'poolservices'),
					"desc" => wp_kses_data( __("Subtitle for the block", 'poolservices') ),
					"divider" => true,
					"dependency" => array(
						'style' => array(1,2,3)
					),
					"value" => "",
					"type" => "text"
				),
				"title" => array(
					"title" => esc_html__("Title", 'poolservices'),
					"desc" => wp_kses_data( __("Title for the block", 'poolservices') ),
					"value" => "",
					"type" => "textarea"
				),
				"description" => array(
					"title" => esc_html__("Description", 'poolservices'),
					"desc" => wp_kses_data( __("Short description for the block", 'poolservices') ),
					"dependency" => array(
						'style' => array(2,3,4,5),
					),
					"value" => "",
					"type" => "textarea"
				),
				"link" => array(
					"title" => esc_html__("Button URL", 'poolservices'),
					"desc" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'poolservices') ),
					"dependency" => array(
						'style' => array(2,3),
					),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"link_caption" => array(
					"title" => esc_html__("Button caption", 'poolservices'),
					"desc" => wp_kses_data( __("Caption for the button at the bottom of the block", 'poolservices') ),
					"dependency" => array(
						'style' => array(2,3),
					),
					"value" => "",
					"type" => "text"
				),
				"link2" => array(
					"title" => esc_html__("Button 2 URL", 'poolservices'),
					"desc" => wp_kses_data( __("Link URL for the second button at the bottom of the block", 'poolservices') ),
					"dependency" => array(
						'style' => array(2)
					),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"link2_caption" => array(
					"title" => esc_html__("Button 2 caption", 'poolservices'),
					"desc" => wp_kses_data( __("Caption for the second button at the bottom of the block", 'poolservices') ),
					"dependency" => array(
						'style' => array(2)
					),
					"value" => "",
					"type" => "text"
				),
				"url" => array(
					"title" => esc_html__("Link", 'poolservices'),
					"desc" => wp_kses_data( __("Link of the intro block", 'poolservices') ),
					"value" => "",
					"type" => "text"
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'poolservices'),
					"desc" => wp_kses_data( __("Select color scheme for the section with text", 'poolservices') ),
					"value" => "",
					"type" => "checklist",
					"options" => poolservices_get_sc_param('schemes')
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
if ( !function_exists( 'poolservices_sc_intro_reg_shortcodes_vc' ) ) {
	//add_action('poolservices_action_shortcodes_list_vc', 'poolservices_sc_intro_reg_shortcodes_vc');
	function poolservices_sc_intro_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_intro",
			"name" => esc_html__("Intro", 'poolservices'),
			"description" => wp_kses_data( __("Insert Intro block", 'poolservices') ),
			"category" => esc_html__('Content', 'poolservices'),
			'icon' => 'icon_trx_intro',
			"class" => "trx_sc_single trx_sc_intro",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style of the block", 'poolservices'),
					"description" => wp_kses_data( __("Select style to display this block", 'poolservices') ),
					"class" => "",
					"admin_label" => true,
					"value" => array_flip(poolservices_get_list_styles(1, 5)),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment of the block", 'poolservices'),
					"description" => wp_kses_data( __("Align whole intro block to left or right side of the page or parent container", 'poolservices') ),
					"class" => "",
					"std" => 'none',
					"value" => array_flip(poolservices_get_sc_param('float')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("Image URL", 'poolservices'),
					"description" => wp_kses_data( __("Select the intro image from the library for this section", 'poolservices') ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'poolservices'),
					"description" => wp_kses_data( __("Select background color for the intro", 'poolservices') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Icon", 'poolservices'),
					"description" => wp_kses_data( __("Select icon from Fontello icons set", 'poolservices') ),
					"class" => "",
					'dependency' => array(
						'element' => 'style',
						'value' => array('5')
					),
					"value" => poolservices_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "content_position",
					"heading" => esc_html__("Content position", 'poolservices'),
					"description" => wp_kses_data( __("Select content position", 'poolservices') ),
					"class" => "",
					"admin_label" => true,
					"value" => array(
						esc_html__('Top Left', 'poolservices') => 'top_left',
						esc_html__('Top Right', 'poolservices') => 'top_right',
						esc_html__('Bottom Right', 'poolservices') => 'bottom_right',
						esc_html__('Bottom Left', 'poolservices') => 'bottom_left'
					),
					'dependency' => array(
						'element' => 'style',
						'value' => array('1')
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "content_width",
					"heading" => esc_html__("Content width", 'poolservices'),
					"description" => wp_kses_data( __("Select content width", 'poolservices') ),
					"class" => "",
					"admin_label" => true,
					"value" => array(
						esc_html__('100%', 'poolservices') => '100%',
						esc_html__('90%', 'poolservices') => '90%',
						esc_html__('80%', 'poolservices') => '80%',
						esc_html__('70%', 'poolservices') => '70%',
						esc_html__('60%', 'poolservices') => '60%',
						esc_html__('50%', 'poolservices') => '50%',
						esc_html__('40%', 'poolservices') => '40%',
						esc_html__('30%', 'poolservices') => '30%'
					),
					'dependency' => array(
						'element' => 'style',
						'value' => array('1')
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "subtitle",
					"heading" => esc_html__("Subtitle", 'poolservices'),
					"description" => wp_kses_data( __("Subtitle for the block", 'poolservices') ),
					'dependency' => array(
						'element' => 'style',
						'value' => array('1','2','3')
					),
					"group" => esc_html__('Captions', 'poolservices'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'poolservices'),
					"description" => wp_kses_data( __("Title for the block", 'poolservices') ),
					"group" => esc_html__('Captions', 'poolservices'),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textarea"
				),
				array(
					"param_name" => "description",
					"heading" => esc_html__("Description", 'poolservices'),
					"description" => wp_kses_data( __("Description for the block", 'poolservices') ),
					"group" => esc_html__('Captions', 'poolservices'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('2','3','4','5')
					),
					"class" => "",
					"value" => "",
					"type" => "textarea"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Button URL", 'poolservices'),
					"description" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'poolservices') ),
					"group" => esc_html__('Captions', 'poolservices'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('2','3')
					),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link_caption",
					"heading" => esc_html__("Button caption", 'poolservices'),
					"description" => wp_kses_data( __("Caption for the button at the bottom of the block", 'poolservices') ),
					"group" => esc_html__('Captions', 'poolservices'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('2','3')
					),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link2",
					"heading" => esc_html__("Button 2 URL", 'poolservices'),
					"description" => wp_kses_data( __("Link URL for the second button at the bottom of the block", 'poolservices') ),
					"group" => esc_html__('Captions', 'poolservices'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('2')
					),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link2_caption",
					"heading" => esc_html__("Button 2 caption", 'poolservices'),
					"description" => wp_kses_data( __("Caption for the second button at the bottom of the block", 'poolservices') ),
					"group" => esc_html__('Captions', 'poolservices'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('2')
					),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "url",
					"heading" => esc_html__("Link", 'poolservices'),
					"description" => wp_kses_data( __("Link of the intro block", 'poolservices') ),
					"value" => '',
					"type" => "textfield"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'poolservices'),
					"description" => wp_kses_data( __("Select color scheme for the section with text", 'poolservices') ),
					"class" => "",
					"value" => array_flip(poolservices_get_sc_param('schemes')),
					"type" => "dropdown"
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
			)
		) );
		
		class WPBakeryShortCode_Trx_Intro extends POOLSERVICES_VC_ShortCodeSingle {}
	}
}
?>