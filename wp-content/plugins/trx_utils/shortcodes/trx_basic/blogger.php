<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('poolservices_sc_blogger_theme_setup')) {
	add_action( 'poolservices_action_before_init_theme', 'poolservices_sc_blogger_theme_setup' );
	function poolservices_sc_blogger_theme_setup() {
		add_action('poolservices_action_shortcodes_list', 		'poolservices_sc_blogger_reg_shortcodes');
		if (function_exists('poolservices_exists_visual_composer') && poolservices_exists_visual_composer())
			add_action('poolservices_action_shortcodes_list_vc','poolservices_sc_blogger_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_blogger id="unique_id" ids="comma_separated_list" cat="id|slug" orderby="date|views|comments" order="asc|desc" count="5" descr="0" dir="horizontal|vertical" style="regular|date|image_large|image_medium|image_small|accordion|list" border="0"]
*/
poolservices_storage_set('sc_blogger_busy', false);

if (!function_exists('poolservices_sc_blogger')) {	
	function poolservices_sc_blogger($atts, $content=null){	
		if (poolservices_in_shortcode_blogger(true)) return '';
		extract(poolservices_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "accordion",
			"filters" => "no",
			"post_type" => "post",
			"ids" => "",
			"cat" => "",
			"count" => "3",
			"columns" => "",
			"offset" => "",
			"orderby" => "date",
			"order" => "desc",
			"only" => "no",
			"descr" => "",
			"readmore" => "",
			"loadmore" => "no",
			"location" => "default",
			"dir" => "horizontal",
			"hover" => poolservices_get_theme_option('hover_style'),
			"hover_dir" => poolservices_get_theme_option('hover_dir'),
			"scroll" => "no",
			"controls" => "no",
			"rating" => "no",
			"info" => "yes",
			"links" => "yes",
			"date_format" => "",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link" => '',
			"link_caption" => esc_html__('Learn more', 'poolservices'),
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
	
		$class .= ($class ? ' ' : '') . poolservices_get_css_position_as_classes($top, $right, $bottom, $left);

		$css .= poolservices_get_css_dimensions_from_values($width, $height);
		$width  = poolservices_prepare_css_value($width);
		$height = poolservices_prepare_css_value($height);
	
		global $post;
	
		poolservices_storage_set('sc_blogger_busy', true);
		poolservices_storage_set('sc_blogger_counter', 0);
	
		if (empty($id)) $id = "sc_blogger_".str_replace('.', '', mt_rand());
		
		if ($style=='date' && empty($date_format)) $date_format = 'd.m+Y';
	
		if (!empty($ids)) {
			$posts = explode(',', str_replace(' ', '', $ids));
			$count = count($posts);
		}
		
		if ($descr == '') $descr = poolservices_get_custom_option('post_excerpt_maxlength'.($columns > 1 ? '_masonry' : ''));
	
		if (!poolservices_param_is_off($scroll)) {
			poolservices_enqueue_slider();
			if (empty($id)) $id = 'sc_blogger_'.str_replace('.', '', mt_rand());
		}
		
		$class = apply_filters('poolservices_filter_blog_class',
					'sc_blogger'
					. ' layout_'.esc_attr($style)
					. ' template_'.esc_attr(poolservices_get_template_name($style))
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. ' ' . esc_attr(poolservices_get_template_property($style, 'container_classes'))
					. ' sc_blogger_' . ($dir=='vertical' ? 'vertical' : 'horizontal')
					. (poolservices_param_is_on($scroll) && poolservices_param_is_on($controls) ? ' sc_scroll_controls sc_scroll_controls_type_top sc_scroll_controls_'.esc_attr($dir) : '')
					. ($descr == 0 ? ' no_description' : ''),
					array('style'=>$style, 'dir'=>$dir, 'descr'=>$descr)
		);
	
		$container = apply_filters('poolservices_filter_blog_container', poolservices_get_template_property($style, 'container'), array('style'=>$style, 'dir'=>$dir));
		$container_start = $container_end = '';
		if (!empty($container)) {
			$container = explode('%s', $container);
			$container_start = !empty($container[0]) ? $container[0] : '';
			$container_end = !empty($container[1]) ? $container[1] : '';
		}
		$container2 = apply_filters('poolservices_filter_blog_container2', poolservices_get_template_property($style, 'container2'), array('style'=>$style, 'dir'=>$dir));
		$container2_start = $container2_end = '';
		if (!empty($container2)) {
			$container2 = explode('%s', $container2);
			$container2_start = !empty($container2[0]) ? $container2[0] : '';
			$container2_end = !empty($container2[1]) ? $container2[1] : '';
		}
	
		$output = '<div'
				. ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="'.($style=='list' ? 'sc_list sc_list_style_iconed ' : '') . esc_attr($class).'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. (!poolservices_param_is_off($animation) ? ' data-animation="'.esc_attr(poolservices_get_animation_classes($animation)).'"' : '')
			. '>'
			. ($container_start)
			. (!empty($title) ? '<h4 class="sc_blogger_title sc_item_title' . (empty($description) ? ' sc_item_title_without_descr' : ' sc_item_title_without_descr') . '">' . trim(poolservices_strmacros($title)) . '</h4>' : '')
			. (!empty($subtitle) ? '<h6 class="sc_blogger_subtitle sc_item_subtitle">' . trim(poolservices_strmacros($subtitle)) . '</h6>' : '')
			. (!empty($description) ? '<div class="sc_blogger_descr sc_item_descr">' . trim(poolservices_strmacros($description)) . '</div>' : '')
			. ($container2_start)
			. ($style=='list' ? '<ul class="sc_list sc_list_style_iconed">' : '')
			. ($dir=='horizontal' && $columns > 1 && poolservices_get_template_property($style, 'need_columns') ? '<div class="columns_wrap">' : '')
			. (poolservices_param_is_on($scroll) 
				? '<div id="'.esc_attr($id).'_scroll" class="sc_scroll sc_scroll_'.esc_attr($dir).' sc_slider_noresize swiper-slider-container scroll-container"'
					. ' style="'.($dir=='vertical' ? 'height:'.($height != '' ? $height : "230px").';' : 'width:'.($width != '' ? $width.';' : "100%;")).'"'
					. '>'
					. '<div class="sc_scroll_wrapper swiper-wrapper">' 
						. '<div class="sc_scroll_slide swiper-slide">' 
				: '')
			;
	
		if (poolservices_get_template_property($style, 'need_isotope')) {
			if (!poolservices_param_is_off($filters))
				$output .= '<div class="isotope_filters"></div>';
			if ($columns<1) $columns = poolservices_substr($style, -1);
			$output .= '<div class="isotope_wrap" data-columns="'.max(1, min(12, $columns)).'">';
		}
	
		$args = array(
			'post_status' => current_user_can('read_private_pages') && current_user_can('read_private_posts') ? array('publish', 'private') : 'publish',
			'posts_per_page' => $count,
			'ignore_sticky_posts' => true,
			'order' => $order=='asc' ? 'asc' : 'desc',
			'orderby' => 'date',
		);
	
		if ($offset > 0 && empty($ids)) {
			$args['offset'] = $offset;
		}
	
		$args = poolservices_query_add_sort_order($args, $orderby, $order);
		if (!poolservices_param_is_off($only)) $args = poolservices_query_add_filters($args, $only);
		$args = poolservices_query_add_posts_and_cats($args, $ids, $post_type, $cat);
	
		$query = new WP_Query( $args );
	
		$flt_ids = array();
	
		while ( $query->have_posts() ) { $query->the_post();
	
			poolservices_storage_inc('sc_blogger_counter');
	
			$args = array(
				'layout' => $style,
				'show' => false,
				'number' => poolservices_storage_get('sc_blogger_counter'),
				'add_view_more' => false,
				'posts_on_page' => ($count > 0 ? $count : $query->found_posts),
				// Additional options to layout generator
				"location" => $location,
				"descr" => $descr,
				"readmore" => $readmore,
				"loadmore" => $loadmore,
				"reviews" => poolservices_param_is_on($rating),
				"dir" => $dir,
				"scroll" => poolservices_param_is_on($scroll),
				"info" => poolservices_param_is_on($info),
				"links" => poolservices_param_is_on($links),
				"orderby" => $orderby,
				"columns_count" => $columns,
				"date_format" => $date_format,
				// Get post data
				'strip_teaser' => false,
				'content' => poolservices_get_template_property($style, 'need_content'),
				'terms_list' => !poolservices_param_is_off($filters) || poolservices_get_template_property($style, 'need_terms'),
				'filters' => poolservices_param_is_off($filters) ? '' : $filters,
				'hover' => $hover,
				'hover_dir' => $hover_dir
			);
			$post_data = poolservices_get_post_data($args);
			$output .= poolservices_show_post_layout($args, $post_data);
		
			if (!poolservices_param_is_off($filters)) {
				if ($filters == 'tags') {			// Use tags as filter items
					if (!empty($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms) && is_array($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms)) {
						foreach ($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms as $tag) {
							$flt_ids[$tag->term_id] = $tag->name;
						}
					}
				}
			}
	
		}
	
		wp_reset_postdata();
	
		// Close isotope wrapper
		if (poolservices_get_template_property($style, 'need_isotope'))
			$output .= '</div>';
	
		// Isotope filters list
		if (!poolservices_param_is_off($filters)) {
			$filters_list = '';
			if ($filters == 'categories') {			// Use categories as filter items
				$taxonomy = poolservices_get_taxonomy_categories_by_post_type($post_type);
				$portfolio_parent = $cat ? max(0, poolservices_get_parent_taxonomy_by_property($cat, 'show_filters', 'yes', true, $taxonomy)) : 0;
				$args2 = array(
					'type'			=> $post_type,
					'child_of'		=> $portfolio_parent,
					'orderby'		=> 'name',
					'order'			=> 'ASC',
					'hide_empty'	=> 1,
					'hierarchical'	=> 0,
					'exclude'		=> '',
					'include'		=> '',
					'number'		=> '',
					'taxonomy'		=> $taxonomy,
					'pad_counts'	=> false
				);
				$portfolio_list = get_categories($args2);
				if (is_array($portfolio_list) && count($portfolio_list) > 0) {
					$filters_list .= '<a href="#" data-filter="*" class="theme_button active">'.esc_html__('All', 'poolservices').'</a>';
					foreach ($portfolio_list as $cat) {
						$filters_list .= '<a href="#" data-filter=".flt_'.esc_attr($cat->term_id).'" class="theme_button">'.($cat->name).'</a>';
					}
				}
			} else {								// Use tags as filter items
				if (is_array($flt_ids) && count($flt_ids) > 0) {
					$filters_list .= '<a href="#" data-filter="*" class="theme_button active">'.esc_html__('All', 'poolservices').'</a>';
					foreach ($flt_ids as $flt_id=>$flt_name) {
						$filters_list .= '<a href="#" data-filter=".flt_'.esc_attr($flt_id).'" class="theme_button">'.($flt_name).'</a>';
					}
				}
			}
			if ($filters_list) {
				poolservices_storage_concat('js_code', '
					jQuery("#'.esc_attr($id).'.isotope_filters").append("'.addslashes($filters_list).'");
				');
			}
		}
		$output	.= (poolservices_param_is_on($scroll) 
				? '</div></div><div id="'.esc_attr($id).'_scroll_bar" class="sc_scroll_bar sc_scroll_bar_'.esc_attr($dir).' '.esc_attr($id).'_scroll_bar"></div></div>'
					. (!poolservices_param_is_off($controls) ? '<div class="sc_scroll_controls_wrap"><a class="sc_scroll_prev" href="#"></a><a class="sc_scroll_next" href="#"></a></div>' : '')
				: '')
			. ($dir=='horizontal' && $columns > 1 && poolservices_get_template_property($style, 'need_columns') ? '</div>' :  '')
			. ($style == 'list' ? '</ul>' : '')
			. ($container2_end)
			. (!empty($link) 
				? '<div class="sc_blogger_button sc_item_button">'.do_shortcode('[trx_button link="'.esc_url($link).'" size="large"]'.esc_html($link_caption).'[/trx_button]').'</div>' 				: '')
			. ($container_end)
			. '</div>';
	
		// Add template specific scripts and styles
		do_action('poolservices_action_blog_scripts', $style);
		
		poolservices_storage_set('sc_blogger_busy', false);
	
		return apply_filters('poolservices_shortcode_output', $output, 'trx_blogger', $atts, $content);
	}
	poolservices_require_shortcode('trx_blogger', 'poolservices_sc_blogger');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'poolservices_sc_blogger_reg_shortcodes' ) ) {
	//add_action('poolservices_action_shortcodes_list', 'poolservices_sc_blogger_reg_shortcodes');
	function poolservices_sc_blogger_reg_shortcodes() {
	
		poolservices_sc_map("trx_blogger", array(
			"title" => esc_html__("Blogger", 'poolservices'),
			"desc" => wp_kses_data( __("Insert posts (pages) in many styles from desired categories or directly from ids", 'poolservices') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"title" => array(
					"title" => esc_html__("Title", 'poolservices'),
					"desc" => wp_kses_data( __("Title for the block", 'poolservices') ),
					"value" => "",
					"type" => "text"
				),
				"subtitle" => array(
					"title" => esc_html__("Subtitle", 'poolservices'),
					"desc" => wp_kses_data( __("Subtitle for the block", 'poolservices') ),
					"value" => "",
					"type" => "text"
				),
				"description" => array(
					"title" => esc_html__("Description", 'poolservices'),
					"desc" => wp_kses_data( __("Short description for the block", 'poolservices') ),
					"value" => "",
					"type" => "textarea"
				),
				"style" => array(
					"title" => esc_html__("Posts output style", 'poolservices'),
					"desc" => wp_kses_data( __("Select desired style for posts output", 'poolservices') ),
					"value" => "accordion",
					"type" => "select",
					"options" => poolservices_get_sc_param('blogger_styles')
				),
				"filters" => array(
					"title" => esc_html__("Show filters", 'poolservices'),
					"desc" => wp_kses_data( __("Use post's tags or categories as filter buttons", 'poolservices') ),
					"value" => "no",
					"dir" => "horizontal",
					"type" => "checklist",
					"options" => poolservices_get_sc_param('filters')
				),
				"hover" => array(
					"title" => esc_html__("Hover effect", 'poolservices'),
					"desc" => wp_kses_data( __("Select hover effect (only if style=Portfolio)", 'poolservices') ),
					"dependency" => array(
						'style' => array('portfolio','grid','square','short','colored')
					),
					"value" => "",
					"type" => "select",
					"options" => poolservices_get_sc_param('hovers')
				),
				"hover_dir" => array(
					"title" => esc_html__("Hover direction", 'poolservices'),
					"desc" => wp_kses_data( __("Select hover direction (only if style=Portfolio and hover=Circle|Square)", 'poolservices') ),
					"dependency" => array(
						'style' => array('portfolio','grid','square','short','colored'),
						'hover' => array('square','circle')
					),
					"value" => "left_to_right",
					"type" => "select",
					"options" => poolservices_get_sc_param('hovers_dir')
				),
				"dir" => array(
					"title" => esc_html__("Posts direction", 'poolservices'),
					"desc" => wp_kses_data( __("Display posts in horizontal or vertical direction", 'poolservices') ),
					"value" => "horizontal",
					"type" => "switch",
					"size" => "big",
					"options" => poolservices_get_sc_param('dir')
				),
				"post_type" => array(
					"title" => esc_html__("Post type", 'poolservices'),
					"desc" => wp_kses_data( __("Select post type to show", 'poolservices') ),
					"value" => "post",
					"type" => "select",
					"options" => poolservices_get_sc_param('posts_types')
				),
				"ids" => array(
					"title" => esc_html__("Post IDs list", 'poolservices'),
					"desc" => wp_kses_data( __("Comma separated list of posts ID. If set - parameters above are ignored!", 'poolservices') ),
					"value" => "",
					"type" => "text"
				),
				"cat" => array(
					"title" => esc_html__("Categories list", 'poolservices'),
					"desc" => wp_kses_data( __("Select the desired categories. If not selected - show posts from any category or from IDs list", 'poolservices') ),
					"dependency" => array(
						'ids' => array('is_empty'),
						'post_type' => array('refresh')
					),
					"divider" => true,
					"value" => "",
					"type" => "select",
					"style" => "list",
					"multiple" => true,
					"options" => poolservices_array_merge(array(0 => esc_html__('- Select category -', 'poolservices')), poolservices_get_sc_param('categories'))
				),
				"count" => array(
					"title" => esc_html__("Total posts to show", 'poolservices'),
					"desc" => wp_kses_data( __("How many posts will be displayed? If used IDs - this parameter ignored.", 'poolservices') ),
					"dependency" => array(
						'ids' => array('is_empty')
					),
					"value" => 3,
					"min" => 1,
					"max" => 100,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns number", 'poolservices'),
					"desc" => wp_kses_data( __("How many columns used to show posts? If empty or 0 - equal to posts number", 'poolservices') ),
					"dependency" => array(
						'dir' => array('horizontal')
					),
					"value" => 3,
					"min" => 1,
					"max" => 100,
					"type" => "spinner"
				),
				"offset" => array(
					"title" => esc_html__("Offset before select posts", 'poolservices'),
					"desc" => wp_kses_data( __("Skip posts before select next part.", 'poolservices') ),
					"dependency" => array(
						'ids' => array('is_empty')
					),
					"value" => 0,
					"min" => 0,
					"max" => 100,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Post order by", 'poolservices'),
					"desc" => wp_kses_data( __("Select desired posts sorting method", 'poolservices') ),
					"value" => "date",
					"type" => "select",
					"options" => poolservices_get_sc_param('sorting')
				),
				"order" => array(
					"title" => esc_html__("Post order", 'poolservices'),
					"desc" => wp_kses_data( __("Select desired posts order", 'poolservices') ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => poolservices_get_sc_param('ordering')
				),
				"only" => array(
					"title" => esc_html__("Select posts only", 'poolservices'),
					"desc" => wp_kses_data( __("Select posts only with reviews, videos, audios, thumbs or galleries", 'poolservices') ),
					"value" => "no",
					"type" => "select",
					"options" => poolservices_get_sc_param('formats')
				),
				"scroll" => array(
					"title" => esc_html__("Use scroller", 'poolservices'),
					"desc" => wp_kses_data( __("Use scroller to show all posts", 'poolservices') ),
					"divider" => true,
					"value" => "no",
					"type" => "switch",
					"options" => poolservices_get_sc_param('yes_no')
				),
				"controls" => array(
					"title" => esc_html__("Show slider controls", 'poolservices'),
					"desc" => wp_kses_data( __("Show arrows to control scroll slider", 'poolservices') ),
					"dependency" => array(
						'scroll' => array('yes')
					),
					"value" => "no",
					"type" => "switch",
					"options" => poolservices_get_sc_param('yes_no')
				),
				"location" => array(
					"title" => esc_html__("Dedicated content location", 'poolservices'),
					"desc" => wp_kses_data( __("Select position for dedicated content (only for style=excerpt)", 'poolservices') ),
					"divider" => true,
					"dependency" => array(
						'style' => array('excerpt')
					),
					"value" => "default",
					"type" => "select",
					"options" => poolservices_get_sc_param('locations')
				),
				"rating" => array(
					"title" => esc_html__("Show rating stars", 'poolservices'),
					"desc" => wp_kses_data( __("Show rating stars under post's header", 'poolservices') ),
					"value" => "no",
					"type" => "switch",
					"options" => poolservices_get_sc_param('yes_no')
				),
				"info" => array(
					"title" => esc_html__("Show post info block", 'poolservices'),
					"desc" => wp_kses_data( __("Show post info block (author, date, tags, etc.)", 'poolservices') ),
					"value" => "no",
					"type" => "switch",
					"options" => poolservices_get_sc_param('yes_no')
				),
				"links" => array(
					"title" => esc_html__("Allow links on the post", 'poolservices'),
					"desc" => wp_kses_data( __("Allow links on the post from each blogger item", 'poolservices') ),
					"value" => "yes",
					"type" => "switch",
					"options" => poolservices_get_sc_param('yes_no')
				),
				"descr" => array(
					"title" => esc_html__("Description length", 'poolservices'),
					"desc" => wp_kses_data( __("How many characters are displayed from post excerpt? If 0 - don't show description", 'poolservices') ),
					"value" => 0,
					"min" => 0,
					"step" => 10,
					"type" => "spinner"
				),
				"readmore" => array(
					"title" => esc_html__("More link text", 'poolservices'),
					"desc" => wp_kses_data( __("Read more link text. If empty - show 'More', else - used as link text", 'poolservices') ),
					"value" => "",
					"type" => "text"
				),
				"link" => array(
					"title" => esc_html__("Button URL", 'poolservices'),
					"desc" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'poolservices') ),
					"value" => "",
					"type" => "text"
				),
				"link_caption" => array(
					"title" => esc_html__("Button caption", 'poolservices'),
					"desc" => wp_kses_data( __("Caption for the button at the bottom of the block", 'poolservices') ),
					"value" => "",
					"type" => "text"
				),
				"width" => poolservices_shortcodes_width(),
				"height" => poolservices_shortcodes_height(),
				"top" => poolservices_get_sc_param('top'),
				"bottom" => poolservices_get_sc_param('bottom'),
				"left" => poolservices_get_sc_param('left'),
				"right" => poolservices_get_sc_param('right'),
				"id" => poolservices_get_sc_param('id'),
				"class" => poolservices_get_sc_param('class'),
				"animation" => poolservices_get_sc_param('animation'),
				"css" => poolservices_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'poolservices_sc_blogger_reg_shortcodes_vc' ) ) {
	//add_action('poolservices_action_shortcodes_list_vc', 'poolservices_sc_blogger_reg_shortcodes_vc');
	function poolservices_sc_blogger_reg_shortcodes_vc() {

		vc_map( array(
			"base" => "trx_blogger",
			"name" => esc_html__("Blogger", 'poolservices'),
			"description" => wp_kses_data( __("Insert posts (pages) in many styles from desired categories or directly from ids", 'poolservices') ),
			"category" => esc_html__('Content', 'poolservices'),
			'icon' => 'icon_trx_blogger',
			"class" => "trx_sc_single trx_sc_blogger",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Output style", 'poolservices'),
					"description" => wp_kses_data( __("Select desired style for posts output", 'poolservices') ),
					"admin_label" => true,
					"std" => "accordion",
					"class" => "",
					"value" => array_flip(poolservices_get_sc_param('blogger_styles')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "filters",
					"heading" => esc_html__("Show filters", 'poolservices'),
					"description" => wp_kses_data( __("Use post's tags or categories as filter buttons", 'poolservices') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(poolservices_get_sc_param('filters')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "hover",
					"heading" => esc_html__("Hover effect", 'poolservices'),
					"description" => wp_kses_data( __("Select hover effect (only if style=Portfolio)", 'poolservices') ),
					"class" => "",
					"value" => array_flip(poolservices_get_sc_param('hovers')),
					'dependency' => array(
						'element' => 'style',
						'value' => array('portfolio_2','portfolio_3','portfolio_4','grid_2','grid_3','grid_4','square_2','square_3','square_4','short_2','short_3','short_4','colored_2','colored_3','colored_4')
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "hover_dir",
					"heading" => esc_html__("Hover direction", 'poolservices'),
					"description" => wp_kses_data( __("Select hover direction (only if style=Portfolio and hover=Circle|Square)", 'poolservices') ),
					"class" => "",
					"value" => array_flip(poolservices_get_sc_param('hovers_dir')),
					'dependency' => array(
						'element' => 'style',
						'value' => array('portfolio_2','portfolio_3','portfolio_4','grid_2','grid_3','grid_4','square_2','square_3','square_4','short_2','short_3','short_4','colored_2','colored_3','colored_4')
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "location",
					"heading" => esc_html__("Dedicated content location", 'poolservices'),
					"description" => wp_kses_data( __("Select position for dedicated content (only for style=excerpt)", 'poolservices') ),
					"class" => "",
					'dependency' => array(
						'element' => 'style',
						'value' => array('excerpt')
					),
					"value" => array_flip(poolservices_get_sc_param('locations')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "dir",
					"heading" => esc_html__("Posts direction", 'poolservices'),
					"description" => wp_kses_data( __("Display posts in horizontal or vertical direction", 'poolservices') ),
					"admin_label" => true,
					"class" => "",
					"std" => "horizontal",
					"value" => array_flip(poolservices_get_sc_param('dir')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "columns",
					"heading" => esc_html__("Columns number", 'poolservices'),
					"description" => wp_kses_data( __("How many columns used to display posts?", 'poolservices') ),
					'dependency' => array(
						'element' => 'dir',
						'value' => 'horizontal'
					),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "rating",
					"heading" => esc_html__("Show rating stars", 'poolservices'),
					"description" => wp_kses_data( __("Show rating stars under post's header", 'poolservices') ),
					"group" => esc_html__('Details', 'poolservices'),
					"class" => "",
					"value" => array(esc_html__('Show rating', 'poolservices') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "info",
					"heading" => esc_html__("Show post info block", 'poolservices'),
					"description" => wp_kses_data( __("Show post info block (author, date, tags, etc.)", 'poolservices') ),
					"class" => "",
					"std" => 'yes',
					"value" => array(esc_html__('Show info', 'poolservices') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "descr",
					"heading" => esc_html__("Description length", 'poolservices'),
					"description" => wp_kses_data( __("How many characters are displayed from post excerpt? If 0 - don't show description", 'poolservices') ),
					"group" => esc_html__('Details', 'poolservices'),
					"class" => "",
					"value" => 0,
					"type" => "textfield"
				),
				array(
					"param_name" => "links",
					"heading" => esc_html__("Allow links to the post", 'poolservices'),
					"description" => wp_kses_data( __("Allow links to the post from each blogger item", 'poolservices') ),
					"group" => esc_html__('Details', 'poolservices'),
					"class" => "",
					"std" => 'yes',
					"value" => array(esc_html__('Allow links', 'poolservices') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "readmore",
					"heading" => esc_html__("More link text", 'poolservices'),
					"description" => wp_kses_data( __("Read more link text. If empty - show 'More', else - used as link text", 'poolservices') ),
					"group" => esc_html__('Details', 'poolservices'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'poolservices'),
					"description" => wp_kses_data( __("Title for the block", 'poolservices') ),
					"admin_label" => true,
					"group" => esc_html__('Captions', 'poolservices'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "subtitle",
					"heading" => esc_html__("Subtitle", 'poolservices'),
					"description" => wp_kses_data( __("Subtitle for the block", 'poolservices') ),
					"group" => esc_html__('Captions', 'poolservices'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "description",
					"heading" => esc_html__("Description", 'poolservices'),
					"description" => wp_kses_data( __("Description for the block", 'poolservices') ),
					"group" => esc_html__('Captions', 'poolservices'),
					"class" => "",
					"value" => "",
					"type" => "textarea"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Button URL", 'poolservices'),
					"description" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'poolservices') ),
					"group" => esc_html__('Captions', 'poolservices'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link_caption",
					"heading" => esc_html__("Button caption", 'poolservices'),
					"description" => wp_kses_data( __("Caption for the button at the bottom of the block", 'poolservices') ),
					"group" => esc_html__('Captions', 'poolservices'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "post_type",
					"heading" => esc_html__("Post type", 'poolservices'),
					"description" => wp_kses_data( __("Select post type to show", 'poolservices') ),
					"group" => esc_html__('Query', 'poolservices'),
					"class" => "",
					"value" => array_flip(poolservices_get_sc_param('posts_types')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "ids",
					"heading" => esc_html__("Post IDs list", 'poolservices'),
					"description" => wp_kses_data( __("Comma separated list of posts ID. If set - parameters above are ignored!", 'poolservices') ),
					"group" => esc_html__('Query', 'poolservices'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "cat",
					"heading" => esc_html__("Categories list", 'poolservices'),
					"description" => wp_kses_data( __("Select category. If empty - show posts from any category or from IDs list", 'poolservices') ),
					'dependency' => array(
						'element' => 'ids',
						'is_empty' => true
					),
					"group" => esc_html__('Query', 'poolservices'),
					"class" => "",
					"value" => array_flip(poolservices_array_merge(array(0 => esc_html__('- Select category -', 'poolservices')), poolservices_get_sc_param('categories'))),
					"type" => "dropdown"
				),
				array(
					"param_name" => "count",
					"heading" => esc_html__("Total posts to show", 'poolservices'),
					"description" => wp_kses_data( __("How many posts will be displayed? If used IDs - this parameter ignored.", 'poolservices') ),
					'dependency' => array(
						'element' => 'ids',
						'is_empty' => true
					),
					"admin_label" => true,
					"group" => esc_html__('Query', 'poolservices'),
					"class" => "",
					"value" => 3,
					"type" => "textfield"
				),
				array(
					"param_name" => "offset",
					"heading" => esc_html__("Offset before select posts", 'poolservices'),
					"description" => wp_kses_data( __("Skip posts before select next part.", 'poolservices') ),
					'dependency' => array(
						'element' => 'ids',
						'is_empty' => true
					),
					"group" => esc_html__('Query', 'poolservices'),
					"class" => "",
					"value" => 0,
					"type" => "textfield"
				),
				array(
					"param_name" => "orderby",
					"heading" => esc_html__("Post order by", 'poolservices'),
					"description" => wp_kses_data( __("Select desired posts sorting method", 'poolservices') ),
					"class" => "",
					"group" => esc_html__('Query', 'poolservices'),
					"value" => array_flip(poolservices_get_sc_param('sorting')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "order",
					"heading" => esc_html__("Post order", 'poolservices'),
					"description" => wp_kses_data( __("Select desired posts order", 'poolservices') ),
					"class" => "",
					"group" => esc_html__('Query', 'poolservices'),
					"value" => array_flip(poolservices_get_sc_param('ordering')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "only",
					"heading" => esc_html__("Select posts only", 'poolservices'),
					"description" => wp_kses_data( __("Select posts only with reviews, videos, audios, thumbs or galleries", 'poolservices') ),
					"class" => "",
					"group" => esc_html__('Query', 'poolservices'),
					"value" => array_flip(poolservices_get_sc_param('formats')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "scroll",
					"heading" => esc_html__("Use scroller", 'poolservices'),
					"description" => wp_kses_data( __("Use scroller to show all posts", 'poolservices') ),
					"group" => esc_html__('Scroll', 'poolservices'),
					"class" => "",
					"value" => array(esc_html__('Use scroller', 'poolservices') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "controls",
					"heading" => esc_html__("Show slider controls", 'poolservices'),
					"description" => wp_kses_data( __("Show arrows to control scroll slider", 'poolservices') ),
					"group" => esc_html__('Scroll', 'poolservices'),
					"class" => "",
					"value" => array(esc_html__('Show controls', 'poolservices') => 'yes'),
					"type" => "checkbox"
				),
				poolservices_get_vc_param('id'),
				poolservices_get_vc_param('class'),
				poolservices_get_vc_param('animation'),
				poolservices_get_vc_param('css'),
				poolservices_vc_width(),
				poolservices_vc_height(),
				poolservices_get_vc_param('margin_top'),
				poolservices_get_vc_param('margin_bottom'),
				poolservices_get_vc_param('margin_left'),
				poolservices_get_vc_param('margin_right')
			),
		) );
		
		class WPBakeryShortCode_Trx_Blogger extends POOLSERVICES_VC_ShortCodeSingle {}
	}
}
?>