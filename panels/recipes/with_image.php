<?php
$meta = get_post_meta($panel->ID);
$img_block = '';
if (!empty($meta["image"][0])) {
    $image = wp_get_attachment_image_src($meta["image"][0], 'full');
    $img_block = '<div class="image small-24 medium-6 large-6 column">'."\n";
    $img_block .= '<img src="'.$image[0].'" alt="">'."\n";
    $img_block .= '</div>'."\n";
}
if($meta["image_pos"][0] == 'left') {
	echo $img_block;
}
?>
<div class="content small-24 medium-18 large-18 column">
<?php
bb_panel_title($panel);
bb_panel_content($panel);
if (!empty(get_post_meta($panel->ID, 'destination', true))) {
?>
    <p class="action-button"><a href="<?php echo get_post_meta($panel->ID, 'destination', true); ?>" class="button cta"><?php echo get_post_meta($panel->ID, 'action_text', true); ?></a></p>
<?php } ?>
</div>
<?php
if($meta["image_pos"][0] == 'right') {
	echo $img_block;
}
