<?php
// build the redirect url
$url = '/?s='.strtolower( substr( $_SERVER['REQUEST_URI'],1) ).'&msg=404'."\n";

// log the 404 path
$logfile = get_template_directory() . '/logs/404.log';
if( file_exists( $logfile ) ){
    file_put_contents( $logfile, date("Y-m-d H:i:s" ) . " | " . strtolower( $_SERVER['REQUEST_URI'] ) . "\n", FILE_APPEND );
}

// redirect :)
wp_redirect( $url );
exit;
