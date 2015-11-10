<?php

/**
 * Class responsible for constructing our content and preparing it for rendering
 *
 * @since 0.1
 */
class Instant_Articles_Post {

	/** @var int ID of the post */
	protected $_ID;

	/**
	 * Setup data and build the content
	 *
	 * @since 0.1
	 * @param int $post_id ID of the post
	 */
	function __construct( $post_id ) {
		$this->_ID = $post_id;
	}

	/**
	 * Get the title for this post
	 *
	 * @since 0.1
	 * @return string The title
	 */
	public function get_title() {
		return get_the_title( $this->_ID );
	}

	/**
	 * Get the canonical URL for this post
	 *
	 * @since 0.1
	 * @return string The canonical URL
	 */
	public function get_canonical_url() {
		return get_permalink( $this->_ID );
	}

}

