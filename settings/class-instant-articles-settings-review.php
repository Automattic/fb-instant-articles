<?php
/**
 * Facebook Instant Articles for WP.
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 *
 * @package default
 */

use Facebook\InstantArticles\Client\Client;

/**
 * Class responsible for functionality and rendering of the settings
 *
 * @since 0.4
 */
class Instant_Articles_Settings_Review {

	public static function getReviewSubmissionStatus() {

		$fb_app_settings = Instant_Articles_Option_FB_App::get_option_decoded();
		$fb_page_settings = Instant_Articles_Option_FB_Page::get_option_decoded();

		if ( isset( $fb_app_settings['app_id'] )
			&& isset( $fb_app_settings['app_secret'] )
			&& isset( $fb_page_settings['page_access_token'] )
			&& isset( $fb_page_settings['page_id'] ) ) {

			$client = Client::create(
				$fb_app_settings['app_id'],
				$fb_app_settings['app_secret'],
				$fb_page_settings['page_access_token'],
				$fb_page_settings['page_id'],
				false
			);

			return $client->getReviewSubmissionStatus();
		}
		return null;
	}

}
