<?php
/**
 * Facebook Instant Articles for WP.
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 *
 * @package default
 */
 require_once( dirname( __FILE__ ) . '/class-instant-articles-wizard-state.php' );

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
	}

	public static function transition() {
		$new_state = filter_input( INPUT_POST, 'new_state' );

		if ( $new_state === 'RESET' ) {
			delete_option( 'instant-articles-stub-current-state' );
		}
		else {
			Instant_Articles_Wizard_State::do_transition( $new_state );
		}

		self::render();
		die();
	}

	public static function render() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html( 'You do not have sufficient permissions to access this page.' ) );
		}

		$current_state = Instant_Articles_Wizard_State::get_current_state();

		include( dirname( __FILE__ ) . '/templates/wizard-template.php' );
	}

 }
