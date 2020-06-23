<?php
/**
 * Theme custom styles
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if (!function_exists('poolservices_action_theme_styles_theme_setup')) {
	add_action( 'poolservices_action_before_init_theme', 'poolservices_action_theme_styles_theme_setup', 1 );
	function poolservices_action_theme_styles_theme_setup() {
	
		// Add theme fonts in the used fonts list
		add_filter('poolservices_filter_used_fonts',			'poolservices_filter_theme_styles_used_fonts');
		// Add theme fonts (from Google fonts) in the main fonts list (if not present).
		add_filter('poolservices_filter_list_fonts',			'poolservices_filter_theme_styles_list_fonts');

		// Add theme stylesheets
		add_action('poolservices_action_add_styles',			'poolservices_action_theme_styles_add_styles');
		// Add theme inline styles
		add_filter('poolservices_filter_add_styles_inline',		'poolservices_filter_theme_styles_add_styles_inline');

		// Add theme scripts
		add_action('poolservices_action_add_scripts',			'poolservices_action_theme_styles_add_scripts');
		// Add theme scripts inline
		add_filter('poolservices_filter_localize_script',		'poolservices_filter_theme_styles_localize_script');

		// Add theme less files into list for compilation
		add_filter('poolservices_filter_compile_less',			'poolservices_filter_theme_styles_compile_less');

		// Add color schemes
		poolservices_add_color_scheme('original', array(

			'title'					=> esc_html__('Original', 'poolservices'),
			
			// Whole block border and background
			'bd_color'				=> '#a2e3ff',
			'bg_color'				=> '#ffffff',
			
			// Headers, text and links colors
			'text'					=> '#33567b',
			'text_light'			=> '#4f7298',
			'text_dark'				=> '#052e5a',
			'text_link'				=> '#00aeef',
			'text_hover'			=> '#fda940',

			// Inverse colors
			'inverse_text'			=> '#ffffff',
			'inverse_light'			=> '#ffffff',
			'inverse_dark'			=> '#ffffff',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#ffffff',
		
			// Input fields
			'input_text'			=> '#8a8a8a',
			'input_light'			=> '#acb4b6',
			'input_dark'			=> '#232a34',
			'input_bd_color'		=> '#ddecee',
			'input_bd_hover'		=> '#bbbbbb',
			'input_bg_color'		=> '#f7f7f7',
			'input_bg_hover'		=> '#f0f0f0',
		
			// Alternative blocks (submenu items, etc.)
			'alter_text'			=> '#007bc2',
			'alter_light'			=> '#acb4b6',
			'alter_dark'			=> '#232a34',
			'alter_link'			=> '#58c7ff',
			'alter_hover'			=> '#a4e0ff',
			'alter_bd_color'		=> '#dddddd',
			'alter_bd_hover'		=> '#bbbbbb',
			'alter_bg_color'		=> '#f7f7f7',
			'alter_bg_hover'		=> '#1c3145',
			)
		);

		// Add color schemes
		poolservices_add_color_scheme('dark', array(

			'title'					=> esc_html__('Dark', 'poolservices'),

			// Whole block border and background
			'bd_color'				=> '#002436',
			'bg_color'				=> '#1c3145',
		
			// Headers, text and links colors
			'text'					=> '#ffffff',
			'text_light'			=> '#5b7da1',
			'text_dark'				=> '#ffffff',
			'text_link'				=> '#00aeef',
			'text_hover'			=> '#007bc2',

			// Inverse colors
			'inverse_text'			=> '#ffffff',
			'inverse_light'			=> '#ffffff',
			'inverse_dark'			=> '#ffffff',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#ffffff',
		
			// Input fields
			'input_text'			=> '#8a8a8a',
			'input_light'			=> '#acb4b6',
			'input_dark'			=> '#232a34',
			'input_bd_color'		=> '#ddecee',
			'input_bd_hover'		=> '#dddddd',
			'input_bg_color'		=> '#ffffff',
			'input_bg_hover'		=> '#f0f0f0',
		
			// Alternative blocks (submenu items, etc.)
			'alter_text'			=> '#fda940',
			'alter_light'			=> '#acb4b6',
			'alter_dark'			=> '#232a34',
			'alter_link'			=> '#58c7ff',
			'alter_hover'			=> '#a4e0ff',
			'alter_bd_color'		=> '#ddecee',
			'alter_bd_hover'		=> '#bbbbbb',
			'alter_bg_color'		=> '#152a3f',
			'alter_bg_hover'		=> '#f0f0f0',
			)
		);


		// Add color schemes
		poolservices_add_color_scheme('red', array(

			'title'					=> esc_html__('Red', 'poolservices'),
			
			// Whole block border and background
			'bd_color'				=> '#a2e3ff',
			'bg_color'				=> '#ffffff',
			
			// Headers, text and links colors
			'text'					=> '#33567b',
			'text_light'			=> '#4f7298',
			'text_dark'				=> '#052e5a',
			'text_link'				=> '#00aeef',
			'text_hover'			=> '#eb5f62',

			// Inverse colors
			'inverse_text'			=> '#ffffff',
			'inverse_light'			=> '#ffffff',
			'inverse_dark'			=> '#ffffff',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#ffffff',
		
			// Input fields
			'input_text'			=> '#8a8a8a',
			'input_light'			=> '#acb4b6',
			'input_dark'			=> '#232a34',
			'input_bd_color'		=> '#ddecee',
			'input_bd_hover'		=> '#bbbbbb',
			'input_bg_color'		=> '#f7f7f7',
			'input_bg_hover'		=> '#f0f0f0',
		
			// Alternative blocks (submenu items, etc.)
			'alter_text'			=> '#007bc2',
			'alter_light'			=> '#acb4b6',
			'alter_dark'			=> '#232a34',
			'alter_link'			=> '#58c7ff',
			'alter_hover'			=> '#a4e0ff',
			'alter_bd_color'		=> '#dddddd',
			'alter_bd_hover'		=> '#bbbbbb',
			'alter_bg_color'		=> '#f7f7f7',
			'alter_bg_hover'		=> '#1c3145',
			)
		);


		// Add color schemes
		poolservices_add_color_scheme('green', array(

			'title'					=> esc_html__('Green', 'poolservices'),
			
			// Whole block border and background
			'bd_color'				=> '#a2e3ff',
			'bg_color'				=> '#ffffff',
			
			// Headers, text and links colors
			'text'					=> '#33567b',
			'text_light'			=> '#4f7298',
			'text_dark'				=> '#052e5a',
			'text_link'				=> '#00aeef',
			'text_hover'			=> '#4bb672',

			// Inverse colors
			'inverse_text'			=> '#ffffff',
			'inverse_light'			=> '#ffffff',
			'inverse_dark'			=> '#ffffff',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#ffffff',
		
			// Input fields
			'input_text'			=> '#8a8a8a',
			'input_light'			=> '#acb4b6',
			'input_dark'			=> '#232a34',
			'input_bd_color'		=> '#ddecee',
			'input_bd_hover'		=> '#bbbbbb',
			'input_bg_color'		=> '#f7f7f7',
			'input_bg_hover'		=> '#f0f0f0',
		
			// Alternative blocks (submenu items, etc.)
			'alter_text'			=> '#007bc2',
			'alter_light'			=> '#acb4b6',
			'alter_dark'			=> '#232a34',
			'alter_link'			=> '#58c7ff',
			'alter_hover'			=> '#a4e0ff',
			'alter_bd_color'		=> '#dddddd',
			'alter_bd_hover'		=> '#bbbbbb',
			'alter_bg_color'		=> '#f7f7f7',
			'alter_bg_hover'		=> '#1c3145',
			)
		);



		// Add color schemes
		poolservices_add_color_scheme('oliva', array(

			'title'					=> esc_html__('Oliva', 'poolservices'),
			
			// Whole block border and background
			'bd_color'				=> '#a2e3ff',
			'bg_color'				=> '#ffffff',
			
			// Headers, text and links colors
			'text'					=> '#33567b',
			'text_light'			=> '#4f7298',
			'text_dark'				=> '#052e5a',
			'text_link'				=> '#00aeef',
			'text_hover'			=> '#e4cb26',

			// Inverse colors
			'inverse_text'			=> '#ffffff',
			'inverse_light'			=> '#ffffff',
			'inverse_dark'			=> '#ffffff',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#ffffff',
		
			// Input fields
			'input_text'			=> '#8a8a8a',
			'input_light'			=> '#acb4b6',
			'input_dark'			=> '#232a34',
			'input_bd_color'		=> '#ddecee',
			'input_bd_hover'		=> '#bbbbbb',
			'input_bg_color'		=> '#f7f7f7',
			'input_bg_hover'		=> '#f0f0f0',
		
			// Alternative blocks (submenu items, etc.)
			'alter_text'			=> '#007bc2',
			'alter_light'			=> '#acb4b6',
			'alter_dark'			=> '#232a34',
			'alter_link'			=> '#58c7ff',
			'alter_hover'			=> '#a4e0ff',
			'alter_bd_color'		=> '#dddddd',
			'alter_bd_hover'		=> '#bbbbbb',
			'alter_bg_color'		=> '#f7f7f7',
			'alter_bg_hover'		=> '#1c3145',
			)
		);

		// Add Custom fonts
		poolservices_add_custom_font('h1', array(
			'title'			=> esc_html__('Heading 1', 'poolservices'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '5em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '1em',
			'margin-top'	=> '0.43em',
			'margin-bottom'	=> '0.43em'
			)
		);
		poolservices_add_custom_font('h2', array(
			'title'			=> esc_html__('Heading 2', 'poolservices'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '4em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '1.2em',
			'margin-top'	=> '0.33em',
			'margin-bottom'	=> '0.33em'
			)
		);
		poolservices_add_custom_font('h3', array(
			'title'			=> esc_html__('Heading 3', 'poolservices'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '2.933em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '1.22em',
			'margin-top'	=> '0.47em',
			'margin-bottom'	=> '0.47em'
			)
		);
		poolservices_add_custom_font('h4', array(
			'title'			=> esc_html__('Heading 4', 'poolservices'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '2.4em',
			'font-weight'	=> '300',
			'font-style'	=> '',
			'line-height'	=> '1.2em',
			'margin-top'	=> '0.9em',
			'margin-bottom'	=> '0.9em'
			)
		);
		poolservices_add_custom_font('h5', array(
			'title'			=> esc_html__('Heading 5', 'poolservices'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '1.6em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '1.35em',
			'margin-top'	=> '1.45em',
			'margin-bottom'	=> '1.45em'
			)
		);
		poolservices_add_custom_font('h6', array(
			'title'			=> esc_html__('Heading 6', 'poolservices'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '1.333em',
			'font-weight'	=> '300',
			'font-style'	=> '',
			'line-height'	=> '1.35em',
			'margin-top'	=> '1.55em',
			'margin-bottom'	=> '1.55em'
			)
		);
		poolservices_add_custom_font('p', array(
			'title'			=> esc_html__('Text', 'poolservices'),
			'description'	=> '',
			'font-family'	=> 'Lato',
			'font-size' 	=> '15px',
			'font-weight'	=> '300',
			'font-style'	=> '',
			'line-height'	=> '1.7em',
			'margin-top'	=> '',
			'margin-bottom'	=> '1.7em'
			)
		);
		poolservices_add_custom_font('link', array(
			'title'			=> esc_html__('Links', 'poolservices'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> ''
			)
		);
		poolservices_add_custom_font('info', array(
			'title'			=> esc_html__('Post info', 'poolservices'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '0.93em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '1.2857em',
			'margin-top'	=> '',
			'margin-bottom'	=> '1.4em'
			)
		);
		poolservices_add_custom_font('menu', array(
			'title'			=> esc_html__('Main menu items', 'poolservices'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '0.8em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '1.2857em',
			'margin-top'	=> '1.8em',
			'margin-bottom'	=> '1.8em'
			)
		);
		poolservices_add_custom_font('submenu', array(
			'title'			=> esc_html__('Dropdown menu items', 'poolservices'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.2857em',
			'margin-top'	=> '',
			'margin-bottom'	=> ''
			)
		);
		poolservices_add_custom_font('logo', array(
			'title'			=> esc_html__('Logo', 'poolservices'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '2.8571em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '0.9em',
			'margin-top'	=> '2.7em',
			'margin-bottom'	=> '0em'
			)
		);
		poolservices_add_custom_font('button', array(
			'title'			=> esc_html__('Buttons', 'poolservices'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.2857em'
			)
		);
		poolservices_add_custom_font('input', array(
			'title'			=> esc_html__('Input fields', 'poolservices'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.31em'
			)
		);

	}
}





//------------------------------------------------------------------------------
// Theme fonts
//------------------------------------------------------------------------------

// Add theme fonts in the used fonts list
if (!function_exists('poolservices_filter_theme_styles_used_fonts')) {
	function poolservices_filter_theme_styles_used_fonts($theme_fonts) {
		$theme_fonts['Lato'] = 1;
		return $theme_fonts;
	}
}

if (!function_exists('poolservices_filter_theme_styles_list_fonts')) {
	function poolservices_filter_theme_styles_list_fonts($list) {
		if (!isset($list['Lato']))	$list['Lato'] = array('family'=>'sans-serif', 'links'=>'Lato:300,700');
		return $list;
	}
}



//------------------------------------------------------------------------------
// Theme stylesheets
//------------------------------------------------------------------------------

// Add theme.less into list files for compilation
if (!function_exists('poolservices_filter_theme_styles_compile_less')) {
	function poolservices_filter_theme_styles_compile_less($files) {
		if (file_exists(poolservices_get_file_dir('css/theme.less'))) {
		 	$files[] = poolservices_get_file_dir('css/theme.less');
		}
		return $files;	
	}
}

// Add theme stylesheets
if (!function_exists('poolservices_action_theme_styles_add_styles')) {
	function poolservices_action_theme_styles_add_styles() {
		// Add stylesheet files only if LESS supported
		if ( poolservices_get_theme_setting('less_compiler') != 'no' ) {
			wp_enqueue_style( 'poolservices-theme-style', poolservices_get_file_url('css/theme.css'), array(), null );
			wp_add_inline_style( 'poolservices-theme-style', poolservices_get_inline_css() );
		}
	}
}

// Add theme inline styles
if (!function_exists('poolservices_filter_theme_styles_add_styles_inline')) {
	function poolservices_filter_theme_styles_add_styles_inline($custom_style) {
		// Submenu width
		$menu_width = poolservices_get_theme_option('menu_width');
		if (!empty($menu_width)) {
			$custom_style .= "
				/* Submenu width */
				.menu_side_nav > li ul,
				.menu_main_nav > li ul {
					width: ".intval($menu_width)."px;
				}
				.menu_side_nav > li > ul ul,
				.menu_main_nav > li > ul ul {
					left:".intval($menu_width+4)."px;
				}
				.menu_side_nav > li > ul ul.submenu_left,
				.menu_main_nav > li > ul ul.submenu_left {
					left:-".intval($menu_width+1)."px;
				}
			";
		}
	
		// Logo height
		$logo_height = poolservices_get_custom_option('logo_height');
		if (!empty($logo_height)) {
			$custom_style .= "
				/* Logo header height */
				.sidebar_outer_logo .logo_main,
				.top_panel_wrap .logo_main,
				.top_panel_wrap .logo_fixed {
					height:".intval($logo_height)."px;
				}
			";
		}
	
		// Logo top offset
		$logo_offset = poolservices_get_custom_option('logo_offset');
		if (!empty($logo_offset)) {
			$custom_style .= "
				/* Logo header top offset */
				.top_panel_wrap .logo {
					margin-top:".intval($logo_offset)."px;
				}
			";
		}

		// Logo footer height
		$logo_height = poolservices_get_theme_option('logo_footer_height');
		if (!empty($logo_height)) {
			$custom_style .= "
				/* Logo footer height */
				.contacts_wrap .logo img {
					height:".intval($logo_height)."px;
				}
			";
		}

		return $custom_style;	
	}
}


//------------------------------------------------------------------------------
// Theme scripts
//------------------------------------------------------------------------------

// Add theme scripts
if (!function_exists('poolservices_action_theme_styles_add_scripts')) {
	function poolservices_action_theme_styles_add_scripts() {
		if (poolservices_get_theme_option('show_theme_customizer') == 'yes' && file_exists(poolservices_get_file_dir('js/theme.customizer.js')))
			wp_enqueue_script( 'poolservices-theme_styles-customizer-script', poolservices_get_file_url('js/theme.customizer.js'), array(), null, true );
	}
}

// Add theme scripts inline
if (!function_exists('poolservices_filter_theme_styles_localize_script')) {
	function poolservices_filter_theme_styles_localize_script($vars) {
		if (empty($vars['theme_font']))
			$vars['theme_font'] = poolservices_get_custom_font_settings('p', 'font-family');
		$vars['theme_color'] = poolservices_get_scheme_color('text_dark');
		$vars['theme_bg_color'] = poolservices_get_scheme_color('bg_color');
		return $vars;
	}
}
?>