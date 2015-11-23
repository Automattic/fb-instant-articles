<?php

/**
 * Compatibility layer for Co-Authors Plus
 *
 * @since 0.1
 *
 */
class Instant_Articles_Co_Authors_Plus {

	/**
	 * Init the compat layer
	 *
	 */
	function init() {
		add_filter( 'instant_articles_authors', array( $this, 'authors' ), 10, 2 );
	}

	/**
	 * Filter the authors
	 *
	 * @param array  $authors  The current authors
	 * @param Instant_Articles_Post  The instant articles post object we are working on
	 */
	function authors( $authors, $iap ) {
		if ( function_exists( 'get_coauthors' ) ) {
			$coauthors = get_coauthors( $iap->get_the_ID() );

			$authors = array();
			foreach( $coauthors as $coauthor ) {

				$author = new stdClass;
				$author->ID            = $coauthor->ID;
				$author->display_name  = is_a( $coauthor, 'WP_User' ) ? $coauthor->data->display_name  : $coauthor->display_name;
				$author->first_name    = $coauthor->first_name;
				$author->last_name     = $coauthor->last_name;
				$author->user_login    = is_a( $coauthor, 'WP_User' ) ? $coauthor->data->user_login    : $coauthor->user_login;
				$author->user_nicename = is_a( $coauthor, 'WP_User' ) ? $coauthor->data->user_nicename : $coauthor->user_nicename;
				$author->user_email    = is_a( $coauthor, 'WP_User' ) ? $coauthor->data->user_email    : $coauthor->user_email;
				$author->user_url      = is_a( $coauthor, 'WP_User' ) ? $coauthor->data->user_url      : $coauthor->website;
				$author->bio           = $coauthor->description;

				$authors[] = $author;
			}
		}

		return $authors;
	}

}
