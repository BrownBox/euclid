<div class="column">
    <div class="wrapper text-center">
<?php
$destination = get_post_meta($panel->ID, 'destination', true);
if (!empty($destination)) {
    echo '        <a class="link" href="'.$destination.'">'."\n";
}
bb_panel_title($panel);
bb_panel_content($panel);
if (!empty(get_post_meta($panel->ID, 'action_text', true))) {
?>
    <p class="button"><?php echo get_post_meta($panel->ID, 'action_text', true); ?></p>
<?php
}
if (!empty($destination)) {
    echo '        </a>'."\n";
}
?>
    </div>
</div>
