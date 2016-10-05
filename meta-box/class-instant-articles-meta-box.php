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
		add_action(
			'wp_ajax_instant_articles_force_submit',
			array( 'Instant_Articles_Meta_Box', 'force_submit' )
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
	public static function force_submit() {
		$post_id = intval( filter_input( INPUT_POST, 'post_ID' ) );
		$force = filter_input( INPUT_POST, 'force' ) === 'true';
		update_post_meta( $post_id, Instant_Articles_Publisher::FORCE_SUBMIT_KEY, $force );
		Instant_Articles_Publisher::submit_article( $post_id, get_post( $post_id ) );
	}

	/**
	 * Renderer for the Metabox.
	 */
	public static function render_meta_box() {
		$post_id = intval( filter_input( INPUT_POST, 'post_ID' ) );
		$post = get_post( $post_id );
		$adapter = new Instant_Articles_Post( $post );
		$article = $adapter->to_instant_article();
		$canonical_url = $adapter->get_canonical_url();
		$submission_status = null;
		$published = 'publish' === $post->post_status;
		$force_submit = get_post_meta( $post_id, Instant_Articles_Publisher::FORCE_SUBMIT_KEY, true );

		Instant_Articles_Wizard::menu_items();
		$settings_page_href = Instant_Articles_Wizard::get_url();

		$publishing_settings = Instant_Articles_Option_Publishing::get_option_decoded();
		$publish_with_warnings = $publishing_settings[ 'publish_with_warnings' ];
		if ( $published ) {
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

					$submission_status_id = get_post_meta( $post_id, Instant_Articles_Publisher::SUBMISSION_ID_KEY, true );
					if ( ! empty( $submission_status_id ) ) {
						$submission_status = $client->getSubmissionStatus( $submission_status_id );
					} else {
						// Grab the latest status of this article and display.
						$article_id = $client->getArticleIDFromCanonicalURL( $canonical_url );
						$submission_status = $client->getLastSubmissionStatus( $article_id );
					}
				}
			} catch ( FacebookResponseException $e ) {
				$submission_status = null;
			}
		}

		include( dirname( __FILE__ ) . '/meta-box-template.php' );

		die();
	}
}
