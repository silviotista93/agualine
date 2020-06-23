<?php
/* Mail Chimp support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('poolservices_mailchimp_theme_setup')) {
	add_action( 'poolservices_action_before_init_theme', 'poolservices_mailchimp_theme_setup', 1 );
	function poolservices_mailchimp_theme_setup() {
		if (poolservices_exists_mailchimp()) {
			if (is_admin()) {
				add_filter( 'poolservices_filter_importer_options',				'poolservices_mailchimp_importer_set_options' );
				add_action( 'poolservices_action_importer_params',				'poolservices_mailchimp_importer_show_params', 10, 1 );
				add_filter( 'poolservices_filter_importer_import_row',			'poolservices_mailchimp_importer_check_row', 9, 4);
			}
		}
		if (is_admin()) {
			add_filter( 'poolservices_filter_importer_required_plugins',		'poolservices_mailchimp_importer_required_plugins', 10, 2 );
			add_filter( 'poolservices_filter_required_plugins',					'poolservices_mailchimp_required_plugins' );
		}
	}
}

// Check if Instagram Feed installed and activated
if ( !function_exists( 'poolservices_exists_mailchimp' ) ) {
	function poolservices_exists_mailchimp() {
		return function_exists('mc4wp_load_plugin');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'poolservices_mailchimp_required_plugins' ) ) {
	//Handler of add_filter('poolservices_filter_required_plugins',	'poolservices_mailchimp_required_plugins');
	function poolservices_mailchimp_required_plugins($list=array()) {
		if (in_array('mailchimp', poolservices_storage_get('required_plugins')))
			$list[] = array(
				'name' 		=> esc_html__('MailChimp for WP', 'poolservices'),
				'slug' 		=> 'mailchimp-for-wp',
				'required' 	=> false
			);
		return $list;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check Mail Chimp in the required plugins
if ( !function_exists( 'poolservices_mailchimp_importer_required_plugins' ) ) {
	//Handler of add_filter( 'poolservices_filter_importer_required_plugins',	'poolservices_mailchimp_importer_required_plugins', 10, 2 );
	function poolservices_mailchimp_importer_required_plugins($not_installed='', $list='') {
		if (poolservices_strpos($list, 'mailchimp')!==false && !poolservices_exists_mailchimp() )
			$not_installed .= '<br>' . esc_html__('Mail Chimp', 'poolservices');
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'poolservices_mailchimp_importer_set_options' ) ) {
	//Handler of add_filter( 'poolservices_filter_importer_options',	'poolservices_mailchimp_importer_set_options' );
	function poolservices_mailchimp_importer_set_options($options=array()) {
		if ( in_array('mailchimp', poolservices_storage_get('required_plugins')) && poolservices_exists_mailchimp() ) {
			// Add slugs to export options for this plugin
			$options['additional_options'][] = 'mc4wp_lite_checkbox';
			$options['additional_options'][] = 'mc4wp_lite_form';
		}
		return $options;
	}
}

// Add checkbox to the one-click importer
if ( !function_exists( 'poolservices_mailchimp_importer_show_params' ) ) {
	//Handler of add_action( 'poolservices_action_importer_params',	'poolservices_mailchimp_importer_show_params', 10, 1 );
	function poolservices_mailchimp_importer_show_params($importer) {
		if ( poolservices_exists_mailchimp() && in_array('mailchimp', poolservices_storage_get('required_plugins')) ) {
			$importer->show_importer_params(array(
				'slug' => 'mailchimp',
				'title' => esc_html__('Import MailChimp for WP', 'poolservices'),
				'part' => 1
			));
		}
	}
}

// Check if the row will be imported
if ( !function_exists( 'poolservices_mailchimp_importer_check_row' ) ) {
	//Handler of add_filter('poolservices_filter_importer_import_row', 'poolservices_mailchimp_importer_check_row', 9, 4);
	function poolservices_mailchimp_importer_check_row($flag, $table, $row, $list) {
		if ($flag || strpos($list, 'mailchimp')===false) return $flag;
		if ( poolservices_exists_mailchimp() ) {
			if ($table == 'posts')
				$flag = $row['post_type']=='mc4wp-form';
		}
		return $flag;
	}
}
?>