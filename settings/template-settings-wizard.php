<?php
/**
 * Facebook Instant Articles for WP.
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 *
 * @package default
 */

if ( Instant_Articles_Settings_Wizard::get_current_step_id() === 'done' ) : ?>

	<p><strong>Success! Your Instant Articles plugin has been activated.</strong></p>

	<form class="instant-articles-inline-form" method="post" action="options.php">
		<p>
			Your App is
			<a
				href="http://developers.facebook.com/apps/<?php echo esc_attr( $fb_app_settings['app_id'] ); ?>"
				target="_blank"><?php
				echo esc_html( $fb_app_settings['app_id'] );
			?></a>.

			<?php settings_fields( Instant_Articles_Option::PAGE_OPTION_GROUP_WIZARD ); ?>
			<?php submit_button( __( 'Update' ), 'default', 'submit', false ); ?>
		</p>
	</form>

	<form class="instant-articles-inline-form" method="post" action="options.php">
		<p>
			Your page is
			<a
		  href="http://facebook.com/<?php echo esc_attr( $fb_page_settings['page_id'] ); ?>"
		  target="_blank"><?php
				echo esc_html( $fb_page_settings['page_name'] );
			?></a>.
			<?php settings_fields( Instant_Articles_Option::PAGE_OPTION_GROUP_WIZARD ); ?>
			<?php submit_button( __( 'Update' ), 'default', 'submit', false ); ?>
		  <div style="display: none">
				<?php do_settings_sections( Instant_Articles_Option_FB_App::OPTION_KEY ); ?>
		  </div>
		</p>
	</form>

<?php elseif ( Instant_Articles_Settings_Wizard::get_current_step_id() === 'app-setup' ) : ?>

	<form method="post" action="options.php">
		<?php settings_fields( Instant_Articles_Option::PAGE_OPTION_GROUP_WIZARD ); ?>

		<p>
			You need a Facebook App to publish Instant Articles using this plugin. If you already have one, input the App ID and App Secret below, which you can find by clicking on your app <a href="https://developers.facebook.com/apps" target="_blank">here</a>. If you don't, create one <a href="https://developers.facebook.com/apps" target="_blank">here</a> before continuing.
		</p>

		<?php do_settings_sections( Instant_Articles_Option_FB_App::OPTION_KEY ); ?>

		<?php submit_button( __( 'Next' ) ); ?>
	</form>

<?php elseif ( Instant_Articles_Settings_Wizard::get_current_step_id() === 'page-selection' ) : ?>

	<form class="instant-articles-inline-form" method="post" action="options.php">
		<p>
			Your App is
			<a
				href="http://developers.facebook.com/apps/<?php echo esc_attr( $fb_app_settings['app_id'] ); ?>"
				target="_blank"><?php
				echo esc_html( $fb_app_settings['app_id'] );
			?></a>.

				<?php settings_fields( Instant_Articles_Option::PAGE_OPTION_GROUP_WIZARD ); ?>
				<?php submit_button( __( 'Update' ), 'default', 'submit', false ); ?>
		</p>
	</form>

	<hr>

	<form method="post" action="options.php">
		<?php settings_fields( Instant_Articles_Option::PAGE_OPTION_GROUP_WIZARD ); ?>
		<?php do_settings_sections( Instant_Articles_Option_FB_Page::OPTION_KEY ); ?>
		<div style="display: none">
			<?php do_settings_sections( Instant_Articles_Option_FB_App::OPTION_KEY ); ?>
		</div>

		<?php
		$fb_helper = new Instant_Articles_Settings_FB_Page();
		$access_token = $fb_helper->get_fb_access_token();
		$permissions = $fb_helper->get_fb_permissions( $access_token );
		?>

		<?php if ( ! $access_token ) : ?>

			<p>Login to Facebook and select the Facebook Page where you will publish Instant Articles.</p>

			<div>
				<a href="<?php echo esc_attr( $fb_helper->get_login_url() ) ?>">
					<img  src="https://fbcdn-dragon-a.akamaihd.net/hphotos-ak-xtf1/t39.2178-6/11405239_920140564714397_256329502_n.png">
				</a>
			</div>

		<?php
		elseif (
			( ! isset( $permissions['pages_manage_instant_articles'] )  ) ||
			( ! isset( $permissions['pages_show_list'] ) )
		) :
		?>

			<p>In order to finish the activation, you need to grant all the requested permissions:</p>
			<ul>
				<?php if ( ! isset( $permissions['pages_show_list'] ) ) : ?>
					<li>
						<b>Show a list of the Pages you manage</b>: allows the plugin to show the list of your
						pages for you to select one.
					</li>
				<?php endif; ?>
				<?php if ( ! isset( $permissions['pages_manage_instant_articles'] ) ) : ?>
					<li>
						<b>Manage Instant Articles for your Pages</b>: allows us to publish
						Instant Articles to your selected page.
					</li>
				<?php endif; ?>
			</p>

			<p>Please grant the needed permissions to continue:</p>

			<div>
				<a href="<?php echo esc_attr( $fb_helper->get_login_url() ) ?>">
					<img  src="https://fbcdn-dragon-a.akamaihd.net/hphotos-ak-xtf1/t39.2178-6/11405239_920140564714397_256329502_n.png">
				</a>
			</div>

		<?php else : ?>

			<?php
			$helper = new Facebook\InstantArticles\Client\Helper(
				$fb_helper->fb_sdk
			);

			$fb_page_settings = Instant_Articles_Option_FB_Page::get_option_decoded();

			// Map GraphNode objects to simple value objects that are smaller when serialized.
			$pages_and_tokens = array_map(function( $page_node ) {
				return (object) array(
					'page_id' => $page_node->getField( 'id' ),
					'page_name' => $page_node->getField( 'name' ),
					'page_access_token' => $page_node->getField( 'access_token' ),
					'supports_instant_articles' => $page_node->getField( 'supports_instant_articles' ),
				);
			}, $helper->getPagesAndTokens( $access_token )->all() );

			$pages_and_tokens = array_filter( $pages_and_tokens, function ( $page ) {
				return $page->supports_instant_articles;
			} );
			?>

			<?php if ( ! empty( $pages_and_tokens ) ) : ?>
				<p>Select the Facebook Pages where you will publish Instant Articles:</p>

				<select id="<?php echo esc_attr( 'instant-articles-fb-page-selector' ) ?>">
					<option value="" disabled selected>Select Page</option>
					<?php foreach ( $pages_and_tokens as $page ) : ?>
						<option value="<?php echo esc_attr( wp_json_encode( $page ) ) ?>">
							<?php echo esc_html( $page->page_name ) ?>
						</option>
					<?php endforeach; ?>
				</select>
				<?php submit_button( 'Next', 'primary', 'instant-articles-select-page', true ); ?>
			<?php else : ?>
				<p>Sorry, you have no Pages signed up for Instant Articles.</p>
				<p><a href="https://www.facebook.com/instant_articles/signup" target="_blank">Sign up</a> for Instant Articles and refresh this page to continue.</p>
			<?php endif; ?>

		<?php endif; ?>
	</form>

<?php endif;
