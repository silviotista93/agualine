<?php 
if (is_singular()) {
	if (poolservices_get_theme_option('use_ajax_views_counter')=='yes') {
		poolservices_storage_set_array('js_vars', 'ajax_views_counter', array(
			'post_id' => get_the_ID(),
			'post_views' => poolservices_get_post_views(get_the_ID())
		));
	} else
		poolservices_set_post_views(get_the_ID());
}
?>