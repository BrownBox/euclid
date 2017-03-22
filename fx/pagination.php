<?php
// Based on https://gist.github.com/veelen/408f09528d163008a1ef

/**
 * Auto-generate WP pagination links for post archives using Zurb Foundation markup
 * @param int $p The number of page items to display before and after the current page
 * @param bool $return Whether to return or echo the page
 * @return null|string
 */
function bb_foundation_pagination($p = 2, $return = false) {
    if (is_singular()) {
        return null;
    }
    global $wp_query, $paged;
    $max_page = $wp_query->max_num_pages;
    $output = bb_generate_pagination($max_page, $p, $paged);
    if ($return) {
        return $output;
    } else {
        echo $output;
    }
}

/**
 * Generate WP pagination links using Zurb Foundation markup
 * @param int $max_page Total number of pages
 * @param int $p The number of page items to display before and after the current page
 * @param int $paged Optional. Current page. Default 1.
 * @param boolean $querystring Optional. Whether to generate links using querystring (?n=$i) instead of default /page/$i/ syntax. Default false.
 * @return string
 */
function bb_generate_pagination_links($max_page, $p = 2, $paged = null, $querystring = false) {
    if ($max_page == 1) {
        return null;
    }
    if (empty($paged)) {
        $paged = 1;
    }
    if ($paged > 1) {
        $output .= bb_p_link($paged - 1, $querystring, 'previous');
    }
    if ($paged > $p + 1) {
        $output .= bb_p_link(1, $querystring, 'first');
    }
    if ($paged > $p + 2) {
        $output .= '<li class="unavailable" aria-disabled="true"><a href="#">&hellip;</a></li>';
    }
    for ($i = $paged - $p; $i <= $paged + $p; $i++) { // Middle pages
        if ($i == 1) {
            $rel = 'rel="first"';
        } elseif ($i == $max_page) {
            $rel = 'rel="last"';
        } else {
            $rel = '';
        }
        if ($i > 0 && $i <= $max_page) {
            $i == $paged ? $output .= "<li class='current' {$rel}><a href='#'>{$i}</a></li> " : $output .= bb_p_link($i, $querystring);
        }
    }
    if ($paged < $max_page - $p - 1) {
        $output .= '<li class="unavailable" aria-disabled="true"><a href="#">&hellip;</a></li>';
    }
    if ($paged < $max_page - $p) {
        $output .= bb_p_link($max_page, $querystring, 'last');
    }
    if ($paged < $max_page) {
        $output .= bb_p_link($paged + 1, $querystring, 'next');
    }
    if (!empty($output)) {
        $output = '<ul class="pagination" role="navigation" aria-label="Pagination">'.$output.'</ul>';
    }
    return $output;
}

/**
 *
 * @param int $i
 * @param string $title
 * @return string
 */
function bb_p_link($i, $querystring = false, $title = '') {
    global $wp_query;
    $max_page = $wp_query->max_num_pages;
    if ($i == 1 || $title == 'first') {
        $rel = 'rel="first"';
    } elseif ($title == 'last' || $i == $max_page) {
        $rel = 'rel="last"';
    } else {
        $rel = '';
    }
    $linktext = $i;
    switch ($title) {
        case 'first':
            $readabletitle = _x('First', 'pagination first page', THEME_TEXTDOMAIN);
            break;
        case 'last':
            $readabletitle = _x('Last', 'pagination last page', THEME_TEXTDOMAIN);
            break;
        case 'previous':
            $readabletitle = $linktext = _x('&laquo; Previous', 'pagination previous page', THEME_TEXTDOMAIN);
            $rel = 'rel="prev"';
            break;
        case 'next':
            $readabletitle = $linktext = _x('Next &raquo;', 'pagination next page', THEME_TEXTDOMAIN);
            $rel = 'rel="next"';
            break;
        default:
            $readabletitle = sprintf(_x("Page %d", 'pagination page number', THEME_TEXTDOMAIN), $i);
    }
    if ($querystring) {
        $link = add_query_arg('n', $i);
    } else {
        $link = esc_html(get_pagenum_link($i));
    }
    return '<li><a href="'.$link.'" '.$rel.' title="'.$readabletitle.'">'.$linktext.'</a></li>';
}
