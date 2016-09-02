<?php
/**
 * Facebook Instant Articles for WP.
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 *
 * @package default
 */
?>
<?php if ( empty( $fb_app_settings['app_id'] ) || empty( $fb_app_settings['app_secret'] ) ) : ?>

	<!-- Step 1: grab your App ID and App Secret -->
	<div class="instant-articles-card">
		<div class="instant-articles-card-title">
			<h3>Log In With Your Developers App and Facebook Account</h3>
			<div class="instant-articles-card-title-right">
				<span class="instant-articles-card-title-step">Step 1 of 2</span>
			</div>
		</div>
		<div class="instant-articles-card-content">
			<div class="instant-articles-card-content-box instant-articles-card-content-left">
				<label>First, log in with your Facebook Developers App</label>

				<p>You'll need to log in with your Facebook Developers App ID to connect your plugin to the Facebook Page you'll use to publish your Instant Articles.</p>

				<p><strong>Already have an App ID and App Secret?</strong> Just enter them to the right.</p>

				<p><strong>Need to create one?</strong></p>

				<p>Click on the 'Get App ID' button below to begin the process of getting your App ID and App Secret. This will open the app set up page in a new tab. Then, follow these steps:</p>

				<ol>
					<li>Click on the green '+ Add a New App' button in the upper right corner of the page.</li>
					<li>If you are prompted to select a platform, select 'basic setup.' If you don't see this prompt, don't worry; just go directly to Step 3.</li>
					<li>Create an app name (it can be whatever you want it to be), input your email and select 'Apps for Pages' in the 'Category' dropdown menu. Click 'Create App ID' when you’re ready.</li>
					<li>Click on 'Settings' in the left nav bar.</li>
					<li>Click '+Add Platform' at the bottom of the page and select 'Website.'</li>
					<li>Under both the 'App Domains' in the main section and 'Site URL' in the 'Website' section, enter this domain: <b><?php echo esc_url( self::get_admin_url() ); ?></b></li>
					<li>Click 'Save Changes' in the lower right corner.</li>
					<li>Select 'Show' to see your App Secret. Copy your App ID and App Secret and enter them to the right.</li>
				</ol>

				<p>For more detailed instructions on setting up your App ID, <a href="https://developers.facebook.com/docs/instant-articles/wordpress-quickstart#appid" target="_blank">check out the docs</a>.</p>

				<a class="instant-articles-button" href="https://developers.facebook.com/apps" target="_blank">
					Get App ID
				</a>
			</div>
			<div class="instant-articles-card-content-box instant-articles-card-content-right">
				<label class="instant-articles-label">App ID</label>
				<input name="app_id" class="instant-articles-input-text" type="text"/>
				<label class="instant-articles-label">App Secret</label>
				<input name="app_secret" class="instant-articles-input-text" type="password"/>
				<button id="instant-articles-wizard-save-app" class="instant-articles-button instant-articles-button-highlight instant-articles-button-disabled">
					<label>Log In</label>
				</button>
			</div>
			<br clear="both" />
		</div>
	</div>

<?php else: ?>

	<!-- Step 2: log in with Facebook -->
	<div class="instant-articles-card">
		<div class="instant-articles-card-title">
			<h3>Log In with Your Developers App and Facebook Account</h3>
			<div class="instant-articles-card-title-right">
				<span class="instant-articles-card-title-step">Step 2 of 2</span>
			</div>
		</div>
		<div class="instant-articles-card-content">
			<div class="instant-articles-card-content-box instant-articles-card-content-full">
				<span class="instant-articles-card-title-checkmark">✔</span>
				<label class="instant-articles-card-title-label">App connected:</label>
				<span class="instant-articles-card-title-value"><?php echo esc_html( $fb_app_settings[ 'app_id' ] ); ?></span>
				<a id="instant-articles-wizard-edit-app" class="instant-articles-card-title-edit" href="#"></a>
				<hr/>
				<label>Next, log into your Facebook account</label>
				<p>Log in with Facebook to finish connecting the Instant Articles Plugin to your account.</p>
				<?php
					if (
						$user_access_token &&
						(
							! isset( $permissions['pages_manage_instant_articles'] ) ||
							! isset( $permissions['pages_show_list'] )
						)
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
					</ul>
				<?php endif;?>

				<a href="<?php echo esc_attr( $fb_helper->get_login_url() ); ?>" class="instant-articles-button">
					<span class="instant-articles-button-icon-facebook"></span>
					<label>Login with Facebook</label>
				</a>
			</div>
			<div clear="both" />
		</div>
	</div>
<?php endif; ?>
