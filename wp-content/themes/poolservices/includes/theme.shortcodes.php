<?php
if (!function_exists('poolservices_theme_shortcodes_setup')) {
	add_action( 'poolservices_action_before_init_theme', 'poolservices_theme_shortcodes_setup', 1 );
	function poolservices_theme_shortcodes_setup() {
		add_filter('poolservices_filter_googlemap_styles', 'poolservices_theme_shortcodes_googlemap_styles');
	}
}


// Add theme-specific Google map styles
if ( !function_exists( 'poolservices_theme_shortcodes_googlemap_styles' ) ) {
	function poolservices_theme_shortcodes_googlemap_styles($list) {
		$list['simple']		= esc_html__('Simple', 'poolservices');
		$list['greyscale']	= esc_html__('Greyscale', 'poolservices');
		$list['inverse']	= esc_html__('Inverse', 'poolservices');
		$list['apple']		= esc_html__('Apple', 'poolservices');
		return $list;
	}
}
?>