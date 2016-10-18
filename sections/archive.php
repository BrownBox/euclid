<div class="small-24 column">
<?php
global $post;
$term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
$title = $term->name;
$archive_page = get_page_by_path(get_post_type($post));
if ($archive_page) {
    echo '    <h1>'.$archive_page->post_title.'</h1>'."\n";
    echo apply_filters('the_content', $archive_page->post_content);
}
while (have_posts()) {
    the_post();
?>
    <article <?php post_class(); ?>>
        <h2><?php the_title(); ?></h2>
        <?php the_content(); ?>
    </article>
<?php
}
?>
</div>
