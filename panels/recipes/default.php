<div class="small-24 medium-24 large-24 column">
<?php
bb_panel_title($panel);
bb_panel_content($panel);
if (!empty(get_post_meta($panel->ID, 'destination', true))) {
?>
    <p class="action-button"><a href="<?php echo get_post_meta($panel->ID, 'destination', true); ?>" class="button cta"><?php echo get_post_meta($panel->ID, 'action_text', true); ?></a></p>
<?php
}
?>
</div>
