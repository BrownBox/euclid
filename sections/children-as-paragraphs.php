<?php
/**
 * Section for displaying children as paragraphs
 */

global $post;

$children = bb_get_children($post);
?>
<aside class="small-24 medium-5 large-5 column">
    <?php get_sidebar('children-as-paragraphs'); ?>
</aside>
<div class="small-24 medium-19 large-19 column">
<article <?php post_class() ?>>
<?php echo apply_filters('the_content', $post->post_content); ?>
</article>
<?php
foreach ($children as $child) {
    $id = $child->ID;
    $slug = get_the_slug($child->ID);
    $title = $child->post_title;
    if (!empty($child->post_excerpt)) {
        $content = apply_filters('the_content', $child->post_excerpt);
    } else {
        $content = apply_filters('the_content', $child->post_content);
    }
    $read_more_label = bb_get_theme_mod(ns_ . 'read_more_label', __( 'Read more on this topic'), ns_);
    $read_more_link = !empty($child->post_excerpt) || bb_has_children($child->ID) ? '<p><a href="' . $slug . '">' . $read_more_label . '</a></p>' : '';
?>
    <article id="<?php echo $slug; ?>" class="<?php post_class('child', $child->ID); ?>">
        <h2><?php echo $title; ?></h2>
        <?php echo $content; ?>
        <?php echo $read_more_link; ?>
    </article>
<?php
}
?>
</div>
