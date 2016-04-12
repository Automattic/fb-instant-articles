<?php
/**
 * Facebook Instant Articles for WP.
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 *
 * @package default
 */

/**
 * Class responsible for functionality and rendering of the settings
 *
 * @since 0.4
 */
class Instant_Articles_Settings_FB_Page {

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
				'default_graph_version' => 'v2.5',
			));

			$this->render_settings_page_scripts();
		}
	}

	/**
	 * Gets the login url scaped.
	 *
	 * @since 0.4
	 */
	public function get_escaped_login_url() {
		if ( isset( $this->fb_sdk ) ) {
			$helper = $this->fb_sdk->getRedirectLoginHelper();

			$login_url = $helper->getLoginUrl(
				Instant_Articles_Settings::get_href_to_settings_page(),
				self::$fb_app_permissions
			);

			return htmlspecialchars( $login_url );
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
			$js_helper = $this->fb_sdk->getJavaScriptHelper();

			try {
				$access_token = $js_helper->getAccessToken();

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

	/**
	 * Render the page scripts.
	 *
	 * @since 0.4
	 */
	public function render_settings_page_scripts() {
		$app_id = $this->fb_app_settings['app_id'];

	?>
		<script>
			window.fbAsyncInit = function() {
				FB.init({
					appId      : <?php echo esc_html( $app_id ); ?>,
					xfbml      : true,
					version    : "v2.5",
					cookie     : true
				});
			};

			(function(d, s, id){
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) {return;}
				js = d.createElement(s); js.id = id;
				js.src = "//connect.facebook.net/en_US/sdk.js";
				fjs.parentNode.insertBefore(js, fjs);
			}(document, "script", "facebook-jssdk"));

			function loginCallback(response) {
				location.reload();
			}
		</script>
		<?php
	}
}
