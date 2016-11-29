<?php
/**
 * Facebook Instant Articles for WP.
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 *
 * @package default
 */
require_once( dirname( __FILE__ ) . '/class-instant-articles-option-ads.php' );
require_once( dirname( __FILE__ ) . '/class-instant-articles-option-analytics.php' );
require_once( dirname( __FILE__ ) . '/class-instant-articles-option-fb-app.php' );
require_once( dirname( __FILE__ ) . '/class-instant-articles-option-fb-page.php' );
require_once( dirname( __FILE__ ) . '/class-instant-articles-option-publishing.php' );
require_once( dirname( __FILE__ ) . '/class-instant-articles-option-styles.php' );
require_once( dirname( __FILE__ ) . '/class-instant-articles-invalid-wizard-transition-exception.php' );
require_once( dirname( __FILE__ ) . '/class-instant-articles-wizard-fb-helper.php' );
require_once( dirname( __FILE__ ) . '/class-instant-articles-wizard-review-submission.php' );

use Facebook\InstantArticles\Client\Client;
use Facebook\InstantArticles\Client\ClientException;

/**
 * Set-up wizard state machine.
 *
 * @since 3.1
 * @see ./state_machine.txt
 */
class Instant_Articles_Wizard_State {

	// STATES
	const STATE_OVERVIEW = 'STATE_OVERVIEW';
	const STATE_APP_SETUP = 'STATE_APP_SETUP';
	const STATE_PAGE_SELECTION = 'STATE_PAGE_SELECTION';
	const STATE_STYLE_SELECTION = 'STATE_STYLE_SELECTION';
	const STATE_REVIEW_SUBMISSION = 'STATE_REVIEW_SUBMISSION';

	// TRANSITIONS
	const TRANSITION_START_WIZARD = 'TRANSITION_START_WIZARD';
	const TRANSITION_SET_UP_APP = 'TRANSITION_SET_UP_APP';
	const TRANSITION_SELECT_PAGE = 'TRANSITION_SELECT_PAGE';
	const TRANSITION_SELECT_STYLE = 'TRANSITION_SELECT_STYLE';
	const TRANSITION_EDIT_STYLE = 'TRANSITION_EDIT_STYLE';
	const TRANSITION_EDIT_PAGE = 'TRANSITION_EDIT_PAGE';
	const TRANSITION_EDIT_APP = 'TRANSITION_EDIT_APP';

	// WIZARD TIMELINE
	const TIMELINE_PAST = 'TIMELINE_PAST';
	const TIMELINE_CURRENT = 'TIMELINE_CURRENT';
	const TIMELINE_FUTURE = 'TIMELINE_FUTURE';


	/**
	 * Transition vectors, format:
	 *
	 * array(
	 *   ORIGINAL_STATE => array(
	 *     NEW_STATE => TRANSITION_NAME,
	 *     ...
	 *   ),
	 *   ...
	 * )
	 *
	 */
	public static $transition_vectors = array(
		self::STATE_OVERVIEW => array (
			self::STATE_APP_SETUP => self::TRANSITION_START_WIZARD
		),
		self::STATE_APP_SETUP => array (
			self::STATE_PAGE_SELECTION => self::TRANSITION_SET_UP_APP
		),
		self::STATE_PAGE_SELECTION => array (
			self::STATE_STYLE_SELECTION => self::TRANSITION_SELECT_PAGE,
			self::STATE_APP_SETUP => self::TRANSITION_EDIT_APP
		),
		self::STATE_STYLE_SELECTION => array (
			self::STATE_REVIEW_SUBMISSION => self::TRANSITION_SELECT_STYLE,
			self::STATE_PAGE_SELECTION => self::TRANSITION_EDIT_PAGE,
			self::STATE_APP_SETUP => self::TRANSITION_EDIT_APP
		),
		self::STATE_REVIEW_SUBMISSION => array (
			self::STATE_STYLE_SELECTION => self::TRANSITION_EDIT_STYLE,
			self::STATE_PAGE_SELECTION => self::TRANSITION_EDIT_PAGE,
			self::STATE_APP_SETUP => self::TRANSITION_EDIT_APP
		),
	);

	/**
	 * Order of states on the wizard
	 */
	public static $timeline = array(
		self::STATE_OVERVIEW => 0,
		self::STATE_APP_SETUP => 1,
		self::STATE_PAGE_SELECTION => 2,
		self::STATE_STYLE_SELECTION => 3,
		self::STATE_REVIEW_SUBMISSION => 4
	);

