<?php
$panel_name = (!empty(get_post_meta($wrapper->ID, 'panel_name', true))) ? get_post_meta($wrapper->ID, 'panel_name', true) : '';
$flavour = get_post_meta($wrapper->ID, 'flavour', true);
$bg_style = '';
$bg_colour = get_post_meta($wrapper->ID, 'bg_colour', true);
if (is_numeric($bg_colour)) {
    $bg_colour = bb_get_theme_mod('colour'.$bg_colour);
}
if (!empty($bg_colour)) {
    $bg_style .= 'background-color: '.$bg_colour.';';
}
?>
<div id="row-panel-<?php echo $wrapper->ID; ?>" class="<?php echo $panel_name.' panel-'.$wrapper->ID; ?> clearfix" style="<?php echo $bg_style; ?>">
	<div class="panel-slider">
<?php
foreach ($children as $panel) {
    include(get_stylesheet_directory().'/panels/banner.php');
}
?>
	</div>
<?php
if (current_user_can('edit_pages') && $wrapper->post_parent == 0) {
?>
    <div class="edit-panel">
        <a title="Edit Panel" target="_edit_panel" href="/wp-admin/post.php?post=<?php echo $wrapper->ID; ?>&action=edit"><i class="fa fa-pencil" aria-hidden="true"></i> <?php echo $wrapper->menu_order; ?></a>
    </div>
<?php
}
?>
</div>
