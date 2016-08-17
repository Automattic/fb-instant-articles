<?php
/**
 * Facebook Instant Articles for WP.
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 *
 * @package default
 */
require_once( dirname( __FILE__ ) . '/class-instant-articles-option-fb-app.php' );
require_once( dirname( __FILE__ ) . '/class-instant-articles-option-fb-page.php' );

use Facebook\InstantArticles\Client\Client;

/**
 * Class responsible for functionality and rendering of the review settings
 *
 * @since 3.1
 */
class Instant_Articles_Wizard_Review_Submission {

	const MIN_ARTICLES = 5;

	const STATUS_REJECTED = 'REJECTED';
	const STATUS_APPROVED = 'APPROVED';
	const STATUS_PENDING = 'PENDING';
	const STATUS_NOT_SUBMITTED = 'NOT_SUBMITTED';

	public static function getArticlesForReview() {
		$post_types = apply_filters( 'instant_articles_post_types', array( 'post' ) );

		// Cap the number of articles returned to
		// 100 because of performance concerns.
		return wp_get_recent_posts(
		 	array(
				'numberposts' => min( self::MIN_ARTICLES, 100 ),
				'post_type' => $post_types
			),
			'OBJECT'
		);
	}

	public static function getPageID() {
		if ( ! static::isPageSet() ) {
			return null;
		}
		$fb_page_settings = Instant_Articles_Option_FB_Page::get_option_decoded();
		return $fb_page_settings['page_id'];
	}

	public static function isPageSet() {
		$fb_app_settings = Instant_Articles_Option_FB_App::get_option_decoded();
		$fb_page_settings = Instant_Articles_Option_FB_Page::get_option_decoded();

		if ( isset( $fb_app_settings['app_id'] )
			&& isset( $fb_app_settings['app_secret'] )
			&& isset( $fb_page_settings['page_access_token'] )
			&& isset( $fb_page_settings['page_id'] ) ) {
			return true;
		}

		return false;
	}

	public static function getClient() {
		if ( ! static::isPageSet() ) {
			return null;
		}

		$fb_app_settings = Instant_Articles_Option_FB_App::get_option_decoded();
		$fb_page_settings = Instant_Articles_Option_FB_Page::get_option_decoded();

		$client = Client::create(
			$fb_app_settings['app_id'],
			$fb_app_settings['app_secret'],
			$fb_page_settings['page_access_token'],
			$fb_page_settings['page_id'],
			false
		);

		return $client;
	}

	public static function getReviewSubmissionStatus() {
		if ( ! static::isPageSet() ) {
			return null;
		}
		return static::getClient()->getReviewSubmissionStatus();
	}

	public static function getArticlesURLs() {
		if ( ! static::isPageSet() ) {
			return null;
		}
		return static::getClient()->getArticlesURLs(static::MIN_ARTICLES);
	}
}
