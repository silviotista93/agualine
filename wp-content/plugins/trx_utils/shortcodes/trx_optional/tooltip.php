<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('poolservices_sc_tooltip_theme_setup')) {
	add_action( 'poolservices_action_before_init_theme', 'poolservices_sc_tooltip_theme_setup' );
	function poolservices_sc_tooltip_theme_setup() {
		add_action('poolservices_action_shortcodes_list', 		'poolservices_sc_tooltip_reg_shortcodes');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_tooltip id="unique_id" title="Tooltip text here"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/tooltip]
*/

if (!function_exists('poolservices_sc_tooltip')) {	
	function poolservices_sc_tooltip($atts, $content=null){	
		if (poolservices_in_shortcode_blogger()) return '';
		extract(poolservices_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		$output = '<span' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_tooltip_parent'. (!empty($class) ? ' '.esc_attr($class) : '').'"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
					. '>'
						. do_shortcode($content)
						. '<span class="sc_tooltip">' . ($title) . '</span>'
					. '</span>';
		return apply_filters('poolservices_shortcode_output', $output, 'trx_tooltip', $atts, $content);
	}
	poolservices_require_shortcode('trx_tooltip', 'poolservices_sc_tooltip');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'poolservices_sc_tooltip_reg_shortcodes' ) ) {
	//add_action('poolservices_action_shortcodes_list', 'poolservices_sc_tooltip_reg_shortcodes');
	function poolservices_sc_tooltip_reg_shortcodes() {
	
		poolservices_sc_map("trx_tooltip", array(
			"title" => esc_html__("Tooltip", 'poolservices'),
			"desc" => wp_kses_data( __("Create tooltip for selected text", 'poolservices') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"title" => array(
					"title" => esc_html__("Title", 'poolservices'),
					"desc" => wp_kses_data( __("Tooltip title (required)", 'poolservices') ),
					"value" => "",
					"type" => "text"
				),
				"_content_" => array(
					"title" => esc_html__("Tipped content", 'poolservices'),
					"desc" => wp_kses_data( __("Highlighted content with tooltip", 'poolservices') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"id" => poolservices_get_sc_param('id'),
				"class" => poolservices_get_sc_param('class'),
				"css" => poolservices_get_sc_param('css')
			)
		));
	}
}
?>