<?php
/**
 * Facebook Instant Articles for WP.
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 *
 * @package default
 */

/**
 * Class responsible for functionality and rendering of the Setup Wizard
 *
 * @since 0.5
 */
class Instant_Articles_Settings_Wizard {

	const STEP_ID_APP_SETUP = 'app-setup';
	const STEP_ID_PAGE_SELECTION = 'page-selection';
	const STEP_ID_DONE = 'done';

	/**
	 * Wizard steps.
	 *
	 * @var array $steps The steps for wizard
	 */
	private static $steps = array(
		1 => self::STEP_ID_APP_SETUP,
		2 => self::STEP_ID_PAGE_SELECTION,
		2 => self::STEP_ID_DONE,
	);

	/**
	 * Returns the current step for the wizard.
	 *
	 * @since 0.5
	 */
	public static function get_current_step_id() {

		$fb_page_settings = Instant_Articles_Option_FB_Page::get_option_decoded();
		$fb_app_settings = Instant_Articles_Option_FB_App::get_option_decoded();

		if ( empty( $fb_app_settings['app_id'] ) || empty( $fb_app_settings['app_secret'] )  ) {
			return self::STEP_ID_APP_SETUP;
		} elseif ( empty( $fb_page_settings['page_id'] ) || empty( $fb_page_settings['page_name'] )  ) {
			return self::STEP_ID_PAGE_SELECTION;
		} else {
			return self::STEP_ID_DONE;
		}
	}
}
