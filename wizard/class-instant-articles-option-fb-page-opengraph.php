<?php
/**
 * Facebook Instant Articles for WP.
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 *
 * @package default
 * @since 4.0
 */

require_once( dirname( __FILE__ ) . '/class-instant-articles-option.php' );

/**
 * FB Page configuration.
 */
class Instant_Articles_Option_FB_Page_OpenGraph extends Instant_Articles_Option {

	const OPTION_KEY = 'instant-articles-option-fb-page_opengraph';

	public static $sections = array(
		'title' => '',
		'description' => '',
	);

	public static $fields = array(

	'page_id' => array(
		'visible' => false,
		'label' => 'Page ID',
		'default' => '',
	),

	);

	/**
	 * Constructor.
	 *
	 * @since 4.0
	 */
	public function __construct() {
		$this->options_manager = new parent(
			self::OPTION_KEY,
			self::$sections,
			self::$fields,
			/**
			 * Register this Option on a specific page group (used as the first
			 * argument of `register_setting()` and called by `settings_fields()`).
			 *
			 * @since 0.5
			 */
			Instant_Articles_Option::PAGE_OPTION_GROUP_WIZARD
		);
	}
}
