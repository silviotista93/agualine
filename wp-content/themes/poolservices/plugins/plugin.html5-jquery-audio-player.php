<?php
/* HTML5 jQuery Audio Player support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('poolservices_html5_jquery_audio_player_theme_setup')) {
    add_action( 'poolservices_action_before_init_theme', 'poolservices_html5_jquery_audio_player_theme_setup' );
    function poolservices_html5_jquery_audio_player_theme_setup() {
        // Add shortcode in the shortcodes list
        if (poolservices_exists_html5_jquery_audio_player()) {
			add_action('poolservices_action_add_styles',					'poolservices_html5_jquery_audio_player_frontend_scripts' );
            add_action('poolservices_action_shortcodes_list',				'poolservices_html5_jquery_audio_player_reg_shortcodes');
			if (function_exists('poolservices_exists_visual_composer') && poolservices_exists_visual_composer())
	            add_action('poolservices_action_shortcodes_list_vc',		'poolservices_html5_jquery_audio_player_reg_shortcodes_vc');
            if (is_admin()) {
                add_filter( 'poolservices_filter_importer_options',			'poolservices_html5_jquery_audio_player_importer_set_options', 10, 1 );
                add_action( 'poolservices_action_importer_params',			'poolservices_html5_jquery_audio_player_importer_show_params', 10, 1 );
                add_action( 'poolservices_action_importer_import',			'poolservices_html5_jquery_audio_player_importer_import', 10, 2 );
				add_action( 'poolservices_action_importer_import_fields',	'poolservices_html5_jquery_audio_player_importer_import_fields', 10, 1 );
                add_action( 'poolservices_action_importer_export',			'poolservices_html5_jquery_audio_player_importer_export', 10, 1 );
                add_action( 'poolservices_action_importer_export_fields',	'poolservices_html5_jquery_audio_player_importer_export_fields', 10, 1 );
            }
        }
        if (is_admin()) {
            add_filter( 'poolservices_filter_importer_required_plugins',	'poolservices_html5_jquery_audio_player_importer_required_plugins', 10, 2 );
            add_filter( 'poolservices_filter_required_plugins',				'poolservices_html5_jquery_audio_player_required_plugins' );
        }
    }
}

// Check if plugin installed and activated
if ( !function_exists( 'poolservices_exists_html5_jquery_audio_player' ) ) {
	function poolservices_exists_html5_jquery_audio_player() {
		return function_exists('hmp_db_create');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'poolservices_html5_jquery_audio_player_required_plugins' ) ) {
	//Handler of add_filter('poolservices_filter_required_plugins',	'poolservices_html5_jquery_audio_player_required_plugins');
	function poolservices_html5_jquery_audio_player_required_plugins($list=array()) {
		if (in_array('html5_jquery_audio_player', poolservices_storage_get('required_plugins')))
			$list[] = array(
					'name' 		=> esc_html__('HTML5 jQuery Audio Player', 'poolservices'),
					'slug' 		=> 'html5-jquery-audio-player',
					'required' 	=> false
				);
		return $list;
	}
}

// Enqueue custom styles
if ( !function_exists( 'poolservices_html5_jquery_audio_player_frontend_scripts' ) ) {
	//Handler of add_action( 'poolservices_action_add_styles', 'poolservices_html5_jquery_audio_player_frontend_scripts' );
	function poolservices_html5_jquery_audio_player_frontend_scripts() {
		if (file_exists(poolservices_get_file_dir('css/plugin.html5-jquery-audio-player.css'))) {
			wp_enqueue_style( 'poolservices-plugin.html5-jquery-audio-player-style',  poolservices_get_file_url('css/plugin.html5-jquery-audio-player.css'), array(), null );
		}
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check HTML5 jQuery Audio Player in the required plugins
if ( !function_exists( 'poolservices_html5_jquery_audio_player_importer_required_plugins' ) ) {
	//Handler of add_filter( 'poolservices_filter_importer_required_plugins',	'poolservices_html5_jquery_audio_player_importer_required_plugins', 10, 2 );
	function poolservices_html5_jquery_audio_player_importer_required_plugins($not_installed='', $list=null) {
		if (poolservices_strpos($list, 'html5_jquery_audio_player')!==false && !poolservices_exists_html5_jquery_audio_player() )
			$not_installed .= '<br>' . esc_html__('HTML5 jQuery Audio Player', 'poolservices');
		return $not_installed;
	}
}


// Set options for one-click importer
if ( !function_exists( 'poolservices_html5_jquery_audio_player_importer_set_options' ) ) {
    //Handler of add_filter( 'poolservices_filter_importer_options',	'poolservices_html5_jquery_audio_player_importer_set_options', 10, 1 );
    function poolservices_html5_jquery_audio_player_importer_set_options($options=array()) {
		if ( in_array('html5_jquery_audio_player', poolservices_storage_get('required_plugins')) && poolservices_exists_html5_jquery_audio_player() ) {
			if (is_array($options['files']) && count($options['files']) > 0) {
				foreach ($options['files'] as $k => $v) {
					$options['files'][$k]['file_with_html5_jquery_audio_player'] = str_replace('posts', 'html5_jquery_audio_player', $v['file_with_posts']);
				}
			}
			// Add option's slugs to export options for this plugin
            $options['additional_options'][] = 'showbuy';
            $options['additional_options'][] = 'buy_text';
            $options['additional_options'][] = 'showlist';
            $options['additional_options'][] = 'autoplay';
            $options['additional_options'][] = 'tracks';
            $options['additional_options'][] = 'currency';
            $options['additional_options'][] = 'color';
            $options['additional_options'][] = 'tcolor';
        }
        return $options;
    }
}

// Add checkbox to the one-click importer
if ( !function_exists( 'poolservices_html5_jquery_audio_player_importer_show_params' ) ) {
    //Handler of add_action( 'poolservices_action_importer_params',	'poolservices_html5_jquery_audio_player_importer_show_params', 10, 1 );
    function poolservices_html5_jquery_audio_player_importer_show_params($importer) {
        ?>
        <input type="checkbox" <?php echo in_array('html5_jquery_audio_player', poolservices_storage_get('required_plugins')) && $importer->options['plugins_initial_state']
											? 'checked="checked"' 
											: ''; ?> value="1" name="import_html5_jquery_audio_player" id="import_html5_jquery_audio_player" /> <label for="import_html5_jquery_audio_player"><?php esc_html_e('Import HTML5 jQuery Audio Player', 'poolservices'); ?></label><br>
    <?php
    }
}


// Import posts
if ( !function_exists( 'poolservices_html5_jquery_audio_player_importer_import' ) ) {
    //Handler of add_action( 'poolservices_action_importer_import',	'poolservices_html5_jquery_audio_player_importer_import', 10, 2 );
    function poolservices_html5_jquery_audio_player_importer_import($importer, $action) {
		if ( $action == 'import_html5_jquery_audio_player' ) {
            $importer->response['result'] = $importer->import_dump('html5_jquery_audio_player', esc_html__('HTML5 jQuery Audio Player', 'poolservices'));
        }
    }
}

// Display import progress
if ( !function_exists( 'poolservices_html5_jquery_audio_player_importer_import_fields' ) ) {
	//Handler of add_action( 'poolservices_action_importer_import_fields',	'poolservices_html5_jquery_audio_player_importer_import_fields', 10, 1 );
	function poolservices_html5_jquery_audio_player_importer_import_fields($importer) {
		?>
		<tr class="import_html5_jquery_audio_player">
			<td class="import_progress_item"><?php esc_html_e('HTML5 jQuery Audio Player', 'poolservices'); ?></td>
			<td class="import_progress_status"></td>
		</tr>
		<?php
	}
}


// Export posts
if ( !function_exists( 'poolservices_html5_jquery_audio_player_importer_export' ) ) {
    //Handler of add_action( 'poolservices_action_importer_export',	'poolservices_html5_jquery_audio_player_importer_export', 10, 1 );
    function poolservices_html5_jquery_audio_player_importer_export($importer) {
		poolservices_storage_set('export_html5_jquery_audio_player', serialize( array(
			'hmp_playlist'	=> $importer->export_dump('hmp_playlist'),
			'hmp_rating'	=> $importer->export_dump('hmp_rating')
			) )
		);
    }
}


// Display exported data in the fields
if ( !function_exists( 'poolservices_html5_jquery_audio_player_importer_export_fields' ) ) {
    //Handler of add_action( 'poolservices_action_importer_export_fields',	'poolservices_html5_jquery_audio_player_importer_export_fields', 10, 1 );
    function poolservices_html5_jquery_audio_player_importer_export_fields($importer) {
        ?>
        <tr>
            <th align="left"><?php esc_html_e('HTML5 jQuery Audio Player', 'poolservices'); ?></th>
            <td><?php poolservices_fpc(poolservices_get_file_dir('core/core.importer/export/html5_jquery_audio_player.txt'), poolservices_storage_get('export_html5_jquery_audio_player')); ?>
                <a download="html5_jquery_audio_player.txt" href="<?php echo esc_url(poolservices_get_file_url('core/core.importer/export/html5_jquery_audio_player.txt')); ?>"><?php esc_html_e('Download', 'poolservices'); ?></a>
            </td>
        </tr>
    <?php
    }
}





// Shortcodes
//------------------------------------------------------------------------

// Register shortcode in the shortcodes list
if (!function_exists('poolservices_html5_jquery_audio_player_reg_shortcodes')) {
    //Handler of add_filter('poolservices_action_shortcodes_list',	'poolservices_html5_jquery_audio_player_reg_shortcodes');
    function poolservices_html5_jquery_audio_player_reg_shortcodes() {
		if (poolservices_storage_isset('shortcodes')) {
			poolservices_sc_map_after('trx_audio', 'hmp_player', array(
                "title" => esc_html__("HTML5 jQuery Audio Player", 'poolservices'),
                "desc" => esc_html__("Insert HTML5 jQuery Audio Player", 'poolservices'),
                "decorate" => true,
                "container" => false,
				"params" => array()
				)
            );
        }
    }
}


// Register shortcode in the VC shortcodes list
if (!function_exists('poolservices_hmp_player_reg_shortcodes_vc')) {
    add_filter('poolservices_action_shortcodes_list_vc',	'poolservices_hmp_player_reg_shortcodes_vc');
    function poolservices_hmp_player_reg_shortcodes_vc() {

        // PoolServices HTML5 jQuery Audio Player
        vc_map( array(
            "base" => "hmp_player",
            "name" => esc_html__("HTML5 jQuery Audio Player", 'poolservices'),
            "description" => esc_html__("Insert HTML5 jQuery Audio Player", 'poolservices'),
            "category" => esc_html__('Content', 'poolservices'),
            'icon' => 'icon_trx_audio',
            "class" => "trx_sc_single trx_sc_hmp_player",
            "content_element" => true,
            "is_container" => false,
            "show_settings_on_create" => false,
            "params" => array()
        ) );

        class WPBakeryShortCode_Hmp_Player extends POOLSERVICES_VC_ShortCodeSingle {}

    }
}
?>