<?php
/**
 * Facebook Instant Articles for WP.
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 *
 * @package default
 */

require_once( dirname( __FILE__ ) . '/class-instant-articles-option.php' );
require_once( dirname(dirname( __FILE__ )) . '/class-instant-articles-amp-markup.php' );

/**
 * AMP Generation Settings
 */
class Instant_Articles_Option_Amp extends Instant_Articles_Option {

	const OPTION_KEY = 'instant-articles-option-amp';

	public static $sections = array(
		'title' => 'AMP Markup Generation',
		'description' => '<p>Settings to control the IA to AMP conversion, check the <a href="https://github.com/facebook/facebook-instant-articles-sdk-extensions-in-php" target="_blank">project\'s repository</a> for more info.</p>',
	);

	public static $fields = array(

		Instant_Articles_Amp_Markup::SETTING_AMP_MARKUP => array(
			'label' => 'Enable Markup',
			'description' => 'With this option enabled, posts will also be available in AMP markup',
			'render' => 'checkbox',
			'default' => false,
			'checkbox_label' => 'Enable AMP markup generation',
		),

		Instant_Articles_Amp_Markup::SETTING_STYLE => array(
			'label' => 'Stylesheet',
			'description' => 'Please paste the contents of the Style JSON file (<a href="https://developers.facebook.com/docs/instant-articles/other-formats#style" target="_blank">downloaded from the Publishing Tools</a>)',
			'render' => 'textarea',
		),

		Instant_Articles_Amp_Markup::SETTING_DL_MEDIA => array(
			'label' => 'Image sizing',
			'description' => 'With this option enabled, images in other servers/domains will be downloaded to get it\'s width and height. Learn more in <a href="https://developers.facebook.com/docs/instant-articles/other-formats#https://developers.facebook.com/docs/instant-articles/other-formats#advanced-media-element-sizing" target="_blank"> the official docs</a>',
			'render' => 'checkbox',
			'checkbox_label' => 'Download Images hosted elsewhere',
			'default' => false,
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
			self::$sections,
			self::$fields
		);
	}
}
