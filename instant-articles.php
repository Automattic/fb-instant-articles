<?php
/**
 * Plugin Name: Instant Articles for WP
 * Description: Add support for Instant Articles for Facebook to your WordPress site.
 * Author: Dekode
 * Author URI: http://dekode.no
 * Version: 0.1
 * License: GPLv2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */



/**
 * Plugin activation hook to add our rewrite rules
 *
 * @since 0.1
 */
function instant_articles_activate(){
	instant_articles_init();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'instant_articles_activate' );

/**
 * Plugin activation hook to remove our rewrite rules
 *
 * @since 0.1
 */
function instant_articles_deactivate(){
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'instant_articles_deactivate' );

/**
 * Register our specal feed
 *
 * @since 0.1
 */
function instant_articles_init() {
	add_feed( 'instant-articles', 'instant_articles_feed' );
}
add_action( 'init', 'instant_articles_init' );

/**
 * Feed display callback
 *
 * @since 0.1
 */
function instant_articles_feed() {

	header( 'Content-Type: ' . feed_content_type( 'rss-http' ) . '; charset=' . get_option( 'blog_charset' ), true );
	echo '<?xml version="1.0" encoding="' . get_option( 'blog_charset' ) . '"?' . '>';
	?>
	<rss version="2.0">
		<title><?php bloginfo_rss( 'name' ); ?> - Instant Articles</title>
		<link><?php bloginfo_rss('url') ?></link>
		<description><?php bloginfo_rss( 'description' ) ?></description>

	</rss>
	<?php
}

