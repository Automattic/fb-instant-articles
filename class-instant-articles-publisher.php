<?php
/**
 * Facebook Instant Articles for WP.
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 *
 * @package default
 */

use Facebook\InstantArticles\Client\Client;
use Facebook\Facebook;

/**
 * Class responsible for drawing the meta box on the post edit page
 *
 * @since 0.1
 */
class Instant_Articles_Publisher {

	/**
	 * Key to store the submission status ID on meta data
	 */
	 const SUBMISSION_ID_KEY = 'instant_articles_submission_id';

	/**
	 * Inits publisher.
	 */
	public static function init() {
		add_action( 'save_post', array( 'Instant_Articles_Publisher', 'submit_article' ), 10, 2 );
	}

	/**
	 * Submits article to Instant Articles.
	 *
	 * @param string $post_id The identifier of post.
	 * @param Post   $post The WP Post.
	 */
	public static function submit_article( $post_id, $post ) {

		// Don't process if this is just a revision or an autosave.
		if ( wp_is_post_revision( $post ) || wp_is_post_autosave( $post ) ) {
			return;
		}

		// Don't process if this post is not published
		if ( 'publish' !== $post->post_status ) {
			return;
		}

		// Only process posts
		$post_types = apply_filters( 'instant_articles_post_types', array( 'post' ) );
		if ( ! in_array( $post->post_type, $post_types ) ) {
			return;
		}

		// Transform the post to an Instant Article.
		$adapter = new Instant_Articles_Post( $post );

		$article = $adapter->to_instant_article();

		// Skip empty articles or articles missing title.
		// This is important because the save_post action is also triggered by bulk updates, but in this case
		// WordPress does not load the content field from DB for performance reasons. In this case, articles
		// will be empty here, despite of them actually having content.
		if ( count( $article->getChildren() ) === 0 || ! $article->getHeader() || ! $article->getHeader()->getTitle() ) {
			return;
		}

		// Instantiate an API client.
		try {
			$fb_app_settings = Instant_Articles_Option_FB_App::get_option_decoded();
			$fb_page_settings = Instant_Articles_Option_FB_Page::get_option_decoded();
			$publishing_settings = Instant_Articles_Option_Publishing::get_option_decoded();

			$dev_mode = isset( $publishing_settings['dev_mode'] )
				? ( $publishing_settings['dev_mode'] ? true : false )
				: false;

			if ( isset( $fb_app_settings['app_id'] )
				&& isset( $fb_app_settings['app_secret'] )
				&& isset( $fb_page_settings['page_access_token'] )
				&& isset( $fb_page_settings['page_id'] ) ) {

				$client = Client::create(
					$fb_app_settings['app_id'],
					$fb_app_settings['app_secret'],
					$fb_page_settings['page_access_token'],
					$fb_page_settings['page_id'],
					$dev_mode
				);

				// Don't publish posts with password protection
				if ( post_password_required( $post ) ) {
					// Unpublishes if already published and from now on it started to have password protection
					$client->removeArticle( $article->getCanonicalURL() );
					delete_post_meta( $post_id, self::SUBMISSION_ID_KEY );
					return;
				}

				// Don't process if contains warnings and blocker flag for transformation warnings is turned on.
				if ( count( $adapter->transformer->getWarnings() ) > 0
				  && isset( $publishing_settings['block_publish_with_warnings'] )
					&& $publishing_settings['block_publish_with_warnings'] ) {

					// Unpublishes if already published
					$client->removeArticle( $article->getCanonicalURL() );
					delete_post_meta( $post_id, self::SUBMISSION_ID_KEY );
					return;
				}

				if ( $dev_mode ) {
					$published = false;
				} else {
					// Any publish status other than 'publish' means draft for the Instant Article.
					$published = true;
				}

				try {
					// Import the article.
					$submission_id = $client->importArticle( $article, $published );
					update_post_meta( $post_id, self::SUBMISSION_ID_KEY, $submission_id );
				} catch ( Exception $e ) {
					// Try without taking live for pages not yet reviewed.
					$submission_id = $client->importArticle( $article, false );
					update_post_meta( $post_id, self::SUBMISSION_ID_KEY, $submission_id );
				}
			}
		} catch ( Exception $e ) {
			Logger::getLogger( 'instantarticles-wp-plugin' )->error(
				'Unable to submit article.',
				$e->getTraceAsString()
			);
		}
	}
}
