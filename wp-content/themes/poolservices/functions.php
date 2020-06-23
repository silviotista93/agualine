<?php
/**
 * Theme sprecific functions and definitions
 */

/* Theme setup section
------------------------------------------------------------------- */

// Set the content width based on the theme's design and stylesheet.
if ( ! isset( $content_width ) ) $content_width = 1170; /* pixels */

// Add theme specific actions and filters
// Attention! Function were add theme specific actions and filters handlers must have priority 1
if ( !function_exists( 'poolservices_theme_setup' ) ) {
	add_action( 'poolservices_action_before_init_theme', 'poolservices_theme_setup', 1 );
	function poolservices_theme_setup() {

        // Add default posts and comments RSS feed links to head
        add_theme_support( 'automatic-feed-links' );

        // Enable support for Post Thumbnails
        add_theme_support( 'post-thumbnails' );

        // Custom header setup
        add_theme_support( 'custom-header', array('header-text'=>false));

        // Custom backgrounds setup
        add_theme_support( 'custom-background');

        // Supported posts formats
        add_theme_support( 'post-formats', array('gallery', 'video', 'audio', 'link', 'quote', 'image', 'status', 'aside', 'chat') );

        // Autogenerate title tag
        add_theme_support('title-tag');

        // Add user menu
        add_theme_support('nav-menus');

        // WooCommerce Support
        add_theme_support( 'woocommerce' );

        // Add wide and full blocks support
        add_theme_support( 'align-wide' );

		// Register theme menus
		add_filter( 'poolservices_filter_add_theme_menus',		'poolservices_add_theme_menus' );

		// Register theme sidebars
		add_filter( 'poolservices_filter_add_theme_sidebars',	'poolservices_add_theme_sidebars' );

		// Set options for importer
		add_filter( 'poolservices_filter_importer_options',		'poolservices_set_importer_options' );

		// Add theme required plugins
		add_filter( 'poolservices_filter_required_plugins',		'poolservices_add_required_plugins' );
		
		// Add preloader styles
		add_filter('poolservices_filter_add_styles_inline',		'poolservices_head_add_page_preloader_styles');

		// Init theme after WP is created
		add_action( 'wp',									'poolservices_core_init_theme' );

		// Add theme specified classes into the body
		add_filter( 'body_class', 							'poolservices_body_classes' );

		// Add data to the head and to the beginning of the body
		add_action('wp_head',								'poolservices_head_add_page_meta', 0);
		add_action('before',								'poolservices_body_add_toc');
		add_action('before',								'poolservices_body_add_page_preloader');

		// Add data to the footer (priority 1, because priority 2 used for localize scripts)
		add_action('wp_footer',								'poolservices_footer_add_views_counter', 1);
		add_action('wp_footer',								'poolservices_footer_add_theme_customizer', 1);
		add_action('wp_footer',								'poolservices_footer_add_scroll_to_top', 1);

		// Set list of the theme required plugins
		poolservices_storage_set('required_plugins', array(
			'essgrids',
			'revslider',
			'trx_utils',
			'booked',
			'visual_composer',
			'woocommerce',
            'wp_gdpr_compliance',
            'contact_form_7'
			)
		);

		// Set list of the theme required custom fonts from folder /css/font-faces
		// Attention! Font's folder must have name equal to the font's name
		poolservices_storage_set('required_custom_fonts', array(
			'Amadeus'
			)
		);

	}
}


// Add/Remove theme nav menus
if ( !function_exists( 'poolservices_add_theme_menus' ) ) {
	function poolservices_add_theme_menus($menus) {
		return $menus;
	}
}


// Add theme specific widgetized areas
if ( !function_exists( 'poolservices_add_theme_sidebars' ) ) {
	function poolservices_add_theme_sidebars($sidebars=array()) {
		if (is_array($sidebars)) {
			$theme_sidebars = array(
				'sidebar_main'		=> esc_html__( 'Main Sidebar', 'poolservices' ),
				'sidebar_footer'	=> esc_html__( 'Footer Sidebar', 'poolservices' )
			);
			if (function_exists('poolservices_exists_woocommerce') && poolservices_exists_woocommerce()) {
				$theme_sidebars['sidebar_cart']  = esc_html__( 'WooCommerce Cart Sidebar', 'poolservices' );
			}
			$sidebars = array_merge($theme_sidebars, $sidebars);
		}
		return $sidebars;
	}
}


// Add theme required plugins
if ( !function_exists( 'poolservices_add_required_plugins' ) ) {
	function poolservices_add_required_plugins($plugins) {
		$plugins[] = array(
			'name' 		=> esc_html__('PoolServices Utilities', 'poolservices'),
			'version'	=> '3.1',					// Minimal required version
			'slug' 		=> 'trx_utils',
			'source'	=> poolservices_get_file_dir('plugins/install/trx_utils.zip'),
			'required' 	=> true
		);
		return $plugins;
	}
}


