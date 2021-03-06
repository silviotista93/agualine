<?php
/* Gutenberg support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('poolservices_gutenberg_theme_setup')) {
    add_action( 'poolservices_action_before_init_theme', 'poolservices_gutenberg_theme_setup', 1 );
    function poolservices_gutenberg_theme_setup() {
        if (is_admin()) {
            add_filter( 'poolservices_filter_required_plugins', 'poolservices_gutenberg_required_plugins' );
        }
    }
}

// Check if Instagram Widget installed and activated
if ( !function_exists( 'poolservices_exists_gutenberg' ) ) {
    function poolservices_exists_gutenberg() {
        return function_exists( 'the_gutenberg_project' ) && function_exists( 'register_block_type' );
    }
}

// Filter to add in the required plugins list
if ( !function_exists( 'poolservices_gutenberg_required_plugins' ) ) {
    //add_filter('poolservices_filter_required_plugins',    'poolservices_gutenberg_required_plugins');
    function poolservices_gutenberg_required_plugins($list=array()) {
        if (in_array('gutenberg', (array)poolservices_storage_get('required_plugins')))
            $list[] = array(
                'name'         => esc_html__('Gutenberg', 'poolservices'),
                'slug'         => 'gutenberg',
                'required'     => false
            );
        return $list;
    }
}