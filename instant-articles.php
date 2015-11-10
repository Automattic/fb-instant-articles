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


require_once( dirname( __FILE__ ) . '/class-instant-articles-post.php' );

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
	<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/">
		<title><?php bloginfo_rss( 'name' ); ?> - Instant Articles</title>
		<link><?php bloginfo_rss('url') ?></link>
		<description><?php bloginfo_rss( 'description' ) ?></description>
		<?php while ( have_posts() ) : the_post(); ?>
			<item>
				<title><?php the_title_rss(); ?></title>
				<link><?php the_permalink(); ?></link>
				<content:encoded><![CDATA[<?php instant_articles_render_post( get_the_ID() ); ?>]]></content:encoded>
				<guid isPermaLink="false"><?php the_guid(); ?></guid>
				<description><![CDATA[<?php the_excerpt_rss(); ?>]]></description>
				<pubDate><?php echo mysql2date( 'c', get_post_time( 'Y-m-d H:i:s', true ), false ); ?></pubDate>
				<author><![CDATA[<?php echo esc_html( get_the_author() ); ?>]]></author>
			</item>
		<?php endwhile; ?>
	</rss>
	<?php
}

/**
 * Render post
 *
 * @since 0.1
 * @param int  $post_id  The ID to the post to render
 */
function instant_articles_render_post( $post_id ) {
	
	/**
     * Fires before the instant article is rendered
     *
     * @since 0.1
     * @param int  $post_id  The ID to the post to render
     */
	do_action( 'pre_instant_article_render', $post_id );
	
	$instant_article_post = new Instant_Articles_Post( $post_id );
	
	$default_template = dirname( __FILE__ ) . '/template.php';

	/**
     * Filter the path to the template to use to render the instant article
     *
     * @since 0.1
     * @param int     $post_id   The ID to the post to render
     * @param string  $template  Path to the current (default) template.
     */
	$template = apply_filters( 'instant_articles_render_post_template', $post_id, $default_template );
	
	// Make sure the template exists. Devs do the darndest things.
	if ( ! file_exists( $template ) ) {
		$template = $default_template;
	}
	include $template;
	
	/**
     * Fires after the instant article is rendered
     *
     * @since 0.1
     * @param int  $post_id  The ID to the post to render
     */
	do_action( 'after_instant_article_render', $post_id );
}



