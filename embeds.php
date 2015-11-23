<?php

/**
 * Oembed/embed handling
 *
 * The embed result is most likely cached, so we have to handle it late. If it is not cached and we handle it early, it will be cached with an unwanted result
 * for regular posts. So we resort to parsing the embed result in the embed_oembed_html and embed_handler_html filters.
 */


/**
 * Filter the oembed results to see if we should to some extra handling
 *
 * @since 0.1
 * @param string  $html     The original HTML returned from the external oembed provider (and potientially filtered locally)
 * @param string  $url      The URL found in the content
 * @param mixed   $attr     An array with extra attributes
 * @param int     $post_ID  The post ID
 * @return string  The potentially filtered HTML
 */
function instant_articles_embed_oembed_html( $html, $url, $attr, $post_ID ) {

	if ( ! class_exists( 'WP_oEmbed' ) ) {
		include_once( WPINC . '/class-oembed.php' );
	}

	// Instead of checking all possible URL variants, use the provider list from WP_oEmbed
	$WP_oEmbed = new WP_oEmbed();
	$provider = $WP_oEmbed->get_provider( $url );

	if ( false !== strpos( $provider, 'instagram.com' ) ) {
		$html = instant_articles_embed_oembed_html_instagram( $html, $url, $attr, $post_ID );
	} elseif( false !== strpos( $provider, 'twitter.com' ) ) {
		$html = instant_articles_embed_oembed_html_twitter( $html, $url, $attr, $post_ID );
	} elseif( false !== strpos( $provider, 'youtube.com' ) ) {
		$html = instant_articles_embed_oembed_html_youtube( $html, $url, $attr, $post_ID );
	} elseif( false !== strpos( $provider, 'vine.co' ) ) {
		$html = instant_articles_embed_oembed_html_vine( $html, $url, $attr, $post_ID );
	}

	return $html;

}
add_filter( 'embed_oembed_html', 'instant_articles_embed_oembed_html', 10, 4 );


/**
 * Filter the oembed results for Instagram embeds
 *
 * @since 0.1
 * @param string  $html     The original HTML returned from the external oembed provider (and potientially filtered locally)
 * @param string  $url      The URL found in the content
 * @param mixed   $attr     An array with extra attributes
 * @param int     $post_ID  The post ID
 * @return string  The filtered HTML
 */
function instant_articles_embed_oembed_html_instagram( $html, $url, $attr, $post_ID ) {

	/*
	Example output from the oembed provider:
	<blockquote class="instagram-media" data-instgrm-version="6" style=" background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); margin: 1px; max-width:658px; padding:0; width:99.375%; width:-webkit-calc(100% - 2px); width:calc(100% - 2px);"><div style="padding:8px;">
	<div style=" background:#F8F8F8; line-height:0; margin-top:40px; padding:50.0% 0; text-align:center; width:100%;">
	<div style=" background:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACwAAAAsCAMAAAApWqozAAAAGFBMVEUiIiI9PT0eHh4gIB4hIBkcHBwcHBwcHBydr+JQAAAACHRSTlMABA4YHyQsM5jtaMwAAADfSURBVDjL7ZVBEgMhCAQBAf//42xcNbpAqakcM0ftUmFAAIBE81IqBJdS3lS6zs3bIpB9WED3YYXFPmHRfT8sgyrCP1x8uEUxLMzNWElFOYCV6mHWWwMzdPEKHlhLw7NWJqkHc4uIZphavDzA2JPzUDsBZziNae2S6owH8xPmX8G7zzgKEOPUoYHvGz1TBCxMkd3kwNVbU0gKHkx+iZILf77IofhrY1nYFnB/lQPb79drWOyJVa/DAvg9B/rLB4cC+Nqgdz/TvBbBnr6GBReqn/nRmDgaQEej7WhonozjF+Y2I/fZou/qAAAAAElFTkSuQmCC); display:block; height:44px; margin:0 auto -44px; position:relative; top:-22px; width:44px;"/>
	</div>
	<p style=" color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; line-height:17px; margin-bottom:0; margin-top:8px; overflow:hidden; padding:8px 0 7px; text-align:center; text-overflow:ellipsis; white-space:nowrap;"><a href="https://www.instagram.com/p/6l9z5RTbAN/" style=" color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:normal; line-height:17px; text-decoration:none;" target="_blank">A photo posted by BjÃ¸rn Johansen (@bjornjohansen)</a> on <time style=" font-family:Arial,sans-serif; font-size:14px; line-height:17px;" datetime="2015-08-20T05:19:53+00:00">Aug 19, 2015 at 10:19pm PDT</time></p>
	</div>
	</blockquote>
	<p><script async="" defer="defer" src="//platform.instagram.com/en_US/embeds.js"/></p>
	*/

	$html = sprintf( '<figure class="op-social"><iframe>%s</iframe></figure>', $html );

	/**
	 * Filter the Instant Article Social Embed markup for Instagram 
	 *
	 * @since 0.1
	 * @param string  $html  The Social Embed markup
	 */
	$html = apply_filters( 'instant_articles_social_embed_instagram', $html );

	return $html;
}