//// One-click import support
////------------------------------------------------------------------------
//
//// Set theme specific importer options
//if ( !function_exists( 'poolservices_set_importer_options' ) ) {
//	function poolservices_set_importer_options($options=array()) {
//		if (is_array($options)) {
//			// Default demo
//			$options['demo_url'] = poolservices_storage_get('demo_data_url');
//			// Default demo
//			$options['files']['default']['title'] = esc_html__('Default Demo', 'poolservices');
//			$options['files']['default']['domain_dev'] = esc_url(poolservices_get_protocol().'://poolservices.dv.ancorathemes.com');		// Developers domain
//			$options['files']['default']['domain_demo']= esc_url(poolservices_get_protocol().'://poolservices.ancorathemes.com');		// Demo-site domain
//
//		}
//		return $options;
//	}
//}

//------------------------------------------------------------------------
// One-click import support
//------------------------------------------------------------------------

// Set theme specific importer options
if ( ! function_exists( 'poolservices_importer_set_options' ) ) {
    add_filter( 'trx_utils_filter_importer_options', 'poolservices_importer_set_options', 9 );
    function poolservices_importer_set_options( $options=array() ) {
        if ( is_array( $options ) ) {
            // Save or not installer's messages to the log-file
            $options['debug'] = false;
            // Prepare demo data
            if ( is_dir( POOLSERVICES_THEME_PATH . 'demo/' ) ) {
                $options['demo_url'] = POOLSERVICES_THEME_PATH . 'demo/';
            } else {
                $options['demo_url'] = esc_url( poolservices_get_protocol().'://demofiles.ancorathemes.com/poolservices/' ); // Demo-site domain
            }

            // Required plugins
            $options['required_plugins'] =  array(
                'essential-grid',
                'revslider',
                'trx_utils',
                'booked',
                'js_composer',
                'woocommerce',
                'contact-form-7'
            );

            $options['theme_slug'] = 'poolservices';

            // Set number of thumbnails to regenerate when its imported (if demo data was zipped without cropped images)
            // Set 0 to prevent regenerate thumbnails (if demo data archive is already contain cropped images)
            $options['regenerate_thumbnails'] = 3;
            // Default demo
            $options['files']['default']['title'] = esc_html__( 'Education Demo', 'poolservices' );
            $options['files']['default']['domain_dev'] = esc_url(poolservices_get_protocol().'://poolservices.dv.ancorathemes.com'); // Developers domain
            $options['files']['default']['domain_demo']= esc_url(poolservices_get_protocol().'://poolservices.ancorathemes.com'); // Demo-site domain

        }
        return $options;
    }
}


// Add data to the head and to the beginning of the body
//------------------------------------------------------------------------

// Add theme specified classes to the body tag
if ( !function_exists('poolservices_body_classes') ) {
	//Handler of add_filter( 'body_class', 'poolservices_body_classes' );
	function poolservices_body_classes( $classes ) {

		$classes[] = 'poolservices_body';
		$classes[] = 'body_style_' . trim(poolservices_get_custom_option('body_style'));
		$classes[] = 'body_' . (poolservices_get_custom_option('body_filled')=='yes' ? 'filled' : 'transparent');
		$classes[] = 'article_style_' . trim(poolservices_get_custom_option('article_style'));
		
		$blog_style = poolservices_get_custom_option(is_singular() && !poolservices_storage_get('blog_streampage') ? 'single_style' : 'blog_style');
		$classes[] = 'layout_' . trim($blog_style);
		$classes[] = 'template_' . trim(poolservices_get_template_name($blog_style));
		
		$body_scheme = poolservices_get_custom_option('body_scheme');
		if (empty($body_scheme)  || poolservices_is_inherit_option($body_scheme)) $body_scheme = 'original';
		$classes[] = 'scheme_' . $body_scheme;

		$top_panel_position = poolservices_get_custom_option('top_panel_position');
		if (!poolservices_param_is_off($top_panel_position)) {
			$classes[] = 'top_panel_show';
			$classes[] = 'top_panel_' . trim($top_panel_position);
		} else 
			$classes[] = 'top_panel_hide';
		$classes[] = poolservices_get_sidebar_class();

		if (poolservices_get_custom_option('show_video_bg')=='yes' && (poolservices_get_custom_option('video_bg_youtube_code')!='' || poolservices_get_custom_option('video_bg_url')!=''))
			$classes[] = 'video_bg_show';

		if (!poolservices_param_is_off(poolservices_get_theme_option('page_preloader')))
			$classes[] = 'preloader';

		return $classes;
	}
}


