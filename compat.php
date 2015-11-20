<?php

// Load compat layer for Co-Authors Plus
if ( function_exists( 'get_coauthors' ) ) {
	include(  dirname( __FILE__ ) . '/compat/class-instant-articles-co-authors-plus.php' );
	$cap = new Instant_Articles_Co_Authors_Plus;
	$cap->init();
}

// Load compat layer for Yoast SEO
if ( defined( 'WPSEO_VERSION' ) ) {
	include(  dirname( __FILE__ ) . '/compat/class-instant-articles-yoast-seo.php' );
	$yseo = new Instant_Articles_Yoast_SEO;
	$yseo->init();
}

