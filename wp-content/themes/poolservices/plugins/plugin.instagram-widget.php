<?php
/* Instagram Widget support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('poolservices_instagram_widget_theme_setup')) {
	add_action( 'poolservices_action_before_init_theme', 'poolservices_instagram_widget_theme_setup', 1 );
	function poolservices_instagram_widget_theme_setup() {
		if (poolservices_exists_instagram_widget()) {
			add_action( 'poolservices_action_add_styles', 						'poolservices_instagram_widget_frontend_scripts' );
		}
		if (is_admin()) {
			add_filter( 'poolservices_filter_importer_required_plugins',		'poolservices_instagram_widget_importer_required_plugins', 10, 2 );
			add_filter( 'poolservices_filter_required_plugins',					'poolservices_instagram_widget_required_plugins' );
		}
	}
}

// Check if Instagram Widget installed and activated
if ( !function_exists( 'poolservices_exists_instagram_widget' ) ) {
	function poolservices_exists_instagram_widget() {
		return function_exists('wpiw_init');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'poolservices_instagram_widget_required_plugins' ) ) {
	//Handler of add_filter('poolservices_filter_required_plugins',	'poolservices_instagram_widget_required_plugins');
	function poolservices_instagram_widget_required_plugins($list=array()) {
		if (in_array('instagram_widget', poolservices_storage_get('required_plugins')))
			$list[] = array(
					'name' 		=> esc_html__('Instagram Widget', 'poolservices'),
					'slug' 		=> 'wp-instagram-widget',
					'required' 	=> false
				);
		return $list;
	}
}

// Enqueue custom styles
if ( !function_exists( 'poolservices_instagram_widget_frontend_scripts' ) ) {
	//Handler of add_action( 'poolservices_action_add_styles', 'poolservices_instagram_widget_frontend_scripts' );
	function poolservices_instagram_widget_frontend_scripts() {
		if (file_exists(poolservices_get_file_dir('css/plugin.instagram-widget.css')))
			wp_enqueue_style( 'poolservices-plugin.instagram-widget-style',  poolservices_get_file_url('css/plugin.instagram-widget.css'), array(), null );
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check Instagram Widget in the required plugins
if ( !function_exists( 'poolservices_instagram_widget_importer_required_plugins' ) ) {
	//Handler of add_filter( 'poolservices_filter_importer_required_plugins',	'poolservices_instagram_widget_importer_required_plugins', 10, 2 );
	function poolservices_instagram_widget_importer_required_plugins($not_installed='', $list='') {
		if (poolservices_strpos($list, 'instagram_widget')!==false && !poolservices_exists_instagram_widget() )
			$not_installed .= '<br>' . esc_html__('WP Instagram Widget', 'poolservices');
		return $not_installed;
	}
}
?>