<div class="small-24 columns">
<?php
/**
 * based on @version 1.0.0
 *
 * Search results
 *
 * STEP 2: CODE
 * @todo code the output markup. focus on grids and layouts for Small, Medium and Large Devices.
 * @todo code the local css. Mobile 1st, then medium and large.
 *
 * STEP 3: SIGN_OFF
 * @todo review code quality (& rework as required)
 * @todo review and promote css (as required)
 * @todo reset transitents and retest
 * @todo set transients for production.
 * @todo leave sign-off name and date
 *
 */

get_search_form();

global $query_string;

$section_args = array(
        'namespace' => 'search', // remember to use keywords like 'section', 'nav' or 'css' where practical.
        'filename'  => str_replace(get_stylesheet_directory(), "", __FILE__ ), // relative path from the theme folder
        'string' => strtolower( $_GET['s'] ),
        'post_type' => array( 'post', 'page' ),
        'popular_search' => 'Can\'t find what you are looking for? Try one of these popular searches...',
        'msg' => array(
        	   '404' => 'We can\'t find what you are looking for. Was it one of these pages?',
    	),
);

if( is_user_logged_in() ) array_push( $section_args['post_type'] , array() ); // add private post types here
if( isset( $_GET['post_type'] ) ) $section_args['post_type'] = array( $_GET['post_type'] );

$transients = defined(WP_BB_ENV) && WP_BB_ENV == 'PRODUCTION'; // Set this to false to force all transients to refresh
$track_hist = true; // change this to false to force $hist transients to refresh

if( isset( $section_args['msg'][$_GET['msg']] ) ) echo '<span class="h1 msg">'.$section_args['msg'][$_GET['msg']].'</span>'."\n";

