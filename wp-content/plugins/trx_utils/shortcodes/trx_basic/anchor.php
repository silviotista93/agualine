<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('poolservices_sc_anchor_theme_setup')) {
	add_action( 'poolservices_action_before_init_theme', 'poolservices_sc_anchor_theme_setup' );
	function poolservices_sc_anchor_theme_setup() {
		add_action('poolservices_action_shortcodes_list', 		'poolservices_sc_anchor_reg_shortcodes');
		if (function_exists('poolservices_exists_visual_composer') && poolservices_exists_visual_composer())
			add_action('poolservices_action_shortcodes_list_vc','poolservices_sc_anchor_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_anchor id="unique_id" description="Anchor description" title="Short Caption" icon="icon-class"]
*/

if (!function_exists('poolservices_sc_anchor')) {	
	function poolservices_sc_anchor($atts, $content = null) {
		if (poolservices_in_shortcode_blogger()) return '';
		extract(poolservices_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"description" => '',
			"icon" => '',
			"url" => "",
			"separator" => "no",
			// Common params
			"id" => ""
		), $atts)));
		$output = $id 
			? '<a id="'.esc_attr($id).'"'
				. ' class="sc_anchor"' 
				. ' title="' . ($title ? esc_attr($title) : '') . '"'
				. ' data-description="' . ($description ? esc_attr(poolservices_strmacros($description)) : ''). '"'
				. ' data-icon="' . ($icon ? $icon : '') . '"' 
				. ' data-url="' . ($url ? esc_attr($url) : '') . '"' 
				. ' data-separator="' . (poolservices_param_is_on($separator) ? 'yes' : 'no') . '"'
				. '></a>'
			: '';
		return apply_filters('poolservices_shortcode_output', $output, 'trx_anchor', $atts, $content);
	}
	poolservices_require_shortcode("trx_anchor", "poolservices_sc_anchor");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'poolservices_sc_anchor_reg_shortcodes' ) ) {
	//add_action('poolservices_action_shortcodes_list', 'poolservices_sc_anchor_reg_shortcodes');
	function poolservices_sc_anchor_reg_shortcodes() {
	
		poolservices_sc_map("trx_anchor", array(
			"title" => esc_html__("Anchor", 'poolservices'),
			"desc" => wp_kses_data( __("Insert anchor for the TOC (table of content)", 'poolservices') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"icon" => array(
					"title" => esc_html__("Anchor's icon",  'poolservices'),
					"desc" => wp_kses_data( __('Select icon for the anchor from Fontello icons set',  'poolservices') ),
					"value" => "",
					"type" => "icons",
					"options" => poolservices_get_sc_param('icons')
				),
				"title" => array(
					"title" => esc_html__("Short title", 'poolservices'),
					"desc" => wp_kses_data( __("Short title of the anchor (for the table of content)", 'poolservices') ),
					"value" => "",
					"type" => "text"
				),
				"description" => array(
					"title" => esc_html__("Long description", 'poolservices'),
					"desc" => wp_kses_data( __("Description for the popup (then hover on the icon). You can use:<br>'{{' and '}}' - to make the text italic,<br>'((' and '))' - to make the text bold,<br>'||' - to insert line break", 'poolservices') ),
					"value" => "",
					"type" => "text"
				),
				"url" => array(
					"title" => esc_html__("External URL", 'poolservices'),
					"desc" => wp_kses_data( __("External URL for this TOC item", 'poolservices') ),
					"value" => "",
					"type" => "text"
				),
				"separator" => array(
					"title" => esc_html__("Add separator", 'poolservices'),
					"desc" => wp_kses_data( __("Add separator under item in the TOC", 'poolservices') ),
					"value" => "no",
					"type" => "switch",
					"options" => poolservices_get_sc_param('yes_no')
				),
				"id" => poolservices_get_sc_param('id')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'poolservices_sc_anchor_reg_shortcodes_vc' ) ) {
	//add_action('poolservices_action_shortcodes_list_vc', 'poolservices_sc_anchor_reg_shortcodes_vc');
	function poolservices_sc_anchor_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_anchor",
			"name" => esc_html__("Anchor", 'poolservices'),
			"description" => wp_kses_data( __("Insert anchor for the TOC (table of content)", 'poolservices') ),
			"category" => esc_html__('Content', 'poolservices'),
			'icon' => 'icon_trx_anchor',
			"class" => "trx_sc_single trx_sc_anchor",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Anchor's icon", 'poolservices'),
					"description" => wp_kses_data( __("Select icon for the anchor from Fontello icons set", 'poolservices') ),
					"class" => "",
					"value" => poolservices_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Short title", 'poolservices'),
					"description" => wp_kses_data( __("Short title of the anchor (for the table of content)", 'poolservices') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "description",
					"heading" => esc_html__("Long description", 'poolservices'),
					"description" => wp_kses_data( __("Description for the popup (then hover on the icon). You can use:<br>'{{' and '}}' - to make the text italic,<br>'((' and '))' - to make the text bold,<br>'||' - to insert line break", 'poolservices') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "url",
					"heading" => esc_html__("External URL", 'poolservices'),
					"description" => wp_kses_data( __("External URL for this TOC item", 'poolservices') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "separator",
					"heading" => esc_html__("Add separator", 'poolservices'),
					"description" => wp_kses_data( __("Add separator under item in the TOC", 'poolservices') ),
					"class" => "",
					"value" => array("Add separator" => "yes" ),
					"type" => "checkbox"
				),
				poolservices_get_vc_param('id')
			),
		) );
		
		class WPBakeryShortCode_Trx_Anchor extends POOLSERVICES_VC_ShortCodeSingle {}
	}
}
?>