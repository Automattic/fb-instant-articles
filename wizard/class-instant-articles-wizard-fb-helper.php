<?php
/**
 * Facebook Instant Articles for WP.
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 *
 * @package default
 */
require_once( dirname( __FILE__ ) . '/class-instant-articles-option-fb-app.php' );
require_once( dirname( __FILE__ ) . '/class-instant-articles-option-fb-page.php' );
require_once( dirname( __FILE__ ) . '/class-instant-articles-wizard-state.php' );

use Facebook\PersistentData\PersistentDataInterface;

/**
 * Class responsible for functionality and rendering of the settings
 *
 * @since 0.4
 */
class Instant_Articles_Wizard_FB_Helper implements PersistentDataInterface {

	/**
	* @var string Prefix to use for session options.
	*/
	protected $session_prefix = 'instant_articles_fbrlh_';

	/**
	* @inheritdoc
	*/
	public function get( $key ) {

		return get_option( $this->session_prefix . $key );
	}

	/**
	* @inheritdoc
	*/
	public function set( $key, $value ) {

		update_option( $this->session_prefix . $key, $value );
	}


	/**
	 * Facebook Permissions.
	 *
	 * @var array $fb_app_permissions The permissions asked for FB user to list pages he manages.
	 */
	public static $fb_app_permissions = array( 'pages_manage_instant_articles', 'pages_show_list' );

	/**
	 * SDK instance.
	 *
	 * @var Facebook $fb_sdk the instance reference to FB sdk
	 */
	public $fb_sdk;

	/**
	 * Settings structure.
	 *
	 * @var array $fb_app_settings The map data structure to store settings.
	 */
	protected $fb_app_settings;

	/**
	 * Constructor for Settings page.
	 *
	 * @since 0.4
	 */
	public function __construct() {
		$this->fb_app_settings = Instant_Articles_Option_FB_App::get_option_decoded();

		if ( isset( $this->fb_app_settings['app_id'] ) && isset( $this->fb_app_settings['app_secret'] ) ) {
			$app_id = $this->fb_app_settings['app_id'];
			$app_secret = $this->fb_app_settings['app_secret'];
		}

		if ( ! empty( $app_id ) && ! empty( $app_secret ) ) {
			$this->fb_sdk = new Facebook\Facebook(array(
				'app_id' => $app_id,
				'app_secret' => $app_secret,
				'default_graph_version' => 'v2.6',
				'persistent_data_handler' => $this
			));
		}
	}

	/**
	 * Gets the login url.
	 *
	 * @since 0.4
	 */
	public function get_login_url() {
		if ( isset( $this->fb_sdk ) ) {
			$helper = $this->fb_sdk->getRedirectLoginHelper();
			$redirect_url = Instant_Articles_Wizard::get_url();

			$login_url = $helper->getLoginUrl(
				$redirect_url,
				self::$fb_app_permissions
			);

			return $login_url;
		}
	}

	/**
	 * Retrieves granted permissions.
	 *
	 * @param string $access_token The user access token.
	 * @since 0.5
	 */
	public function get_fb_permissions( $access_token ) {

		$permissions = array();

		if ( isset( $this->fb_sdk ) && $access_token ) {

			try {
				$permissions_query = $this->fb_sdk->get( '/me/permissions', $access_token );
				$decoded_permissions = $permissions_query->getDecodedBody();
				if ( isset( $decoded_permissions['data'] ) ) {
					foreach ( $decoded_permissions['data'] as $permission ) {
						if ( 'granted' === $permission['status'] ) {
							$permissions[ $permission['permission'] ] = 'granted';
						}
					}
				}
			} catch (Facebook\Exceptions\FacebookResponseException $e) {
				// When Graph returns an error.
				Logger::getLogger( 'instantarticles-wp-plugin' )->error(
					'Graph returned an error: '.$e->getMessage(),
					$e->getTraceAsString()
				);

			} catch (Facebook\Exceptions\FacebookSDKException $e) {
				// When validation fails or other local issues.
				Logger::getLogger( 'instantarticles-wp-plugin' )->error(
					'Facebook SDK returned an error: '.$e->getMessage(),
					$e->getTraceAsString()
				);

			}
		}

		if ( isset( $permissions ) ) {
			// Logged in.
			return $permissions;
		}
	}

	/**
	 * Retrieves Facebook access token.
	 *
	 * @since 0.4
	 */
	public function get_fb_access_token() {
		$access_token = null;

		if ( isset( $this->fb_sdk ) ) {
			try {
				$helper = $this->fb_sdk->getRedirectLoginHelper();
				$access_token = $helper->getAccessToken();
			} catch (Facebook\Exceptions\FacebookResponseException $e) {
				// When Graph returns an error.
				Logger::getLogger( 'instantarticles-wp-plugin' )->error(
					'Graph returned an error: '.$e->getMessage(),
					$e->getTraceAsString()
				);

			} catch (Facebook\Exceptions\FacebookSDKException $e) {
				// When validation fails or other local issues.
				Logger::getLogger( 'instantarticles-wp-plugin' )->error(
					'Facebook SDK returned an error: '.$e->getMessage(),
					$e->getTraceAsString()
				);
			}
		}

		if ( null !== $access_token ) {
			// Logged in.
			return $access_token;
		}
	}

	public function get_pages() {
		$helper = new Facebook\InstantArticles\Client\Helper(
			$this->fb_sdk
		);

		$fb_app_settings = Instant_Articles_Option_FB_App::get_option_decoded();

		$page_nodes = $helper->getPagesAndTokens(
			new Facebook\Authentication\AccessToken( $fb_app_settings[ 'user_access_token' ] )
		)->all();

		$pages = array();

		// Map GraphNode objects to simple value objects that are smaller when serialized.
		foreach ( $page_nodes as $page_node ) {
			$pages[ $page_node->getField( 'id' ) ] = array(
				'page_id' => $page_node->getField( 'id' ),
				'page_name' => $page_node->getField( 'name' ),
				'page_picture' => $page_node->getField( 'picture' )->getField( 'url' ),
				'page_access_token' => $page_node->getField( 'access_token' ),
				'supports_instant_articles' => $page_node->getField( 'supports_instant_articles' ),
			);
		}

		return $pages;
	}
}
