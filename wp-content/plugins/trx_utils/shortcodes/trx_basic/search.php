<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('poolservices_sc_search_theme_setup')) {
	add_action( 'poolservices_action_before_init_theme', 'poolservices_sc_search_theme_setup' );
	function poolservices_sc_search_theme_setup() {
		add_action('poolservices_action_shortcodes_list', 		'poolservices_sc_search_reg_shortcodes');
		if (function_exists('poolservices_exists_visual_composer') && poolservices_exists_visual_composer())
			add_action('poolservices_action_shortcodes_list_vc','poolservices_sc_search_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_search id="unique_id" open="yes|no"]
*/

if (!function_exists('poolservices_sc_search')) {	
	function poolservices_sc_search($atts, $content=null){	
		if (poolservices_in_shortcode_blogger()) return '';
		extract(poolservices_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "",
			"state" => "",
			"ajax" => "",
			"title" => esc_html__('Search', 'poolservices'),
			"scheme" => "original",
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
		if ($style == 'fullscreen') {
			if (empty($ajax)) $ajax = "no";
			if (empty($state)) $state = "closed";
		} else if ($style == 'expand') {
			if (empty($ajax)) $ajax = poolservices_get_theme_option('use_ajax_search');
			if (empty($state)) $state = "closed";
		} else if ($style == 'slide') {
			if (empty($ajax)) $ajax = poolservices_get_theme_option('use_ajax_search');
			if (empty($state)) $state = "closed";
		} else {
			if (empty($ajax)) $ajax = poolservices_get_theme_option('use_ajax_search');
			if (empty($state)) $state = "fixed";
		}
		// Load core messages
		poolservices_enqueue_messages();
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') . ' class="search_wrap search_style_'.esc_attr($style).' search_state_'.esc_attr($state)
						. (poolservices_param_is_on($ajax) ? ' search_ajax' : '')
						. ($class ? ' '.esc_attr($class) : '')
						. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!poolservices_param_is_off($animation) ? ' data-animation="'.esc_attr(poolservices_get_animation_classes($animation)).'"' : '')
					. '>
						<div class="search_form_wrap">
							<form role="search" method="get" class="search_form" action="' . esc_url(home_url('/')) . '">
								<button type="submit" class="search_submit icon-search" title="' . ($state=='closed' ? esc_attr__('Open search', 'poolservices') : esc_attr__('Start search', 'poolservices')) . '"></button>
								<input type="text" class="search_field" placeholder="' . esc_attr($title) . '" value="' . esc_attr(get_search_query()) . '" name="s" />'
								. ($style == 'fullscreen' ? '<a class="search_close icon-cancel"></a>' : '')
							. '</form>
						</div>'
						. (poolservices_param_is_on($ajax) ? '<div class="search_results widget_area' . ($scheme && !poolservices_param_is_off($scheme) && !poolservices_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') . '"><a class="search_results_close icon-cancel"></a><div class="search_results_content"></div></div>' : '')
					. '</div>';
		return apply_filters('poolservices_shortcode_output', $output, 'trx_search', $atts, $content);
	}
	poolservices_require_shortcode('trx_search', 'poolservices_sc_search');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'poolservices_sc_search_reg_shortcodes' ) ) {
	//add_action('poolservices_action_shortcodes_list', 'poolservices_sc_search_reg_shortcodes');
	function poolservices_sc_search_reg_shortcodes() {
	
		poolservices_sc_map("trx_search", array(
			"title" => esc_html__("Search", 'poolservices'),
			"desc" => wp_kses_data( __("Show search form", 'poolservices') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", 'poolservices'),
					"desc" => wp_kses_data( __("Select style to display search field", 'poolservices') ),
					"value" => "regular",
					"options" => poolservices_get_list_search_styles(),
					"type" => "checklist"
				),
				"state" => array(
					"title" => esc_html__("State", 'poolservices'),
					"desc" => wp_kses_data( __("Select search field initial state", 'poolservices') ),
					"value" => "fixed",
					"options" => array(
						"fixed"  => esc_html__('Fixed',  'poolservices'),
						"opened" => esc_html__('Opened', 'poolservices'),
						"closed" => esc_html__('Closed', 'poolservices')
					),
					"type" => "checklist"
				),
				"title" => array(
					"title" => esc_html__("Title", 'poolservices'),
					"desc" => wp_kses_data( __("Title (placeholder) for the search field", 'poolservices') ),
					"value" => esc_html__("Search &hellip;", 'poolservices'),
					"type" => "text"
				),
				"ajax" => array(
					"title" => esc_html__("AJAX", 'poolservices'),
					"desc" => wp_kses_data( __("Search via AJAX or reload page", 'poolservices') ),
					"value" => "yes",
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
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'poolservices_sc_search_reg_shortcodes_vc' ) ) {
	//add_action('poolservices_action_shortcodes_list_vc', 'poolservices_sc_search_reg_shortcodes_vc');
	function poolservices_sc_search_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_search",
			"name" => esc_html__("Search form", 'poolservices'),
			"description" => wp_kses_data( __("Insert search form", 'poolservices') ),
			"category" => esc_html__('Content', 'poolservices'),
			'icon' => 'icon_trx_search',
			"class" => "trx_sc_single trx_sc_search",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'poolservices'),
					"description" => wp_kses_data( __("Select style to display search field", 'poolservices') ),
					"class" => "",
					"value" => poolservices_get_list_search_styles(),
					"type" => "dropdown"
				),
				array(
					"param_name" => "state",
					"heading" => esc_html__("State", 'poolservices'),
					"description" => wp_kses_data( __("Select search field initial state", 'poolservices') ),
					"class" => "",
					"value" => array(
						esc_html__('Fixed', 'poolservices')  => "fixed",
						esc_html__('Opened', 'poolservices') => "opened",
						esc_html__('Closed', 'poolservices') => "closed"
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'poolservices'),
					"description" => wp_kses_data( __("Title (placeholder) for the search field", 'poolservices') ),
					"admin_label" => true,
					"class" => "",
					"value" => esc_html__("Search &hellip;", 'poolservices'),
					"type" => "textfield"
				),
				array(
					"param_name" => "ajax",
					"heading" => esc_html__("AJAX", 'poolservices'),
					"description" => wp_kses_data( __("Search via AJAX or reload page", 'poolservices') ),
					"class" => "",
					"value" => array(esc_html__('Use AJAX search', 'poolservices') => 'yes'),
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
			)
		) );
		
		class WPBakeryShortCode_Trx_Search extends POOLSERVICES_VC_ShortCodeSingle {}
	}
}
?>