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
			<h3>Log In with Your Developers App and Facebook Account</h3>
			<div class="instant-articles-card-title-right">
				<span class="instant-articles-card-title-step">Step 1 of 2</span>
			</div>
		</div>
		<div class="instant-articles-card-content">
			<div class="instant-articles-card-content-box instant-articles-card-content-left">
				<label>First, log in with your Facebook Developers App</label>

				<p>Log in with your Facebook Developers App ID to connect your plugin to the Facebook Page you'll use to publish your Instant Articles.</p>

				<p><strong>Already have an App ID and Secret?</strong> Just enter them to the right.</p>

				<p><strong>Need to create one?</strong></p>

				<p>Click on the 'Get App ID' button below to begin the process of getting your App ID and Secret.  This will open the App set up page in a new tab. Then, follow these steps:</p>

				<ol>
					<li>Create an app name, input your email and select 'Apps for Pages' in the 'Category' dropdown menu. Click 'Create App ID' when you’re ready.</li>
					<li>Click on 'Settings' in the left nav bar.</li>
					<li>Click '+Add Platform' at the bottom of the page and select 'Website.'</li>
					<li>Under 'Website,' enter your URL. Click 'Save Changes' in the lower right corner.</li>
					<li>Select 'Show' to see your App Secret. Copy your App ID and Secret and enter them to the right.</li>
				</ol>

				<p><strong>Need more help?</strong> <a href="https://developers.facebook.com/docs/instant-articles/wordpress-quickstart#appid" target="_blank">Learn more</a>.</p>

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
				<a href="<?php echo esc_attr( $fb_helper->get_login_url() ); ?>" class="instant-articles-button">
					<span class="instant-articles-button-icon-facebook"></span>
					<label>Login with Facebook</label>
				</a>
			</div>
			<br clear="both" />
		</div>
	</div>
<?php endif; ?>
