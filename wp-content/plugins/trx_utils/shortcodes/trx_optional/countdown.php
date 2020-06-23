<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('poolservices_sc_countdown_theme_setup')) {
	add_action( 'poolservices_action_before_init_theme', 'poolservices_sc_countdown_theme_setup' );
	function poolservices_sc_countdown_theme_setup() {
		add_action('poolservices_action_shortcodes_list', 		'poolservices_sc_countdown_reg_shortcodes');
		if (function_exists('poolservices_exists_visual_composer') && poolservices_exists_visual_composer())
			add_action('poolservices_action_shortcodes_list_vc','poolservices_sc_countdown_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */


if (!function_exists('poolservices_sc_countdown')) {	
	function poolservices_sc_countdown($atts, $content = null) {
		if (poolservices_in_shortcode_blogger()) return '';
		extract(poolservices_html_decode(shortcode_atts(array(
			// Individual params
			"date" => "",
			"time" => "",
			"style" => "1",
			"align" => "center",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => "",
			"width" => "",
			"height" => ""
		), $atts)));
		if (empty($id)) $id = "sc_countdown_".str_replace('.', '', mt_rand());
		$class .= ($class ? ' ' : '') . poolservices_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= poolservices_get_css_dimensions_from_values($width, $height);
		if (empty($interval)) $interval = 1;
		wp_enqueue_script( 'poolservices-jquery-plugin-script', poolservices_get_file_url('js/countdown/jquery.plugin.js'), array('jquery'), null, true );	
		wp_enqueue_script( 'poolservices-countdown-script', poolservices_get_file_url('js/countdown/jquery.countdown.js'), array('jquery'), null, true );	
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
			. ' class="sc_countdown sc_countdown_style_' . esc_attr(max(1, min(2, $style))) . (!empty($align) && $align!='none' ? ' align'.esc_attr($align) : '') . (!empty($class) ? ' '.esc_attr($class) : '') .'"'
			. ($css ? ' style="'.esc_attr($css).'"' : '')
			. ' data-date="'.esc_attr(empty($date) ? date('Y-m-d') : $date).'"'
			. ' data-time="'.esc_attr(empty($time) ? '00:00:00' : $time).'"'
			. (!poolservices_param_is_off($animation) ? ' data-animation="'.esc_attr(poolservices_get_animation_classes($animation)).'"' : '')
			. '>'
				. ($align=='center' ? '<div class="sc_countdown_inner">' : '')
				. '<div class="sc_countdown_item sc_countdown_days">'
					. '<span class="sc_countdown_digits"><span></span><span></span><span></span></span>'
					. '<span class="sc_countdown_label">'.esc_html__('Days', 'poolservices').'</span>'
				. '</div>'
				. '<div class="sc_countdown_item sc_countdown_hours">'
					. '<span class="sc_countdown_digits"><span></span><span></span></span>'
					. '<span class="sc_countdown_label">'.esc_html__('Hours', 'poolservices').'</span>'
				. '</div>'
				. '<div class="sc_countdown_item sc_countdown_minutes">'
					. '<span class="sc_countdown_digits"><span></span><span></span></span>'
					. '<span class="sc_countdown_label">'.esc_html__('Minutes', 'poolservices').'</span>'
				. '</div>'
				. '<div class="sc_countdown_item sc_countdown_seconds">'
					. '<span class="sc_countdown_digits"><span></span><span></span></span>'
					. '<span class="sc_countdown_label">'.esc_html__('Seconds', 'poolservices').'</span>'
				. '</div>'
				. '<div class="sc_countdown_placeholder hide"></div>'
				. ($align=='center' ? '</div>' : '')
			. '</div>';
		return apply_filters('poolservices_shortcode_output', $output, 'trx_countdown', $atts, $content);
	}
	poolservices_require_shortcode("trx_countdown", "poolservices_sc_countdown");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'poolservices_sc_countdown_reg_shortcodes' ) ) {
	//add_action('poolservices_action_shortcodes_list', 'poolservices_sc_countdown_reg_shortcodes');
	function poolservices_sc_countdown_reg_shortcodes() {
	
		poolservices_sc_map("trx_countdown", array(
			"title" => esc_html__("Countdown", 'poolservices'),
			"desc" => wp_kses_data( __("Insert countdown object", 'poolservices') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"date" => array(
					"title" => esc_html__("Date", 'poolservices'),
					"desc" => wp_kses_data( __("Upcoming date (format: yyyy-mm-dd)", 'poolservices') ),
					"value" => "",
					"format" => "yy-mm-dd",
					"type" => "date"
				),
				"time" => array(
					"title" => esc_html__("Time", 'poolservices'),
					"desc" => wp_kses_data( __("Upcoming time (format: HH:mm:ss)", 'poolservices') ),
					"value" => "",
					"type" => "text"
				),
				"style" => array(
					"title" => esc_html__("Style", 'poolservices'),
					"desc" => wp_kses_data( __("Countdown style", 'poolservices') ),
					"value" => "1",
					"type" => "checklist",
					"options" => poolservices_get_list_styles(1, 2)
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'poolservices'),
					"desc" => wp_kses_data( __("Align counter to left, center or right", 'poolservices') ),
					"divider" => true,
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => poolservices_get_sc_param('align')
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
if ( !function_exists( 'poolservices_sc_countdown_reg_shortcodes_vc' ) ) {
	//add_action('poolservices_action_shortcodes_list_vc', 'poolservices_sc_countdown_reg_shortcodes_vc');
	function poolservices_sc_countdown_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_countdown",
			"name" => esc_html__("Countdown", 'poolservices'),
			"description" => wp_kses_data( __("Insert countdown object", 'poolservices') ),
			"category" => esc_html__('Content', 'poolservices'),
			'icon' => 'icon_trx_countdown',
			"class" => "trx_sc_single trx_sc_countdown",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "date",
					"heading" => esc_html__("Date", 'poolservices'),
					"description" => wp_kses_data( __("Upcoming date (format: yyyy-mm-dd)", 'poolservices') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "time",
					"heading" => esc_html__("Time", 'poolservices'),
					"description" => wp_kses_data( __("Upcoming time (format: HH:mm:ss)", 'poolservices') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'poolservices'),
					"description" => wp_kses_data( __("Countdown style", 'poolservices') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(poolservices_get_list_styles(1, 2)),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'poolservices'),
					"description" => wp_kses_data( __("Align counter to left, center or right", 'poolservices') ),
					"class" => "",
					"value" => array_flip(poolservices_get_sc_param('align')),
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
		
		class WPBakeryShortCode_Trx_Countdown extends POOLSERVICES_VC_ShortCodeSingle {}
	}
}
?>