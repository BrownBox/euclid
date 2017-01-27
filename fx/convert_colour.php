<?php
/**
 * Converts colours from RGB to Hex and vice-versa
 * @param string|array $colour Colour as hex string or RGB array
 * @param string $as Optional. Defines the type of value returned. Accepted values are 'string' or 'array'.
 * @return string|array
 */
function bb_convert_colour($colour, $as = 'string') {
    // do we have what we need for this function?
    if (strpos($colour, ','))
        $colour = explode(',', $colour);
    if (strpos($colour, '#') === false || (is_array($colour) && count($colour) != 3))
        return;
    if ($as != 'string' && $as != 'array')
        return;

    if (strpos($colour, '#') !== false) {
        $hex = str_replace("#", "", $colour);

        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        $rgb = array(
                $r,
                $g,
                $b
        );

        if ($as == 'string')
            return implode(",", $rgb); // returns the rgb values separated by commas
        if ($as == 'array')
            return $rgb; // returns an array with the rgb values
    } // end $from = hex

    if (is_array($colour) && count($colour) == 3) {

        $rgb = explode(',', $colour);

        $hex = "#";
        $hex .= str_pad(dechex($rgb[0]), 2, "0", STR_PAD_LEFT);
        $hex .= str_pad(dechex($rgb[1]), 2, "0", STR_PAD_LEFT);
        $hex .= str_pad(dechex($rgb[2]), 2, "0", STR_PAD_LEFT);

        return $hex; // returns the hex value including the number sign (#)
    }
}

/**
 * Convert a hex colour to a semi-transparent RGBA
 * @param string $hex
 * @param float $opacity
 * @return string
 */
function bb_colour_opacity($hex, $opacity){
    $rgba = 'rgba('.bb_convert_colour($hex).', '.$opacity.')';
    return $rgba;
}

/**
 * Darken a colour
 * @param string|array $colour Colour as hex string or RGB array
 * @param number $change Amount to change colour by. Default 30.
 * @param boolean $echo Whether to echo the result. Default true.
 * @return array|null RGB array or null, based on value of $echo
 */
function bb_colour_darker($colour, $change = 30, $echo = true) {
    bb_colour_change($colour, -($change), $echo);
}

/**
 * Lighten a colour
 * @param string|array $colour Colour as hex string or RGB array
 * @param number $change Amount to change colour by. Default 30.
 * @param boolean $echo Whether to echo the result. Default true.
 * @return array|null RGB array or null, based on value of $echo
 */
function bb_colour_lighter($colour, $change = 30, $echo = true) {
    bb_colour_change($colour, $change, $echo);
}

/**
 * Make a colour lighter or darker
 * @param string|array $colour Colour as hex string or RGB array
 * @param number $change Amount to change colour by. Positive values will produce a lighter colour; negative will produce a darker colour. Default 30.
 * @param boolean $echo Whether to echo the result. Default true.
 * @return array|null RGB array or null, based on value of $echo
 */
function bb_colour_change($colour, $change = 30, $echo = true) {
    if (!is_array($colour)) {
        $colour = bb_convert_colour($colour, 'array');
    }
    for ($i = 0; $i < 3; $i++) {
        $colour[$i] = $colour[$i] + $change;
        if ($colour[$i] < 0) {
            $colour[$i] = 0;
        } elseif ($colour[$i] > 255) {
            $colour[$i] = 255;
        }
    }
    $colour = implode(',', $colour);
    if ($echo) {
        echo $colour;
    } else {
        return $colour;
    }
}