/**
 * Filter the oembed results for Twitter embeds
 *
 * @since 0.1
 * @param string  $html     The original HTML returned from the external oembed provider (and potientially filtered locally)
 * @param string  $url      The URL found in the content
 * @param mixed   $attr     An array with extra attributes
 * @param int     $post_ID  The post ID
 * @return string  The filtered HTML
 */
function instant_articles_embed_oembed_html_twitter( $html, $url, $attr, $post_ID ) {

	/*
	Example output from the oembed provider:
	<blockquote class="twitter-tweet" width="550"><p lang="en" dir="ltr">Will my Drupal site upgrade itself automatically to version 8, or do I click a button somewhere?</p>
	<p>— BjÃ¸rn Johansen (@bjornjohansen) <a href="https://twitter.com/bjornjohansen/status/667263124794417152">November 19, 2015</a></p>
	</blockquote>
	<p><script async="" src="//platform.twitter.com/widgets.js" charset="utf-8"/></p>
	*/

	$html = sprintf( '<figure class="op-social"><iframe>%s</iframe></figure>', $html );

	/**
	 * Filter the Instant Article Social Embed markup for Twitter 
	 *
	 * @since 0.1
	 * @param string  $html  The Social Embed markup
	 */
	$html = apply_filters( 'instant_articles_social_embed_twitter', $html );

	return $html;
}

/**
 * Filter the oembed results for YouTube embeds
 *
 * @since 0.1
 * @param string  $html     The original HTML returned from the external oembed provider (and potientially filtered locally)
 * @param string  $url      The URL found in the content
 * @param mixed   $attr     An array with extra attributes
 * @param int     $post_ID  The post ID
 * @return string  The filtered HTML
 */
function instant_articles_embed_oembed_html_youtube( $html, $url, $attr, $post_ID ) {

	/*
	Example output from the oembed provider:
	<iframe width="660" height="371" src="https://www.youtube.com/embed/SQjSJ0T7PiE?feature=oembed" frameborder="0" allowfullscreen></iframe>
	*/

	$html = sprintf( '<figure class="op-social"><iframe>%s</iframe></figure>', $html );

	/**
	 * Filter the Instant Article Social Embed markup for YouTube 
	 *
	 * @since 0.1
	 * @param string  $html  The Social Embed markup
	 */
	$html = apply_filters( 'instant_articles_social_embed_youtube', $html );

	return $html;
}

/**
 * Filter the oembed results for Vine embeds
 *
 * @since 0.1
 * @param string  $html     The original HTML returned from the external oembed provider (and potientially filtered locally)
 * @param string  $url      The URL found in the content
 * @param mixed   $attr     An array with extra attributes
 * @param int     $post_ID  The post ID
 * @return string  The filtered HTML
 */
function instant_articles_embed_oembed_html_vine( $html, $url, $attr, $post_ID ) {

	/*
	Example output from the oembed provider:
	<iframe class="vine-embed" src="https://vine.co/v/e9U7gav5e5h/embed/simple" width="660" height="660" frameborder="0"/><script async="" src="//platform.vine.co/static/scripts/embed.js"/>
	*/

	$html = sprintf( '<figure class="op-social"><iframe>%s</iframe></figure>', $html );

	/**
	 * Filter the Instant Article Social Embed markup for Vine 
	 *
	 * @since 0.1
	 * @param string  $html  The Social Embed markup
	 */
	$html = apply_filters( 'instant_articles_social_embed_vine', $html );

	return $html;
}

