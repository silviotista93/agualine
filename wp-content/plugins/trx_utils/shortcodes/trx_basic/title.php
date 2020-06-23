<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('poolservices_sc_title_theme_setup')) {
	add_action( 'poolservices_action_before_init_theme', 'poolservices_sc_title_theme_setup' );
	function poolservices_sc_title_theme_setup() {
		add_action('poolservices_action_shortcodes_list', 		'poolservices_sc_title_reg_shortcodes');
		if (function_exists('poolservices_exists_visual_composer') && poolservices_exists_visual_composer())
			add_action('poolservices_action_shortcodes_list_vc','poolservices_sc_title_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_title id="unique_id" style='regular|iconed' icon='' image='' background="on|off" type="1-6"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_title]
*/

if (!function_exists('poolservices_sc_title')) {	
	function poolservices_sc_title($atts, $content=null){	
		if (poolservices_in_shortcode_blogger()) return '';
		extract(poolservices_html_decode(shortcode_atts(array(
			// Individual params
			"type" => "1",
			"style" => "regular",
			"align" => "",
			"font_weight" => "",
			"font_size" => "",
			"color" => "",
			"icon" => "",
			"image" => "",
			"picture" => "",
			"image_size" => "small",
			"position" => "left",
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
		$css .= poolservices_get_css_dimensions_from_values($width)
			.($align && $align!='none' && !poolservices_param_is_inherit($align) ? 'text-align:' . esc_attr($align) .';' : '')
			.($color ? 'color:' . esc_attr($color) .';' : '')
			.($font_weight && !poolservices_param_is_inherit($font_weight) ? 'font-weight:' . esc_attr($font_weight) .';' : '')
			.($font_size   ? 'font-size:' . esc_attr($font_size) .';' : '')
			;
		$type = min(6, max(1, $type));
		if ($picture > 0) {
			$attach = wp_get_attachment_image_src( $picture, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$picture = $attach[0];
		}
		$pic = $style!='iconed' 
			? '' 
			: '<span class="sc_title_icon sc_title_icon_'.esc_attr($position).'  sc_title_icon_'.esc_attr($image_size).($icon!='' && $icon!='none' ? ' '.esc_attr($icon) : '').'"'.'>'
				.($picture ? '<img src="'.esc_url($picture).'" alt="" />' : '')
				.(empty($picture) && $image && $image!='none' ? '<img src="'.esc_url(poolservices_strpos($image, 'http')===0 ? $image : poolservices_get_file_url('images/icons/'.($image).'.png')).'" alt="" />' : '')
				.'</span>';
		$output = '<h' . esc_attr($type) . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_title sc_title_'.esc_attr($style)
					.($align && $align!='none' && !poolservices_param_is_inherit($align) ? ' sc_align_' . esc_attr($align) : '')
					.(!empty($class) ? ' '.esc_attr($class) : '')
					.'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. (!poolservices_param_is_off($animation) ? ' data-animation="'.esc_attr(poolservices_get_animation_classes($animation)).'"' : '')
				. '>'
					. ($pic)
					. ($style=='divider' ? '<span class="sc_title_divider_before"'.($color ? ' style="background-color: '.esc_attr($color).'"' : '').'></span>' : '')
					. do_shortcode($content) 
					. ($style=='divider' ? '<span class="sc_title_divider_after"'.($color ? ' style="background-color: '.esc_attr($color).'"' : '').'></span>' : '')
				. '</h' . esc_attr($type) . '>';
		return apply_filters('poolservices_shortcode_output', $output, 'trx_title', $atts, $content);
	}
	poolservices_require_shortcode('trx_title', 'poolservices_sc_title');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'poolservices_sc_title_reg_shortcodes' ) ) {
	//add_action('poolservices_action_shortcodes_list', 'poolservices_sc_title_reg_shortcodes');
	function poolservices_sc_title_reg_shortcodes() {
	
		poolservices_sc_map("trx_title", array(
			"title" => esc_html__("Title", 'poolservices'),
			"desc" => wp_kses_data( __("Create header tag (1-6 level) with many styles", 'poolservices') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Title content", 'poolservices'),
					"desc" => wp_kses_data( __("Title content", 'poolservices') ),
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"type" => array(
					"title" => esc_html__("Title type", 'poolservices'),
					"desc" => wp_kses_data( __("Title type (header level)", 'poolservices') ),
					"divider" => true,
					"value" => "1",
					"type" => "select",
					"options" => array(
						'1' => esc_html__('Header 1', 'poolservices'),
						'2' => esc_html__('Header 2', 'poolservices'),
						'3' => esc_html__('Header 3', 'poolservices'),
						'4' => esc_html__('Header 4', 'poolservices'),
						'5' => esc_html__('Header 5', 'poolservices'),
						'6' => esc_html__('Header 6', 'poolservices'),
					)
				),
				"style" => array(
					"title" => esc_html__("Title style", 'poolservices'),
					"desc" => wp_kses_data( __("Title style", 'poolservices') ),
					"value" => "regular",
					"type" => "select",
					"options" => array(
						'regular' => esc_html__('Regular', 'poolservices'),
						'underline' => esc_html__('Underline', 'poolservices'),
						'divider' => esc_html__('Divider', 'poolservices'),
						'iconed' => esc_html__('With icon (image)', 'poolservices')
					)
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'poolservices'),
					"desc" => wp_kses_data( __("Title text alignment", 'poolservices') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => poolservices_get_sc_param('align')
				), 
				"font_size" => array(
					"title" => esc_html__("Font_size", 'poolservices'),
					"desc" => wp_kses_data( __("Custom font size. If empty - use theme default", 'poolservices') ),
					"value" => "",
					"type" => "text"
				),
				"font_weight" => array(
					"title" => esc_html__("Font weight", 'poolservices'),
					"desc" => wp_kses_data( __("Custom font weight. If empty or inherit - use theme default", 'poolservices') ),
					"value" => "",
					"type" => "select",
					"size" => "medium",
					"options" => array(
						'inherit' => esc_html__('Default', 'poolservices'),
						'100' => esc_html__('Thin (100)', 'poolservices'),
						'300' => esc_html__('Light (300)', 'poolservices'),
						'400' => esc_html__('Normal (400)', 'poolservices'),
						'600' => esc_html__('Semibold (600)', 'poolservices'),
						'700' => esc_html__('Bold (700)', 'poolservices'),
						'900' => esc_html__('Black (900)', 'poolservices')
					)
				),
				"color" => array(
					"title" => esc_html__("Title color", 'poolservices'),
					"desc" => wp_kses_data( __("Select color for the title", 'poolservices') ),
					"value" => "",
					"type" => "color"
				),
				"icon" => array(
					"title" => esc_html__('Title font icon',  'poolservices'),
					"desc" => wp_kses_data( __("Select font icon for the title from Fontello icons set (if style=iconed)",  'poolservices') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "icons",
					"options" => poolservices_get_sc_param('icons')
				),
				"image" => array(
					"title" => esc_html__('or image icon',  'poolservices'),
					"desc" => wp_kses_data( __("Select image icon for the title instead icon above (if style=iconed)",  'poolservices') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "images",
					"size" => "small",
					"options" => poolservices_get_sc_param('images')
				),
				"picture" => array(
					"title" => esc_html__('or URL for image file', 'poolservices'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site (if style=iconed)", 'poolservices') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"image_size" => array(
					"title" => esc_html__('Image (picture) size', 'poolservices'),
					"desc" => wp_kses_data( __("Select image (picture) size (if style='iconed')", 'poolservices') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "small",
					"type" => "checklist",
					"options" => array(
						'small' => esc_html__('Small', 'poolservices'),
						'medium' => esc_html__('Medium', 'poolservices'),
						'large' => esc_html__('Large', 'poolservices')
					)
				),
				"position" => array(
					"title" => esc_html__('Icon (image) position', 'poolservices'),
					"desc" => wp_kses_data( __("Select icon (image) position (if style=iconed)", 'poolservices') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "left",
					"type" => "checklist",
					"options" => array(
						'top' => esc_html__('Top', 'poolservices'),
						'left' => esc_html__('Left', 'poolservices')
					)
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
if ( !function_exists( 'poolservices_sc_title_reg_shortcodes_vc' ) ) {
	//add_action('poolservices_action_shortcodes_list_vc', 'poolservices_sc_title_reg_shortcodes_vc');
	function poolservices_sc_title_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_title",
			"name" => esc_html__("Title", 'poolservices'),
			"description" => wp_kses_data( __("Create header tag (1-6 level) with many styles", 'poolservices') ),
			"category" => esc_html__('Content', 'poolservices'),
			'icon' => 'icon_trx_title',
			"class" => "trx_sc_single trx_sc_title",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "content",
					"heading" => esc_html__("Title content", 'poolservices'),
					"description" => wp_kses_data( __("Title content", 'poolservices') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				array(
					"param_name" => "type",
					"heading" => esc_html__("Title type", 'poolservices'),
					"description" => wp_kses_data( __("Title type (header level)", 'poolservices') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Header 1', 'poolservices') => '1',
						esc_html__('Header 2', 'poolservices') => '2',
						esc_html__('Header 3', 'poolservices') => '3',
						esc_html__('Header 4', 'poolservices') => '4',
						esc_html__('Header 5', 'poolservices') => '5',
						esc_html__('Header 6', 'poolservices') => '6'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Title style", 'poolservices'),
					"description" => wp_kses_data( __("Title style: only text (regular) or with icon/image (iconed)", 'poolservices') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Regular', 'poolservices') => 'regular',
						esc_html__('Underline', 'poolservices') => 'underline',
						esc_html__('Divider', 'poolservices') => 'divider',
						esc_html__('With icon (image)', 'poolservices') => 'iconed'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'poolservices'),
					"description" => wp_kses_data( __("Title text alignment", 'poolservices') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(poolservices_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'poolservices'),
					"description" => wp_kses_data( __("Custom font size. If empty - use theme default", 'poolservices') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "font_weight",
					"heading" => esc_html__("Font weight", 'poolservices'),
					"description" => wp_kses_data( __("Custom font weight. If empty or inherit - use theme default", 'poolservices') ),
					"class" => "",
					"value" => array(
						esc_html__('Default', 'poolservices') => 'inherit',
						esc_html__('Thin (100)', 'poolservices') => '100',
						esc_html__('Light (300)', 'poolservices') => '300',
						esc_html__('Normal (400)', 'poolservices') => '400',
						esc_html__('Semibold (600)', 'poolservices') => '600',
						esc_html__('Bold (700)', 'poolservices') => '700',
						esc_html__('Black (900)', 'poolservices') => '900'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Title color", 'poolservices'),
					"description" => wp_kses_data( __("Select color for the title", 'poolservices') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Title font icon", 'poolservices'),
					"description" => wp_kses_data( __("Select font icon for the title from Fontello icons set (if style=iconed)", 'poolservices') ),
					"class" => "",
					"group" => esc_html__('Icon &amp; Image', 'poolservices'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => poolservices_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("or image icon", 'poolservices'),
					"description" => wp_kses_data( __("Select image icon for the title instead icon above (if style=iconed)", 'poolservices') ),
					"class" => "",
					"group" => esc_html__('Icon &amp; Image', 'poolservices'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => poolservices_get_sc_param('images'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "picture",
					"heading" => esc_html__("or select uploaded image", 'poolservices'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site (if style=iconed)", 'poolservices') ),
					"group" => esc_html__('Icon &amp; Image', 'poolservices'),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "image_size",
					"heading" => esc_html__("Image (picture) size", 'poolservices'),
					"description" => wp_kses_data( __("Select image (picture) size (if style=iconed)", 'poolservices') ),
					"group" => esc_html__('Icon &amp; Image', 'poolservices'),
					"class" => "",
					"value" => array(
						esc_html__('Small', 'poolservices') => 'small',
						esc_html__('Medium', 'poolservices') => 'medium',
						esc_html__('Large', 'poolservices') => 'large'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "position",
					"heading" => esc_html__("Icon (image) position", 'poolservices'),
					"description" => wp_kses_data( __("Select icon (image) position (if style=iconed)", 'poolservices') ),
					"group" => esc_html__('Icon &amp; Image', 'poolservices'),
					"class" => "",
					"std" => "left",
					"value" => array(
						esc_html__('Top', 'poolservices') => 'top',
						esc_html__('Left', 'poolservices') => 'left'
					),
					"type" => "dropdown"
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
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Title extends POOLSERVICES_VC_ShortCodeSingle {}
	}
}
?>