<?php
/**
 * Generate "teaser" text from longer content
 * @param string $content
 * @param integer $max_chars
 * @param string $suffix
 * @return string
 */
function bb_extract($content, $max_chars = 200, $suffix = '...') {
    if (strlen(strip_tags($content)) > $max_chars)
        return apply_filters('the_content', substr(strip_tags($content), 0, strrpos(substr(strip_tags($content), 0, $max_chars), ' ')+1).$suffix)."\n";
    else
        return $content;
}
