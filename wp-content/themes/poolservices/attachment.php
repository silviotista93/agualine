<?php
/**
 * Attachment page
 */
get_header(); 

while ( have_posts() ) { the_post();

	// Move poolservices_set_post_views to the javascript - counter will work under cache system
	if (poolservices_get_custom_option('use_ajax_views_counter')=='no') {
		poolservices_set_post_views(get_the_ID());
	}

	poolservices_show_post_layout(
		array(
			'layout' => 'attachment',
			'sidebar' => !poolservices_param_is_off(poolservices_get_custom_option('show_sidebar_main'))
		)
	);

}

get_footer();
?>