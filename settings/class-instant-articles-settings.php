<?php
/**
 * Facebook Instant Articles for WP.
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 *
 * @package default
 */

require_once( dirname( __FILE__ ) . '/class-instant-articles-option-fb-page.php' );
require_once( dirname( __FILE__ ) . '/class-instant-articles-option-fb-app.php' );
require_once( dirname( __FILE__ ) . '/class-instant-articles-option-ads.php' );
require_once( dirname( __FILE__ ) . '/class-instant-articles-option-styles.php' );
require_once( dirname( __FILE__ ) . '/class-instant-articles-option-analytics.php' );
require_once( dirname( __FILE__ ) . '/class-instant-articles-option-publishing.php' );

require_once( dirname( __FILE__ ) . '/class-instant-articles-settings-wizard.php' );
require_once( dirname( __FILE__ ) . '/class-instant-articles-settings-fb-page.php' );

/**
 * Class responsible for functionality and rendering of the settings.
 *
 * @since 0.4
 */
class Instant_Articles_Settings {

	const IA_PLUGIN_SETTINGS_SLUG = 'instant-articles-settings';

	/**
	 * Initiator.
	 *
	 * @since 0.4
	 */
	public static function init() {
		add_action( 'admin_menu', array( 'Instant_Articles_Settings', 'menu_items' ) );

		add_filter( 'plugin_action_links_' . IA_PLUGIN_PATH, array( 'Instant_Articles_Settings', 'add_settings_link_to_plugin_actions' ) );

		add_action( 'admin_init', function () {
			new Instant_Articles_Option_FB_App();
			new Instant_Articles_Option_FB_Page();
			new Instant_Articles_Option_Styles();
			new Instant_Articles_Option_Ads();
			new Instant_Articles_Option_Analytics();
			new Instant_Articles_Option_Publishing();
		});

	}

	/**
	 * Gets the URL/path for this page.
	 *
	 * @since 0.4
	 */
	public static function get_href_to_settings_page() {
		return menu_page_url( self::IA_PLUGIN_SETTINGS_SLUG, false );
	}

	/**
	 * Creates an anchor element.
	 *
	 * @param array $links The links will be added to anchor.
	 * @since 0.4
	 */
	public static function add_settings_link_to_plugin_actions( $links ) {
		$link_text = __( 'Settings' );
		$settings_href = self::get_href_to_settings_page();
		$settings_link = '<a href="' . esc_url( $settings_href ) . '">' . $link_text . '</a>';
		array_push( $links, $settings_link );
		return $links;
	}

	/**
	 * Creates the menu items for FB Instant Article in WordPress side menu.
	 *
	 * @since 0.4
	 */
	public static function menu_items() {
		add_menu_page(
			'Instant Articles Settings',
			'Instant Articles',
			'manage_options',
			self::IA_PLUGIN_SETTINGS_SLUG,
			array( 'Instant_Articles_Settings', 'render_settings_page' )
			,'dashicons-facebook'
		);
	}

	/**
	 * Optains the state for current step from state-machine.
	 *
	 * @param int $step_id The step identifier.
	 * @since 0.4
	 */
	public static function get_state_for_wizard_step( $step_id ) {
		$fb_page_settings = Instant_Articles_Option_FB_Page::get_option_decoded();

		switch ( $step_id ) {
			case 'app-id':
				if ( isset( $fb_page_settings['app_id'] ) && isset( $fb_page_settings['app_secret'] ) ) {
					return '';
				} else {
					return 'current';
				}
				break;

			case 'page-id':
				if ( isset( $fb_page_settings['page_id'] ) && isset( $fb_page_settings['page_access_token'] ) ) {
					return '';
				} else {
					return 'current';
				}
				break;

			default:
				# code...
				break;
		}
	}

	/**
	 *
	 *
	 * @since 0.4
	 */
	public static function render_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html( 'You do not have sufficient permissions to access this page.' ) );
		}

		settings_errors();

		$fb_page_settings = Instant_Articles_Option_FB_Page::get_option_decoded();
		$fb_app_settings = Instant_Articles_Option_FB_App::get_option_decoded();

		if ( filter_input( INPUT_GET, 'current_tab' ) ) {
			$tab = filter_input( INPUT_GET, 'current_tab' );
		} else {
			$tab = 'basic';
		}

		$settings_page_href = self::get_href_to_settings_page();

		include( dirname( __FILE__ ) . '/template-settings.php' );
	}
}
