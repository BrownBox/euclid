<?php
if (has_post_thumbnail($panel->ID)) {
    $image = get_value_from_hierarchy('featured_image', $panel->ID);
    $style .= 'background-image: url('.$image[0].');';
?>
<style>
.swimlane {background-size: 10%; background-repeat: repeat; padding-top:2rem; padding-bottom:1.5rem; background-color: #00b1f0; <?php echo $style; ?>}
.swimlane:before {content: ' '; display: block; position: relative; top: -2rem; left: -0.9375rem; width: 102%; border-style: solid; border-width: 50px 0px 0px; -moz-border-image: url(/wp-content/themes/splash/images/lane-rope.png) 445 0 0 repeat stretch; -webkit-border-image: url(/wp-content/themes/splash/images/lane-rope.png) 445 0 0 repeat stretch; -o-border-image: url(/wp-content/themes/splash/images/lane-rope.png) 445 0 0 repeat stretch; border-image: url(/wp-content/themes/splash/images/lane-rope.png) 445 0 0 repeat stretch;}
.swimlane h1 {text-align: left;}
.swimlane p {color:#fff; }
.swimlane .panel-box p.action-button{ text-align:left;}
.swimlane a.button {background: rgba(0, 0, 0, 0) none repeat scroll 0 0; border: 1px solid #fff; border-radius: 10px; font-family: oxygen; font-size: 1.5rem; margin-top: 1rem; padding: 0.9rem 1.5rem;}
.swimlane a.button:hover {background: rgba(255, 255, 255, 0.5) none repeat scroll 0 0; border: 1px solid rgba(255, 255, 255, 0.125);}
</style>
<?php
}
$cell1 .= '<div class="content small-24 medium-14 large-16 column">'."\n";
$cell1 .= '<h1>'.$panel->post_title.'</h1>'."\n";
$cell1 .= apply_filters('the_content', $panel->post_content);

if (!empty(get_post_meta($panel->ID, 'destination', true))) {
	$cell1 .= '<p class="action-button"><a href="'.get_post_meta($panel->ID, 'destination', true).'" class="button flat small">'.get_post_meta($panel->ID, 'action_text', true).'</a></p>'."\n";
}
$cell1 .= '</div>'."\n";

$cell2 .= '<div class="image small-24 medium-8 large-6 column">'."\n";
$meta = get_post_meta($panel->ID);
$image_two = wp_get_attachment_image_src($meta["image"][0], 'full');
$cell2 .= '<img src="'.$image_two[0].'" alt="" >'."\n";
$cell2 .= '</div>'."\n";

if($meta["image_position"][0] == 'right') {
	echo $cell1; echo $cell2;
} else {
	echo $cell2; echo $cell1;
}
