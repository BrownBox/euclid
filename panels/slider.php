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
<div id="row-panel-<?php echo $wrapper->ID; ?>" class="panel-slider <?php echo $panel_name.' panel-'.$wrapper->ID; ?> clearfix" style="<?php echo $bg_style; ?>">
<?php
foreach ($children as $panel) {
    include(get_stylesheet_directory().'/panels/banner.php');
}
?>
</div>
