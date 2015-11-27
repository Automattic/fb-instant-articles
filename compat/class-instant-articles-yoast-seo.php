<?php

/**
 * Compatibility layer for Yoast SEO
 *
 * @since 0.1
 * @todo Add all the filters
 *
 */
class Instant_Articles_Yoast_SEO {

	/**
	 * Init the compat layer
	 *
	 */
	function init() {
		add_filter( 'instant_articles_featured_image', array( $this, 'override_featured_image' ), 10, 2 );
	}

	/**
	 * Override the featured image with the one set for Facebook
	 */
	function override_featured_image( $image_data, $post_id ) {

		$image_url = get_post_meta( $post_id, '_yoast_wpseo_opengraph-image', true );

		if ( strlen( $image_url ) ) {
			$image_data[ 'src' ] = $image_url;

			$desc = get_post_meta( $post_id, '_yoast_wpseo_opengraph-description', true );

			if ( strlen( $desc ) ) {
				$image_data[ 'caption' ] = $desc;
			}
		}

		return $image_data;
	}

}
