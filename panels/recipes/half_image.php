<?php
$meta = get_post_meta($panel->ID);
if (!empty($meta["image"][0])) {
    $image = wp_get_attachment_image_src($meta["image"][0], 'full');
}
?>
<div class="row image-<?php echo $meta["image_pos"][0]; ?>">
    <div class="column">
        <div class="content">
<?php
bb_panel_title($panel);
bb_panel_content($panel);
if (!empty($meta["destination"][0])) {
?>
            <p class="action-button"><a href="<?php echo $meta["destination"][0]; ?>" class="button"><?php echo $meta["action_text"][0]; ?></a></p>
<?php
}
?>
        </div>
    </div>
</div>
<div class="image" style="background-image: url(<?php echo $image[0]; ?>); background-size:cover;"></div>
