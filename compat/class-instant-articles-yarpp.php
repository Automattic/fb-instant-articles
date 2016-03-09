<?php

/**
 * Support class for Yet Another Related Posts Plugin (YARPP)
 *
 * @since 0.2
 *
 */
class Instant_Articles_YARPP {

	/**
	 * Init the compat layer
	 *
	 */
	function init() {
		add_filter( 'instant_articles_use_related_articles_in_footer', array( __CLASS__, 'has_related_posts' ), 10, 2 );
		add_filter( 'instant_articles_related_articles', array( __CLASS__, 'get_related_posts' ), 10, 2 );
	}

	/**
	 * Does the post has related entries?
	 *
	 * @since 0.2
	 * @param bool                   $related_articles  The current value
	 * @param Instant_Articles_Post  $ia_post           The current IA post object
	 * @return bool  Whether the current post as related posts or not
	 */
	public static function has_related_posts( $has_related_posts, $ia_post ) {
		
		// If something else claims we have related articles, let’s not protest
		if ( ! $has_related_posts ) {
			$has_related_posts = related_entries_exist( array(), $ia_post->get_the_ID() );
		}

		return $has_related_posts;

	}

	/**
	 * Use related posts from YARPP
	 *
	 * @since 0.2
	 * @param array  $related_articles  The related posts
	 */
	public static function get_related_posts( $related_articles, $ia_post ) {

		if ( ! is_array( $related_articles ) ) {
			$related_articles = array();
		}

		$yarpp_related_entries = yarpp_get_related( array(), $ia_post->get_the_ID() );
		
		foreach ( $yarpp_related_entries as $post ) {
			$related_article = new stdClass();
			$related_article->url = get_permalink( $post->ID );
			$related_article->is_sponsored = false;

			$related_articles[] = $related_article;
		}

		return $related_articles;

	}

}
