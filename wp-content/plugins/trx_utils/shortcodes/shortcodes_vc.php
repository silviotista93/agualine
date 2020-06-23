<?php
if (is_admin() 
		|| (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true' )
		|| (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline')
	) {
    require_once trx_utils_get_file_dir('shortcodes/shortcodes_vc_classes.php');
}

// Width and height params
if ( !function_exists( 'poolservices_vc_width' ) ) {
	function poolservices_vc_width($w='') {
		return array(
			"param_name" => "width",
			"heading" => esc_html__("Width", 'poolservices'),
			"description" => wp_kses_data( __("Width of the element", 'poolservices') ),
			"group" => esc_html__('Size &amp; Margins', 'poolservices'),
			"value" => $w,
			"type" => "textfield"
		);
	}
}
if ( !function_exists( 'poolservices_vc_height' ) ) {
	function poolservices_vc_height($h='') {
		return array(
			"param_name" => "height",
			"heading" => esc_html__("Height", 'poolservices'),
			"description" => wp_kses_data( __("Height of the element", 'poolservices') ),
			"group" => esc_html__('Size &amp; Margins', 'poolservices'),
			"value" => $h,
			"type" => "textfield"
		);
	}
}

// Load scripts and styles for VC support
if ( !function_exists( 'poolservices_shortcodes_vc_scripts_admin' ) ) {
	//add_action( 'admin_enqueue_scripts', 'poolservices_shortcodes_vc_scripts_admin' );
	function poolservices_shortcodes_vc_scripts_admin() {
		// Include CSS 
		wp_enqueue_style ( 'shortcodes_vc_admin-style', trx_utils_get_file_url('shortcodes/theme.shortcodes_vc_admin.css'), array(), null );
		// Include JS
		wp_enqueue_script( 'shortcodes_vc_admin-script', trx_utils_get_file_url('shortcodes/shortcodes_vc_admin.js'), array('jquery'), null, true );
	}
}

// Load scripts and styles for VC support
if ( !function_exists( 'poolservices_shortcodes_vc_scripts_front' ) ) {
	//add_action( 'wp_enqueue_scripts', 'poolservices_shortcodes_vc_scripts_front' );
	function poolservices_shortcodes_vc_scripts_front() {
		if (poolservices_vc_is_frontend()) {
			// Include CSS 
			wp_enqueue_style ( 'shortcodes_vc_front-style', trx_utils_get_file_url('shortcodes/theme.shortcodes_vc_front.css'), array(), null );
			// Include JS
			wp_enqueue_script( 'shortcodes_vc_front-script', trx_utils_get_file_url('shortcodes/shortcodes_vc_front.js'), array('jquery'), null, true );
			wp_enqueue_script( 'shortcodes_vc_theme-script', trx_utils_get_file_url('shortcodes/theme.shortcodes_vc_front.js'), array('jquery'), null, true );
		}
	}
}

// Add init script into shortcodes output in VC frontend editor
if ( !function_exists( 'poolservices_shortcodes_vc_add_init_script' ) ) {
	//add_filter('poolservices_shortcode_output', 'poolservices_shortcodes_vc_add_init_script', 10, 4);
	function poolservices_shortcodes_vc_add_init_script($output, $tag='', $atts=array(), $content='') {
		if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') && (isset($_POST['action']) && $_POST['action']=='vc_load_shortcode')
				&& ( isset($_POST['shortcodes'][0]['tag']) && $_POST['shortcodes'][0]['tag']==$tag )
		) {
			if (poolservices_strpos($output, 'poolservices_vc_init_shortcodes')===false) {
				$id = "poolservices_vc_init_shortcodes_".str_replace('.', '', mt_rand());
				// Attention! This code will be appended in the shortcode's output
				// to init shortcode after it inserted in the page in the VC Frontend editor
				$holder = 'script';
				$output .= '<'.trim($holder).' id="'.esc_attr($id).'">
						try {
							poolservices_init_post_formats();
							poolservices_init_shortcodes(jQuery("body").eq(0));
							poolservices_scroll_actions();
						} catch (e) { };
					</'.trim($holder).'>';
			}
		}
		return $output;
	}
}

// Return vc_param value
if ( !function_exists( 'poolservices_get_vc_param' ) ) {
	function poolservices_get_vc_param($prm) {
		return poolservices_storage_get_array('vc_params', $prm);
	}
}

// Set vc_param value
if ( !function_exists( 'poolservices_set_vc_param' ) ) {
	function poolservices_set_vc_param($prm, $val) {
		poolservices_storage_set_array('vc_params', $prm, $val);
	}
}


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'poolservices_shortcodes_vc_theme_setup' ) ) {
	//if ( poolservices_vc_is_frontend() )
	if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') || (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline') )
		add_action( 'poolservices_action_before_init_theme', 'poolservices_shortcodes_vc_theme_setup', 20 );
	else
		add_action( 'poolservices_action_after_init_theme', 'poolservices_shortcodes_vc_theme_setup' );
	function poolservices_shortcodes_vc_theme_setup() {

		// Add/Remove params in the standard VC shortcodes

		// Add color scheme
		$scheme = array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'poolservices'),
					"description" => wp_kses_data( __("Select color scheme for this block", 'poolservices') ),
					"group" => esc_html__('Color scheme', 'poolservices'),
					"class" => "",
					"value" => array_flip(poolservices_get_list_color_schemes(true)),
					"type" => "dropdown"
		);
		vc_add_param("vc_row", $scheme);
		vc_add_param("vc_row_inner", $scheme);
		vc_add_param("vc_column", $scheme);
		vc_add_param("vc_column_inner", $scheme);
		vc_add_param("vc_column_text", $scheme);

		// Add param 'inverse'
		vc_add_param("vc_row", array(
					"param_name" => "inverse",
					"heading" => esc_html__("Inverse colors", 'poolservices'),
					"description" => wp_kses_data( __("Inverse all colors of this block", 'poolservices') ),
					"group" => esc_html__('Color scheme', 'poolservices'),
					"class" => "",
					"std" => "no",
					"value" => array(esc_html__('Inverse colors', 'poolservices') => 'yes'),
					"type" => "checkbox"
		));

		// Add custom params to the VC shortcodes
		add_filter( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'poolservices_shortcodes_vc_add_params_classes', 10, 3 );

		if (poolservices_shortcodes_is_used() && class_exists('POOLSERVICES_VC_ShortCodeSingle')) {

			// Set VC as main editor for the theme
			vc_set_as_theme( true );
			
			// Enable VC on follow post types
			vc_set_default_editor_post_types( array('page', 'team') );
			
			// Load scripts and styles for VC support
			add_action( 'wp_enqueue_scripts',		'poolservices_shortcodes_vc_scripts_front');
			add_action( 'admin_enqueue_scripts',	'poolservices_shortcodes_vc_scripts_admin' );

			// Add init script into shortcodes output in VC frontend editor
			add_filter('poolservices_shortcode_output', 'poolservices_shortcodes_vc_add_init_script', 10, 4);

			poolservices_storage_set('vc_params', array(
				
				// Common arrays and strings
				'category' => esc_html__("PoolServices shortcodes", 'poolservices'),
			
				// Current element id
				'id' => array(
					"param_name" => "id",
					"heading" => esc_html__("Element ID", 'poolservices'),
					"description" => wp_kses_data( __("ID for the element", 'poolservices') ),
					"group" => esc_html__('ID &amp; Class', 'poolservices'),
					"value" => "",
					"type" => "textfield"
				),
			
				// Current element class
				'class' => array(
					"param_name" => "class",
					"heading" => esc_html__("Element CSS class", 'poolservices'),
					"description" => wp_kses_data( __("CSS class for the element", 'poolservices') ),
					"group" => esc_html__('ID &amp; Class', 'poolservices'),
					"value" => "",
					"type" => "textfield"
				),

				// Current element animation
				'animation' => array(
					"param_name" => "animation",
					"heading" => esc_html__("Animation", 'poolservices'),
					"description" => wp_kses_data( __("Select animation while object enter in the visible area of page", 'poolservices') ),
					"group" => esc_html__('ID &amp; Class', 'poolservices'),
					"class" => "",
					"value" => array_flip(poolservices_get_sc_param('animations')),
					"type" => "dropdown"
				),
			
				// Current element style
				'css' => array(
					"param_name" => "css",
					"heading" => esc_html__("CSS styles", 'poolservices'),
					"description" => wp_kses_data( __("Any additional CSS rules (if need)", 'poolservices') ),
					"group" => esc_html__('ID &amp; Class', 'poolservices'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
			
				// Margins params
				'margin_top' => array(
					"param_name" => "top",
					"heading" => esc_html__("Top margin", 'poolservices'),
					"description" => wp_kses_data( __("Margin above this shortcode", 'poolservices') ),
					"group" => esc_html__('Size &amp; Margins', 'poolservices'),
					"std" => "inherit",
					"value" => array_flip(poolservices_get_sc_param('margins')),
					"type" => "dropdown"
				),
			
				'margin_bottom' => array(
					"param_name" => "bottom",
					"heading" => esc_html__("Bottom margin", 'poolservices'),
					"description" => wp_kses_data( __("Margin below this shortcode", 'poolservices') ),
					"group" => esc_html__('Size &amp; Margins', 'poolservices'),
					"std" => "inherit",
					"value" => array_flip(poolservices_get_sc_param('margins')),
					"type" => "dropdown"
				),
			
				'margin_left' => array(
					"param_name" => "left",
					"heading" => esc_html__("Left margin", 'poolservices'),
					"description" => wp_kses_data( __("Margin on the left side of this shortcode", 'poolservices') ),
					"group" => esc_html__('Size &amp; Margins', 'poolservices'),
					"std" => "inherit",
					"value" => array_flip(poolservices_get_sc_param('margins')),
					"type" => "dropdown"
				),
				
				'margin_right' => array(
					"param_name" => "right",
					"heading" => esc_html__("Right margin", 'poolservices'),
					"description" => wp_kses_data( __("Margin on the right side of this shortcode", 'poolservices') ),
					"group" => esc_html__('Size &amp; Margins', 'poolservices'),
					"std" => "inherit",
					"value" => array_flip(poolservices_get_sc_param('margins')),
					"type" => "dropdown"
				)
			) );
			
			// Add theme-specific shortcodes
			do_action('poolservices_action_shortcodes_list_vc');

		}
	}
}

// Add params in the standard VC shortcodes
if ( !function_exists( 'poolservices_shortcodes_vc_add_params_classes' ) ) {
	//Handler of the add_filter( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG,				'poolservices_shortcodes_vc_add_params_classes', 10, 3 );
	function poolservices_shortcodes_vc_add_params_classes($classes, $sc, $atts) {
		if (in_array($sc, array('vc_row', 'vc_row_inner', 'vc_column', 'vc_column_inner', 'vc_column_text'))) {
			if (!empty($atts['scheme']) && !poolservices_param_is_off($atts['scheme']) && !poolservices_param_is_inherit($atts['scheme']))
				$classes .= ($classes ? ' ' : '') . 'scheme_' . $atts['scheme'];
		}
		if (in_array($sc, array('vc_row'))) {
			if (!empty($atts['inverse']) && !poolservices_param_is_off($atts['inverse']))
				$classes .= ($classes ? ' ' : '') . 'inverse_colors';
		}
		return $classes;
	}
}
?>