<?php
$panel_name = (!empty(get_post_meta($slider->ID, 'panel_name', true))) ? get_post_meta($slider->ID, 'panel_name', true) : '';
$flavour = get_post_meta($slider->ID, 'flavour', true);
?>
<div id="row-panel-<?php echo $panel->ID; ?>" class="panel-slider <?php echo get_post_meta($panel->ID, 'recipe', true).' '.$panel_name.' panel-'.$panel->ID; ?> clearfix">
<?php
foreach ($slides as $panel) {
    include(get_stylesheet_directory().'/panels/banner.php');
}
?>
</div>
