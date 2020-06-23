<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'poolservices_template_form_custom_theme_setup' ) ) {
	add_action( 'poolservices_action_before_init_theme', 'poolservices_template_form_custom_theme_setup', 1 );
	function poolservices_template_form_custom_theme_setup() {
		poolservices_add_template(array(
			'layout' => 'form_custom',
			'mode'   => 'forms',
			'title'  => esc_html__('Custom Form', 'poolservices')
			));
	}
}

// Template output
if ( !function_exists( 'poolservices_template_form_custom_output' ) ) {
	function poolservices_template_form_custom_output($post_options, $post_data) {
		$form_style = poolservices_get_theme_option('input_hover');
		?>
		<form <?php echo !empty($post_options['id']) ? ' id="'.esc_attr($post_options['id']).'_form"' : ''; ?>
			class="sc_input_hover_<?php echo esc_attr($form_style); ?>"
			data-formtype="<?php echo esc_attr($post_options['layout']); ?>" 
			method="post" 
			action="<?php echo esc_url($post_options['action'] ? $post_options['action'] : admin_url('admin-ajax.php')); ?>">
			<?php
			poolservices_sc_form_show_fields($post_options['fields']);
			poolservices_show_layout($post_options['content']);
			?>
			<div class="result sc_infobox"></div>
		</form>
		<?php
	}
}
?>