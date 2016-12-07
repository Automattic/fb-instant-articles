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
	function init( $is_playbuzz_active ) {
		add_filter( 'instant_articles_transformer_rules_loaded', array( 'Instant_Articles_Playbuzz', 'transformer_loaded' ) );
		if ( $is_playbuzz_active ) {
			add_filter( 'pre_option_playbuzz', function( $option ) {
				if ( is_feed( 'instant-articles' ) ) {
					$option['embeddedon'] = 'all';
				}
				return $option;
			} );
		}
	}

	public static function transformer_loaded( $transformer ) {
		// Appends more rules to transformer
		$file_path = plugin_dir_path( __FILE__ ) . 'playbuzz-rules-configuration.json';
		$configuration = file_get_contents( $file_path );
		$transformer->loadRules( $configuration );

		return $transformer;
	}
}
