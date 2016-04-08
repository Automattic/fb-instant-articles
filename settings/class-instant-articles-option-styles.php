<?php
/**
 * Facebook Instant Articles for WP.
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 *
 * @package default
 */

require_once( dirname( __FILE__ ) . '/class-instant-articles-option.php' );

/**
 * Configuration class for Ads.
 */
class Instant_Articles_Option_Styles extends Instant_Articles_Option {

	const OPTION_KEY = 'instant-articles-option-styles';

	public static $sections = array(
		'title' => 'Styles',
		'description' => 'This is where you manage your styles',
	);

	public static $fields = array(

		'article_style' => array(
			'label' => 'Article Style',
			'default' => 'default',
		),

	);

	/**
	 * Constructor.
	 *
	 * @since 0.4
	 */
	public function __construct() {
		parent::__construct(
			self::OPTION_KEY,
			self::$sections,
			self::$fields
		);
	}
}
