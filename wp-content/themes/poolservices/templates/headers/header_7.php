<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'poolservices_template_header_7_theme_setup' ) ) {
	add_action( 'poolservices_action_before_init_theme', 'poolservices_template_header_7_theme_setup', 1 );
	function poolservices_template_header_7_theme_setup() {
		poolservices_add_template(array(
			'layout' => 'header_7',
			'mode'   => 'header',
			'title'  => esc_html__('Header 7', 'poolservices'),
			'icon'   => poolservices_get_file_url('templates/headers/images/7.jpg'),
			'thumb_title'  => esc_html__('Original image', 'poolservices'),
			'w'		 => null,
			'h_crop' => null,
			'h'      => null
			));
	}
}

// Template output
if ( !function_exists( 'poolservices_template_header_7_output' ) ) {
	function poolservices_template_header_7_output($post_options, $post_data) {

		// Get custom image (for blog) or featured image (for single)
		$header_css = '';
		if (is_singular()) {
			$post_id = get_the_ID();
			$post_format = get_post_format();
		}
		if (empty($header_image))
			$header_image = poolservices_get_custom_option('top_panel_image');
		if (empty($header_image))
			$header_image = get_header_image();
		if (!empty($header_image)) {
			$header_css = ' style="background-image: url('.esc_url($header_image).')"';
		}
		?>
		
		<div class="top_panel_fixed_wrap"></div>
		<?php if(poolservices_get_custom_option('show_panel_image') == 'yes' && !is_front_page()) {?>
		<section class="top_panel_image" <?php poolservices_show_layout($header_css); ?>>
		<?php } ?>
		<header class="top_panel_wrap top_panel_style_7 scheme_<?php echo esc_attr($post_options['scheme']); ?>">
			<div class="top_panel_wrap_inner top_panel_inner_style_7 top_panel_position_<?php echo esc_attr(poolservices_get_custom_option('top_panel_position')); ?>">

			<div class="top_panel_middle">
				<div class="content_wrap">
					<div class="column-1_7 contact_logo">
						<?php poolservices_show_logo(true, true, false, false, false, false); ?>
					</div>
					<div class="column-6_7 menu_main_wrap">
						<nav class="menu_main_nav_area menu_hover_<?php echo esc_attr(poolservices_get_theme_option('menu_hover')); ?>">
							<?php
							$menu_main = poolservices_get_nav_menu('menu_main');
							if (empty($menu_main)) $menu_main = poolservices_get_nav_menu();
							poolservices_show_layout($menu_main);
							?>
						</nav>
						<?php
						if (function_exists('poolservices_exists_woocommerce') && poolservices_exists_woocommerce() && (poolservices_is_woocommerce_page() && poolservices_get_custom_option('show_cart')=='shop' || poolservices_get_custom_option('show_cart')=='always') && !(is_checkout() || is_cart() || defined('WOOCOMMERCE_CHECKOUT') || defined('WOOCOMMERCE_CART'))) { 
							?>
							<div class="menu_main_cart top_panel_icon">
								<?php get_template_part(poolservices_get_file_slug('templates/headers/_parts/contact-info-cart.php')); ?>
							</div>
							<?php
						}
						if (poolservices_get_custom_option('show_search')=='yes' && function_exists('poolservices_sc_search'))
							poolservices_show_layout(poolservices_sc_search(array('class'=>"top_panel_icon", 'state'=>"closed")));
						poolservices_template_set_args('top-panel-top', array(
							'top_panel_top_components' => array('contact_info')
						));
						get_template_part(poolservices_get_file_slug('templates/headers/_parts/top-panel-top.php'));
						?>
					</div>
				</div>
			</div>
			

			</div>
		</header>
		<?php if(poolservices_get_custom_option('show_panel_image') == 'yes' && !is_front_page()) {?>
			<div class="top_panel_image_header">
				<h1 itemprop="headline" class="top_panel_image_title entry-title"><?php echo strip_tags(poolservices_get_blog_title()); ?></h1>
				<div class="breadcrumbs">
					<?php if (!is_404()) poolservices_show_breadcrumbs(); ?>
				</div>
			</div>
		</section>
		<?php } ?>
		<?php
		poolservices_storage_set('header_mobile', array(
				 'open_hours' => false,
				 'login' => false,
				 'socials' => false,
				 'bookmarks' => false,
				 'contact_address' => false,
				 'contact_phone_email' => false,
				 'woo_cart' => true,
				 'search' => true
			)
		);
	}
}
?>