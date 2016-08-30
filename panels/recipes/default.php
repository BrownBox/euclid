<div class="column">
<?php
$destination = get_post_meta($panel->ID, 'destination', true);
if (!empty($destination)) {
    echo '        <a href="'.$destination.'">'."\n";
}
bb_panel_title($panel);
bb_panel_content($panel);
if (!empty(get_post_meta($panel->ID, 'action_text', true))) {
?>
    <p class="action-button button cta"><?php echo get_post_meta($panel->ID, 'action_text', true); ?></p>
<?php
}
if (!empty($destination)) {
    echo '        </a>'."\n";
}
?>
</div>