	/**
	 * Gets the timeline position for a given state.
	 *
	 * @param string $state The state constant
	 * @return string The timeline constant (PAST, CURRENT or FUTURE)
	 */
	public static function get_timeline_position( $state ) {
		$current_state = self::get_current_state();

		if ( self::$timeline[ $current_state ] > self::$timeline[ $state ] ) {
			return self::TIMELINE_PAST;
		} elseif ( $state === $current_state ) {
			if (
				$current_state === self::STATE_REVIEW_SUBMISSION &&
				Instant_Articles_Wizard_Review_Submission::getReviewSubmissionStatus() === Instant_Articles_Wizard_Review_Submission::STATUS_APPROVED
			) {
				return self::TIMELINE_PAST;
			}
			return self::TIMELINE_CURRENT;
		} else {
			return self::TIMELINE_FUTURE;
		}
	}

	/**
	 * Retrieves the current state of the wizard
	 *
	 * @return string The state constant for the current state
	 */
	public static function get_current_state() {

		$option = get_option( 'instant-articles-current-state', null );
		if ( in_array( $option, array_keys( self::$transition_vectors ) ) ) {
			return $option;
		}

		// Legacy compatibility - calculate state from existing setup step
		$fb_page_settings = Instant_Articles_Option_FB_Page::get_option_decoded();
		$fb_app_settings = Instant_Articles_Option_FB_App::get_option_decoded();

		$app_set_up = ! empty( $fb_app_settings['app_id'] ) && ! empty( $fb_app_settings['app_secret'] );
		$user_logged_in = ! empty( $fb_app_settings['user_access_token'] );
		$page_selected = ! empty( $fb_page_settings['page_id'] ) && ! empty( $fb_page_settings['page_name'] );
		$review_submitted = Instant_Articles_Wizard_Review_Submission::getReviewSubmissionStatus() === Instant_Articles_Wizard_Review_Submission::STATUS_NOT_SUBMITTED;

		if ( ! $app_set_up ) {
			return self::STATE_OVERVIEW;
		} elseif ( ! $user_logged_in && ! $page_selected ) {
			return self::STATE_APP_SETUP;
		} elseif ( ! $page_selected ) {
			return self::STATE_PAGE_SELECTION;
		} elseif ( ! $review_submitted ) {
			return self::STATE_REVIEW_SUBMISSION;
		} else {
			return self::STATE_STYLE_SELECTION;
		}

	}

	/**
	 * Calculates the transition name for 2 given states
	 *
	 * @param string $from Original state constant
	 * @param string $to New state constant
	 * @return string The transition constant or null for invalid transitions
	 */
	public static function get_transition( $from, $to ) {
		if ( isset ( self::$transition_vectors[ $from ][ $to ] ) ) {
			return self::$transition_vectors[ $from ][ $to ];
		}
		return null;
	}


	/**
	 * Executes the transition from the current state to a new state
	 *
	 * @param string $new_state Constant for the final state of the transition
	 * @param mixed[] $params Parameters for the transition
	 * @throws Instant_Articles_Invalid_Wizard_Transition_Exception if an invalid transition is attempted
	 */
	public static function do_transition( $new_state, $params = array() ) {
		$current_state = self::get_current_state();
		$transition = self::get_transition( $current_state, $new_state );

		if ( $transition === null ) {
			throw new Instant_Articles_Invalid_Wizard_Transition_Exception( $current_state, $new_state );
		}

		switch ( $transition ) {
			case self::TRANSITION_START_WIZARD:
				return self::transition_start_wizard();

			case self::TRANSITION_SET_UP_APP:
				return self::transition_set_up_app( $params[ 'app_id' ], $params[ 'app_secret'], $params[ 'user_access_token' ] );

			case self::TRANSITION_SELECT_PAGE:
				// TODO: validate params
				return self::transition_select_page( $params[ 'page_id'] );
			case self::TRANSITION_SELECT_STYLE:
				// TODO: validate params
				return self::transition_select_style();
			case self::TRANSITION_EDIT_APP:
				return self::transition_edit_app();
			case self::TRANSITION_EDIT_PAGE:
				return self::transition_edit_page();
			case self::TRANSITION_EDIT_STYLE:
				return self::transition_edit_style();
		}
	}

