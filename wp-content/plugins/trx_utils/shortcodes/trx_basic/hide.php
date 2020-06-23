<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('poolservices_sc_hide_theme_setup')) {
	add_action( 'poolservices_action_before_init_theme', 'poolservices_sc_hide_theme_setup' );
	function poolservices_sc_hide_theme_setup() {
		add_action('poolservices_action_shortcodes_list', 		'poolservices_sc_hide_reg_shortcodes');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_hide selector="unique_id"]
*/

if (!function_exists('poolservices_sc_hide')) {	
	function poolservices_sc_hide($atts, $content=null){	
		if (poolservices_in_shortcode_blogger()) return '';
		extract(poolservices_html_decode(shortcode_atts(array(
			// Individual params
			"selector" => "",
			"hide" => "on",
			"delay" => 0
		), $atts)));
		$selector = trim(chop($selector));
		if (!empty($selector)) {
			poolservices_storage_concat('js_code', '
				'.($delay>0 ? 'setTimeout(function() {' : '').'
					jQuery("'.esc_attr($selector).'").' . ($hide=='on' ? 'hide' : 'show') . '();
				'.($delay>0 ? '},'.($delay).');' : '').'
			');
		}
		return apply_filters('poolservices_shortcode_output', $output, 'trx_hide', $atts, $content);
	}
	poolservices_require_shortcode('trx_hide', 'poolservices_sc_hide');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'poolservices_sc_hide_reg_shortcodes' ) ) {
	//add_action('poolservices_action_shortcodes_list', 'poolservices_sc_hide_reg_shortcodes');
	function poolservices_sc_hide_reg_shortcodes() {
	
		poolservices_sc_map("trx_hide", array(
			"title" => esc_html__("Hide/Show any block", 'poolservices'),
			"desc" => wp_kses_data( __("Hide or Show any block with desired CSS-selector", 'poolservices') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"selector" => array(
					"title" => esc_html__("Selector", 'poolservices'),
					"desc" => wp_kses_data( __("Any block's CSS-selector", 'poolservices') ),
					"value" => "",
					"type" => "text"
				),
				"hide" => array(
					"title" => esc_html__("Hide or Show", 'poolservices'),
					"desc" => wp_kses_data( __("New state for the block: hide or show", 'poolservices') ),
					"value" => "yes",
					"size" => "small",
					"options" => poolservices_get_sc_param('yes_no'),
					"type" => "switch"
				)
			)
		));
	}
}
?>