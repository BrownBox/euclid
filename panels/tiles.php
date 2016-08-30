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
$small_count = get_post_meta($wrapper->ID, 'num_per_row_small', true);
$medium_count = get_post_meta($wrapper->ID, 'num_per_row_medium', true);
$large_count = get_post_meta($wrapper->ID, 'num_per_row_large', true);
?>
<div id="row-panel-<?php echo $wrapper->ID; ?>" class="panel-tiles <?php echo $panel_name.' panel-'.$wrapper->ID; ?> clearfix small-up-<?php echo $small_count; ?> medium-up-<?php echo $medium_count; ?> large-up-<?php echo $large_count; ?>" style="<?php echo $bg_style; ?>">
<?php
foreach ($children as $panel) {
?>
    <div class="column tile">
<?php
    include(get_stylesheet_directory().'/panels/banner.php');
?>
    </div>
<?php
}
?>
</div>
