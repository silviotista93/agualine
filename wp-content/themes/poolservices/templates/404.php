<?php
/*
 * The template for displaying "Page 404"
*/

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'poolservices_template_404_theme_setup' ) ) {
	add_action( 'poolservices_action_before_init_theme', 'poolservices_template_404_theme_setup', 1 );
	function poolservices_template_404_theme_setup() {
		poolservices_add_template(array(
			'layout' => '404',
			'mode'   => 'internal',
			'title'  => 'Page 404',
			'theme_options' => array(
				'article_style' => 'stretch'
			)
		));
	}
}

// Template output
if ( !function_exists( 'poolservices_template_404_output' ) ) {
	function poolservices_template_404_output() {
		?>
		<article class="post_item post_item_404">
			<div class="post_content">
				<h1 class="page_title"><?php esc_html_e( '404', 'poolservices' ); ?></h1>
				<h2 class="page_subtitle"><?php esc_html_e('The requested page cannot be found', 'poolservices'); ?></h2>
				<p class="page_description"><?php echo wp_kses_data( sprintf( __('Can\'t find what you need? Take a moment and do a search below or start from <a href="%s">our homepage</a>.', 'poolservices'), esc_url(home_url('/')) ) ); ?></p>
				<div class="page_search"><?php if (function_exists('poolservices_sc_search'))  poolservices_show_layout(poolservices_sc_search(array('state'=>'fixed', 'title'=>__('To search type and hit enter', 'poolservices')))); ?></div>
			</div>
		</article>
		<?php
	}
}
?>