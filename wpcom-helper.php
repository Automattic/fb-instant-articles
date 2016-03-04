<?php

// Wrap the wpcom tracking pixel to comply with the FBIA spec
// https://developers.facebook.com/docs/instant-articles/reference/analytics
function wpcom_fbia_stats_pixel() {
	global $post;

	if ( ! defined( 'INSTANT_ARTICLES_SLUG' ) ) {
		return;
	}

	if ( ! is_feed( INSTANT_ARTICLES_SLUG ) ) {
		return;
	}

	// Stop wpcom adding the tracking pixel
	remove_filter( 'the_content', 'add_bug_to_feed', 100 );

	add_filter( 'the_content', '_wpcom_fbia_stats_pixel', 100 );

}
add_action( 'template_redirect', 'wpcom_fbia_stats_pixel' );

function _wpcom_fbia_stats_pixel( $content ) {
	global $post, $current_blog;

	if( ! is_feed() )
		return $content;

	$web_bug = '<img alt="" border="0" src="' . esc_url( http() . '://pixel.wp.com/b.gif?host=' . $_SERVER[ 'HTTP_HOST' ] . '&blog=' . $current_blog->blog_id . '&post=' . $post->ID . '&subd=' . str_replace( '.wordpress.com', '', $current_blog->domain ) . '&ref=&feed=1' ) . '" width="1" height="1" />';
	$url = 'https://pixel.wp.com/b.gif?host=' . $_SERVER[ 'HTTP_HOST' ] . '&blog=' . $current_blog->blog_id . '&post=' . $post->ID . '&subd=' . str_replace( '.wordpress.com', '', $current_blog->domain ) . '&ref=&feed=1';

	$fbia_pixel = '
<figure class="op-tracker">
	<iframe>
		<script>
			var x = new Image(); x.src = "' . esc_js( $url ) . '&rand=" +Math.random();
		</script>
	</iframe>
</figure>';

	return $content . $fbia_pixel;

}