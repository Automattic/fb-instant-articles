<?php

/**
 * Support class for Apester
 *
 * @since 3.3.2
 *
 */
class Instant_Articles_Apester {

	/**
	 * Init the compat layer
	 *
	 */
	function init() {
		add_filter( 'instant_articles_transformer_rules_loaded', array( 'Instant_Articles_Apester', 'transformer_loaded' ) );
	}

	public static function transformer_loaded( $transformer ) {
		// Appends more rules to transformer
		$file_path = __DIR__ . '/apester-rules-configuration.json';
		$configuration = file_get_contents( $file_path );
		$transformer->loadRules( $configuration );

		return $transformer;
	}
}
