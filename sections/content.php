<?php
$class = 'small-24 medium-15 large-17 column';
if (!is_singular()) {
?>
<div class="<?php echo $class; ?>">
<?php
$class = '';
}
while (have_posts()) {
    the_post();
?>
    <article <?php post_class($class); ?>>
        <h1><?php the_title(); ?></h1>
        <?php the_content(); ?>
    </article>
<?php
}
if (!is_singular()) {
?>
</div>
<?php
}
?>
<aside class="small-24 medium-9 large-7 column">
    <?php get_sidebar(); ?>
</aside>
