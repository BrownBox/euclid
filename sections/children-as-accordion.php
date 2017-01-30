<?php
/**
 * Section for displaying children as accordion
 */
global $post;
$children = bb_get_children($post);
?>
<div class="small-24 column">
    <h1><?php the_title(); ?></h1>
</div>
<?php
if (get_value_from_hierarchy('hero_style') == 'alternate') {
?>
<div class="small-24 column"><?php include(trailingslashit(dirname(__FILE__)).'partials/alternate-hero.php'); ?></div>
<?php
}
?>
<div class="small-24 column">
    <article <?php post_class() ?>>
        <?php echo apply_filters('the_content', $post->post_content); ?>
    </article>
    <div class="accordion" data-accordion>
<?php
$tmp_post = $post;
foreach ($children as $post) {
    setup_postdata($post);
    $id = $post->ID;
    $slug = get_the_slug($post->ID);
    $title = get_the_title($post);
    $content = apply_filters('the_content', $post->post_content);
?>
        <article id="<?php echo $slug; ?>" class="<?php post_class('child accordion-item', $post->ID); ?>" data-accordion-item>
            <a href="#" class="accordion-title"><h2><?php echo $title; ?></h2></a>
            <div class="accordion-content" data-tab-content>
                <?php echo $content; ?>
            </div>
        </article>
<?php
}
$post = $tmp_post;
?>
    </div>
</div>
