<?php
/* WP GDPR Compliance support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('poolservices_wp_gdpr_compliance_theme_setup')) {
    add_action( 'poolservices_action_before_init_theme', 'poolservices_wp_gdpr_compliance_theme_setup', 1 );
    function poolservices_wp_gdpr_compliance_theme_setup() {
        if (is_admin()) {
            add_filter( 'poolservices_filter_required_plugins', 'poolservices_wp_gdpr_compliance_required_plugins' );
        }
    }
}

// Check if Instagram Widget installed and activated
if ( !function_exists( 'poolservices_exists_wp_gdpr_compliance' ) ) {
    function poolservices_exists_wp_gdpr_compliance() {
        return defined( 'WP_GDPR_Compliance_VERSION' );
    }
}

// Filter to add in the required plugins list
if ( !function_exists( 'poolservices_wp_gdpr_compliance_required_plugins' ) ) {
    //add_filter('poolservices_filter_required_plugins',    'poolservices_wp_gdpr_compliance_required_plugins');
    function poolservices_wp_gdpr_compliance_required_plugins($list=array()) {
        if (in_array('wp_gdpr_compliance', (array)poolservices_storage_get('required_plugins')))
            $list[] = array(
                'name'         => esc_html__('WP GDPR Compliance', 'poolservices'),
                'slug'         => 'wp-gdpr-compliance',
                'required'     => false
            );
        return $list;
    }
}
