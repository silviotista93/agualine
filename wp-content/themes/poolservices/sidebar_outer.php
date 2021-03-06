<?php
/**
 * The Sidebar Outer containing the outer (left or right) widget areas.
 */

$sidebar_show   = poolservices_get_custom_option('show_sidebar_outer');
$sidebar_scheme = poolservices_get_custom_option('sidebar_outer_scheme');
$sidebar_name   = poolservices_get_custom_option('sidebar_outer');

if (!poolservices_param_is_off($sidebar_show) && is_active_sidebar($sidebar_name)) {
	?>
	<div class="sidebar_outer widget_area scheme_<?php echo esc_attr($sidebar_scheme); ?>" role="complementary">
		<div class="sidebar_outer_inner widget_area_inner">
			<div class="sidebar_outer_logo_wrap">
			<?php
			if (poolservices_get_custom_option('sidebar_outer_show_logo')=='yes') {
				?>
				<div class="sidebar_outer_logo">
					<?php poolservices_show_logo(false, false, false, true); ?>
				</div>
				<?php
			}
			if (poolservices_get_custom_option('sidebar_outer_show_socials')=='yes') {
				?>
				<div class="sidebar_outer_socials">
					<?php poolservices_show_layout(poolservices_sc_socials(array('size'=>"tiny", 'shape'=>"round"))); ?>
				</div>
				<?php
			}
			?>
			</div>
			<?php
			if (poolservices_get_custom_option('sidebar_outer_show_menu')=='yes') {
				if (($menu_side = poolservices_get_nav_menu('menu_side')) != '') {
					?>
					<div class="sidebar_outer_menu">
						<?php poolservices_show_layout($menu_side); ?>
						<span class="sidebar_outer_menu_buttons">
						<a href="#" class="sidebar_outer_menu_responsive_button icon-down"><?php esc_html_e('Select menu item', 'poolservices'); ?></a>
						<?php if (poolservices_get_custom_option('sidebar_outer_show_widgets')=='yes') { ?>
						<a href="#" class="sidebar_outer_widgets_button icon-book-open"></a>
						<?php } ?>
						</span>
					</div>
					<?php
				}
			}
			if (poolservices_get_custom_option('sidebar_outer_show_widgets')=='yes') {
				?>
				<div class="sidebar_outer_widgets">
					<?php
					ob_start();
					do_action( 'before_sidebar' );
					poolservices_storage_set('current_sidebar', 'outer');
					if ( !dynamic_sidebar($sidebar_name) ) {
						// Put here html if user no set widgets in sidebar
					}
					do_action( 'after_sidebar' );
					$out = ob_get_contents();
					ob_end_clean();
					poolservices_show_layout(preg_replace("/<\/aside>[\r\n\s]*<aside/", "</aside><aside", $out));
					?>
				</div> <!-- /.sidebar_outer_widgets -->
				<?php
			}
			?>
		</div> <!-- /.sidebar_outer_inner -->
	</div> <!-- /.sidebar_outer -->
	<?php
}
?>