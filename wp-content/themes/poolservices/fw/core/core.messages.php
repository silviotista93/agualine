<?php
/**
 * PoolServices Framework: messages subsystem
 *
 * @package	poolservices
 * @since	poolservices 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('poolservices_messages_theme_setup')) {
	add_action( 'poolservices_action_before_init_theme', 'poolservices_messages_theme_setup' );
	function poolservices_messages_theme_setup() {
		// Core messages strings
		add_filter('poolservices_filter_localize_script', 'poolservices_messages_localize_script');
	}
}


/* Session messages
------------------------------------------------------------------------------------- */

if (!function_exists('poolservices_get_error_msg')) {
	function poolservices_get_error_msg() {
		return poolservices_storage_get('error_msg');
	}
}

if (!function_exists('poolservices_set_error_msg')) {
	function poolservices_set_error_msg($msg) {
		$msg2 = poolservices_get_error_msg();
		poolservices_storage_set('error_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}

if (!function_exists('poolservices_get_success_msg')) {
	function poolservices_get_success_msg() {
		return poolservices_storage_get('success_msg');
	}
}

if (!function_exists('poolservices_set_success_msg')) {
	function poolservices_set_success_msg($msg) {
		$msg2 = poolservices_get_success_msg();
		poolservices_storage_set('success_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}

if (!function_exists('poolservices_get_notice_msg')) {
	function poolservices_get_notice_msg() {
		return poolservices_storage_get('notice_msg');
	}
}

if (!function_exists('poolservices_set_notice_msg')) {
	function poolservices_set_notice_msg($msg) {
		$msg2 = poolservices_get_notice_msg();
		poolservices_storage_set('notice_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}


/* System messages (save when page reload)
------------------------------------------------------------------------------------- */
if (!function_exists('poolservices_set_system_message')) {
	function poolservices_set_system_message($msg, $status='info', $hdr='') {
		update_option(poolservices_storage_get('options_prefix') . '_message', array('message' => $msg, 'status' => $status, 'header' => $hdr));
	}
}

if (!function_exists('poolservices_get_system_message')) {
	function poolservices_get_system_message($del=false) {
		$msg = get_option(poolservices_storage_get('options_prefix') . '_message', false);
		if (!$msg)
			$msg = array('message' => '', 'status' => '', 'header' => '');
		else if ($del)
			poolservices_del_system_message();
		return $msg;
	}
}

if (!function_exists('poolservices_del_system_message')) {
	function poolservices_del_system_message() {
		delete_option(poolservices_storage_get('options_prefix') . '_message');
	}
}


/* Messages strings
------------------------------------------------------------------------------------- */

if (!function_exists('poolservices_messages_localize_script')) {
	//Handler of add_filter('poolservices_filter_localize_script', 'poolservices_messages_localize_script');
	function poolservices_messages_localize_script($vars) {
		$vars['strings'] = array(
			'ajax_error'		=> esc_html__('Invalid server answer', 'poolservices'),
			'bookmark_add'		=> esc_html__('Add the bookmark', 'poolservices'),
            'bookmark_added'	=> esc_html__('Current page has been successfully added to the bookmarks. You can see it in the right panel on the tab \'Bookmarks\'', 'poolservices'),
            'bookmark_del'		=> esc_html__('Delete this bookmark', 'poolservices'),
            'bookmark_title'	=> esc_html__('Enter bookmark title', 'poolservices'),
            'bookmark_exists'	=> esc_html__('Current page already exists in the bookmarks list', 'poolservices'),
			'search_error'		=> esc_html__('Error occurs in AJAX search! Please, type your query and press search icon for the traditional search way.', 'poolservices'),
			'email_confirm'		=> esc_html__('On the e-mail address "%s" we sent a confirmation email. Please, open it and click on the link.', 'poolservices'),
			'reviews_vote'		=> esc_html__('Thanks for your vote! New average rating is:', 'poolservices'),
			'reviews_error'		=> esc_html__('Error saving your vote! Please, try again later.', 'poolservices'),
			'error_like'		=> esc_html__('Error saving your like! Please, try again later.', 'poolservices'),
			'error_global'		=> esc_html__('Global error text', 'poolservices'),
			'name_empty'		=> esc_html__('The name can\'t be empty', 'poolservices'),
			'name_long'			=> esc_html__('Too long name', 'poolservices'),
			'email_empty'		=> esc_html__('Too short (or empty) email address', 'poolservices'),
			'email_long'		=> esc_html__('Too long email address', 'poolservices'),
			'email_not_valid'	=> esc_html__('Invalid email address', 'poolservices'),
			'subject_empty'		=> esc_html__('The subject can\'t be empty', 'poolservices'),
			'subject_long'		=> esc_html__('Too long subject', 'poolservices'),
			'text_empty'		=> esc_html__('The message text can\'t be empty', 'poolservices'),
			'text_long'			=> esc_html__('Too long message text', 'poolservices'),
			'send_complete'		=> esc_html__("Send message complete!", 'poolservices'),
			'send_error'		=> esc_html__('Transmit failed!', 'poolservices'),
			'geocode_error'			=> esc_html__('Geocode was not successful for the following reason:', 'poolservices'),
			'googlemap_not_avail'	=> esc_html__('Google map API not available!', 'poolservices'),
			'editor_save_success'	=> esc_html__("Post content saved!", 'poolservices'),
			'editor_save_error'		=> esc_html__("Error saving post data!", 'poolservices'),
			'editor_delete_post'	=> esc_html__("You really want to delete the current post?", 'poolservices'),
			'editor_delete_post_header'	=> esc_html__("Delete post", 'poolservices'),
			'editor_delete_success'	=> esc_html__("Post deleted!", 'poolservices'),
			'editor_delete_error'	=> esc_html__("Error deleting post!", 'poolservices'),
			'editor_caption_cancel'	=> esc_html__('Cancel', 'poolservices'),
			'editor_caption_close'	=> esc_html__('Close', 'poolservices')
			);
		return $vars;
	}
}
?>