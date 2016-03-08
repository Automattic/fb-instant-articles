<?php

/**
 * Support class for Jetpack
 *
 * @since 0.2
 *
 */
class Instant_Articles_Jetpack {

	/**
	 * Init the compat layer
	 *
	 */
	function init() {

		/**
		 * Do not "fix" bare URLs on their own line of the form
		 * http://www.youtube.com/v/9FhMMmqzbD8?fs=1&hl=en_US
		 * as we have oEmbed to handle those
		 */
		wp_embed_unregister_handler( 'wpcom_youtube_embed_crazy_url' );
		
	}

}
