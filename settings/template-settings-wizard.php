<?php
/**
 * Facebook Instant Articles for WP.
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 *
 * @package default
 */

  if ( Instant_Articles_Settings_Wizard::get_current_step_id() === 'next-steps' ) : ?>

	<h2>Initial Setup Complete</h2>

	<h4 class="dashicons-before dashicons-facebook">
		Your Instant Articles will be published to:
		<a href="http://facebook.com/<?php echo absint( $fb_page_settings['page_id'] ); ?>" target="_blank">
			<?php echo esc_html( $fb_page_settings['page_name'] ); ?>
		</a>.
	</h4>

	<form method="post" action="options.php">
		<?php settings_fields( Instant_Articles_Option::PAGE_OPTION_GROUP_WIZARD ); ?>
		<?php submit_button( __( 'Reconfigure' ) ); ?>
	</form>

	<hr>

	<p>
		You're all set up!
	<p>
		To begin publishing into Instant Articles, proceed to any post
		and you'll see more information regarding it's published status.
	</p>

	<p>
		Existing posts will only be published if you re-save it.
	</p>

	<h4>Next steps:</h4>

	<ol>
		<li>Configure styles (<a href="https://developers.facebook.com/docs/instant-articles/guides/design">more info</a>)</li>
		<li>Signup for notifications</li>
		<li>Submit articles for review</li>
	</ol>

