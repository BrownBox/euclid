<?php
/**
 * Dump the variable on the screen
 * @param mixed $var
 * @param boolean $stop_execution
 * @author Anton Zaroutski <anton@brownbox.net.au>
 */
function dump($var, $stop_execution = true) {
    echo '<pre>';

    if (is_bool($var)) {
        var_dump($var);
    } else {
        print_r($var);
    }

    echo '</pre>';

    if ($stop_execution) {
        exit();
    }
}

/**
 * Show all PHP notices, warnings and errors
 */
function bb_show_all_errors() {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}
