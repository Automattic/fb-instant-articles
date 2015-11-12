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
 * Filters/Actions
 *
 * Filters:
 * instant_articles_slug - Change the feed slug. Default: instant-articles
 * instant_articles_show_style - Show/hide meta tag with style info. Default: true
 * instant_articles_style - Style attribute. Default: default
 *
 * Actions
 * -
 */

defined( 'ABSPATH' ) || die('Shame on you');

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
 * Load plugin textdomain
 *
 * @since 0.1
 */
function instant_articles_load_textdomain() {
	load_plugin_textdomain( 'instant_articles', false, plugin_dir_path( __FILE__ ) . '/languages' );
}
add_action( 'plugins_loaded', 'instant_articles_load_textdomain' );

/**
 * Register our special feed
 *
 * @since 0.1
 */
function instant_articles_init() {
	$feed_slug = apply_filters( 'instant_articles_slug', 'instant-articles' );
	add_feed( $feed_slug, 'instant_articles_feed' );
}
add_action( 'init', 'instant_articles_init' );

/**
 * Feed display callback
 *
 * @since 0.1
 */
function instant_articles_feed() {
	include( dirname( __FILE__ ) . '/feed-template.php' );
}

/**
 * Article <head> style
 *
 * @since 0.1
 * @todo Per article or global? Move into Instant_Articles_Post? (refactor)
 */
function instant_articles_style() {
	if ( false === apply_filters( 'instant_articles_show_style', true ) ) {
		return;
	}

	$article_style = apply_filters( 'instant_articles_style', 'default' );

	printf( '<meta property="fb:article_style" content="%s">', esc_attr( $article_style ) );
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