<?php else : ?>

	<div class="instant-articles-wizard-container">

		<ol class="instant-articles-wizard-steps">

			<!-- Wizard Step: ia-signup -->
			<li
				data-step-id="ia-signup"
				class="<?php echo esc_attr( Instant_Articles_Settings_Wizard::get_state_for_step( 'ia-signup' ) ) ?>">

				<h3>Sign up for Instant Articles</h3>

				<div class="step-details">
					<p>
						This step needs to be done within Facebook. Here's a
						<a href="https://facebook.com/instant_articles/signup" target="_blank">link</a>.
					</p>

					<form method="post" action="options.php">
						<?php settings_fields( Instant_Articles_Option::PAGE_OPTION_GROUP_WIZARD ); ?>
						<input type="hidden" name="instant-articles-option-wizard[sign_up]" value="true">
						<?php submit_button( __( 'Next' ) ); ?>
					</form>
				</div>

			</li>


			<!-- Wizard Step: app-setup -->
			<li
				data-step-id="app-setup"
				class="<?php echo esc_attr( Instant_Articles_Settings_Wizard::get_state_for_step( 'app-setup' ) ) ?>">

				<h3>Set up your Facebook App</h3>
				<div class="step-details">

					<form method="post" action="options.php">
						<?php settings_fields( Instant_Articles_Option::PAGE_OPTION_GROUP_WIZARD ); ?>

						<p>
							Your Instant Articles need to be published to a specific Facebook Page through
							<a href="https://developers.facebook.com/apps/">an existing</a>
							Facebook App:
						</p>

						<?php do_settings_sections( Instant_Articles_Option_Wizard::OPTION_KEY ); ?>
						<?php do_settings_sections( Instant_Articles_Option_FB_App::OPTION_KEY ); ?>

						<?php submit_button( __( 'Next' ) ); ?>
					</form>

				</div>
			</li>




			<!-- Wizard Step: page-selection -->
			<li
				data-step-id="page-selection"
				class="<?php
					echo esc_attr ( Instant_Articles_Settings_Wizard::get_state_for_step( 'page-selection' ) );
				?>"
			>
				<h3>Facebook Page</h3>
				<div class="step-details">

					<form method="post" action="options.php">
						<?php settings_fields( Instant_Articles_Option::PAGE_OPTION_GROUP_WIZARD ); ?>
						<?php do_settings_sections( Instant_Articles_Option_Wizard::OPTION_KEY ); ?>
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

							<script>
								//*
								function loginCallback2(response) {
									if (response.status === 'connected') {
										location.reload();
										//probably good to do an ajax call to a login_callback page
									} else if (response.status === 'not_authorized') {
										// The person is logged into Facebook, but not your app.
									} else {
										// The person is not logged into Facebook, so we're not sure if
										// they are logged into this app or not.
									}
								}
								//*/
							</script>

							<div
								class="fb-login-button"
								data-size="medium"
								data-scope="<?php
									echo esc_html(
										implode( Instant_Articles_Settings_FB_Page::$fb_app_permissions, ',' )
									);
								?>"
								onlogin="loginCallback2">
								List Pages
							</div>

						<?php
						elseif (
							( ! isset( $permissions['pages_manage_instant_articles'] )  ) ||
							( ! isset( $permissions['pages_show_list'] ) )
						) :
						?>

							<script>
								//*
								function loginCallback2(response) {
									if (response.status === 'connected') {
										location.reload();
										//probably good to do an ajax call to a login_callback page
									} else if (response.status === 'not_authorized') {
										// The person is logged into Facebook, but not your app.
									} else {
										// The person is not logged into Facebook, so we're not sure if
										// they are logged into this app or not.
									}
								}
								//*/
							</script>

							<p>In order to finish the setup, you need to grant these permissions:</p>
							<ul>
								<?php if ( ! isset( $permissions['pages_show_list'] ) ) : ?>
									<li>
										<b>pages_show_list</b>: allows us to show the list of your
										pages for you to pick one.
									</li>
								<?php endif; ?>
								<?php if ( ! isset( $permissions['pages_manage_instant_articles'] ) ) : ?>
									<li>
										<b>pages_manage_instant_articles</b>: allows us to publish
										Instant Articles to your page.
									</li>
								<?php endif; ?>
							</p>

							<p>Please grant the needed permissions to continue:</p>

							<div
								class="fb-login-button"
								data-size="medium"
								data-scope="<?php
									echo esc_attr(
										implode( Instant_Articles_Settings_FB_Page::$fb_app_permissions, ',' )
									);
								?>"
								onlogin="loginCallback2">
								Grant needed permissions
							</div>

						<?php else : ?>

							<table class="form-table">
								<tr>
									<th scope="row">
										Facebook Page
									</th>
									<td>
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
											);
										}, $helper->getPagesAndTokens( $access_token )->all());

										?>
										<select id="<?php echo esc_attr( 'instant-articles-fb-page-selector' ) ?>">
											<option value="" disabled selected>Select your Page</option>
											<?php foreach ( $pages_and_tokens as $page ) : ?>
												<option value="<?php echo esc_attr( json_encode( $page ) ) ?>">
													<?php echo esc_html( $page->page_name ) ?>
												</option>
											<?php endforeach; ?>
										</select>
									</td>
								</tr>
							</table>
							<?php submit_button( __( 'Next' ) ); ?>
						<?php endif; ?>
					</form>
				</div>
			</li>


			<!-- Wizard Step: claim-url -->
			<li
				data-step-id="claim-url"
				class="<?php echo esc_attr( Instant_Articles_Settings_Wizard::get_state_for_step( 'claim-url' ) ) ?>"
			>

				<h3>Claim URLs</h3>
				<div class="step-details">
					<p>
						Claim your URLs <a target="_blank" href="<?php echo esc_attr( $claim_url_href ) ?>">here</a>.
						We think the one for this Wordpress site is:
						<strong><code><?php echo esc_html( site_url() ); ?></code></strong>.
					</p>

					<form method="post" action="options.php">
						<?php settings_fields( Instant_Articles_Option::PAGE_OPTION_GROUP_WIZARD ); ?>
						<div style="display: none">
							<?php do_settings_sections( Instant_Articles_Option_Wizard::OPTION_KEY ); ?>
							<?php do_settings_sections( Instant_Articles_Option_FB_Page::OPTION_KEY ); ?>
							<?php do_settings_sections( Instant_Articles_Option_FB_App::OPTION_KEY ); ?>
						</div>

						<input type="hidden" name="instant-articles-option-wizard[claimed_url]" value="true">
						<?php submit_button( __( 'Done' ) ); ?>
					</form>
				</div>

			</li>
		</ol>

		<hr>

		<?php if ( Instant_Articles_Settings_Wizard::get_current_step_id() !== 'ia-signup' ) : ?>
			<form method="post" action="options.php" id="instant-articles-restart-wizard">
				<?php settings_fields( Instant_Articles_Option::PAGE_OPTION_GROUP_WIZARD ); ?>
				<?php submit_button( __( 'Restart Wizard' ), 'secondary' ); ?>
			</form>
		<?php endif; ?>

	</div>

<?php endif;
