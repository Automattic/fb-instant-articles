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

	const STEP_STATE_IDLE = 'idle';
	const STEP_STATE_CURRENT = 'current';
	const STEP_STATE_COMPLETE = 'complete';

	const STEP_ID_IA_SIGNUP = 'ia-signup';
	const STEP_ID_APP_SETUP = 'app-setup';
	const STEP_ID_PAGE_SELECTION = 'page-selection';
	const STEP_ID_CLAIM_URL = 'claim-url';
	const STEP_ID_NEXT_STEPS = 'next-steps';

	/**
	 * Wizard steps.
	 *
	 * @var array $steps The steps for wizard
	 */
	private static $steps = array(
		1 => self::STEP_ID_IA_SIGNUP,
		2 => self::STEP_ID_APP_SETUP,
		3 => self::STEP_ID_PAGE_SELECTION,
		4 => self::STEP_ID_CLAIM_URL,
		5 => self::STEP_ID_NEXT_STEPS,
	);

	/**
	 * Returns the current step for the wizard.
	 *
	 * @since 0.5
	 */
	public static function get_current_step_id() {

		$wizard_settings = Instant_Articles_Option_Wizard::get_option_decoded();
		$fb_page_settings = Instant_Articles_Option_FB_Page::get_option_decoded();
		$fb_app_settings = Instant_Articles_Option_FB_App::get_option_decoded();

		if ( ! isset( $wizard_settings['sign_up'] ) ||  empty( $wizard_settings['sign_up'] ) ) {
			return self::STEP_ID_IA_SIGNUP;
		} elseif ( empty( $fb_app_settings['app_id'] ) || empty( $fb_app_settings['app_secret'] )  ) {
			return self::STEP_ID_APP_SETUP;
		} elseif ( empty( $fb_page_settings['page_id'] ) || empty( $fb_page_settings['page_name'] )  ) {
			return self::STEP_ID_PAGE_SELECTION;
		} elseif ( ! isset( $wizard_settings['claimed_url'] ) || empty( $wizard_settings['claimed_url'] ) ) {
			return self::STEP_ID_CLAIM_URL;
		} else {
			return self::STEP_ID_NEXT_STEPS;
		}
	}


	/**
	 * Returns the current state of a step.
	 *
	 * @param int $step_id The step id of wizard.
	 * @since 0.5
	 */
	public static function get_state_for_step( $step_id ) {
		$current_step_id = self::get_current_step_id();

		$step_number = array_search( $step_id, self::$steps );
		$current_step_number = array_search( $current_step_id, self::$steps );

		if ( $step_number === $current_step_number ) {
			return self::STEP_STATE_CURRENT;
		} elseif ( $step_number > $current_step_number ) {
			return self::STEP_STATE_IDLE;
		} else {
			return self::STEP_STATE_COMPLETE;
		}
	}
}
