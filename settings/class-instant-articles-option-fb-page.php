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
 * FB Page configuration.
 */
class Instant_Articles_Option_FB_Page extends Instant_Articles_Option {

	const OPTION_KEY = IA_PLUGIN_TEXT_DOMAIN . '-option-fb-page';

	const SECTIONS = array(
		'title' => '',
		'description' => '',
	);

	const FIELDS = array(

	'page_id' => array(
		'visible' => false,
		'label' => 'Page ID',
		'default' => '',
	),

	'page_name' => array(
		'visible' => false,
		'label' => 'Page Name',
		'default' => '',
	),

	'page_access_token' => array(
		'visible' => false,
		'label' => 'Page Access Token',
		'default' => '',
	),

	'page_access_token_expiration' => array(
		'visible' => false,
		'label' => 'Page Token Expiration',
		'default' => '',
	),

	);

	/**
	 * Constructor.
	 *
	 * @since 0.4
	 */
	public function __construct() {
		$this->options_manager = new parent(
			self::OPTION_KEY,
			self::SECTIONS,
			self::FIELDS,
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
