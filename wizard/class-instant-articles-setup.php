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
require_once( dirname( __FILE__ ) . '/class-instant-articles-option-configuration-flow.php' );
require_once( dirname( __FILE__ ) . '/class-instant-articles-option-styles.php' );
require_once( dirname( __FILE__ ) . '/class-instant-articles-wizard-state.php' );
require_once( dirname( __FILE__ ) . '/class-instant-articles-wizard-fb-helper.php' );
require_once( dirname( __FILE__ ) . '/class-instant-articles-wizard-review-submission.php' );

use Facebook\InstantArticles\Client\Client;
use Facebook\InstantArticles\Client\ClientException;

/**
* Controller for Set-up. It forks between wizard and opengraph setup.
*
* @since 4.0
*/
class Instant_Articles_Setup {

	public static function init() {

		// If setup is already complete on API, it means a migration,
		//   set the configuration as API and the flow goes that way
		// otherwise
		//   set the configuration as Open Graph and flow goes this way

		$flow = Instant_Articles_Option_Configuration_Flow::get_option_decoded();
		if ( !$flow[ 'configuration_flow' ] || $flow[ 'configuration_flow' ] === '' ) {
			Instant_Articles_Option_Configuration_Flow::update_option( array(
				'configuration_flow' => 'opengraph'
			) );
		}

		$flow = Instant_Articles_Option_Configuration_Flow::get_option_decoded();
		if ( $flow[ 'configuration_flow' ] === 'api' ) {
			// Initialize the Instant Articles Wizard page.
			Instant_Articles_Wizard::init();
			$current_state = Instant_Articles_Wizard_State::get_current_state();
			if ( $current_state !== Instant_Articles_Wizard_State::STATE_APP_SETUP ) {
				die();
			}
		}
		else {
			add_action( 'admin_menu', array( 'Instant_Articles_Setup', 'menu_items' ) );

			add_filter( 'plugin_action_links_' . IA_PLUGIN_PATH, array( 'Instant_Articles_Setup', 'add_settings_link_to_plugin_actions' ) );

			add_action( 'admin_init', function () {
				new Instant_Articles_Option_FB_App();
				new Instant_Articles_Option_FB_Page();
				new Instant_Articles_Option_Configuration_Flow();
				new Instant_Articles_Option_Styles();
				new Instant_Articles_Option_Ads();
				new Instant_Articles_Option_Analytics();
				new Instant_Articles_Option_Publishing();
			});

			add_action(
				'wp_ajax_instant_articles_setup_opengraph_save_page',
				array( 'Instant_Articles_Setup', 'save_page' )
			);

			add_action(
				'wp_ajax_instant_articles_setup_opengraph_edit_page',
				array( 'Instant_Articles_Setup', 'edit_page' )
			);
		}
	}

	public static function add_settings_link_to_plugin_actions( $links ) {
		$link_text = __( 'Settings' );
		$settings_href = self::get_url();
		$settings_link = '<a href="' . esc_url( $settings_href ) . '">' . esc_html( $link_text ) . '</a>';
		array_push( $links, $settings_link );
		return $links;
	}

	public static function menu_items() {
		add_menu_page(
			'Instant Articles Setup',
			'Instant Articles',
			'manage_options',
			'instant-articles-setup',
			array( 'Instant_Articles_Setup', 'render' )
			,'dashicons-facebook'
		);
		// Hack to let the URL visible to ajax handlers
		update_option( 'instant-articles-setup-url', menu_page_url( 'instant-articles-setup', false) );
	}

	public static function get_url() {
		$url = menu_page_url( 'instant-articles-setup', false );

		// Needed when calling from ajax
		if ( ! $url ) {
			$url = get_option( 'instant-articles-setup-url' );
		}

		return $url;
	}


	public static function get_admin_url() {
		$url = parse_url( admin_url() );
		return $url['host'];
	}

	/**
	 * Saves the Page ID for Open Graph Ingestion.
	 */
	public static function save_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html( 'You do not have sufficient permissions to access this page.' ) );
		}

		$page_id = sanitize_text_field( $_POST[ 'page_id' ] );

		Instant_Articles_Option_FB_Page::update_option( array(
			'page_id' => $page_id
		) );

		self::render( true );
		die();
	}

	/**
	 * Resets the Page ID for Open Graph Ingestion.
	 */
	public static function edit_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html( 'You do not have sufficient permissions to access this page.' ) );
		}

		Instant_Articles_Option_FB_Page::delete_option();

		self::render( true );
		die();
	}

	public static function transition() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html( 'You do not have sufficient permissions to access this page.' ) );
		}

		$new_state = sanitize_text_field( $_POST[ 'new_state' ] );

		$params = $_POST[ 'params' ];
		$params = json_decode( stripslashes( $params ), true );
		foreach ( $params as $key => $param ) {
			// escape every key
			$params[ $key ] = sanitize_text_field( $param );
		}

		try {
			Instant_Articles_Wizard_State::do_transition( $new_state, $params );
		} catch ( Exception $e ) {
			// If something went wrong, simply render the error + the same state.
			echo '<div class="error settings-error notice is-dismissible"><p><strong>' . esc_html( $e->getMessage() ) . '</strong></p></div>';
		}

		self::render( true );
		die();
	}

	public static function render( $ajax = false ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html( 'You do not have sufficient permissions to access this page.' ) );
		}

		// Used on the templates
		$fb_page_settings = Instant_Articles_Option_FB_Page::get_option_decoded();

		include( dirname( __FILE__ ) . '/templates/setup-template.php' );
	}

 }
