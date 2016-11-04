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
class Instant_Articles_Option_Configuration_Flow extends Instant_Articles_Option {

	const OPTION_KEY = 'instant-articles-option-configuration-flow';

	public static $sections = array(
		'title' => 'Configuration Flow',
		'description' => '<p>This will set the configuration flow to be used. </p><p><b>Open Graph: </b>This setup allows to configure this plugin to setup a meta-tag into every single article, then, on the moment of share, it will be scraped by Facebook crawler.</p></p><p><b>API: </b>This configuration allows to configure an APP into Facebook and will use the push strategy to make articles available as Instant Articles.</p>',
	);

	public static $fields = array(

		'configuration_flow' => array(
			'label' => 'Configuration Flow',
			'render' => 'select',
			'select_options' => array(
				'opengraph' => 'Open Graph',
				'api' => 'API',
			),
			'default' => 'opengraph',
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