	/**
	 * Claims the URL for this site.
	 */
	public static function claim_url() {
		$fb_app_settings = Instant_Articles_Option_FB_App::get_option_decoded();
		$fb_page_settings = Instant_Articles_Option_FB_Page::get_option_decoded();

		if ( ! $fb_app_settings[ 'app_id' ] ) {
			throw new InvalidArgumentException( 'Missing app_id for claiming the URL.' );
		}
		if ( ! $fb_app_settings[ 'app_secret' ] ) {
			throw new InvalidArgumentException( 'Missing app_secret for claiming the URL.' );
		}
		if ( ! $fb_page_settings[ 'page_access_token' ] ) {
			throw new InvalidArgumentException( 'Missing page_access_token for claiming the URL.' );
		}
		if ( ! $fb_page_settings[ 'page_id' ] ) {
			throw new InvalidArgumentException( 'Missing page_id for claiming the URL.' );
		}

		$client = Client::create(
			$fb_app_settings[ 'app_id' ],
			$fb_app_settings[ 'app_secret' ],
			$fb_page_settings[ 'page_access_token' ],
			$fb_page_settings[ 'page_id' ]
		);


		// We need the home URL without the protocol for claiming
		$url = preg_replace( '/^https?:\/\//i', '', esc_url_raw( home_url( '/' ) ) );

		try {
			$client->claimURL( $url );
		} catch (Exception $e) {
			// Here we override the error message to give an actionable
			// instruction to the user that is specific for WordPress.
			throw new Exception("Could not automatically claim the URL for this site, please claim it manually on your Page's Publishing Tools.");
		}
	}

	//---------------------------
	// Transition implementations
	//---------------------------

	/**
	 * Transition for when starting the wizard from overview.
	 */
	private static function transition_start_wizard() {
		return update_option( 'instant-articles-current-state', self::STATE_APP_SETUP );
	}

	/**
	 * Transition for when the user inputs the app info and logs in with it's personal account.
	 */
	private static function transition_set_up_app( $app_id, $app_secret, $user_access_token ) {
		if ( ! $app_id ) {
			throw new InvalidArgumentException( 'Missing App ID when authenticating the plugin' );
		}
		if ( ! $app_secret ) {
			throw new InvalidArgumentException( 'Missing App Secret when authenticating the plugin' );
		}
		if ( ! $user_access_token ) {
			throw new InvalidArgumentException( 'Missing Access Token when authenticating the plugin' );
		}

		Instant_Articles_Option_FB_App::update_option( array(
			'app_id' => $app_id,
			'app_secret' => $app_secret,
			'user_access_token' => $user_access_token
		) );
		return update_option( 'instant-articles-current-state', self::STATE_PAGE_SELECTION );
	}

	/**
	 * Transition for when the user selects the page to connect to.
	 *
	 * This transition stores page info including the long-lived access token information.
	 */
	private static function transition_select_page( $page_id ) {
		if ( ! $page_id ) {
			throw new InvalidArgumentException( 'Missing Page ID when selcting the page' );
		}

		$fb_helper = new Instant_Articles_Wizard_FB_Helper();
		$pages = $fb_helper->get_pages();

		if ( ! $pages[ $page_id ] ) {
			throw new InvalidArgumentException( 'Invalid Page ID when selcting the page' );
		}
		if ( ! $pages[ $page_id ][ 'supports_instant_articles'] ) {
			throw new InvalidArgumentException( 'Selected page is not signed up to Instant Articles' );
		}

		Instant_Articles_Option_FB_Page::update_option( $pages[ $page_id ] );

		// Update the option before claiming the URL.
		$success = update_option( 'instant-articles-current-state', self::STATE_STYLE_SELECTION );

		// You should always claim the URL after updating the FB Page option so the fb:pages meta tag is rendered.
		if ( $success ) {
			self::claim_url();
		}

		return $success;
	}

	/**
	 * Transition for when the user confirms he has selected the style for the articles.
	 */
	private static function transition_select_style() {
		return update_option( 'instant-articles-current-state', self::STATE_REVIEW_SUBMISSION );
	}

	/**
	 * Transition for when the user decides to change the FB App.
	 */
	private static function transition_edit_app() {
		Instant_Articles_Option_FB_App::delete_option();
		Instant_Articles_Option_FB_Page::delete_option();
		return update_option( 'instant-articles-current-state', self::STATE_APP_SETUP );
	}

	/**
	 * Transition for when the user decides to change the selected page.
	 */
	private static function transition_edit_page() {
		Instant_Articles_Option_FB_Page::delete_option();

		// For backwards compatibility, we transition one step back
		// for users of the old versions that selected a page but the
		// plugin didn't save the user access token. In this case
		// we need the user to log in again.
		$fb_app_settings = Instant_Articles_Option_FB_App::get_option_decoded();
		$user_logged_in = ! empty( $fb_app_settings['user_access_token'] );
		if ( ! $user_logged_in ) {
				return update_option( 'instant-articles-current-state', self::STATE_APP_SETUP );
		}

		return update_option( 'instant-articles-current-state', self::STATE_PAGE_SELECTION );
	}

	/**
	 * Transition for when the user decides to edit the style.
	 */
	private static function transition_edit_style() {
		return update_option( 'instant-articles-current-state', self::STATE_STYLE_SELECTION );
	}

}
