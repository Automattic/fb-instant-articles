<?php

/**
 * Support class for Playbuzz
 *
 * @since 3.1.1
 *
 */
class Instant_Articles_Playbuzz {

	/**
	 * Init the compat layer
	 *
	 */
	function init() {
		add_filter( 'instant_articles_transformer_rules_loaded', array( 'Instant_Articles_Playbuzz', 'transformer_loaded' ) );
	}

	public static function transformer_loaded( $transformer ) {
		// Appends more rules to transformer
		$file_path = __DIR__ . '/playbuzz-rules-configuration.json';
		$configuration = file_get_contents( $file_path );
		$transformer->loadRules( $configuration );

		return $transformer;
	}
}
