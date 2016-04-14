<?php
/**
 * Facebook Instant Articles for WP.
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 *
 * @package default
 */

use Facebook\InstantArticles\Client\Client;
use Facebook\InstantArticles\Client\InstantArticleStatus;
use Facebook\InstantArticles\Client\ServerMessage;
use Facebook\Exceptions\FacebookResponseException;

/**
 * Class responsible for drawing the meta box on the post edit page
 *
 * @since 0.1
 */
class Instant_Articles_Meta_Box {

	/**
	 * Initiator for Metabox.
	 */
	public static function init() {
		add_action( 'add_meta_boxes', array( 'Instant_Articles_Meta_Box', 'register_meta_box' ) );
		add_action(
			'wp_ajax_instant_articles_meta_box',
			array( 'Instant_Articles_Meta_Box', 'render_meta_box' )
		);
	}

	/**
	 * Register Meta Box renderer.
	 */
	public static function register_meta_box() {
		add_meta_box(
			'instant_article_meta_box',
			'Facebook Instant Articles',
			array( 'Instant_Articles_Meta_Box', 'render_meta_box_loader' ),
			'post',
			'normal',
			'default'
		);
	}

	/**
	 * Includes the template for Metabox.
	 *
	 * @param Post $post the post request content.
	 */
	public static function render_meta_box_loader( $post ) {
		include( dirname( __FILE__ ) . '/meta-box-loader-template.php' );
	}

	/**
	 * Renderer for the Metabox.
	 */
	public static function render_meta_box() {
		$post = get_post( intval( filter_input( INPUT_POST, 'post_ID' ) ) );
		$adapter = new Instant_Articles_Post( $post );
		$article = $adapter->to_instant_article();
		$canonical_url = $adapter->get_canonical_url();
		$submission_status = null;
		$published = 'publish' === $post->post_status;

		Instant_Articles_Settings::menu_items();
		$settings_page_href = Instant_Articles_Settings::get_href_to_settings_page();

		try {
			$fb_app_settings = Instant_Articles_Option_FB_App::get_option_decoded();
			$fb_page_settings = Instant_Articles_Option_FB_Page::get_option_decoded();

			if ( isset( $fb_app_settings['app_id'] )
				&& isset( $fb_app_settings['app_secret'] )
				&& isset( $fb_page_settings['page_access_token'] )
				&& isset( $fb_page_settings['page_id'] ) ) {
				// Instantiate a new Client to get the status of this article.
				$client = Client::create(
					$fb_app_settings['app_id'],
					$fb_app_settings['app_secret'],
					$fb_page_settings['page_access_token'],
					$fb_page_settings['page_id']
				);

				// Grab the latest status of this article and display.
				$article_id = $client->getArticleIDFromCanonicalURL( $canonical_url );
				$submission_status = $client->getLastSubmissionStatus( $article_id );
			}
		} catch ( FacebookResponseException $e ) {
			$submission_status = null;
		}

		include( dirname( __FILE__ ) . '/meta-box-template.php' );

		die();
	}
}
