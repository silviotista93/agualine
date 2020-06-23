<?php
/**
 * Single post
 */
get_header(); 

$single_style = poolservices_storage_get('single_style');
if (empty($single_style)) $single_style = poolservices_get_custom_option('single_style');

while ( have_posts() ) { the_post();
	poolservices_show_post_layout(
		array(
			'layout' => $single_style,
			'sidebar' => !poolservices_param_is_off(poolservices_get_custom_option('show_sidebar_main')),
			'content' => poolservices_get_template_property($single_style, 'need_content'),
			'terms_list' => poolservices_get_template_property($single_style, 'need_terms')
		)
	);
}

get_footer();
?>