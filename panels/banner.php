<?php
$panel_name = (!empty(get_post_meta($panel->ID, 'panel_name', true))) ? get_post_meta($panel->ID, 'panel_name', true) : '';
$flavour = get_post_meta($panel->ID, 'flavour', true);
$inner_wrapper_style = $outer_wrapper_style = '';
$bg_style = '';
$bg_colour = get_post_meta($panel->ID, 'bg_colour', true);
if (is_numeric($bg_colour)) {
    $bg_colour = bb_get_theme_mod('colour'.$bg_colour);
}
if (!empty($bg_colour)) {
    $outer_wrapper_style .= 'background-color: '.$bg_colour.';';
}
if (has_post_thumbnail($panel->ID)) {
    $image = get_value_from_hierarchy('featured_image', $panel->ID);
    $bg_style .= 'background-image: url('.$image[0].');';
    $bg_pos_x = get_post_meta($panel->ID, 'bg_pos_x', true);
    $bg_pos_y = get_post_meta($panel->ID, 'bg_pos_y', true);
    if (!empty($bg_pos_x)) {
        $bg_style .= 'background-position-x: '.$bg_pos_x.';';
    }
    if (!empty($bg_pos_y)) {
        $bg_style .= 'background-position-y: '.$bg_pos_y.';';
    }
}
switch ($flavour) {
    case 'full_bleed':
        $inner_wrapper_class = 'row-full';
        $outer_wrapper_style .= $bg_style;
        break;
    case 'fully_contained':
        $inner_wrapper_class = 'row';
        $inner_wrapper_style .= $bg_style;
        break;
    case 'partial_bleed':
    default:
        $inner_wrapper_class = 'row';
        $outer_wrapper_style .= $bg_style;
        break;
}
?>
<div id="row-panel-<?php echo $panel->ID; ?>" class="row-wrapper panel-wrapper <?php echo get_post_meta($panel->ID, 'recipe', true).' '.$panel_name.' panel-'.$panel->ID; ?> clearfix" style="<?php echo $outer_wrapper_style; ?>">
    <div id="row-inner-panels-<?php echo $panel->ID; ?>" class="row-inner-wrapper panel-inner-wrapper <?php echo $inner_wrapper_class; ?> clearfix" style="<?php echo $inner_wrapper_style; ?>">
<?php bb_panel_cook_recipe($panel); ?>
	</div>
</div>
