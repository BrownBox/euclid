<?php
$panel_name = !empty(bb_get_post_meta($panel->ID, 'panel_name')) ? bb_get_post_meta($panel->ID, 'panel_name') : '';
$flavour = bb_get_post_meta($panel->ID, 'flavour');
$bg_opacity = bb_get_post_meta($panel->ID, 'bg_opacity');
$outer_wrapper_style = '';
$bg_style = '';
$bg_colour = bb_get_post_meta($panel->ID, 'bg_colour');
if (is_numeric($bg_colour)) {
    $bg_colour = bb_get_theme_mod('colour'.$bg_colour);
}
if (!empty($bg_colour)) {
    $outer_wrapper_style .= 'background-color: '.$bg_colour.';';
}
if (has_post_thumbnail($panel->ID)) {
    if (is_numeric($bg_opacity)) {
        $bg_style .= 'opacity: '.$bg_opacity.';';
    }
    $image = get_value_from_hierarchy('featured_image', $panel->ID);
    $bg_style .= 'background-image: url('.$image.');';
    $bg_pos_x = bb_get_post_meta($panel->ID, 'bg_pos_x');
    $bg_pos_y = bb_get_post_meta($panel->ID, 'bg_pos_y');
    if (!empty($bg_pos_x)) {
        $bg_style .= 'background-position-x: '.$bg_pos_x.';';
    }
    if (!empty($bg_pos_y)) {
        $bg_style .= 'background-position-y: '.$bg_pos_y.';';
    }
}
switch ($flavour) {
    case 'full_bleed':
        $bg_wrapper_class = $inner_wrapper_class = 'row-full';
        break;
    case 'fully_contained':
        $bg_wrapper_class = $inner_wrapper_class = 'row';
        break;
    case 'partial_bleed':
    default:
        $inner_wrapper_class = 'row';
        $bg_wrapper_class = 'row-full';
        break;
}
?>
<div id="row-panel-<?php echo $panel->ID; ?>" class="row-wrapper panel-wrapper <?php echo bb_get_post_meta($panel->ID, 'recipe').' '.$panel_name.' panel-'.$panel->ID; ?> clearfix" style="<?php echo $outer_wrapper_style; ?>">
	<div id="row-inner-panel-<?php echo $panel->ID; ?>" class="row-inner-wrapper panel-inner-wrapper <?php echo $inner_wrapper_class; ?> clearfix">
<?php bb_panel_cook_recipe($panel); ?>
	</div>
	<div id="row-bg-panel" class="row-bg-wrapper panel-bg-wrapper <?php echo $bg_wrapper_class; ?>" style="<?php echo $bg_style; ?>"></div>
<?php
if (current_user_can('edit_pages') && $panel->post_parent == 0) {
?>
    <div class="edit-panel">
        <a title="Edit Panel" target="_edit_panel" href="/wp-admin/post.php?post=<?php echo $panel->ID; ?>&action=edit"><i class="fa fa-pencil" aria-hidden="true"></i> <?php echo $panel->menu_order; ?></a>
    </div>
<?php
}
?>
</div>
