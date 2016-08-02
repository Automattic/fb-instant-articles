<?php
/**
 * Facebook Instant Articles for WP.
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 *
 * @package default
 */
require_once( dirname( __FILE__ ) . '/class-instant-articles-wizard-state.php' );
require_once( dirname( __FILE__ ) . '/class-instant-articles-wizard-fb-helper.php' );

/**
* Controller for Set-up Wizard
*
* @since 3.1
*/
class Instant_Articles_Wizard {

	public static function init() {
		add_action( 'admin_menu', array( 'Instant_Articles_Wizard', 'menu_items' ) );

		add_action(
			'wp_ajax_instant_articles_wizard_transition',
			array( 'Instant_Articles_Wizard', 'transition' )
		);

		add_action(
			'wp_ajax_instant_articles_wizard_save_app',
			array( 'Instant_Articles_Wizard', 'save_app' )
		);

		add_action(
			'wp_ajax_instant_articles_wizard_edit_app',
			array( 'Instant_Articles_Wizard', 'edit_app' )
		);

		add_action(
			'wp_ajax_instant_articles_wizard_is_page_signed_up',
			array( 'Instant_Articles_Wizard', 'is_page_signed_up' )
		);
	}

	public static function menu_items() {
		add_menu_page(
			'Instant Articles Setup Wizard',
			'Instant Articles',
			'manage_options',
			'instant-articles-wizard',
			array( 'Instant_Articles_Wizard', 'render' )
			,'dashicons-facebook-alt'
		);
		// Hack to let the URL visible to ajax handlers
		update_option( 'instant-articles-wizard-url', menu_page_url( 'instant-articles-wizard', false) );
	}

	public static function get_url() {
		$url = menu_page_url( 'instant-articles-wizard', false );

		// Needed when calling from ajax
		if ( ! $url ) {
			$url = get_option( 'instant-articles-wizard-url' );
		}

		return $url;
	}

	public static function transition() {
		$new_state = filter_input( INPUT_POST, 'new_state' );
		$params = filter_input( INPUT_POST, 'params' );

		$params = json_decode( $params, true );

		if ( $new_state === 'RESET' ) {
			delete_option( 'instant-articles-current-state' );
		}
		else {
			Instant_Articles_Wizard_State::do_transition( $new_state, $params );
		}

		self::render( true );
		die();
	}

	/**
	 * Saves the App ID and App Secret.
	 *
	 * That does not trigger a state transition, as the state transition will
	 * only happen when the user logs in and we grab the access token.
	 */
	public static function save_app() {
		$current_state = Instant_Articles_Wizard_State::get_current_state();
		if ( $current_state !== Instant_Articles_Wizard_State::STATE_APP_SETUP ) {
			die();
		}

		$app_id = filter_input( INPUT_POST, 'app_id' );
		$app_secret = filter_input( INPUT_POST, 'app_secret' );

		Instant_Articles_Option_FB_App::update_option( array(
			'app_id' => $app_id,
			'app_secret' => $app_secret
		) );

		self::render( true );
		die();
	}

	/**
	 * Edits the App ID and App Secret within the APP_SETUP state.
	 */
	public static function edit_app() {
		$current_state = Instant_Articles_Wizard_State::get_current_state();
		if ( $current_state !== Instant_Articles_Wizard_State::STATE_APP_SETUP ) {
			die();
		}

		Instant_Articles_Option_FB_App::delete_option();

		self::render( true );
		die();
	}

	public static function is_page_signed_up() {
		$page_id = filter_input( INPUT_POST, 'page_id' );

		$fb_helper = new Instant_Articles_Wizard_FB_Helper();
		$pages = $fb_helper->get_pages();

		if ( isset( $pages[ $page_id ] ) && $pages[ $page_id ][ 'supports_instant_articles' ] ) {
			die( 'yes' );
		}
		else {
			die( 'no' );
		}
	}

	public static function render( $ajax = false ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html( 'You do not have sufficient permissions to access this page.' ) );
		}

		// Read options (they are used on the templates)
		$current_state = Instant_Articles_Wizard_State::get_current_state();
		$fb_page_settings = Instant_Articles_Option_FB_Page::get_option_decoded();
		$fb_app_settings = Instant_Articles_Option_FB_App::get_option_decoded();
		$fb_helper = new Instant_Articles_Wizard_FB_Helper();

		// Handle redirection from Login flow
		// ----------------------------------
		// Only during STATE_APP_SETUP
		if ( $current_state === Instant_Articles_Wizard_State::STATE_APP_SETUP ) {
			$user_access_token = $fb_helper->get_fb_access_token();
			$permissions = $fb_helper->get_fb_permissions( $user_access_token );

			// Trigger transition if all needed permissions are granted
			if ( $user_access_token && isset( $permissions[ 'pages_manage_instant_articles' ] ) && isset( $permissions[ 'pages_show_list' ] ) ) {
				Instant_Articles_Wizard_State::do_transition( Instant_Articles_Wizard_State::STATE_PAGE_SELECTION, array(
					'app_id' => $fb_app_settings[ 'app_id' ],
					'app_secret' => $fb_app_settings[ 'app_secret' ],
					'user_access_token' => $user_access_token->getValue()
				) );
				// Override step
				$current_state = Instant_Articles_Wizard_State::get_current_state();
			}
		}
		// ----------------------------------
		// ----------------------------------

		include( dirname( __FILE__ ) . '/templates/wizard-template.php' );
	}

 }
