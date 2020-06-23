<?php
/* Booking Calendar support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('poolservices_booking_theme_setup')) {
	add_action( 'poolservices_action_before_init_theme', 'poolservices_booking_theme_setup', 1 );
	function poolservices_booking_theme_setup() {
		// Register shortcode in the shortcodes list
		if (poolservices_exists_booking()) {
			add_action('poolservices_action_add_styles',					'poolservices_booking_frontend_scripts');
			add_action('poolservices_action_shortcodes_list',				'poolservices_booking_reg_shortcodes');
			if (function_exists('poolservices_exists_visual_composer') && poolservices_exists_visual_composer())
				add_action('poolservices_action_shortcodes_list_vc',		'poolservices_booking_reg_shortcodes_vc');
			if (is_admin()) {
				add_filter( 'poolservices_filter_importer_options',			'poolservices_booking_importer_set_options' );
				add_action( 'poolservices_action_importer_params',			'poolservices_booking_importer_show_params', 10, 1 );
				add_action( 'poolservices_action_importer_import',			'poolservices_booking_importer_import', 10, 2 );
				add_action( 'poolservices_action_importer_import_fields',	'poolservices_booking_importer_import_fields', 10, 1 );
				add_action( 'poolservices_action_importer_export',			'poolservices_booking_importer_export', 10, 1 );
				add_action( 'poolservices_action_importer_export_fields',	'poolservices_booking_importer_export_fields', 10, 1 );
			}
		}
		if (is_admin()) {
			add_filter( 'poolservices_filter_importer_required_plugins',	'poolservices_booking_importer_required_plugins', 10, 2);
			add_filter( 'poolservices_filter_required_plugins',				'poolservices_booking_required_plugins' );
		}
	}
}


// Check if Booking Calendar installed and activated
if ( !function_exists( 'poolservices_exists_booking' ) ) {
	function poolservices_exists_booking() {
		return function_exists('wp_booking_start_session');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'poolservices_booking_required_plugins' ) ) {
	//Handler of add_filter('poolservices_filter_required_plugins',	'poolservices_booking_required_plugins');
	function poolservices_booking_required_plugins($list=array()) {
		if (in_array('booking', poolservices_storage_get('required_plugins'))) {
			$path = poolservices_get_file_dir('plugins/install/wp-booking-calendar.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> esc_html__('Booking Calendar', 'poolservices'),
					'slug' 		=> 'wp-booking-calendar',
					'source'	=> $path,
					'required' 	=> false
					);
			}
		}
		return $list;
	}
}

// Enqueue custom styles
if ( !function_exists( 'poolservices_booking_frontend_scripts' ) ) {
	//Handler of add_action( 'poolservices_action_add_styles', 'poolservices_booking_frontend_scripts' );
	function poolservices_booking_frontend_scripts() {
		if (file_exists(poolservices_get_file_dir('css/plugin.booking.css')))
			wp_enqueue_style( 'poolservices-plugin.booking-style',  poolservices_get_file_url('css/plugin.booking.css'), array(), null );
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check in the required plugins
if ( !function_exists( 'poolservices_booking_importer_required_plugins' ) ) {
	//Handler of add_filter( 'poolservices_filter_importer_required_plugins',	'poolservices_booking_importer_required_plugins', 10, 2);
	function poolservices_booking_importer_required_plugins($not_installed='', $list='') {
		if (poolservices_strpos($list, 'booking')!==false && !poolservices_exists_booking() )
			$not_installed .= '<br>' . esc_html__('Booking Calendar', 'poolservices');
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'poolservices_booking_importer_set_options' ) ) {
	//Handler of add_filter( 'poolservices_filter_importer_options',	'poolservices_booking_importer_set_options', 10, 1 );
	function poolservices_booking_importer_set_options($options=array()) {
		if ( in_array('booking', poolservices_storage_get('required_plugins')) && poolservices_exists_booking() ) {
			if (is_array($options['files']) && count($options['files']) > 0) {
				foreach ($options['files'] as $k => $v) {
					$options['files'][$k]['file_with_booking'] = str_replace('name.ext', 'booking.txt', $v['file_with_']);
				}
			}
		}
		return $options;
	}
}

// Add checkbox to the one-click importer
if ( !function_exists( 'poolservices_booking_importer_show_params' ) ) {
	//Handler of add_action( 'poolservices_action_importer_params',	'poolservices_booking_importer_show_params', 10, 1 );
	function poolservices_booking_importer_show_params($importer) {
		$importer->show_importer_params(array(
			'slug' => 'booking',
			'title' => esc_html__('Import Booking Calendar', 'poolservices'),
			'part' => 0
			));
	}
}

// Import posts
if ( !function_exists( 'poolservices_booking_importer_import' ) ) {
	//Handler of add_action( 'poolservices_action_importer_import',	'poolservices_booking_importer_import', 10, 2 );
	function poolservices_booking_importer_import($importer, $action) {
		if ( $action == 'import_booking' ) {
			$importer->response['start_from_id'] = 0;
			$importer->import_dump('booking', esc_html__('Booking Calendar', 'poolservices'));
		}
	}
}

// Display import progress
if ( !function_exists( 'poolservices_booking_importer_import_fields' ) ) {
	//Handler of add_action( 'poolservices_action_importer_import_fields',	'poolservices_booking_importer_import_fields', 10, 1 );
	function poolservices_booking_importer_import_fields($importer) {
		$importer->show_importer_fields(array(
			'slug' => 'booking',
			'title' => esc_html__('Booking Calendar', 'poolservices')
			));
	}
}

// Export posts
if ( !function_exists( 'poolservices_booking_importer_export' ) ) {
	//Handler of add_action( 'poolservices_action_importer_export',	'poolservices_booking_importer_export', 10, 1 );
	function poolservices_booking_importer_export($importer) {
		poolservices_fpc(poolservices_get_file_dir('core/core.importer/export/booking.txt'), serialize( array(
			"booking_calendars"		=> $importer->export_dump("booking_calendars"),
			"booking_categories"	=> $importer->export_dump("booking_categories"),
            "booking_config"		=> $importer->export_dump("booking_config"),
            "booking_reservation"	=> $importer->export_dump("booking_reservation"),
            "booking_slots"			=> $importer->export_dump("booking_slots")
            ) )
        );
	}
}

// Display exported data in the fields
if ( !function_exists( 'poolservices_booking_importer_export_fields' ) ) {
	//Handler of add_action( 'poolservices_action_importer_export_fields',	'poolservices_booking_importer_export_fields', 10, 1 );
	function poolservices_booking_importer_export_fields($importer) {
		$importer->show_exporter_fields(array(
			'slug' => 'booking',
			'title' => esc_html__('Booking', 'poolservices')
			));
	}
}


// Lists
//------------------------------------------------------------------------

// Return Booking categories list, prepended inherit (if need)
if ( !function_exists( 'poolservices_get_list_booking_categories' ) ) {
	function poolservices_get_list_booking_categories($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_booking_cats'))=='') {
			$list = array();
			if (poolservices_exists_booking()) {
				global $wpdb;
				$rows = $wpdb->get_results( "SELECT category_id, category_name FROM " . esc_sql($wpdb->prefix) . "booking_categories" );
				if (is_array($rows) && count($rows) > 0) {
					foreach ($rows as $row) {
						$list[$row->category_id] = $row->category_name;
					}
				}
			}
			$list = apply_filters('poolservices_filter_list_booking_categories', $list);
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_booking_cats', $list); 
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}

// Return Booking calendars list, prepended inherit (if need)
if ( !function_exists( 'poolservices_get_list_booking_calendars' ) ) {
	function poolservices_get_list_booking_calendars($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_booking_calendars'))=='') {
			$list = array();
			if (poolservices_exists_booking()) {
				global $wpdb;
				$rows = $wpdb->get_results( "SELECT cl.calendar_id, cl.calendar_title, ct.category_name"
												. " FROM " . esc_sql($wpdb->prefix) . "booking_calendars AS cl"
												. " INNER JOIN " . esc_sql($wpdb->prefix) . "booking_categories AS ct ON cl.category_id=ct.category_id"
										);
				if (is_array($rows) && count($rows) > 0) {
					foreach ($rows as $row) {
						$list[$row->calendar_id] = $row->calendar_title . ' (' . $row->category_name . ')';
					}
				}
			}
			$list = apply_filters('poolservices_filter_list_booking_calendars', $list);
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_booking_calendars', $list); 
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}



// Shortcodes
//------------------------------------------------------------------------

// Register shortcode in the shortcodes list
if (!function_exists('poolservices_booking_reg_shortcodes')) {
	//Handler of add_filter('poolservices_action_shortcodes_list',	'poolservices_booking_reg_shortcodes');
	function poolservices_booking_reg_shortcodes() {
		if (poolservices_storage_isset('shortcodes')) {

			$booking_cats = poolservices_get_list_booking_categories();
			$booking_cals = poolservices_get_list_booking_calendars();

			poolservices_sc_map('wp_booking_calendar', array(
				"title" => esc_html__("Booking Calendar", 'poolservices'),
				"desc" => esc_html__("Insert Booking calendar", 'poolservices'),
				"decorate" => true,
				"container" => false,
				"params" => array(
					"category_id" => array(
						"title" => esc_html__("Category", 'poolservices'),
						"desc" => esc_html__("Select booking category", 'poolservices'),
						"value" => "",
						"type" => "select",
						"options" => poolservices_array_merge(array(0 => esc_html__('- Select category -', 'poolservices')), $booking_cats)
					),
					"calendar_id" => array(
						"title" => esc_html__("Calendar", 'poolservices'),
						"desc" => esc_html__("or select booking calendar (id category is empty)", 'poolservices'),
						"dependency" => array(
							'category_id' => array('empty', '0')
						),
						"value" => "",
						"type" => "select",
						"options" => poolservices_array_merge(array(0 => esc_html__('- Select calendar -', 'poolservices')), $booking_cals)
					)
				)
			));
		}
	}
}


// Register shortcode in the VC shortcodes list
if (!function_exists('poolservices_booking_reg_shortcodes_vc')) {
	//Handler of add_filter('poolservices_action_shortcodes_list_vc',	'poolservices_booking_reg_shortcodes_vc');
	function poolservices_booking_reg_shortcodes_vc() {

		$booking_cats = poolservices_get_list_booking_categories();
		$booking_cals = poolservices_get_list_booking_calendars();


		// PoolServices Donations form
		vc_map( array(
				"base" => "wp_booking_calendar",
				"name" => esc_html__("Booking Calendar", 'poolservices'),
				"description" => esc_html__("Insert Booking calendar", 'poolservices'),
				"category" => esc_html__('Content', 'poolservices'),
				'icon' => 'icon_trx_booking',
				"class" => "trx_sc_single trx_sc_booking",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "category_id",
						"heading" => esc_html__("Category", 'poolservices'),
						"description" => esc_html__("Select Booking category", 'poolservices'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(poolservices_array_merge(array(0 => esc_html__('- Select category -', 'poolservices')), $booking_cats)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "calendar_id",
						"heading" => esc_html__("Calendar", 'poolservices'),
						"description" => esc_html__("Select Booking calendar", 'poolservices'),
						"admin_label" => true,
						'dependency' => array(
							'element' => 'category_id',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip(poolservices_array_merge(array(0 => esc_html__('- Select calendar -', 'poolservices')), $booking_cals)),
						"type" => "dropdown"
					)
				)
			) );
			
		class WPBakeryShortCode_Wp_Booking_Calendar extends POOLSERVICES_VC_ShortCodeSingle {}

	}
}
?>