// setup local css transient
$transient = ns_.$section_args['namespace'].'_css_'.md5( $section_args['filename'] );
delete_transient( $transient );
if ( false === ( $ob = get_transient( $transient ) ) ) {
    ob_start();
?>
<style>
/* START: <?php echo $section_args['filename'].' - '.date("Y-m-d H:i:s"); ?> */
@media only screen {
	#row-search .column {margin-bottom: 0.2rem;}
	#row-search .column > a {background-color: #eee; display: inline-block; padding: 1rem; width: 100%; position: relative;}
	#row-search .column > a span {display: inline-block; float: left; font-size: 0.8rem; width: 100%;}
	#row-search .column > a span.post_type {float: none; position: absolute; right: 0.5rem; text-align: right; top: 0.375rem; opacity: 0.125;}
	#row-search .column > a > span.h1 {color: blue; font-size: 1rem; margin-bottom: 0.4rem; text-decoration: underline;}
	#row-search .column > a:hover {background-color: #222;}
	#row-search .column > a:hover span {color: #fff;}

	#row-search .search-counts {margin-left: 0px; list-style: none; clear: both;}
	#row-search .search-counts > li {border-left: 1px solid rgba(0, 0, 0, 0.2); display: inline-block; height: 0.8rem; line-height: 0.7rem; margin-left: 0.5rem; padding-left: 0.5rem;}
	#row-search .search-counts > li:first-of-type{border-left: none; margin-left: 0px; padding-left: 0px;}

	#row-search {padding-top: 1.5rem;}
	#row-search p {clear: both; margin-left: 1rem;}
	#row-search ul.pop-searches {margin-left: 0px; list-style: none;margin-top: 1rem;}
	#row-search ul.pop-searches li {display: inline-block;}
	#row-search ul.pop-searches li > a {background-color: #eee; display: inline-block; margin-bottom: 0.4rem; margin-right: 0.4rem; padding: 0.1rem 0.6rem 0.3rem; font-size: 0.8rem; color:#222;}
	#row-search ul.pop-searches li > a:hover {background-color: #222; color: #fff;}
	#row-search span.pop-searches {margin-top: 1rem; display: inline-block;}

	/* search form */
	.search-form {border: 1px solid rgba(0, 0, 0, 0.4); clear: both; display: block; max-width: 500px;}
	.search-field {border: none; border-radius: 0; box-shadow: none; display: inline-block; float: left; height: 2.5rem; max-width: 75%;}
	.search-submit {background-color: #222; border: 3px solid #fff; border-radius: 0; color: #fff; display: inline-block; height: 2.5rem; width: 25%;}

	#row-search span {clear: both !important; display: inline-block; float: left; margin: 1rem 0;}
	#row-search span.h1{width: 100%;}

	body.search #row-inner-hero {height: 100px;}
}
@media only screen and (min-width: 40em) { /* <-- min-width 640px - medium screens and up */
	#row-search .column > a {min-height: 125px;}
	#row-search .column {margin-bottom: 1rem;}
	#row-search	.search-form {max-width: 60%;}
	body.search #row-inner-hero {height: 200px;}
}
@media only screen and (min-width: 64em) { /* <-- min-width 1024px - large screens and up */
	#row-search .column {margin-bottom: 1.2rem;}
	#row-search	.search-form {max-width: 40%;}
	body.search #row-inner-hero {height: 300px;}
}

/* END: <?php echo $section_args['filename']; ?> */
</style>
<?php

    $ob = ob_get_clean();
    set_transient( $transient, $ob, LONG_TERM );
    delete_transient( $transient );
    echo $ob; unset( $ob );
}
unset($transient);

// setup search results
$transient = ns_.$section_args['namespace'].'_results_'.$section_args['string'].'_'.md5( $section_args['filename'] );
if( false === $transients) delete_transient( $transient );
if ( false === ( $search1 = unserialize( get_transient( $transient ) ) ) ) {

	$args = array( 's' => $section_args['string'], 'posts_per_page' => -1, 'post_type' => $section_args['post_type'] );
	$search1 = new WP_Query( $args );

    set_transient( $transient, serialize( $search1 ), SHORT_TERM );
    if( false === $transients) delete_transient( $transient );
}
unset( $transient );

// track search strings
$transient = ns_.$section_args['namespace'].'_hist_'.md5( $section_args['filename'] );
if( false === $track_hist) delete_transient( $transient );
if ( false === ( $hist = get_transient( $transient ) ) ) $hist = array();
// var_dump( count( $search1->posts ) );
if( count( $search1->posts ) > 0 ) {
	if( !is_array( $hist[ $section_args['string'] ] ) ) $hist[ $section_args['string'] ] = array();
	array_push( $hist[ $section_args['string'] ], time() );

	set_transient( $transient, $hist, 30 * DAY_IN_SECONDS ); // 30 days
	if( false === $track_hist) delete_transient( $transient );

}
unset( $transient );

if (defined('BB_SUPER_SEARCH') && BB_SUPER_SEARCH) {
    // setup search results
    $transient = ns_.$section_args['namespace'].'_results_'.$section_args['string'].'_'.md5( $section_args['filename'] );
    if( false === $transients) delete_transient( $transient );
    if ( false === ( $search2 = unserialize( get_transient( $transient ) ) ) ) {

    	$args = array(
    		'posts_per_page' => -1,
    		'post_type' => $section_args['post_type'],

    		'meta_query' => array(
    			array(
    				'key'     => 'keywords',
    				'value'   => $section_args['string'],
    				'compare' => 'LIKE',
    			),
    		),

    		);
    	$search2 = new WP_Query( $args );

        set_transient( $transient, serialize( $search2 ), SHORT_TERM );
        if( false === $transients) delete_transient( $transient );
    }
    unset( $transient );
}

$transient = ns_.$section_args['namespace'].'_markup_'.$section_args['string'].'_'.md5( $section_args['filename'] );
if( false === $transients) delete_transient( $transient );
if ( false === ( $ob = get_transient( $transient ) ) ) {

    ob_start();

    $markup = '';
    if( count( $search1->posts ) > 0 ){
		$markup .= '<span class="h1">We found '.count( $search1->posts ).' page/s that match your search request</span>'."\n";
		$markup .= '<div class="row small-up-1 medium-up-2 large-up-3">'."\n";

		$post_types = array();
		$searched_posts = array();
		foreach ($search1->posts as $post) {

			$post_types[ $post->post_type ]++;
			$searched_posts[] = $post->ID;
			$markup .= '<div class="column">'."\n";
			// echo '	<img src="" alt="">'."\n";
			$markup .= '  <a href="'.get_permalink( $post->ID ).'">'."\n";
			$markup .= '  	<span class="h1">'.$post->post_title.'</span>'."\n";

			if( function_exists( 'bb_extract' ) ) {
				$markup .= '  	<span class="hide-for-medium-only">'.strip_tags( bb_extract( $post->post_content, 220 ) ).'</span>'."\n";
				$markup .= '  	<span class="show-for-medium-only">'.strip_tags( bb_extract( $post->post_content, 120 ) ).'</span>'."\n";
			}
			if( $post->post_type !== 'page' ) $markup .= '  	<span class="post_type">'.$post->post_type.'</span>'."\n";

			$markup .= '  </a>'."\n";
			$markup .= '</div>'."\n";

		}
		$markup .= '</div>'."\n";
		$markup .= '<hr>'."\n";

		if( count( $post_types ) > 1 ) {
			echo '<ul class="search-counts">';
			echo '<li><a href="/?s='.$string.'">All</a> ('.count( $search1->posts ).')</li>';
			foreach ($post_types as $post_type => $count) {
				if( $post_type !== 'page' ) echo '<li><a href="/?s='.$section_args['string'].'&post_type='.$post_type.'">'.ucfirst( $post_type ).'</a> ('.$count.')</li>';
			}
			echo '</ul>';
		}
		unset( $post_types );
	} elseif (!defined('BB_SUPER_SEARCH') || !BB_SUPER_SEARCH) {
		$markup .= '<span class="h1">No pages were found that match your search request. Please try a different search term, or use the menus to find what you\'re looking for.</span>'."\n";
	}
	echo $markup;
	unset( $markup );

	if( count( $search2->posts ) > 0 ){

		$search3 = array();
		foreach ($search2->posts as $post) {
			if( !in_array( $post->ID, $searched_posts ) ) {
				array_push( $search3, $post );
			}
		}

		if( count( $search3 ) > 0 ){
			$markup .= '<div class="row small-up-1 medium-up-2 large-up-3">'."\n";
			$markup .= '<span class="h1"> We found '.count( $search3 ).' related page/s that match your search request</span>'."\n";

			// var_dump( $search2->posts);

			$post_types = array();
			foreach ($search2->posts as $post) {

				$post_types[ $post->post_type ]++;
				$markup .= '<div class="column">'."\n";
				// echo '	<img src="" alt="">'."\n";
				$markup .= '  <a href="'.get_permalink( $post->ID ).'">'."\n";
				$markup .= '  	<span class="h1">'.$post->post_title.'</span>'."\n";

				if( function_exists( 'bb_extract' ) ) {
					$markup .= '  	<span class="hide-for-medium-only">'.strip_tags( bb_extract( $post->post_content, 220 ) ).'</span>'."\n";
					$markup .= '  	<span class="show-for-medium-only">'.strip_tags( bb_extract( $post->post_content, 120 ) ).'</span>'."\n";
				}
				if( $post->post_type !== 'page' ) $markup .= '  	<span class="post_type">'.$post->post_type.'</span>'."\n";

				$markup .= '  </a>'."\n";
				$markup .= '</div>'."\n";

			}
			$markup .= '</div>'."\n";
			$markup .= '<hr>'."\n";

			if( count( $post_types ) > 1 ) {
				echo '<ul class="search-counts">';
				echo '<li><a href="/?s='.$string.'">All</a> ('.count( $search3 ).')</li>';
				foreach ($post_types as $post_type => $count) {
					if( $post_type !== 'page' ) echo '<li><a href="/?s='.$section_args['string'].'&post_type='.$post_type.'">'.ucfirst( $post_type ).'</a> ('.$count.')</li>';
				}
				echo '</ul>';
			}
			unset( $post_types );
		}
		echo $markup;
		unset( $markup );
	}

    $ob = ob_get_clean();
    set_transient( $transient, $ob, SHORT_TERM );
    if( false === $transients) delete_transient( $transient );

}
unset( $transient );
echo $ob; unset( $ob );

if (defined('BB_SUPER_SEARCH') && BB_SUPER_SEARCH) {
    // other popular searches
    $strings = array();
    foreach ($hist as $key => $value) $strings[$key] = count( $hist[$key] );
    echo '<span class="h1 pop-searches">'.$section_args['popular_search'].'</span>'."\n";
    echo '<ul class="pop-searches">';
    arsort($strings);
    $count = 0;
    foreach ($strings as $string => $count) {
    	if( strlen( $string) > 0 ) echo '<li><a href="/?s='.$string.'">'.$string.'</a></li>';
    }
    echo '</ul>';

    $logfile = get_template_directory() . '/logs/search.log';
    if( strlen( $section_args['string'] ) > 1 && file_exists( $logfile ) ) {
    	$log = array(
    		'exact' 	=> str_pad( count( $search1->posts ), 3, '0', STR_PAD_LEFT ),
    		'related' 	=> str_pad( count( $search2->posts ), 3, '0', STR_PAD_LEFT ),
    		'string'	=> str_pad( $section_args['string'], 30, ' ', STR_PAD_RIGHT ),
    		'query' 	=> $_SERVER["QUERY_STRING"],
    	);
    	$log = implode( ' | ', $log );
    	file_put_contents( $logfile, date("Y-m-d H:i:s" ) . " | " . $log . "\n", FILE_APPEND );
    }
}
?>
</div>
