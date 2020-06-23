<?php
/**
 * PoolServices Framework: return lists
 *
 * @package poolservices
 * @since poolservices 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }



// Return styles list
if ( !function_exists( 'poolservices_get_list_styles' ) ) {
	function poolservices_get_list_styles($from=1, $to=2, $prepend_inherit=false) {
		$list = array();
		for ($i=$from; $i<=$to; $i++)
			$list[$i] = sprintf(esc_html__('Style %d', 'poolservices'), $i);
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}


// Return list of the shortcodes margins
if ( !function_exists( 'poolservices_get_list_margins' ) ) {
	function poolservices_get_list_margins($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_margins'))=='') {
			$list = array(
				'null'		=> esc_html__('0 (No margin)',	'poolservices'),
				'tiny'		=> esc_html__('Tiny',		'poolservices'),
				'small'		=> esc_html__('Small',		'poolservices'),
				'medium'	=> esc_html__('Medium',		'poolservices'),
				'large'		=> esc_html__('Large',		'poolservices'),
				'huge'		=> esc_html__('Huge',		'poolservices'),
				'tiny-'		=> esc_html__('Tiny (negative)',	'poolservices'),
				'small-'	=> esc_html__('Small (negative)',	'poolservices'),
				'medium-'	=> esc_html__('Medium (negative)',	'poolservices'),
				'large-'	=> esc_html__('Large (negative)',	'poolservices'),
				'huge-'		=> esc_html__('Huge (negative)',	'poolservices')
				);
			$list = apply_filters('poolservices_filter_list_margins', $list);
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_margins', $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}


// Return list of the line styles
if ( !function_exists( 'poolservices_get_list_line_styles' ) ) {
	function poolservices_get_list_line_styles($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_line_styles'))=='') {
			$list = array(
				'solid'	=> esc_html__('Solid', 'poolservices'),
				'dashed'=> esc_html__('Dashed', 'poolservices'),
				'dotted'=> esc_html__('Dotted', 'poolservices'),
				'double'=> esc_html__('Double', 'poolservices'),
				'image'	=> esc_html__('Image', 'poolservices')
				);
			$list = apply_filters('poolservices_filter_list_line_styles', $list);
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_line_styles', $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}


// Return list of the animations
if ( !function_exists( 'poolservices_get_list_animations' ) ) {
	function poolservices_get_list_animations($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_animations'))=='') {
			$list = array(
				'none'			=> esc_html__('- None -',	'poolservices'),
				'bounce'		=> esc_html__('Bounce',		'poolservices'),
				'elastic'		=> esc_html__('Elastic',	'poolservices'),
				'flash'			=> esc_html__('Flash',		'poolservices'),
				'flip'			=> esc_html__('Flip',		'poolservices'),
				'pulse'			=> esc_html__('Pulse',		'poolservices'),
				'rubberBand'	=> esc_html__('Rubber Band','poolservices'),
				'shake'			=> esc_html__('Shake',		'poolservices'),
				'swing'			=> esc_html__('Swing',		'poolservices'),
				'tada'			=> esc_html__('Tada',		'poolservices'),
				'wobble'		=> esc_html__('Wobble',		'poolservices')
				);
			$list = apply_filters('poolservices_filter_list_animations', $list);
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_animations', $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}


// Return list of the enter animations
if ( !function_exists( 'poolservices_get_list_animations_in' ) ) {
	function poolservices_get_list_animations_in($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_animations_in'))=='') {
			$list = array(
				'none'				=> esc_html__('- None -',			'poolservices'),
				'bounceIn'			=> esc_html__('Bounce In',			'poolservices'),
				'bounceInUp'		=> esc_html__('Bounce In Up',		'poolservices'),
				'bounceInDown'		=> esc_html__('Bounce In Down',		'poolservices'),
				'bounceInLeft'		=> esc_html__('Bounce In Left',		'poolservices'),
				'bounceInRight'		=> esc_html__('Bounce In Right',	'poolservices'),
				'elastic'			=> esc_html__('Elastic In',			'poolservices'),
				'fadeIn'			=> esc_html__('Fade In',			'poolservices'),
				'fadeInUp'			=> esc_html__('Fade In Up',			'poolservices'),
				'fadeInUpSmall'		=> esc_html__('Fade In Up Small',	'poolservices'),
				'fadeInUpBig'		=> esc_html__('Fade In Up Big',		'poolservices'),
				'fadeInDown'		=> esc_html__('Fade In Down',		'poolservices'),
				'fadeInDownBig'		=> esc_html__('Fade In Down Big',	'poolservices'),
				'fadeInLeft'		=> esc_html__('Fade In Left',		'poolservices'),
				'fadeInLeftBig'		=> esc_html__('Fade In Left Big',	'poolservices'),
				'fadeInRight'		=> esc_html__('Fade In Right',		'poolservices'),
				'fadeInRightBig'	=> esc_html__('Fade In Right Big',	'poolservices'),
				'flipInX'			=> esc_html__('Flip In X',			'poolservices'),
				'flipInY'			=> esc_html__('Flip In Y',			'poolservices'),
				'lightSpeedIn'		=> esc_html__('Light Speed In',		'poolservices'),
				'rotateIn'			=> esc_html__('Rotate In',			'poolservices'),
				'rotateInUpLeft'	=> esc_html__('Rotate In Down Left','poolservices'),
				'rotateInUpRight'	=> esc_html__('Rotate In Up Right',	'poolservices'),
				'rotateInDownLeft'	=> esc_html__('Rotate In Up Left',	'poolservices'),
				'rotateInDownRight'	=> esc_html__('Rotate In Down Right','poolservices'),
				'rollIn'			=> esc_html__('Roll In',			'poolservices'),
				'slideInUp'			=> esc_html__('Slide In Up',		'poolservices'),
				'slideInDown'		=> esc_html__('Slide In Down',		'poolservices'),
				'slideInLeft'		=> esc_html__('Slide In Left',		'poolservices'),
				'slideInRight'		=> esc_html__('Slide In Right',		'poolservices'),
				'wipeInLeftTop'		=> esc_html__('Wipe In Left Top',	'poolservices'),
				'zoomIn'			=> esc_html__('Zoom In',			'poolservices'),
				'zoomInUp'			=> esc_html__('Zoom In Up',			'poolservices'),
				'zoomInDown'		=> esc_html__('Zoom In Down',		'poolservices'),
				'zoomInLeft'		=> esc_html__('Zoom In Left',		'poolservices'),
				'zoomInRight'		=> esc_html__('Zoom In Right',		'poolservices')
				);
			$list = apply_filters('poolservices_filter_list_animations_in', $list);
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_animations_in', $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}


// Return list of the out animations
if ( !function_exists( 'poolservices_get_list_animations_out' ) ) {
	function poolservices_get_list_animations_out($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_animations_out'))=='') {
			$list = array(
				'none'				=> esc_html__('- None -',			'poolservices'),
				'bounceOut'			=> esc_html__('Bounce Out',			'poolservices'),
				'bounceOutUp'		=> esc_html__('Bounce Out Up',		'poolservices'),
				'bounceOutDown'		=> esc_html__('Bounce Out Down',	'poolservices'),
				'bounceOutLeft'		=> esc_html__('Bounce Out Left',	'poolservices'),
				'bounceOutRight'	=> esc_html__('Bounce Out Right',	'poolservices'),
				'fadeOut'			=> esc_html__('Fade Out',			'poolservices'),
				'fadeOutUp'			=> esc_html__('Fade Out Up',		'poolservices'),
				'fadeOutUpBig'		=> esc_html__('Fade Out Up Big',	'poolservices'),
				'fadeOutDown'		=> esc_html__('Fade Out Down',		'poolservices'),
				'fadeOutDownSmall'	=> esc_html__('Fade Out Down Small','poolservices'),
				'fadeOutDownBig'	=> esc_html__('Fade Out Down Big',	'poolservices'),
				'fadeOutLeft'		=> esc_html__('Fade Out Left',		'poolservices'),
				'fadeOutLeftBig'	=> esc_html__('Fade Out Left Big',	'poolservices'),
				'fadeOutRight'		=> esc_html__('Fade Out Right',		'poolservices'),
				'fadeOutRightBig'	=> esc_html__('Fade Out Right Big',	'poolservices'),
				'flipOutX'			=> esc_html__('Flip Out X',			'poolservices'),
				'flipOutY'			=> esc_html__('Flip Out Y',			'poolservices'),
				'hinge'				=> esc_html__('Hinge Out',			'poolservices'),
				'lightSpeedOut'		=> esc_html__('Light Speed Out',	'poolservices'),
				'rotateOut'			=> esc_html__('Rotate Out',			'poolservices'),
				'rotateOutUpLeft'	=> esc_html__('Rotate Out Down Left','poolservices'),
				'rotateOutUpRight'	=> esc_html__('Rotate Out Up Right','poolservices'),
				'rotateOutDownLeft'	=> esc_html__('Rotate Out Up Left',	'poolservices'),
				'rotateOutDownRight'=> esc_html__('Rotate Out Down Right','poolservices'),
				'rollOut'			=> esc_html__('Roll Out',			'poolservices'),
				'slideOutUp'		=> esc_html__('Slide Out Up',		'poolservices'),
				'slideOutDown'		=> esc_html__('Slide Out Down',		'poolservices'),
				'slideOutLeft'		=> esc_html__('Slide Out Left',		'poolservices'),
				'slideOutRight'		=> esc_html__('Slide Out Right',	'poolservices'),
				'zoomOut'			=> esc_html__('Zoom Out',			'poolservices'),
				'zoomOutUp'			=> esc_html__('Zoom Out Up',		'poolservices'),
				'zoomOutDown'		=> esc_html__('Zoom Out Down',		'poolservices'),
				'zoomOutLeft'		=> esc_html__('Zoom Out Left',		'poolservices'),
				'zoomOutRight'		=> esc_html__('Zoom Out Right',		'poolservices')
				);
			$list = apply_filters('poolservices_filter_list_animations_out', $list);
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_animations_out', $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}

// Return classes list for the specified animation
if (!function_exists('poolservices_get_animation_classes')) {
	function poolservices_get_animation_classes($animation, $speed='normal', $loop='none') {
		// speed:	fast=0.5s | normal=1s | slow=2s
		// loop:	none | infinite
		return poolservices_param_is_off($animation) ? '' : 'animated '.esc_attr($animation).' '.esc_attr($speed).(!poolservices_param_is_off($loop) ? ' '.esc_attr($loop) : '');
	}
}


// Return list of the main menu hover effects
if ( !function_exists( 'poolservices_get_list_menu_hovers' ) ) {
	function poolservices_get_list_menu_hovers($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_menu_hovers'))=='') {
			$list = array(
				'fade'			=> esc_html__('Fade',		'poolservices'),
				'slide_line'	=> esc_html__('Slide Line',	'poolservices'),
				'slide_box'		=> esc_html__('Slide Box',	'poolservices'),
				'zoom_line'		=> esc_html__('Zoom Line',	'poolservices'),
				'path_line'		=> esc_html__('Path Line',	'poolservices'),
				'roll_down'		=> esc_html__('Roll Down',	'poolservices'),
				'color_line'	=> esc_html__('Color Line',	'poolservices'),
				);
			$list = apply_filters('poolservices_filter_list_menu_hovers', $list);
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_menu_hovers', $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}


// Return list of the button's hover effects
if ( !function_exists( 'poolservices_get_list_button_hovers' ) ) {
	function poolservices_get_list_button_hovers($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_button_hovers'))=='') {
			$list = array(
				'default'		=> esc_html__('Default',			'poolservices'),
				'fade'			=> esc_html__('Fade',				'poolservices'),
				'slide_left'	=> esc_html__('Slide from Left',	'poolservices'),
				'slide_top'		=> esc_html__('Slide from Top',		'poolservices'),
				'arrow'			=> esc_html__('Arrow',				'poolservices'),
				);
			$list = apply_filters('poolservices_filter_list_button_hovers', $list);
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_button_hovers', $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}


// Return list of the input field's hover effects
if ( !function_exists( 'poolservices_get_list_input_hovers' ) ) {
	function poolservices_get_list_input_hovers($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_input_hovers'))=='') {
			$list = array(
				'default'	=> esc_html__('Default',	'poolservices'),
				'accent'	=> esc_html__('Accented',	'poolservices'),
				'path'		=> esc_html__('Path',		'poolservices'),
				'jump'		=> esc_html__('Jump',		'poolservices'),
				'underline'	=> esc_html__('Underline',	'poolservices'),
				'iconed'	=> esc_html__('Iconed',		'poolservices'),
				);
			$list = apply_filters('poolservices_filter_list_input_hovers', $list);
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_input_hovers', $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}


// Return list of the search field's styles
if ( !function_exists( 'poolservices_get_list_search_styles' ) ) {
	function poolservices_get_list_search_styles($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_search_styles'))=='') {
			$list = array(
				'default'	=> esc_html__('Default',	'poolservices'),
				'fullscreen'=> esc_html__('Fullscreen',	'poolservices'),
				'slide'		=> esc_html__('Slide',		'poolservices'),
				'expand'	=> esc_html__('Expand',		'poolservices'),
				);
			$list = apply_filters('poolservices_filter_list_search_styles', $list);
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_search_styles', $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}


// Return list of categories
if ( !function_exists( 'poolservices_get_list_categories' ) ) {
	function poolservices_get_list_categories($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_categories'))=='') {
			$list = array();
			$args = array(
				'type'                     => 'post',
				'child_of'                 => 0,
				'parent'                   => '',
				'orderby'                  => 'name',
				'order'                    => 'ASC',
				'hide_empty'               => 0,
				'hierarchical'             => 1,
				'exclude'                  => '',
				'include'                  => '',
				'number'                   => '',
				'taxonomy'                 => 'category',
				'pad_counts'               => false );
			$taxonomies = get_categories( $args );
			if (is_array($taxonomies) && count($taxonomies) > 0) {
				foreach ($taxonomies as $cat) {
					$list[$cat->term_id] = $cat->name;
				}
			}
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_categories', $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}


// Return list of taxonomies
if ( !function_exists( 'poolservices_get_list_terms' ) ) {
	function poolservices_get_list_terms($prepend_inherit=false, $taxonomy='category') {
		if (($list = poolservices_storage_get('list_taxonomies_'.($taxonomy)))=='') {
			$list = array();
			if ( is_array($taxonomy) || taxonomy_exists($taxonomy) ) {
				$terms = get_terms( $taxonomy, array(
					'child_of'                 => 0,
					'parent'                   => '',
					'orderby'                  => 'name',
					'order'                    => 'ASC',
					'hide_empty'               => 0,
					'hierarchical'             => 1,
					'exclude'                  => '',
					'include'                  => '',
					'number'                   => '',
					'taxonomy'                 => $taxonomy,
					'pad_counts'               => false
					)
				);
			} else {
				$terms = poolservices_get_terms_by_taxonomy_from_db($taxonomy);
			}
			if (!is_wp_error( $terms ) && is_array($terms) && count($terms) > 0) {
				foreach ($terms as $cat) {
					$list[$cat->term_id] = $cat->name;	// . ($taxonomy!='category' ? ' /'.($cat->taxonomy).'/' : '');
				}
			}
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_taxonomies_'.($taxonomy), $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}

// Return list of post's types
if ( !function_exists( 'poolservices_get_list_posts_types' ) ) {
	function poolservices_get_list_posts_types($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_posts_types'))=='') {
			// Return only theme inheritance supported post types
			$list = apply_filters('poolservices_filter_list_post_types', array());
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_posts_types', $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}


// Return list post items from any post type and taxonomy
if ( !function_exists( 'poolservices_get_list_posts' ) ) {
	function poolservices_get_list_posts($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'post',
			'post_status'		=> 'publish',
			'taxonomy'			=> 'category',
			'taxonomy_value'	=> '',
			'posts_per_page'	=> -1,
			'orderby'			=> 'post_date',
			'order'				=> 'desc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));

		$hash = 'list_posts_'.($opt['post_type']).'_'.($opt['taxonomy']).'_'.($opt['taxonomy_value']).'_'.($opt['orderby']).'_'.($opt['order']).'_'.($opt['return']).'_'.($opt['posts_per_page']);
		if (($list = poolservices_storage_get($hash))=='') {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'poolservices');
			$args = array(
				'post_type' => $opt['post_type'],
				'post_status' => $opt['post_status'],
				'posts_per_page' => $opt['posts_per_page'],
				'ignore_sticky_posts' => true,
				'orderby'	=> $opt['orderby'],
				'order'		=> $opt['order']
			);
			if (!empty($opt['taxonomy_value'])) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => $opt['taxonomy'],
						'field' => (int) $opt['taxonomy_value'] > 0 ? 'id' : 'slug',
						'terms' => $opt['taxonomy_value']
					)
				);
			}
			$posts = get_posts( $args );
			if (is_array($posts) && count($posts) > 0) {
				foreach ($posts as $post) {
					$list[$opt['return']=='id' ? $post->ID : $post->post_title] = $post->post_title;
				}
			}
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set($hash, $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}


// Return list pages
if ( !function_exists( 'poolservices_get_list_pages' ) ) {
	function poolservices_get_list_pages($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'page',
			'post_status'		=> 'publish',
			'posts_per_page'	=> -1,
			'orderby'			=> 'title',
			'order'				=> 'asc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));
		return poolservices_get_list_posts($prepend_inherit, $opt);
	}
}


// Return list of registered users
if ( !function_exists( 'poolservices_get_list_users' ) ) {
	function poolservices_get_list_users($prepend_inherit=false, $roles=array('administrator', 'editor', 'author', 'contributor', 'shop_manager')) {
		if (($list = poolservices_storage_get('list_users'))=='') {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'poolservices');
			$args = array(
				'orderby'	=> 'display_name',
				'order'		=> 'ASC' );
			$users = get_users( $args );
			if (is_array($users) && count($users) > 0) {
				foreach ($users as $user) {
					$accept = true;
					if (is_array($user->roles)) {
						if (is_array($user->roles) && count($user->roles) > 0) {
							$accept = false;
							foreach ($user->roles as $role) {
								if (in_array($role, $roles)) {
									$accept = true;
									break;
								}
							}
						}
					}
					if ($accept) $list[$user->user_login] = $user->display_name;
				}
			}
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_users', $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}


// Return slider engines list, prepended inherit (if need)
if ( !function_exists( 'poolservices_get_list_sliders' ) ) {
	function poolservices_get_list_sliders($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_sliders'))=='') {
			$list = array(
				'swiper' => esc_html__("Posts slider (Swiper)", 'poolservices')
			);
			$list = apply_filters('poolservices_filter_list_sliders', $list);
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_sliders', $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}


// Return slider controls list, prepended inherit (if need)
if ( !function_exists( 'poolservices_get_list_slider_controls' ) ) {
	function poolservices_get_list_slider_controls($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_slider_controls'))=='') {
			$list = array(
				'no'		=> esc_html__('None', 'poolservices'),
				'side'		=> esc_html__('Side', 'poolservices'),
				'bottom'	=> esc_html__('Bottom', 'poolservices'),
				'pagination'=> esc_html__('Pagination', 'poolservices')
				);
			$list = apply_filters('poolservices_filter_list_slider_controls', $list);
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_slider_controls', $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}


// Return slider controls classes
if ( !function_exists( 'poolservices_get_slider_controls_classes' ) ) {
	function poolservices_get_slider_controls_classes($controls) {
		if (poolservices_param_is_off($controls))	$classes = 'sc_slider_nopagination sc_slider_nocontrols';
		else if ($controls=='bottom')			$classes = 'sc_slider_nopagination sc_slider_controls sc_slider_controls_bottom';
		else if ($controls=='pagination')		$classes = 'sc_slider_pagination sc_slider_pagination_bottom sc_slider_nocontrols';
		else									$classes = 'sc_slider_nopagination sc_slider_controls sc_slider_controls_side';
		return $classes;
	}
}

// Return list with popup engines
if ( !function_exists( 'poolservices_get_list_popup_engines' ) ) {
	function poolservices_get_list_popup_engines($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_popup_engines'))=='') {
			$list = array(
				"pretty"	=> esc_html__("Pretty photo", 'poolservices'),
				"magnific"	=> esc_html__("Magnific popup", 'poolservices')
				);
			$list = apply_filters('poolservices_filter_list_popup_engines', $list);
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_popup_engines', $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}

// Return menus list, prepended inherit
if ( !function_exists( 'poolservices_get_list_menus' ) ) {
	function poolservices_get_list_menus($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_menus'))=='') {
			$list = array();
			$list['default'] = esc_html__("Default", 'poolservices');
			$menus = wp_get_nav_menus();
			if (is_array($menus) && count($menus) > 0) {
				foreach ($menus as $menu) {
					$list[$menu->slug] = $menu->name;
				}
			}
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_menus', $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}

// Return custom sidebars list, prepended inherit and main sidebars item (if need)
if ( !function_exists( 'poolservices_get_list_sidebars' ) ) {
	function poolservices_get_list_sidebars($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_sidebars'))=='') {
			if (($list = poolservices_storage_get('registered_sidebars'))=='') $list = array();
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_sidebars', $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}

// Return sidebars positions
if ( !function_exists( 'poolservices_get_list_sidebars_positions' ) ) {
	function poolservices_get_list_sidebars_positions($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_sidebars_positions'))=='') {
			$list = array(
				'none'  => esc_html__('Hide',  'poolservices'),
				'left'  => esc_html__('Left',  'poolservices'),
				'right' => esc_html__('Right', 'poolservices')
				);
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_sidebars_positions', $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}

// Return sidebars class
if ( !function_exists( 'poolservices_get_sidebar_class' ) ) {
	function poolservices_get_sidebar_class() {
		$sb_main = poolservices_get_custom_option('show_sidebar_main');
		$sb_outer = poolservices_get_custom_option('show_sidebar_outer');
		return (poolservices_param_is_off($sb_main) ? 'sidebar_hide' : 'sidebar_show sidebar_'.($sb_main))
				. ' ' . (poolservices_param_is_off($sb_outer) ? 'sidebar_outer_hide' : 'sidebar_outer_show sidebar_outer_'.($sb_outer));
	}
}

// Return body styles list, prepended inherit
if ( !function_exists( 'poolservices_get_list_body_styles' ) ) {
	function poolservices_get_list_body_styles($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_body_styles'))=='') {
			$list = array(
				'boxed'	=> esc_html__('Boxed',		'poolservices'),
				'wide'	=> esc_html__('Wide',		'poolservices')
				);
			if (poolservices_get_theme_setting('allow_fullscreen')) {
				$list['fullwide']	= esc_html__('Fullwide',	'poolservices');
				$list['fullscreen']	= esc_html__('Fullscreen',	'poolservices');
			}
			$list = apply_filters('poolservices_filter_list_body_styles', $list);
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_body_styles', $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}

// Return templates list, prepended inherit
if ( !function_exists( 'poolservices_get_list_templates' ) ) {
	function poolservices_get_list_templates($mode='') {
		if (($list = poolservices_storage_get('list_templates_'.($mode)))=='') {
			$list = array();
			$tpl = poolservices_storage_get('registered_templates');
			if (is_array($tpl) && count($tpl) > 0) {
				foreach ($tpl as $k=>$v) {
					if ($mode=='' || in_array($mode, explode(',', $v['mode'])))
						$list[$k] = !empty($v['icon']) 
									? $v['icon'] 
									: (!empty($v['title']) 
										? $v['title'] 
										: poolservices_strtoproper($v['layout'])
										);
				}
			}
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_templates_'.($mode), $list);
		}
		return $list;
	}
}

// Return blog styles list, prepended inherit
if ( !function_exists( 'poolservices_get_list_templates_blog' ) ) {
	function poolservices_get_list_templates_blog($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_templates_blog'))=='') {
			$list = poolservices_get_list_templates('blog');
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_templates_blog', $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}

// Return blogger styles list, prepended inherit
if ( !function_exists( 'poolservices_get_list_templates_blogger' ) ) {
	function poolservices_get_list_templates_blogger($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_templates_blogger'))=='') {
			$list = poolservices_array_merge(poolservices_get_list_templates('blogger'), poolservices_get_list_templates('blog'));
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_templates_blogger', $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}

// Return single page styles list, prepended inherit
if ( !function_exists( 'poolservices_get_list_templates_single' ) ) {
	function poolservices_get_list_templates_single($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_templates_single'))=='') {
			$list = poolservices_get_list_templates('single');
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_templates_single', $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}

// Return header styles list, prepended inherit
if ( !function_exists( 'poolservices_get_list_templates_header' ) ) {
	function poolservices_get_list_templates_header($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_templates_header'))=='') {
			$list = poolservices_get_list_templates('header');
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_templates_header', $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}

// Return form styles list, prepended inherit
if ( !function_exists( 'poolservices_get_list_templates_forms' ) ) {
	function poolservices_get_list_templates_forms($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_templates_forms'))=='') {
			$list = poolservices_get_list_templates('forms');
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_templates_forms', $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}

// Return article styles list, prepended inherit
if ( !function_exists( 'poolservices_get_list_article_styles' ) ) {
	function poolservices_get_list_article_styles($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_article_styles'))=='') {
			$list = array(
				"boxed"   => esc_html__('Boxed', 'poolservices'),
				"stretch" => esc_html__('Stretch', 'poolservices')
				);
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_article_styles', $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}

// Return post-formats filters list, prepended inherit
if ( !function_exists( 'poolservices_get_list_post_formats_filters' ) ) {
	function poolservices_get_list_post_formats_filters($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_post_formats_filters'))=='') {
			$list = array(
				"no"      => esc_html__('All posts', 'poolservices'),
				"thumbs"  => esc_html__('With thumbs', 'poolservices'),
				"reviews" => esc_html__('With reviews', 'poolservices'),
				"video"   => esc_html__('With videos', 'poolservices'),
				"audio"   => esc_html__('With audios', 'poolservices'),
				"gallery" => esc_html__('With galleries', 'poolservices')
				);
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_post_formats_filters', $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}

// Return portfolio filters list, prepended inherit
if ( !function_exists( 'poolservices_get_list_portfolio_filters' ) ) {
	function poolservices_get_list_portfolio_filters($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_portfolio_filters'))=='') {
			$list = array(
				"hide"		=> esc_html__('Hide', 'poolservices'),
				"tags"		=> esc_html__('Tags', 'poolservices'),
				"categories"=> esc_html__('Categories', 'poolservices')
				);
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_portfolio_filters', $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}

// Return hover styles list, prepended inherit
if ( !function_exists( 'poolservices_get_list_hovers' ) ) {
	function poolservices_get_list_hovers($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_hovers'))=='') {
			$list = array();
			$list['circle effect1']  = esc_html__('Circle Effect 1',  'poolservices');
			$list['circle effect2']  = esc_html__('Circle Effect 2',  'poolservices');
			$list['circle effect3']  = esc_html__('Circle Effect 3',  'poolservices');
			$list['circle effect4']  = esc_html__('Circle Effect 4',  'poolservices');
			$list['circle effect5']  = esc_html__('Circle Effect 5',  'poolservices');
			$list['circle effect6']  = esc_html__('Circle Effect 6',  'poolservices');
			$list['circle effect7']  = esc_html__('Circle Effect 7',  'poolservices');
			$list['circle effect8']  = esc_html__('Circle Effect 8',  'poolservices');
			$list['circle effect9']  = esc_html__('Circle Effect 9',  'poolservices');
			$list['circle effect10'] = esc_html__('Circle Effect 10',  'poolservices');
			$list['circle effect11'] = esc_html__('Circle Effect 11',  'poolservices');
			$list['circle effect12'] = esc_html__('Circle Effect 12',  'poolservices');
			$list['circle effect13'] = esc_html__('Circle Effect 13',  'poolservices');
			$list['circle effect14'] = esc_html__('Circle Effect 14',  'poolservices');
			$list['circle effect15'] = esc_html__('Circle Effect 15',  'poolservices');
			$list['circle effect16'] = esc_html__('Circle Effect 16',  'poolservices');
			$list['circle effect17'] = esc_html__('Circle Effect 17',  'poolservices');
			$list['circle effect18'] = esc_html__('Circle Effect 18',  'poolservices');
			$list['circle effect19'] = esc_html__('Circle Effect 19',  'poolservices');
			$list['circle effect20'] = esc_html__('Circle Effect 20',  'poolservices');
			$list['square effect1']  = esc_html__('Square Effect 1',  'poolservices');
			$list['square effect2']  = esc_html__('Square Effect 2',  'poolservices');
			$list['square effect3']  = esc_html__('Square Effect 3',  'poolservices');
			$list['square effect5']  = esc_html__('Square Effect 5',  'poolservices');
			$list['square effect6']  = esc_html__('Square Effect 6',  'poolservices');
			$list['square effect7']  = esc_html__('Square Effect 7',  'poolservices');
			$list['square effect8']  = esc_html__('Square Effect 8',  'poolservices');
			$list['square effect9']  = esc_html__('Square Effect 9',  'poolservices');
			$list['square effect10'] = esc_html__('Square Effect 10',  'poolservices');
			$list['square effect11'] = esc_html__('Square Effect 11',  'poolservices');
			$list['square effect12'] = esc_html__('Square Effect 12',  'poolservices');
			$list['square effect13'] = esc_html__('Square Effect 13',  'poolservices');
			$list['square effect14'] = esc_html__('Square Effect 14',  'poolservices');
			$list['square effect15'] = esc_html__('Square Effect 15',  'poolservices');
			$list['square effect_dir']   = esc_html__('Square Effect Dir',   'poolservices');
			$list['square effect_shift'] = esc_html__('Square Effect Shift', 'poolservices');
			$list['square effect_book']  = esc_html__('Square Effect Book',  'poolservices');
			$list['square effect_more']  = esc_html__('Square Effect More',  'poolservices');
			$list['square effect_fade']  = esc_html__('Square Effect Fade',  'poolservices');
			$list['square effect_pull']  = esc_html__('Square Effect Pull',  'poolservices');
			$list['square effect_slide'] = esc_html__('Square Effect Slide', 'poolservices');
			$list['square effect_border'] = esc_html__('Square Effect Border', 'poolservices');
			$list = apply_filters('poolservices_filter_portfolio_hovers', $list);
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_hovers', $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}


// Return list of the blog counters
if ( !function_exists( 'poolservices_get_list_blog_counters' ) ) {
	function poolservices_get_list_blog_counters($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_blog_counters'))=='') {
			$list = array(
				'views'		=> esc_html__('Views', 'poolservices'),
				'likes'		=> esc_html__('Likes', 'poolservices'),
				'rating'	=> esc_html__('Rating', 'poolservices'),
				'comments'	=> esc_html__('Comments', 'poolservices')
				);
			$list = apply_filters('poolservices_filter_list_blog_counters', $list);
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_blog_counters', $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}

// Return list of the item sizes for the portfolio alter style, prepended inherit
if ( !function_exists( 'poolservices_get_list_alter_sizes' ) ) {
	function poolservices_get_list_alter_sizes($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_alter_sizes'))=='') {
			$list = array(
					'1_1' => esc_html__('1x1', 'poolservices'),
					'1_2' => esc_html__('1x2', 'poolservices'),
					'2_1' => esc_html__('2x1', 'poolservices'),
					'2_2' => esc_html__('2x2', 'poolservices'),
					'1_3' => esc_html__('1x3', 'poolservices'),
					'2_3' => esc_html__('2x3', 'poolservices'),
					'3_1' => esc_html__('3x1', 'poolservices'),
					'3_2' => esc_html__('3x2', 'poolservices'),
					'3_3' => esc_html__('3x3', 'poolservices')
					);
			$list = apply_filters('poolservices_filter_portfolio_alter_sizes', $list);
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_alter_sizes', $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}

// Return extended hover directions list, prepended inherit
if ( !function_exists( 'poolservices_get_list_hovers_directions' ) ) {
	function poolservices_get_list_hovers_directions($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_hovers_directions'))=='') {
			$list = array(
				'left_to_right' => esc_html__('Left to Right',  'poolservices'),
				'right_to_left' => esc_html__('Right to Left',  'poolservices'),
				'top_to_bottom' => esc_html__('Top to Bottom',  'poolservices'),
				'bottom_to_top' => esc_html__('Bottom to Top',  'poolservices'),
				'scale_up'      => esc_html__('Scale Up',  'poolservices'),
				'scale_down'    => esc_html__('Scale Down',  'poolservices'),
				'scale_down_up' => esc_html__('Scale Down-Up',  'poolservices'),
				'from_left_and_right' => esc_html__('From Left and Right',  'poolservices'),
				'from_top_and_bottom' => esc_html__('From Top and Bottom',  'poolservices')
			);
			$list = apply_filters('poolservices_filter_portfolio_hovers_directions', $list);
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_hovers_directions', $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}


// Return list of the label positions in the custom forms
if ( !function_exists( 'poolservices_get_list_label_positions' ) ) {
	function poolservices_get_list_label_positions($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_label_positions'))=='') {
			$list = array(
				'top'		=> esc_html__('Top',		'poolservices'),
				'bottom'	=> esc_html__('Bottom',		'poolservices'),
				'left'		=> esc_html__('Left',		'poolservices'),
				'over'		=> esc_html__('Over',		'poolservices')
			);
			$list = apply_filters('poolservices_filter_label_positions', $list);
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_label_positions', $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}


// Return list of the bg image positions
if ( !function_exists( 'poolservices_get_list_bg_image_positions' ) ) {
	function poolservices_get_list_bg_image_positions($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_bg_image_positions'))=='') {
			$list = array(
				'left top'	   => esc_html__('Left Top', 'poolservices'),
				'center top'   => esc_html__("Center Top", 'poolservices'),
				'right top'    => esc_html__("Right Top", 'poolservices'),
				'left center'  => esc_html__("Left Center", 'poolservices'),
				'center center'=> esc_html__("Center Center", 'poolservices'),
				'right center' => esc_html__("Right Center", 'poolservices'),
				'left bottom'  => esc_html__("Left Bottom", 'poolservices'),
				'center bottom'=> esc_html__("Center Bottom", 'poolservices'),
				'right bottom' => esc_html__("Right Bottom", 'poolservices')
			);
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_bg_image_positions', $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}


// Return list of the bg image repeat
if ( !function_exists( 'poolservices_get_list_bg_image_repeats' ) ) {
	function poolservices_get_list_bg_image_repeats($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_bg_image_repeats'))=='') {
			$list = array(
				'repeat'	=> esc_html__('Repeat', 'poolservices'),
				'repeat-x'	=> esc_html__('Repeat X', 'poolservices'),
				'repeat-y'	=> esc_html__('Repeat Y', 'poolservices'),
				'no-repeat'	=> esc_html__('No Repeat', 'poolservices')
			);
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_bg_image_repeats', $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}


// Return list of the bg image attachment
if ( !function_exists( 'poolservices_get_list_bg_image_attachments' ) ) {
	function poolservices_get_list_bg_image_attachments($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_bg_image_attachments'))=='') {
			$list = array(
				'scroll'	=> esc_html__('Scroll', 'poolservices'),
				'fixed'		=> esc_html__('Fixed', 'poolservices'),
				'local'		=> esc_html__('Local', 'poolservices')
			);
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_bg_image_attachments', $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}


// Return list of the bg tints
if ( !function_exists( 'poolservices_get_list_bg_tints' ) ) {
	function poolservices_get_list_bg_tints($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_bg_tints'))=='') {
			$list = array(
				'white'	=> esc_html__('White', 'poolservices'),
				'light'	=> esc_html__('Light', 'poolservices'),
				'dark'	=> esc_html__('Dark', 'poolservices')
			);
			$list = apply_filters('poolservices_filter_bg_tints', $list);
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_bg_tints', $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}

// Return custom fields types list, prepended inherit
if ( !function_exists( 'poolservices_get_list_field_types' ) ) {
	function poolservices_get_list_field_types($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_field_types'))=='') {
			$list = array(
				'text'     => esc_html__('Text',  'poolservices'),
				'textarea' => esc_html__('Text Area','poolservices'),
				'password' => esc_html__('Password',  'poolservices'),
				'radio'    => esc_html__('Radio',  'poolservices'),
				'checkbox' => esc_html__('Checkbox',  'poolservices'),
				'select'   => esc_html__('Select',  'poolservices'),
				'date'     => esc_html__('Date','poolservices'),
				'time'     => esc_html__('Time','poolservices'),
				'button'   => esc_html__('Button','poolservices')
			);
			$list = apply_filters('poolservices_filter_field_types', $list);
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_field_types', $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}

// Return Google map styles
if ( !function_exists( 'poolservices_get_list_googlemap_styles' ) ) {
	function poolservices_get_list_googlemap_styles($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_googlemap_styles'))=='') {
			$list = array(
				'default' => esc_html__('Default', 'poolservices')
			);
			$list = apply_filters('poolservices_filter_googlemap_styles', $list);
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_googlemap_styles', $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}

// Return images list
if (!function_exists('poolservices_get_list_images')) {
	function poolservices_get_list_images($folder, $ext='', $only_names=false) {
		return function_exists('trx_utils_get_folder_list') ? trx_utils_get_folder_list($folder, $ext, $only_names) : array();
	}
}

// Return iconed classes list
if ( !function_exists( 'poolservices_get_list_icons' ) ) {
	function poolservices_get_list_icons($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_icons'))=='') {
			$list = poolservices_parse_icons_classes(poolservices_get_file_dir("css/fontello/css/fontello-codes.css"));
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_icons', $list);
		}
		return $prepend_inherit ? array_merge(array('inherit'), $list) : $list;
	}
}

// Return socials list
if ( !function_exists( 'poolservices_get_list_socials' ) ) {
	function poolservices_get_list_socials($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_socials'))=='') {
			$list = poolservices_get_list_images("images/socials", "png");
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_socials', $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}

// Return list with 'Yes' and 'No' items
if ( !function_exists( 'poolservices_get_list_yesno' ) ) {
	function poolservices_get_list_yesno($prepend_inherit=false) {
		$list = array(
			'yes' => esc_html__("Yes", 'poolservices'),
			'no'  => esc_html__("No", 'poolservices')
		);
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}

// Return list with 'On' and 'Of' items
if ( !function_exists( 'poolservices_get_list_onoff' ) ) {
	function poolservices_get_list_onoff($prepend_inherit=false) {
		$list = array(
			"on" => esc_html__("On", 'poolservices'),
			"off" => esc_html__("Off", 'poolservices')
		);
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}

// Return list with 'Show' and 'Hide' items
if ( !function_exists( 'poolservices_get_list_showhide' ) ) {
	function poolservices_get_list_showhide($prepend_inherit=false) {
		$list = array(
			"show" => esc_html__("Show", 'poolservices'),
			"hide" => esc_html__("Hide", 'poolservices')
		);
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}

// Return list with 'Ascending' and 'Descending' items
if ( !function_exists( 'poolservices_get_list_orderings' ) ) {
	function poolservices_get_list_orderings($prepend_inherit=false) {
		$list = array(
			"asc" => esc_html__("Ascending", 'poolservices'),
			"desc" => esc_html__("Descending", 'poolservices')
		);
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}

// Return list with 'Horizontal' and 'Vertical' items
if ( !function_exists( 'poolservices_get_list_directions' ) ) {
	function poolservices_get_list_directions($prepend_inherit=false) {
		$list = array(
			"horizontal" => esc_html__("Horizontal", 'poolservices'),
			"vertical" => esc_html__("Vertical", 'poolservices')
		);
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}

// Return list with item's shapes
if ( !function_exists( 'poolservices_get_list_shapes' ) ) {
	function poolservices_get_list_shapes($prepend_inherit=false) {
		$list = array(
			"round"  => esc_html__("Round", 'poolservices'),
			"square" => esc_html__("Square", 'poolservices')
		);
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}

// Return list with item's sizes
if ( !function_exists( 'poolservices_get_list_sizes' ) ) {
	function poolservices_get_list_sizes($prepend_inherit=false) {
		$list = array(
			"tiny"   => esc_html__("Tiny", 'poolservices'),
			"small"  => esc_html__("Small", 'poolservices'),
			"medium" => esc_html__("Medium", 'poolservices'),
			"large"  => esc_html__("Large", 'poolservices')
		);
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}

// Return list with slider (scroll) controls positions
if ( !function_exists( 'poolservices_get_list_controls' ) ) {
	function poolservices_get_list_controls($prepend_inherit=false) {
		$list = array(
			"hide" => esc_html__("Hide", 'poolservices'),
			"side" => esc_html__("Side", 'poolservices'),
			"bottom" => esc_html__("Bottom", 'poolservices')
		);
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}

// Return list with float items
if ( !function_exists( 'poolservices_get_list_floats' ) ) {
	function poolservices_get_list_floats($prepend_inherit=false) {
		$list = array(
			"none" => esc_html__("None", 'poolservices'),
			"left" => esc_html__("Float Left", 'poolservices'),
			"right" => esc_html__("Float Right", 'poolservices')
		);
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}

// Return list with alignment items
if ( !function_exists( 'poolservices_get_list_alignments' ) ) {
	function poolservices_get_list_alignments($justify=false, $prepend_inherit=false) {
		$list = array(
			"none" => esc_html__("None", 'poolservices'),
			"left" => esc_html__("Left", 'poolservices'),
			"center" => esc_html__("Center", 'poolservices'),
			"right" => esc_html__("Right", 'poolservices')
		);
		if ($justify) $list["justify"] = esc_html__("Justify", 'poolservices');
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}

// Return list with horizontal positions
if ( !function_exists( 'poolservices_get_list_hpos' ) ) {
	function poolservices_get_list_hpos($prepend_inherit=false, $center=false) {
		$list = array();
		$list['left'] = esc_html__("Left", 'poolservices');
		if ($center) $list['center'] = esc_html__("Center", 'poolservices');
		$list['right'] = esc_html__("Right", 'poolservices');
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}

// Return list with vertical positions
if ( !function_exists( 'poolservices_get_list_vpos' ) ) {
	function poolservices_get_list_vpos($prepend_inherit=false, $center=false) {
		$list = array();
		$list['top'] = esc_html__("Top", 'poolservices');
		if ($center) $list['center'] = esc_html__("Center", 'poolservices');
		$list['bottom'] = esc_html__("Bottom", 'poolservices');
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}

// Return sorting list items
if ( !function_exists( 'poolservices_get_list_sortings' ) ) {
	function poolservices_get_list_sortings($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_sortings'))=='') {
			$list = array(
				"date" => esc_html__("Date", 'poolservices'),
				"title" => esc_html__("Alphabetically", 'poolservices'),
				"views" => esc_html__("Popular (views count)", 'poolservices'),
				"comments" => esc_html__("Most commented (comments count)", 'poolservices'),
				"author_rating" => esc_html__("Author rating", 'poolservices'),
				"users_rating" => esc_html__("Visitors (users) rating", 'poolservices'),
				"random" => esc_html__("Random", 'poolservices')
			);
			$list = apply_filters('poolservices_filter_list_sortings', $list);
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_sortings', $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}

// Return list with columns widths
if ( !function_exists( 'poolservices_get_list_columns' ) ) {
	function poolservices_get_list_columns($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_columns'))=='') {
			$list = array(
				"none" => esc_html__("None", 'poolservices'),
				"1_1" => esc_html__("100%", 'poolservices'),
				"1_2" => esc_html__("1/2", 'poolservices'),
				"1_3" => esc_html__("1/3", 'poolservices'),
				"2_3" => esc_html__("2/3", 'poolservices'),
				"1_4" => esc_html__("1/4", 'poolservices'),
				"3_4" => esc_html__("3/4", 'poolservices'),
				"1_5" => esc_html__("1/5", 'poolservices'),
				"2_5" => esc_html__("2/5", 'poolservices'),
				"3_5" => esc_html__("3/5", 'poolservices'),
				"4_5" => esc_html__("4/5", 'poolservices'),
				"1_6" => esc_html__("1/6", 'poolservices'),
				"5_6" => esc_html__("5/6", 'poolservices'),
				"1_7" => esc_html__("1/7", 'poolservices'),
				"2_7" => esc_html__("2/7", 'poolservices'),
				"3_7" => esc_html__("3/7", 'poolservices'),
				"4_7" => esc_html__("4/7", 'poolservices'),
				"5_7" => esc_html__("5/7", 'poolservices'),
				"6_7" => esc_html__("6/7", 'poolservices'),
				"1_8" => esc_html__("1/8", 'poolservices'),
				"3_8" => esc_html__("3/8", 'poolservices'),
				"5_8" => esc_html__("5/8", 'poolservices'),
				"7_8" => esc_html__("7/8", 'poolservices'),
				"1_9" => esc_html__("1/9", 'poolservices'),
				"2_9" => esc_html__("2/9", 'poolservices'),
				"4_9" => esc_html__("4/9", 'poolservices'),
				"5_9" => esc_html__("5/9", 'poolservices'),
				"7_9" => esc_html__("7/9", 'poolservices'),
				"8_9" => esc_html__("8/9", 'poolservices'),
				"1_10"=> esc_html__("1/10", 'poolservices'),
				"3_10"=> esc_html__("3/10", 'poolservices'),
				"7_10"=> esc_html__("7/10", 'poolservices'),
				"9_10"=> esc_html__("9/10", 'poolservices'),
				"1_11"=> esc_html__("1/11", 'poolservices'),
				"2_11"=> esc_html__("2/11", 'poolservices'),
				"3_11"=> esc_html__("3/11", 'poolservices'),
				"4_11"=> esc_html__("4/11", 'poolservices'),
				"5_11"=> esc_html__("5/11", 'poolservices'),
				"6_11"=> esc_html__("6/11", 'poolservices'),
				"7_11"=> esc_html__("7/11", 'poolservices'),
				"8_11"=> esc_html__("8/11", 'poolservices'),
				"9_11"=> esc_html__("9/11", 'poolservices'),
				"10_11"=> esc_html__("10/11", 'poolservices'),
				"1_12"=> esc_html__("1/12", 'poolservices'),
				"5_12"=> esc_html__("5/12", 'poolservices'),
				"7_12"=> esc_html__("7/12", 'poolservices'),
				"10_12"=> esc_html__("10/12", 'poolservices'),
				"11_12"=> esc_html__("11/12", 'poolservices')
			);
			$list = apply_filters('poolservices_filter_list_columns', $list);
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_columns', $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}

// Return list of locations for the dedicated content
if ( !function_exists( 'poolservices_get_list_dedicated_locations' ) ) {
	function poolservices_get_list_dedicated_locations($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_dedicated_locations'))=='') {
			$list = array(
				"default" => esc_html__('As in the post defined', 'poolservices'),
				"center"  => esc_html__('Above the text of the post', 'poolservices'),
				"left"    => esc_html__('To the left the text of the post', 'poolservices'),
				"right"   => esc_html__('To the right the text of the post', 'poolservices'),
				"alter"   => esc_html__('Alternates for each post', 'poolservices')
			);
			$list = apply_filters('poolservices_filter_list_dedicated_locations', $list);
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_dedicated_locations', $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}

// Return post-format name
if ( !function_exists( 'poolservices_get_post_format_name' ) ) {
	function poolservices_get_post_format_name($format, $single=true) {
		$name = '';
		if ($format=='gallery')		$name = $single ? esc_html__('gallery', 'poolservices') : esc_html__('galleries', 'poolservices');
		else if ($format=='video')	$name = $single ? esc_html__('video', 'poolservices') : esc_html__('videos', 'poolservices');
		else if ($format=='audio')	$name = $single ? esc_html__('audio', 'poolservices') : esc_html__('audios', 'poolservices');
		else if ($format=='image')	$name = $single ? esc_html__('image', 'poolservices') : esc_html__('images', 'poolservices');
		else if ($format=='quote')	$name = $single ? esc_html__('quote', 'poolservices') : esc_html__('quotes', 'poolservices');
		else if ($format=='link')	$name = $single ? esc_html__('link', 'poolservices') : esc_html__('links', 'poolservices');
		else if ($format=='status')	$name = $single ? esc_html__('status', 'poolservices') : esc_html__('statuses', 'poolservices');
		else if ($format=='aside')	$name = $single ? esc_html__('aside', 'poolservices') : esc_html__('asides', 'poolservices');
		else if ($format=='chat')	$name = $single ? esc_html__('chat', 'poolservices') : esc_html__('chats', 'poolservices');
		else						$name = $single ? esc_html__('standard', 'poolservices') : esc_html__('standards', 'poolservices');
		return apply_filters('poolservices_filter_list_post_format_name', $name, $format);
	}
}

// Return post-format icon name (from Fontello library)
if ( !function_exists( 'poolservices_get_post_format_icon' ) ) {
	function poolservices_get_post_format_icon($format) {
		$icon = 'icon-';
		if ($format=='gallery')		$icon .= 'pictures';
		else if ($format=='video')	$icon .= 'video';
		else if ($format=='audio')	$icon .= 'note';
		else if ($format=='image')	$icon .= 'picture';
		else if ($format=='quote')	$icon .= 'quote';
		else if ($format=='link')	$icon .= 'link';
		else if ($format=='status')	$icon .= 'comment';
		else if ($format=='aside')	$icon .= 'doc-text';
		else if ($format=='chat')	$icon .= 'chat';
		else						$icon .= 'book-open';
		return apply_filters('poolservices_filter_list_post_format_icon', $icon, $format);
	}
}

// Return fonts styles list, prepended inherit
if ( !function_exists( 'poolservices_get_list_fonts_styles' ) ) {
	function poolservices_get_list_fonts_styles($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_fonts_styles'))=='') {
			$list = array(
				'i' => esc_html__('I','poolservices'),
				'u' => esc_html__('U', 'poolservices')
			);
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_fonts_styles', $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}

// Return Google fonts list
if ( !function_exists( 'poolservices_get_list_fonts' ) ) {
	function poolservices_get_list_fonts($prepend_inherit=false) {
		if (($list = poolservices_storage_get('list_fonts'))=='') {
			$list = array();
			$list = poolservices_array_merge($list, poolservices_get_list_font_faces());
			$list = poolservices_array_merge($list, array(
				'Advent Pro' => array('family'=>'sans-serif'),
				'Alegreya Sans' => array('family'=>'sans-serif'),
				'Arimo' => array('family'=>'sans-serif'),
				'Asap' => array('family'=>'sans-serif'),
				'Averia Sans Libre' => array('family'=>'cursive'),
				'Averia Serif Libre' => array('family'=>'cursive'),
				'Bree Serif' => array('family'=>'serif',),
				'Cabin' => array('family'=>'sans-serif'),
				'Cabin Condensed' => array('family'=>'sans-serif'),
				'Caudex' => array('family'=>'serif'),
				'Comfortaa' => array('family'=>'cursive'),
				'Cousine' => array('family'=>'sans-serif'),
				'Crimson Text' => array('family'=>'serif'),
				'Cuprum' => array('family'=>'sans-serif'),
				'Dosis' => array('family'=>'sans-serif'),
				'Economica' => array('family'=>'sans-serif'),
				'Exo' => array('family'=>'sans-serif'),
				'Expletus Sans' => array('family'=>'cursive'),
				'Karla' => array('family'=>'sans-serif'),
				'Lato' => array('family'=>'sans-serif'),
				'Lekton' => array('family'=>'sans-serif'),
				'Lobster Two' => array('family'=>'cursive'),
				'Maven Pro' => array('family'=>'sans-serif'),
				'Merriweather' => array('family'=>'serif'),
				'Montserrat' => array('family'=>'sans-serif'),
				'Neuton' => array('family'=>'serif'),
				'Noticia Text' => array('family'=>'serif'),
				'Old Standard TT' => array('family'=>'serif'),
				'Open Sans' => array('family'=>'sans-serif'),
				'Orbitron' => array('family'=>'sans-serif'),
				'Oswald' => array('family'=>'sans-serif'),
				'Overlock' => array('family'=>'cursive'),
				'Oxygen' => array('family'=>'sans-serif'),
				'Philosopher' => array('family'=>'serif'),
				'PT Serif' => array('family'=>'serif'),
				'Puritan' => array('family'=>'sans-serif'),
				'Raleway' => array('family'=>'sans-serif'),
				'Roboto' => array('family'=>'sans-serif'),
				'Roboto Slab' => array('family'=>'sans-serif'),
				'Roboto Condensed' => array('family'=>'sans-serif'),
				'Rosario' => array('family'=>'sans-serif'),
				'Share' => array('family'=>'cursive'),
				'Signika' => array('family'=>'sans-serif'),
				'Signika Negative' => array('family'=>'sans-serif'),
				'Source Sans Pro' => array('family'=>'sans-serif'),
				'Tinos' => array('family'=>'serif'),
				'Ubuntu' => array('family'=>'sans-serif'),
				'Vollkorn' => array('family'=>'serif')
				)
			);
			$list = apply_filters('poolservices_filter_list_fonts', $list);
			if (poolservices_get_theme_setting('use_list_cache')) poolservices_storage_set('list_fonts', $list);
		}
		return $prepend_inherit ? poolservices_array_merge(array('inherit' => esc_html__("Inherit", 'poolservices')), $list) : $list;
	}
}

// Return Custom font-face list
if ( !function_exists( 'poolservices_get_list_font_faces' ) ) {
	function poolservices_get_list_font_faces($prepend_inherit=false) {
		static $list = false;
		if (is_array($list)) return $list;
		$fonts = poolservices_storage_get('required_custom_fonts');
		$list = array();
		if (is_array($fonts)) {
			foreach ($fonts as $font) {
				if (($url = poolservices_get_file_url('css/font-face/'.trim($font).'/stylesheet.css'))!='') {
					$list[sprintf(esc_html__('%s (uploaded font)', 'poolservices'), $font)] = array('css' => $url);
				}
			}
		}
		return $list;
	}
}
?>