<?php
/**
 * Plugin Name: Instant Articles for WP
 * Description: Add support for Instant Articles for Facebook to your WordPress site.
 * Author: Dekode
 * Author URI: https://dekode.no
 * Version: 0.1
 * Text Domain: instant-articles
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

require_once( dirname( __FILE__ ) . '/dom-transform-filters/class-instant-articles-dom-transform-filter.php' );
require_once( dirname( __FILE__ ) . '/class-instant-articles-dom-transform-filter-runner.php' );
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
	load_plugin_textdomain( 'instant-articles', false, plugin_dir_path( __FILE__ ) . '/languages' );
}
add_action( 'plugins_loaded', 'instant_articles_load_textdomain' );

/**
 * Register our special feed
 *
 * @since 0.1
 */
function instant_articles_init() {
	/**
	 * Filter the feed slug
	 *
	 * @since 0.1
	 * @param string  $feed_slug  The default feed slug
	 */
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
 * Register included DOM transformation filters
 *
 * @since 0.1
 */
function instant_articles_register_transformation_filters() {
	include( dirname( __FILE__ ) . '/dom-transform-filters/class-instant-articles-dom-transform-filter-image.php' );
	Instant_Articles_DOM_Transform_Filter_Runner::register( 'Instant_Articles_DOM_Transform_Filter_Image' );
}
add_action( 'instant_articles_register_dom_transformation_filters', 'instant_articles_register_transformation_filters' );

