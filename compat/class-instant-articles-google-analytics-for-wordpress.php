<?php

/**
 * Support class for Google Analytics for WordPress (Google Analytics by Yoast)
 *
 * @since 0.1
 *
 */
class Instant_Articles_Google_Analytics_For_WordPress {

	/**
	 * Init the compat layer
	 *
	 */
	function init() {
		add_filter( 'instant_articles_content', array( $this, 'add_ga_code' ), 10, 1 );
	}

	/**
	 * Add the GA tracking code to the end of the content
	 *
	 * @since 0.1
	 * @param string  $content  The post content.
	 */
	function add_ga_code( $content ) {

		$options = Yoast_GA_Options::instance()->options;

		if ( isset( $options['enable_universal'] ) && 1 == $options['enable_universal'] ) {
			$tracker = new Yoast_GA_Universal;
		} else {
			$tracker = new Yoast_GA_JS;
		}

		ob_start();
		$tracker->tracking();
		$ga_code = ob_get_clean();

		if ( strlen( $ga_code ) ) {
			$ga = '<figure class="op-tracker"><iframe>';
			$ga .= $ga_code;
			$ga .= '</iframe></figure>';

			$content .= $ga;
		}

		return $content;
	}

}
