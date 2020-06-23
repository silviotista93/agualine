<?php
/* Instagram Feed support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('poolservices_instagram_feed_theme_setup')) {
	add_action( 'poolservices_action_before_init_theme', 'poolservices_instagram_feed_theme_setup', 1 );
	function poolservices_instagram_feed_theme_setup() {
		if (poolservices_exists_instagram_feed()) {
			if (is_admin()) {
				add_filter( 'poolservices_filter_importer_options',				'poolservices_instagram_feed_importer_set_options' );
			}
		}
		if (is_admin()) {
			add_filter( 'poolservices_filter_importer_required_plugins',		'poolservices_instagram_feed_importer_required_plugins', 10, 2 );
			add_filter( 'poolservices_filter_required_plugins',					'poolservices_instagram_feed_required_plugins' );
		}
	}
}

// Check if Instagram Feed installed and activated
if ( !function_exists( 'poolservices_exists_instagram_feed' ) ) {
	function poolservices_exists_instagram_feed() {
		return defined('SBIVER');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'poolservices_instagram_feed_required_plugins' ) ) {
	//Handler of add_filter('poolservices_filter_required_plugins',	'poolservices_instagram_feed_required_plugins');
	function poolservices_instagram_feed_required_plugins($list=array()) {
		if (in_array('instagram_feed', poolservices_storage_get('required_plugins')))
			$list[] = array(
					'name' 		=> esc_html__('Instagram Feed', 'poolservices'),
					'slug' 		=> 'instagram-feed',
					'required' 	=> false
				);
		return $list;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check Instagram Feed in the required plugins
if ( !function_exists( 'poolservices_instagram_feed_importer_required_plugins' ) ) {
	//Handler of add_filter( 'poolservices_filter_importer_required_plugins',	'poolservices_instagram_feed_importer_required_plugins', 10, 2 );
	function poolservices_instagram_feed_importer_required_plugins($not_installed='', $list='') {
		if (poolservices_strpos($list, 'instagram_feed')!==false && !poolservices_exists_instagram_feed() )
			$not_installed .= '<br>' . esc_html__('Instagram Feed', 'poolservices');
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'poolservices_instagram_feed_importer_set_options' ) ) {
	//Handler of add_filter( 'poolservices_filter_importer_options',	'poolservices_instagram_feed_importer_set_options' );
	function poolservices_instagram_feed_importer_set_options($options=array()) {
		if ( in_array('instagram_feed', poolservices_storage_get('required_plugins')) && poolservices_exists_instagram_feed() ) {
			// Add slugs to export options for this plugin
			$options['additional_options'][] = 'sb_instagram_settings';
		}
		return $options;
	}
}
?>