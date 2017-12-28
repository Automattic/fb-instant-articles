<?php
/**
 * Facebook Instant Articles for WP.
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 *
 * @package default
 * @since 4.0
 */

class Instant_Articles_Rules {

	const QUERY_ARG = 'rules'; // Query argument that will trigger the AMP markup generation

	/**
	 * Adds the meta tag for Rules if enabled
	 *
	 * @since 4.0
	 */
	static function inject_link_rel() {

		// Transform the post to an Instant Article.
		$adapter = new Instant_Articles_Post( get_post() );

		if ( ! $adapter->should_submit_post() ) {
			return;
		}

		$url = $adapter->get_canonical_url();
		$url = add_query_arg( self::QUERY_ARG, '1', $url );

		?>
		<meta property="ia:rules_url" content="<?php echo esc_url($url); ?>">
		<?php
	}

	/**
	 * Generates the AMP markup if post has amp_markup
	 *
	 * NOTE: side-effect: function calls die() in the end.
	 *
	 * @since 4.0
	 */
	static function print_rules() {
		if ( ! (isset( $_GET[ self::QUERY_ARG ] ) && $_GET[ self::QUERY_ARG ]) ) {
			return;
		}

		echo file_get_contents(__DIR__.'/transformation-rules.json');

		die();
	}
}
