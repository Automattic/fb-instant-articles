<?php
/**
 * Facebook Instant Articles for WP.
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 *
 * @package default
 */

// Load compat layer for Co-Authors Plus.
if ( function_exists( 'get_coauthors' ) && ! defined( 'CAP_IA_COMPAT' ) ) {
	include( dirname( __FILE__ ) . '/compat/class-instant-articles-co-authors-plus.php' );
	$cap = new Instant_Articles_Co_Authors_Plus;
	$cap->init();
}

// Load compat layer for Yoast SEO.
if ( defined( 'WPSEO_VERSION' ) && ! defined( 'WPSEO_IA_COMPAT' ) ) {
	include( dirname( __FILE__ ) . '/compat/class-instant-articles-yoast-seo.php' );
	$yseo = new Instant_Articles_Yoast_SEO;
	$yseo->init();
}

// Load support for Google Analytics for WordPress (Google Analytics by Yoast).
if ( defined( 'GAWP_VERSION' ) && ! defined( 'GAWP_IA_COMPAT' ) ) {
	include( dirname( __FILE__ ) . '/compat/class-instant-articles-google-analytics-for-wordpress.php' );
	$gawp = new Instant_Articles_Google_Analytics_For_WordPress;
	$gawp->init();
}

// Load support for Jetpack
if ( defined( 'JETPACK__VERSION' ) ) {
	include( dirname( __FILE__ ) . '/compat/class-instant-articles-jetpack.php' );
	$jp = new Instant_Articles_Jetpack;
	$jp->init();
}

// Load compat layer for facebook embeds.
include( dirname( __FILE__ ) . '/compat/class-instant-articles-facebook-embed.php' );
$facebok_embed = new Instant_Articles_Facebook_Embed;
$facebok_embed->init();
