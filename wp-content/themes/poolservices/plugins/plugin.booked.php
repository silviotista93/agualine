<?php
/* Booked Appointments support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('poolservices_booked_theme_setup')) {
	add_action( 'poolservices_action_before_init_theme', 'poolservices_booked_theme_setup', 1 );
	function poolservices_booked_theme_setup() {
		// Register shortcode in the shortcodes list
		if (poolservices_exists_booked()) {
			add_action('poolservices_action_add_styles', 					'poolservices_booked_frontend_scripts');
			add_action('poolservices_action_shortcodes_list',				'poolservices_booked_reg_shortcodes');
			if (function_exists('poolservices_exists_visual_composer') && poolservices_exists_visual_composer())
				add_action('poolservices_action_shortcodes_list_vc',		'poolservices_booked_reg_shortcodes_vc');
			if (is_admin()) {
				add_filter( 'poolservices_filter_importer_options',			'poolservices_booked_importer_set_options' );
				add_filter( 'poolservices_filter_importer_import_row',		'poolservices_booked_importer_check_row', 9, 4);
			}
		}
		if (is_admin()) {
			add_filter( 'poolservices_filter_importer_required_plugins',	'poolservices_booked_importer_required_plugins', 10, 2);
			add_filter( 'poolservices_filter_required_plugins',				'poolservices_booked_required_plugins' );
		}
	}
}


// Check if plugin installed and activated
if ( !function_exists( 'poolservices_exists_booked' ) ) {
	function poolservices_exists_booked() {
		return class_exists('booked_plugin');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'poolservices_booked_required_plugins' ) ) {
	//Handler of add_filter('poolservices_filter_required_plugins',	'poolservices_booked_required_plugins');
	function poolservices_booked_required_plugins($list=array()) {
		if (in_array('booked', poolservices_storage_get('required_plugins'))) {
			$path = poolservices_get_file_dir('plugins/install/booked.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> esc_html__('Booked', 'poolservices'),
					'slug' 		=> 'booked',
					'source'	=> $path,
					'required' 	=> false
					);
			}
		}
		return $list;
	}
}

// Enqueue custom styles
if ( !function_exists( 'poolservices_booked_frontend_scripts' ) ) {
	//Handler of add_action( 'poolservices_action_add_styles', 'poolservices_booked_frontend_scripts' );
	function poolservices_booked_frontend_scripts() {
		if (file_exists(poolservices_get_file_dir('css/plugin.booked.css')))
			wp_enqueue_style( 'poolservices-plugin.booked-style',  poolservices_get_file_url('css/plugin.booked.css'), array(), null );
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check in the required plugins
if ( !function_exists( 'poolservices_booked_importer_required_plugins' ) ) {
	//Handler of add_filter( 'poolservices_filter_importer_required_plugins',	'poolservices_booked_importer_required_plugins', 10, 2);
	function poolservices_booked_importer_required_plugins($not_installed='', $list='') {
		//if (in_array('booked', poolservices_storage_get('required_plugins')) && !poolservices_exists_booked() )
		if (poolservices_strpos($list, 'booked')!==false && !poolservices_exists_booked() )
			$not_installed .= '<br>' . esc_html__('Booked Appointments', 'poolservices');
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'poolservices_booked_importer_set_options' ) ) {
	//Handler of add_filter( 'poolservices_filter_importer_options',	'poolservices_booked_importer_set_options', 10, 1 );
	function poolservices_booked_importer_set_options($options=array()) {
		if (in_array('booked', poolservices_storage_get('required_plugins')) && poolservices_exists_booked()) {
			$options['additional_options'][] = 'booked_%';		// Add slugs to export options for this plugin
		}
		return $options;
	}
}

// Check if the row will be imported
if ( !function_exists( 'poolservices_booked_importer_check_row' ) ) {
	//Handler of add_filter('poolservices_filter_importer_import_row', 'poolservices_booked_importer_check_row', 9, 4);
	function poolservices_booked_importer_check_row($flag, $table, $row, $list) {
		if ($flag || strpos($list, 'booked')===false) return $flag;
		if ( poolservices_exists_booked() ) {
			if ($table == 'posts')
				$flag = $row['post_type']=='booked_appointments';
		}
		return $flag;
	}
}


// Lists
//------------------------------------------------------------------------

// Return booked calendars list, prepended inherit (if need)
if ( !function_exists( 'poolservices_get_list_booked_calendars' ) ) {
	function poolservices_get_list_booked_calendars($prepend_inherit=false) {
		return poolservices_exists_booked() ? poolservices_get_list_terms($prepend_inherit, 'booked_custom_calendars') : array();
	}
}



// Register plugin's shortcodes
//------------------------------------------------------------------------

// Register shortcode in the shortcodes list
if (!function_exists('poolservices_booked_reg_shortcodes')) {
	//Handler of add_filter('poolservices_action_shortcodes_list',	'poolservices_booked_reg_shortcodes');
	function poolservices_booked_reg_shortcodes() {
		if (poolservices_storage_isset('shortcodes')) {

			$booked_cals = poolservices_get_list_booked_calendars();

			poolservices_sc_map('booked-appointments', array(
				"title" => esc_html__("Booked Appointments", 'poolservices'),
				"desc" => esc_html__("Display the currently logged in user's upcoming appointments", 'poolservices'),
				"decorate" => true,
				"container" => false,
				"params" => array()
				)
			);

			poolservices_sc_map('booked-calendar', array(
				"title" => esc_html__("Booked Calendar", 'poolservices'),
				"desc" => esc_html__("Insert booked calendar", 'poolservices'),
				"decorate" => true,
				"container" => false,
				"params" => array(
					"calendar" => array(
						"title" => esc_html__("Calendar", 'poolservices'),
						"desc" => esc_html__("Select booked calendar to display", 'poolservices'),
						"value" => "0",
						"type" => "select",
						"options" => poolservices_array_merge(array(0 => esc_html__('- Select calendar -', 'poolservices')), $booked_cals)
					),
					"year" => array(
						"title" => esc_html__("Year", 'poolservices'),
						"desc" => esc_html__("Year to display on calendar by default", 'poolservices'),
						"value" => date("Y"),
						"min" => date("Y"),
						"max" => date("Y")+10,
						"type" => "spinner"
					),
					"month" => array(
						"title" => esc_html__("Month", 'poolservices'),
						"desc" => esc_html__("Month to display on calendar by default", 'poolservices'),
						"value" => date("m"),
						"min" => 1,
						"max" => 12,
						"type" => "spinner"
					)
				)
			));
		}
	}
}


// Register shortcode in the VC shortcodes list
if (!function_exists('poolservices_booked_reg_shortcodes_vc')) {
	//Handler of add_filter('poolservices_action_shortcodes_list_vc',	'poolservices_booked_reg_shortcodes_vc');
	function poolservices_booked_reg_shortcodes_vc() {

		$booked_cals = poolservices_get_list_booked_calendars();

		// Booked Appointments
		vc_map( array(
				"base" => "booked-appointments",
				"name" => esc_html__("Booked Appointments", 'poolservices'),
				"description" => esc_html__("Display the currently logged in user's upcoming appointments", 'poolservices'),
				"category" => esc_html__('Content', 'poolservices'),
				'icon' => 'icon_trx_booked',
				"class" => "trx_sc_single trx_sc_booked_appointments",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array()
			) );
			
		class WPBakeryShortCode_Booked_Appointments extends POOLSERVICES_VC_ShortCodeSingle {}

		// Booked Calendar
		vc_map( array(
				"base" => "booked-calendar",
				"name" => esc_html__("Booked Calendar", 'poolservices'),
				"description" => esc_html__("Insert booked calendar", 'poolservices'),
				"category" => esc_html__('Content', 'poolservices'),
				'icon' => 'icon_trx_booked',
				"class" => "trx_sc_single trx_sc_booked_calendar",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "calendar",
						"heading" => esc_html__("Calendar", 'poolservices'),
						"description" => esc_html__("Select booked calendar to display", 'poolservices'),
						"admin_label" => true,
						"class" => "",
						"std" => "0",
						"value" => array_flip(poolservices_array_merge(array(0 => esc_html__('- Select calendar -', 'poolservices')), $booked_cals)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "year",
						"heading" => esc_html__("Year", 'poolservices'),
						"description" => esc_html__("Year to display on calendar by default", 'poolservices'),
						"admin_label" => true,
						"class" => "",
						"std" => date("Y"),
						"value" => date("Y"),
						"type" => "textfield"
					),
					array(
						"param_name" => "month",
						"heading" => esc_html__("Month", 'poolservices'),
						"description" => esc_html__("Month to display on calendar by default", 'poolservices'),
						"admin_label" => true,
						"class" => "",
						"std" => date("m"),
						"value" => date("m"),
						"type" => "textfield"
					)
				)
			) );
			
		class WPBakeryShortCode_Booked_Calendar extends POOLSERVICES_VC_ShortCodeSingle {}

	}
}
?>