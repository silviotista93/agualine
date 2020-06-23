<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('poolservices_sc_br_theme_setup')) {
	add_action( 'poolservices_action_before_init_theme', 'poolservices_sc_br_theme_setup' );
	function poolservices_sc_br_theme_setup() {
		add_action('poolservices_action_shortcodes_list', 		'poolservices_sc_br_reg_shortcodes');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_br clear="left|right|both"]
*/

if (!function_exists('poolservices_sc_br')) {	
	function poolservices_sc_br($atts, $content = null) {
		if (poolservices_in_shortcode_blogger()) return '';
		extract(poolservices_html_decode(shortcode_atts(array(
			"clear" => ""
		), $atts)));
		$output = in_array($clear, array('left', 'right', 'both', 'all')) 
			? '<div class="clearfix" style="clear:' . str_replace('all', 'both', $clear) . '"></div>'
			: '<br />';
		return apply_filters('poolservices_shortcode_output', $output, 'trx_br', $atts, $content);
	}
	poolservices_require_shortcode("trx_br", "poolservices_sc_br");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'poolservices_sc_br_reg_shortcodes' ) ) {
	//add_action('poolservices_action_shortcodes_list', 'poolservices_sc_br_reg_shortcodes');
	function poolservices_sc_br_reg_shortcodes() {
	
		poolservices_sc_map("trx_br", array(
			"title" => esc_html__("Break", 'poolservices'),
			"desc" => wp_kses_data( __("Line break with clear floating (if need)", 'poolservices') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"clear" => 	array(
					"title" => esc_html__("Clear floating", 'poolservices'),
					"desc" => wp_kses_data( __("Clear floating (if need)", 'poolservices') ),
					"value" => "",
					"type" => "checklist",
					"options" => array(
						'none' => esc_html__('None', 'poolservices'),
						'left' => esc_html__('Left', 'poolservices'),
						'right' => esc_html__('Right', 'poolservices'),
						'both' => esc_html__('Both', 'poolservices')
					)
				)
			)
		));
	}
}
?>