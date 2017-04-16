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
require_once( dirname( __FILE__ ) . '/class-instant-articles-option-fb-page-opengraph.php' );
require_once( dirname( __FILE__ ) . '/class-instant-articles-option-publishing.php' );
require_once( dirname( __FILE__ ) . '/class-instant-articles-option-configuration-flow.php' );
require_once( dirname( __FILE__ ) . '/class-instant-articles-option-styles.php' );
require_once( dirname( __FILE__ ) . '/class-instant-articles-wizard-state.php' );
require_once( dirname( __FILE__ ) . '/class-instant-articles-wizard-fb-helper.php' );
require_once( dirname( __FILE__ ) . '/class-instant-articles-wizard-review-submission.php' );

use Facebook\InstantArticles\Client\Client;
use Facebook\InstantArticles\Client\ClientException;

/**
* Controller for Set-up Wizard (API) and Open Graph
*
* @since 3.1
*/
class Instant_Articles_Wizard {

	public static function init() {

		// If setup is already complete on API, it means a migration,
		//   set the configuration as API and the flow goes that way
		// otherwise
		//   set the configuration as Open Graph and flow goes this way
		$flow = Instant_Articles_Option_Configuration_Flow::get_option_decoded();
		if ( !$flow[ 'configuration_flow' ] || $flow[ 'configuration_flow' ] === '' ) {
			$current_state = Instant_Articles_Wizard_State::get_current_state();
			if ( $current_state === Instant_Articles_Wizard_State::STATE_APP_SETUP ) {
				Instant_Articles_Option_Configuration_Flow::update_option( array(
					'configuration_flow' => 'api'
				) );
			}
			else {
				Instant_Articles_Option_Configuration_Flow::update_option( array(
					'configuration_flow' => 'opengraph'
				) );
			}
		}

		add_action( 'admin_menu', array( 'Instant_Articles_Wizard', 'menu_items' ) );

		add_filter( 'plugin_action_links_' . IA_PLUGIN_PATH, array( 'Instant_Articles_Wizard', 'add_settings_link_to_plugin_actions' ) );

		add_action( 'admin_init', function () {
			new Instant_Articles_Option_FB_App();
			new Instant_Articles_Option_FB_Page();
			new Instant_Articles_Option_FB_Page_OpenGraph();
			new Instant_Articles_Option_Configuration_Flow();
			new Instant_Articles_Option_Styles();
			new Instant_Articles_Option_Ads();
			new Instant_Articles_Option_Analytics();
			new Instant_Articles_Option_Publishing();
		});

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

		add_action(
			'wp_ajax_instant_articles_wizard_submit_for_review',
			array( 'Instant_Articles_Wizard', 'submit_for_review' )
		);

		add_action(
			'wp_ajax_instant_articles_wizard_save_page',
			array( 'Instant_Articles_Wizard', 'save_page' )
		);

		add_action(
			'wp_ajax_instant_articles_wizard_edit_page',
			array( 'Instant_Articles_Wizard', 'edit_page' )
		);

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
			'Instant Articles Setup Wizard',
			'Instant Articles',
			'manage_options',
			'instant-articles-wizard',
			array( 'Instant_Articles_Wizard', 'render' )
			,'dashicons-facebook'
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


	public static function get_admin_url() {
		$url = parse_url( admin_url() );
		return $url['host'];
	}

	public static function transition() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html( 'You do not have sufficient permissions to access this page.' ) );
		}

		$new_state = sanitize_text_field( $_POST[ 'new_state' ] );

		$params = $_POST[ 'params' ];
		$params = json_decode( stripslashes( $params ), true );
		if ( $params && !empty( $params ) ) {
			foreach ( $params as $key => $param ) {
				// escape every key
				$params[ $key ] = sanitize_text_field( $param );
			}
		}
		else {
			$params = array();
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

	/**
	 * Saves the App ID and App Secret.
	 *
	 * That does not trigger a state transition, as the state transition will
	 * only happen when the user logs in and we grab the access token.
	 */
	public static function save_app() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html( 'You do not have sufficient permissions to access this page.' ) );
		}

		$current_state = Instant_Articles_Wizard_State::get_current_state();
		if ( $current_state !== Instant_Articles_Wizard_State::STATE_APP_SETUP ) {
			die();
		}

		$app_id = sanitize_text_field( $_POST[ 'app_id' ] );
		$app_secret = sanitize_text_field( $_POST[ 'app_secret' ] );

		Instant_Articles_Option_FB_App::update_option( array(
			'app_id' => $app_id,
			'app_secret' => $app_secret
		) );

		self::render( true );
		die();
	}

	/**
	 * Submits the select page for review
	 */
	public static function submit_for_review() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html( 'You do not have sufficient permissions to access this page.' ) );
		}

		$current_state = Instant_Articles_Wizard_State::get_current_state();
		if ( $current_state !== Instant_Articles_Wizard_State::STATE_REVIEW_SUBMISSION ) {
			die();
		}

		$fb_app_settings = Instant_Articles_Option_FB_App::get_option_decoded();
		$fb_page_settings = Instant_Articles_Option_FB_Page::get_option_decoded();

		try {
			$client = Client::create(
				$fb_app_settings[ 'app_id' ],
				$fb_app_settings[ 'app_secret' ],
				$fb_page_settings[ 'page_access_token' ],
				$fb_page_settings[ 'page_id' ]
			);

			// Bulk upload articles for review
			$articles_for_review = Instant_Articles_Wizard_Review_Submission::getArticlesForReview();
			foreach ( $articles_for_review as $post ) {
				Instant_Articles_Publisher::submit_article( $post->ID, $post );
			}

			// Trigger review submission
			$client->submitForReview();
		} catch ( Exception $e ) {
			// If something went wrong, simply render the error + the same state.
			echo '<div class="error settings-error notice is-dismissible"><p><strong>' . esc_html( $e->getMessage() ) . '</strong></p></div>';
		}

		self::render( true );
		die();
	}

	/**
	 * Edits the App ID and App Secret within the APP_SETUP state.
	 */
	public static function edit_app() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html( 'You do not have sufficient permissions to access this page.' ) );
		}

		$current_state = Instant_Articles_Wizard_State::get_current_state();
		if ( $current_state !== Instant_Articles_Wizard_State::STATE_APP_SETUP ) {
			die();
		}

		Instant_Articles_Option_FB_App::delete_option();

		self::render( true );
		die();
	}

	public static function is_page_signed_up() {
		$page_id = sanitize_text_field( $_POST[ 'page_id' ] );

		$fb_helper = new Instant_Articles_Wizard_FB_Helper();
		$pages = $fb_helper->get_pages();

		if ( isset( $pages[ $page_id ] ) && $pages[ $page_id ][ 'supports_instant_articles' ] ) {
			die( 'yes' );
		}
		else {
			die( 'no' );
		}
	}

	/**
	 * Saves the Page ID for Open Graph Ingestion.
	 */
	public static function save_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html( 'You do not have sufficient permissions to access this page.' ) );
		}

		$page_id = sanitize_text_field( $_POST[ 'page_id' ] );

		Instant_Articles_Option_FB_Page_OpenGraph::update_option( array(
			'page_id' => $page_id,
			'editing' => false,
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

		$previous = Instant_Articles_Option_FB_Page_OpenGraph::get_option_decoded();

		Instant_Articles_Option_FB_Page_OpenGraph::update_option( array(
			'page_id' => $previous[ 'page_id' ],
			'editing' => true,
		) );

		self::render( true );
		die();
	}

	public static function render( $ajax = false ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html( 'You do not have sufficient permissions to access this page.' ) );
		}

		try {
			// Read options (they are used on the templates)
			$current_state = Instant_Articles_Wizard_State::get_current_state();
			$fb_page_settings = Instant_Articles_Option_FB_Page::get_option_decoded();
			$fb_page_opengraph_settings = Instant_Articles_Option_FB_Page_OpenGraph::get_option_decoded();
			$fb_app_settings = Instant_Articles_Option_FB_App::get_option_decoded();
			$fb_helper = new Instant_Articles_Wizard_FB_Helper();
			$fb_flow_settings = Instant_Articles_Option_Configuration_Flow::get_option_decoded();
			$flow = $fb_flow_settings[ 'configuration_flow' ];
			$settings_url = self::get_url();

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

			// Handle redirection from Login flow
			// ----------------------------------
			// Only during STATE_PAGE_SELECTION
			if ( $current_state === Instant_Articles_Wizard_State::STATE_PAGE_SELECTION ) {
				$fb_helper = new Instant_Articles_Wizard_FB_Helper();
				try {
					$pages = $fb_helper->get_pages();
				} catch ( Exception $e ) {
					// If we couldn't fetch the pages, revert to the App setup
					Instant_Articles_Wizard_State::do_transition( Instant_Articles_Wizard_State::STATE_APP_SETUP );
				}
			}

			// Grabs the current configured style
			// ----------------------------------
			// Only during STATE_STYLE_SELECTION
			if ( $current_state === Instant_Articles_Wizard_State::STATE_STYLE_SELECTION ) {
				$settings_style = Instant_Articles_Option_Styles::get_option_decoded();
				if ( isset( $settings_style['article_style'] ) && ! empty( $settings_style['article_style'] ) ) {
					$article_style = $settings_style['article_style'];
				} else {
					$article_style = 'default';
				}
			}
			// ----------------------------------


			// Check submission status
			// ----------------------------------
			// Only during STATE_REVIEW_SUBMISSION
			if ( $current_state === Instant_Articles_Wizard_State::STATE_REVIEW_SUBMISSION ) {
				$review_submission_status = Instant_Articles_Wizard_Review_Submission::getReviewSubmissionStatus();

				if ( $review_submission_status === Instant_Articles_Wizard_Review_Submission::STATUS_NOT_SUBMITTED ) {
					$articles_for_review = Instant_Articles_Wizard_Review_Submission::getArticlesForReview();

					// Map to Instant_Articles_Post instances
					$instant_articles_for_review = array_map( function ( $article ) {
						$instant_articles_post = new Instant_Articles_Post( $article );
						// Call transformation to load warnings
						$instant_articles_post->to_instant_article();
						return $instant_articles_post;
					}, $articles_for_review );

					// Filter articles with warnings and not forced
					$instant_articles_with_warnings = array_filter( $instant_articles_for_review, function ( $article ) {
						$has_warnings = ( count( $article->transformer->getWarnings() ) > 0 );
						$force_submit = get_post_meta( $article->get_the_id(), Instant_Articles_Publisher::FORCE_SUBMIT_KEY, true );
						return $has_warnings && ! $force_submit;
					} );
				}
			}
			// ----------------------------------

			include( dirname( __FILE__ ) . '/templates/wizard-template.php' );
		} catch (Exception $e) {
			if ( Instant_Articles_Wizard_State::get_current_state() !== Instant_Articles_Wizard_State::STATE_REVIEW_SUBMISSION ) {
				// Restarts the wizard
				Instant_Articles_Wizard_State::do_transition( Instant_Articles_Wizard_State::STATE_APP_SETUP );
				echo '<div class="error settings-error notice is-dismissible"><p><strong>'.
					esc_html(
						'Error processing your request. Check server log for more details. Setup and login again to renew Application credentials. Error message: ' .
						$e->getMessage()
					) . '</strong></p></div>';
				Instant_Articles_Wizard::render( $ajax, true );
			}
		}
	}

 }
