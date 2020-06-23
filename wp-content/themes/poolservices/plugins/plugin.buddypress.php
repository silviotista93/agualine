<?php
/* BuddyPress support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('poolservices_buddypress_theme_setup')) {
	add_action( 'poolservices_action_before_init_theme', 'poolservices_buddypress_theme_setup', 1 );
	function poolservices_buddypress_theme_setup() {
		if (poolservices_exists_buddypress()) {
			// Add custom styles for Buddy & BBPress
			add_action( 'poolservices_action_add_styles', 				'poolservices_buddypress_frontend_scripts' );
			// One-click import support
			if (is_admin()) {
				add_filter( 'poolservices_filter_importer_options',			'poolservices_buddypress_importer_set_options' );
				add_action( 'poolservices_action_importer_params',			'poolservices_buddypress_importer_show_params', 10, 1 );
				add_action( 'poolservices_action_importer_clear_tables',	'poolservices_buddypress_importer_clear_tables', 10, 2 );
				add_action( 'poolservices_action_importer_import',			'poolservices_buddypress_importer_import', 10, 2 );
				add_filter( 'poolservices_filter_importer_import_row',		'poolservices_buddypress_importer_check_row', 9, 4 );
				add_action( 'poolservices_action_importer_import_fields',	'poolservices_buddypress_importer_import_fields', 10, 1 );
				add_action( 'poolservices_action_importer_export',			'poolservices_buddypress_importer_export', 10, 1 );
				add_action( 'poolservices_action_importer_export_fields',	'poolservices_buddypress_importer_export_fields', 10, 1 );
			}
		}
		if (poolservices_is_buddypress_page()) {
			// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
			add_filter('poolservices_filter_get_blog_type',				'poolservices_buddypress_get_blog_type', 9, 2);
			add_filter('poolservices_filter_get_blog_title',			'poolservices_buddypress_get_blog_title', 9, 2);
			add_filter('poolservices_filter_get_stream_page_title',		'poolservices_buddypress_get_stream_page_title', 9, 2);
			add_filter('poolservices_filter_get_stream_page_link',		'poolservices_buddypress_get_stream_page_link', 9, 2);
			add_filter('poolservices_filter_get_stream_page_id',		'poolservices_buddypress_get_stream_page_id', 9, 2);
			add_filter('poolservices_filter_detect_inheritance_key',	'poolservices_buddypress_detect_inheritance_key', 9, 1);
		}
		if (is_admin()) {
			add_filter( 'poolservices_filter_importer_required_plugins',	'poolservices_buddypress_importer_required_plugins', 10, 2 );
			add_filter( 'poolservices_filter_required_plugins',				'poolservices_buddypress_required_plugins' );
		}
	}
}
if ( !function_exists( 'poolservices_buddypress_settings_theme_setup2' ) ) {
	add_action( 'poolservices_action_before_init_theme', 'poolservices_buddypress_settings_theme_setup2', 3 );
	function poolservices_buddypress_settings_theme_setup2() {
		if (poolservices_exists_buddypress()) {
			poolservices_add_theme_inheritance( array('buddypress' => array(
				'stream_template' => 'buddypress',
				'single_template' => '',
				'taxonomy' => array(),
				'taxonomy_tags' => array(),
				'post_type' => array('forum', 'topic', 'reply'),
				'override' => 'page'
				) )
			);
		}
	}
}

// Check if BuddyPress and/or BBPress installed and activated
if ( !function_exists( 'poolservices_exists_buddypress' ) ) {
	function poolservices_exists_buddypress() {
		return class_exists( 'BuddyPress' ) || class_exists( 'bbPress' );
	}
}

// Check if current page is BuddyPress and/or BBPress page
if ( !function_exists( 'poolservices_is_buddypress_page' ) ) {
	function poolservices_is_buddypress_page() {
		$is = false;
		if ( poolservices_exists_buddypress() ) {
			$is = in_array(poolservices_storage_get('page_template'), array('buddypress'));
			if (!$is && poolservices_storage_empty('pre_query') )
				$is = (function_exists('is_buddypress') && is_buddypress())
						||
						(function_exists('is_bbpress') && is_bbpress());
		}
		return $is;
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'poolservices_buddypress_detect_inheritance_key' ) ) {
	//Handler of add_filter('poolservices_filter_detect_inheritance_key',	'poolservices_buddypress_detect_inheritance_key', 9, 1);
	function poolservices_buddypress_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return poolservices_is_buddypress_page() ? 'buddypress' : $key;
	}
}

// Filter to detect current page slug
if ( !function_exists( 'poolservices_buddypress_get_blog_type' ) ) {
	//Handler of add_filter('poolservices_filter_get_blog_type',	'poolservices_buddypress_get_blog_type', 9, 2);
	function poolservices_buddypress_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		if ($query && $query->get('post_type')=='forum' || get_query_var('post_type')=='forum')
			$page = 'buddypress_forum';
		else if ($query && $query->get('post_type')=='topic' || get_query_var('post_type')=='topic')
			$page = 'buddypress_topic';
		else if ($query && $query->get('post_type')=='reply' || get_query_var('post_type')=='reply')
			$page = 'buddypress_reply';
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'poolservices_buddypress_get_blog_title' ) ) {
	//Handler of add_filter('poolservices_filter_get_blog_title',	'poolservices_buddypress_get_blog_title', 9, 2);
	function poolservices_buddypress_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		if ( poolservices_strpos($page, 'buddypress')!==false ) {
			if ( $page == 'buddypress_forum' || $page == 'buddypress_topic' || $page == 'buddypress_reply' ) {
				$title = poolservices_get_post_title();
			} else {
				$title = esc_html__('Forums', 'poolservices');
			}
		}
		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'poolservices_buddypress_get_stream_page_title' ) ) {
	//Handler of add_filter('poolservices_filter_get_stream_page_title',	'poolservices_buddypress_get_stream_page_title', 9, 2);
	function poolservices_buddypress_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (poolservices_strpos($page, 'buddypress')!==false) {
			// Page exists at root slug path, so use its permalink
			$page = bbp_get_page_by_path( bbp_get_root_slug() );
			if ( !empty( $page ) )
				$title = get_the_title( $page->ID );
			else
				$title = esc_html__('Forums', 'poolservices');				
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'poolservices_buddypress_get_stream_page_id' ) ) {
	//Handler of add_filter('poolservices_filter_get_stream_page_id',	'poolservices_buddypress_get_stream_page_id', 9, 2);
	function poolservices_buddypress_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (poolservices_strpos($page, 'buddypress')!==false) {
			// Page exists at root slug path, so use its permalink
			$page = bbp_get_page_by_path( bbp_get_root_slug() );
			if ( !empty( $page ) ) $id = $page->ID;
		}
		return $id;
	}
}

// Filter to detect stream page URL
if ( !function_exists( 'poolservices_buddypress_get_stream_page_link' ) ) {
	//Handler of add_filter('poolservices_filter_get_stream_page_link', 'poolservices_buddypress_get_stream_page_link', 9, 2);
	function poolservices_buddypress_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (poolservices_strpos($page, 'buddypress')!==false) {
			// Page exists at root slug path, so use its permalink
			$page = bbp_get_page_by_path( bbp_get_root_slug() );
			if ( !empty( $page ) )
				$url = get_permalink( $page->ID );
			else
				$url = get_post_type_archive_link( bbp_get_forum_post_type() );
		}
		return $url;
	}
}


// Enqueue BuddyPress and/or BBPress custom styles
if ( !function_exists( 'poolservices_buddypress_frontend_scripts' ) ) {
	//Handler of add_action( 'poolservices_action_add_styles', 'poolservices_buddypress_frontend_scripts' );
	function poolservices_buddypress_frontend_scripts() {
		if (file_exists(poolservices_get_file_dir('css/plugin.buddypress.css')))
			wp_enqueue_style( 'poolservices-plugin.buddypress-style',  poolservices_get_file_url('css/plugin.buddypress.css'), array(), null );
	}
}


// Filter to add in the required plugins list
if ( !function_exists( 'poolservices_buddypress_required_plugins' ) ) {
	//Handler of add_filter('poolservices_filter_required_plugins',	'poolservices_buddypress_required_plugins');
	function poolservices_buddypress_required_plugins($list=array()) {
		if (in_array('buddypress', poolservices_storage_get('required_plugins'))) {
			$list[] = array(
					'name' 		=> 'BuddyPress',
					'slug' 		=> 'buddypress',
					'required' 	=> false
					);
			$list[] = array(
					'name' 		=> 'bbPress',
					'slug' 		=> 'bbpress',
					'required' 	=> false
					);
		}
		return $list;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check in the required plugins
if ( !function_exists( 'poolservices_buddypress_importer_required_plugins' ) ) {
	//Handler of add_filter( 'poolservices_filter_importer_required_plugins',	'poolservices_buddypress_importer_required_plugins', 10, 2 );
	function poolservices_buddypress_importer_required_plugins($not_installed='', $list='') {
		if (poolservices_strpos($list, 'buddypress')!==false && !poolservices_exists_buddypress() )
			$not_installed .= '<br>'.esc_html__('BuddyPress and BBPress', 'poolservices');
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'poolservices_buddypress_importer_set_options' ) ) {
	//Handler of add_filter( 'poolservices_filter_importer_options',	'poolservices_buddypress_importer_set_options', 10, 1 );
	function poolservices_buddypress_importer_set_options($options=array()) {
		if ( in_array('buddypress', poolservices_storage_get('required_plugins')) && poolservices_exists_buddypress() ) {
			if (is_array($options['files']) && count($options['files']) > 0) {
				foreach ($options['files'] as $k => $v) {
					$options['files'][$k]['file_with_buddypress'] = str_replace('name.ext', 'buddypress.txt', $v['file_with_']);
				}
			}
			// Add option's names to export options for this plugin
			$options['additional_options'][] = 'bp-active-components';
			$options['additional_options'][] = 'bp-pages';
			$options['additional_options'][] = 'widget_bp_%';
			$options['additional_options'][] = 'bp-deactivated-components';
			$options['additional_options'][] = 'bb-config-location';
			$options['additional_options'][] = 'bp-xprofile-base-group-name';
			$options['additional_options'][] = 'bp-xprofile-fullname-field-name';
			$options['additional_options'][] = 'hide-loggedout-adminbar';
			$options['additional_options'][] = 'bp-disable-account-deletion';
			$options['additional_options'][] = 'bp-disable-avatar-uploads';
			$options['additional_options'][] = 'bp-disable-cover-image-uploads';
			$options['additional_options'][] = 'bp-disable-profile-sync';
			$options['additional_options'][] = 'bp_restrict_group_creation';
			$options['additional_options'][] = 'bp-disable-group-avatar-uploads';
			$options['additional_options'][] = 'bp-disable-group-cover-image-uploads';
			$options['additional_options'][] = 'bp-disable-blogforum-comments';
			$options['additional_options'][] = '_bp_enable_heartbeat_refresh';
		}
		return $options;
	}
}

// Add checkbox to the one-click importer
if ( !function_exists( 'poolservices_buddypress_importer_show_params' ) ) {
	//Handler of add_action( 'poolservices_action_importer_params',	'poolservices_buddypress_importer_show_params', 10, 1 );
	function poolservices_buddypress_importer_show_params($importer) {
		$importer->show_importer_params(array(
			'slug' => 'buddypress',
			'title' => esc_html__('Import BuddyPress and BBPress', 'poolservices'),
			'part' => 0
			));
	}
}

// Clear tables
if ( !function_exists( 'poolservices_buddypress_importer_clear_tables' ) ) {
	//Handler of add_action( 'poolservices_action_importer_clear_tables',	'poolservices_buddypress_importer_clear_tables', 10, 2 );
	function poolservices_buddypress_importer_clear_tables($importer, $clear_tables) {
		if (poolservices_strpos($clear_tables, 'buddypress')!==false) {
			if ($importer->options['debug']) dfl(esc_html__('Clear BuddyPress tables', 'poolservices'));
			global $wpdb;
			$activity = count($wpdb->get_results( "SHOW TABLES LIKE '".esc_sql($wpdb->prefix)."bp_activity'", ARRAY_A )) == 1;
			$friends  = count($wpdb->get_results( "SHOW TABLES LIKE '".esc_sql($wpdb->prefix)."bp_friends'", ARRAY_A )) == 1;
			$groups   = count($wpdb->get_results( "SHOW TABLES LIKE '".esc_sql($wpdb->prefix)."bp_groups'", ARRAY_A )) == 1;
			$messages = count($wpdb->get_results( "SHOW TABLES LIKE '".esc_sql($wpdb->prefix)."bp_messages_messages'", ARRAY_A )) == 1;
			$blog     = count($wpdb->get_results( "SHOW TABLES LIKE '".esc_sql($wpdb->prefix)."bp_user_blogs'", ARRAY_A )) == 1;
			$notify   = count($wpdb->get_results( "SHOW TABLES LIKE '".esc_sql($wpdb->prefix)."bp_notifications'", ARRAY_A )) == 1;
			$extended = count($wpdb->get_results( "SHOW TABLES LIKE '".esc_sql($wpdb->prefix)."bp_xprofile_data'", ARRAY_A )) == 1;
			if ($activity==0 || $friends==0 || $groups==0 || $messages==0 || $blog==0 || $notify==0 || $extended==0) {
				$bp = buddypress();
				require_once $bp->plugin_dir . '/bp-core/admin/bp-core-admin-schema.php';
				if ($activity==0)	bp_core_install_activity_streams();
				if ($friends==0)	bp_core_install_friends();
				if ($groups==0)		bp_core_install_groups();
				if ($messages==0)	bp_core_install_private_messaging();
				if ($blog==0)		bp_core_install_blog_tracking();
				if ($notify==0)		bp_core_install_notifications();
				if ($extended==0)	bp_core_install_extended_profiles();
				bp_core_maybe_install_signups();
			}
		}
	}
}

// Import posts
if ( !function_exists( 'poolservices_buddypress_importer_import' ) ) {
	//Handler of add_action( 'poolservices_action_importer_import',	'poolservices_buddypress_importer_import', 10, 2 );
	function poolservices_buddypress_importer_import($importer, $action) {
		if ( $action == 'import_buddypress' ) {
			$importer->response['start_from_id'] = 0;
			$importer->import_dump('buddypress', esc_html__('BuddyPress', 'poolservices'));
		}
	}
}

// Check if the row will be imported
if ( !function_exists( 'poolservices_buddypress_importer_check_row' ) ) {
	//Handler of add_filter('poolservices_filter_importer_import_row', 'poolservices_buddypress_importer_check_row', 9, 4);
	function poolservices_buddypress_importer_check_row($flag, $table, $row, $list) {
		if ($flag || strpos($list, 'buddypress')===false) return $flag;
		static $buddypress_pt_list = false;
		if ( poolservices_exists_buddypress() ) {
			if ($table == 'posts') {
				if ($buddypress_pt_list === false) {
					$buddypress_pt_list = array();
					if (function_exists('bbp_get_forum_post_type'))	$buddypress_pt_list[] = bbp_get_forum_post_type();
					if (function_exists('bbp_get_topic_post_type'))	$buddypress_pt_list[] = bbp_get_topic_post_type();
					if (function_exists('bbp_get_reply_post_type'))	$buddypress_pt_list[] = bbp_get_reply_post_type();
					if (function_exists('bp_get_email_post_type'))	$buddypress_pt_list[] = bp_get_email_post_type();
				}
				$flag = in_array($row['post_type'], $buddypress_pt_list);
			}
		}
		return $flag;
	}
}

// Display import progress
if ( !function_exists( 'poolservices_buddypress_importer_import_fields' ) ) {
	//Handler of add_action( 'poolservices_action_importer_import_fields',	'poolservices_buddypress_importer_import_fields', 10, 1 );
	function poolservices_buddypress_importer_import_fields($importer) {
		$importer->show_importer_fields(array(
			'slug' => 'buddypress',
			'title' => esc_html__('BuddyPress and BBPress', 'poolservices')
			));
	}
}

// Export posts
if ( !function_exists( 'poolservices_buddypress_importer_export' ) ) {
	//Handler of add_action( 'poolservices_action_importer_export',	'poolservices_buddypress_importer_export', 10, 1 );
	function poolservices_buddypress_importer_export($importer) {

		// BuddyPress tables
		poolservices_fpc(poolservices_get_file_dir('core/core.importer/export/buddypress.txt'), serialize( array(
			'bp_activity'			=> $importer->export_dump("bp_activity"),
            'bp_activity_meta'		=> $importer->export_dump("bp_activity_meta"),
            'bp_friends'			=> $importer->export_dump("bp_friends"),
            'bp_groups'				=> $importer->export_dump("bp_groups"),
            'bp_groups_groupmeta'	=> $importer->export_dump("bp_groups_groupmeta"),
            'bp_groups_members'		=> $importer->export_dump("bp_groups_members"),
            'bp_messages_messages'	=> $importer->export_dump("bp_messages_messages"),
            'bp_messages_meta'		=> $importer->export_dump("bp_messages_meta"),
            'bp_messages_notices'	=> $importer->export_dump("bp_messages_notices"),
            'bp_messages_recipients'=> $importer->export_dump("bp_messages_recipients"),
            'bp_user_blogs'			=> $importer->export_dump("bp_user_blogs"),
            'bp_user_blogs_blogmeta'=> $importer->export_dump("bp_user_blogs_blogmeta"),
            'bp_notifications'		=> $importer->export_dump("bp_notifications"),
            'bp_notifications_meta'	=> $importer->export_dump("bp_notifications_meta"),
            'bp_xprofile_data'		=> $importer->export_dump("bp_xprofile_data"),
            'bp_xprofile_fields'	=> $importer->export_dump("bp_xprofile_fields"),
            'bp_xprofile_groups'	=> $importer->export_dump("bp_xprofile_groups"),
            'bp_xprofile_meta'		=> $importer->export_dump("bp_xprofile_meta")
			) )
        );
	}
}

// Display exported data in the fields
if ( !function_exists( 'poolservices_buddypress_importer_export_fields' ) ) {
	//Handler of add_action( 'poolservices_action_importer_export_fields',	'poolservices_buddypress_importer_export_fields', 10, 1 );
	function poolservices_buddypress_importer_export_fields($importer) {
		$importer->show_exporter_fields(array(
			'slug' => 'buddypress',
			'title' => esc_html__('BuddyPress and BBPress', 'poolservices')
			));
	}
}
?>