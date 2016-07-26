<?php
/**
 * Facebook Instant Articles for WP.
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 *
 * @package default
 */

require_once( dirname( __FILE__ ) . '/class-instant-articles-invalid-wizard-transition-exception.php' );

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
	 * Order of states for breadcrumbs creation
	 */
	public static $breadcrumbs_order = array(
		self::STATE_APP_SETUP => 1,
		self::STATE_PAGE_SELECTION => 2,
		self::STATE_STYLE_SELECTION => 3,
		self::STATE_REVIEW_SUBMISSION => 4
	);


	/**
	 * Retrieves the current state of the wizard
	 *
	 * @return string The state constant for the current state
	 */
	public static function get_current_state() {
		// TODO: implement (this is a stub)
		return get_option( 'instant-articles-stub-current-state', self::STATE_OVERVIEW );
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
				// TODO: validate params
				return self::transition_start_wizard();
			case self::TRANSITION_SET_UP_APP:
				// TODO: validate params
				return self::transition_set_up_app( $params[ 'app_id' ], $params[ 'app_secret '] );
			case self::TRANSITION_SELECT_PAGE:
				// TODO: validate params
				return self::transition_select_page( $params[ 'page_id'] );
			case self::TRANSITION_SELECT_STYLE:
				// TODO: validate params
				return self::transition_select_style( $params[ 'article_style'] );
			case self::TRANSITION_EDIT_APP:
				return self::transition_edit_app();
			case self::TRANSITION_EDIT_PAGE:
				return self::transition_edit_page();
			case self::TRANSITION_EDIT_STYLE:
				return self::transition_edit_style();
		}
	}

	//---------------------------
	// Transition implementations
	//---------------------------

	private static function transition_start_wizard() {
		// TODO: implement (this is a stub)
		return update_option( 'instant-articles-stub-current-state', self::STATE_APP_SETUP );
	}

	private static function transition_set_up_app( $app_id, $app_secret ) {
		// TODO: implement (this is a stub)
		return update_option( 'instant-articles-stub-current-state', self::STATE_PAGE_SELECTION );
	}

	private static function transition_select_page( $page_id ) {
		// TODO: implement (this is a stub)
		return update_option( 'instant-articles-stub-current-state', self::STATE_STYLE_SELECTION );
	}

	private static function transition_select_style( $article_style ) {
		// TODO: implement (this is a stub)
		return update_option( 'instant-articles-stub-current-state', self::STATE_REVIEW_SUBMISSION );
	}

	private static function transition_edit_app() {
		// TODO: implement (this is a stub)
		return update_option( 'instant-articles-stub-current-state', self::STATE_APP_SETUP );
	}

	private static function transition_edit_page() {
		// TODO: implement (this is a stub)
		return update_option( 'instant-articles-stub-current-state', self::STATE_PAGE_SELECTION );
	}

	private static function transition_edit_style() {
		// TODO: implement (this is a stub)
		return update_option( 'instant-articles-stub-current-state', self::STATE_STYLE_SELECTION );
	}

}
