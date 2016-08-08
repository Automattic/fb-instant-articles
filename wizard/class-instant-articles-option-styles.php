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
		'description' => '<p>Assign your Instant Articles a custom style. To begin, customize a template using the Style Editor. Next, input the name of the style below.</p><p><strong>Note:</strong> If this field is left blank, the plugin will enable the “Default” style. Learn more about Instant Articles style options in the <a href="https://developers.facebook.com/docs/instant-articles/guides/design" target="_blank">Design Guide</a>.</p>',
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