// Add page meta to the head
if (!function_exists('poolservices_head_add_page_meta')) {
	function poolservices_head_add_page_meta() {
		?>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1<?php if (poolservices_get_theme_option('responsive_layouts')=='yes') echo ', maximum-scale=1'; ?>">
		<meta name="format-detection" content="telephone=no">
	
		<link rel="profile" href="http://gmpg.org/xfn/11" />
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		<?php
	}
}

// Add page preloader styles to the head
if (!function_exists('poolservices_head_add_page_preloader_styles')) {
	function poolservices_head_add_page_preloader_styles($css) {
		if (($preloader=poolservices_get_theme_option('page_preloader'))!='none') {
			$image = poolservices_get_theme_option('page_preloader_image');
			$bg_clr = poolservices_get_scheme_color('bg_color');
			$link_clr = poolservices_get_scheme_color('text_link');
			$css .= '
				#page_preloader {
					background-color: '. esc_attr($bg_clr) . ';'
					. ($preloader=='custom' && $image
						? 'background-image:url('.esc_url($image).');'
						: ''
						)
				    . '
				}
				.preloader_wrap > div {
					background-color: '.esc_attr($link_clr).';
				}';
		}
		return $css;
	}
}

// Add TOC anchors to the beginning of the body 
if (!function_exists('poolservices_body_add_toc')) {
	function poolservices_body_add_toc() {
		// Add TOC items 'Home' and "To top"
		if (poolservices_get_custom_option('menu_toc_home')=='yes' && function_exists('poolservices_sc_anchor'))
			poolservices_show_layout(poolservices_sc_anchor(array(
				'id' => "toc_home",
				'title' => esc_html__('Home', 'poolservices'),
				'description' => esc_html__('{{Return to Home}} - ||navigate to home page of the site', 'poolservices'),
				'icon' => "icon-home",
				'separator' => "yes",
				'url' => esc_url(home_url('/'))
				)
			)); 
		if (poolservices_get_custom_option('menu_toc_top')=='yes' && function_exists('poolservices_sc_anchor'))
			poolservices_show_layout(poolservices_sc_anchor(array(
				'id' => "toc_top",
				'title' => esc_html__('To Top', 'poolservices'),
				'description' => esc_html__('{{Back to top}} - ||scroll to top of the page', 'poolservices'),
				'icon' => "icon-double-up",
				'separator' => "yes")
				)); 
	}
}

// Add page preloader to the beginning of the body
if (!function_exists('poolservices_body_add_page_preloader')) {
	function poolservices_body_add_page_preloader() {
		if ( ($preloader=poolservices_get_theme_option('page_preloader')) != 'none' && ( $preloader != 'custom' || ($image=poolservices_get_theme_option('page_preloader_image')) != '')) {
			?><div id="page_preloader"><?php
				if ($preloader == 'circle') {
					?><div class="preloader_wrap preloader_<?php echo esc_attr($preloader); ?>"><div class="preloader_circ1"></div><div class="preloader_circ2"></div><div class="preloader_circ3"></div><div class="preloader_circ4"></div></div><?php
				} else if ($preloader == 'square') {
					?><div class="preloader_wrap preloader_<?php echo esc_attr($preloader); ?>"><div class="preloader_square1"></div><div class="preloader_square2"></div></div><?php
				}
			?></div><?php
		}
	}
}


// Add data to the footer
//------------------------------------------------------------------------

// Add post/page views counter
if (!function_exists('poolservices_footer_add_views_counter')) {
	function poolservices_footer_add_views_counter() {
		// Post/Page views counter
		get_template_part(poolservices_get_file_slug('templates/_parts/views-counter.php'));
	}
}

// Add theme customizer
if (!function_exists('poolservices_footer_add_theme_customizer')) {
	function poolservices_footer_add_theme_customizer() {
		// Front customizer
		if (poolservices_get_custom_option('show_theme_customizer')=='yes') {
			require_once POOLSERVICES_FW_PATH . 'core/core.customizer/front.customizer.php';
		}
	}
}

// Add scroll to top button
if (!function_exists('poolservices_footer_add_scroll_to_top')) {
	function poolservices_footer_add_scroll_to_top() {
		?><a href="#" class="scroll_to_top icon-up" title="<?php esc_attr_e('Scroll to top', 'poolservices'); ?>"></a><?php
	}
}

// Add theme required plugins
if ( !function_exists( 'poolservices_add_trx_utils' ) ) {
    add_filter( 'trx_utils_active', 'poolservices_add_trx_utils' );
    function poolservices_add_trx_utils($enable=true) {
        return true;
    }
}


// Include framework core files
//-------------------------------------------------------------------
require_once trailingslashit( get_template_directory() ) . 'fw/loader.php';
?>