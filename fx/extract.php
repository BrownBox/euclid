<?php
/**
 * Generate "teaser" text from longer content
* @param string $content
* @param integer $max_chars
* @param string $suffix
* @return string
*/
function bb_extract($content, $max_chars = 200, $suffix = '...') {
    if (strlen(strip_tags($content)) > $max_chars) {
        return substr(strip_tags($content), 0, strrpos(substr(strip_tags($content), 0, $max_chars), ' ')+1).$suffix."\n";
    } else {
        return $content;
    }
}

/**
 * Generate "teaser" text for post. Will use custom excerpt if defined, otherwise will look for WP "More" tag and return preceding content, else generate automatic extract via @see bb_extract.
 * @param mixed $post
 * @param number $max_chars
 * @param string $suffix
 * @return string
 */
function bb_post_extract($post = null, $max_chars = 200, $suffix = '...') {
    $post = get_post($post);
    if (!empty($post->post_excerpt)) { // Custom Excerpt
        $output = get_the_excerpt($post);
    } elseif (preg_match('/<!--more(.*?)?-->/', $post->post_content)) { // More
        setup_postdata($post);
        global $more;
        $tmp_more = $more;
        $more = false;
        $output = get_the_content('').$suffix;
        $more = $tmp_more;
    } else {
        $output = bb_extract($post->post_content, $max_chars, $suffix);
    }

    return $output;
}
