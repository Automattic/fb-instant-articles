<?php if ( empty( $fb_app_settings['app_id'] ) || empty( $fb_app_settings['app_secret'] ) ) : ?>

	<!-- Step 1: grab your App ID and App Secret -->
	<div class="instant-articles-card">
		<div class="instant-articles-card-title">
			<h3>Authenticate Plugin</h3>
			<div class="instant-articles-card-title-right">
				<span class="instant-articles-card-title-step">Step 1 of 2</span>
			</div>
		</div>
		<div class="instant-articles-card-content">
			<div class="instant-articles-card-content-box instant-articles-card-content-left">
				<label>Title of this section</label>
				<p>
					Yo dawg, you need a Facebook App to enable this plugin. It's for really cool security reasons.
					If you already know what I'm sayin' and have an App ID and App Secret, <a href="#">go here</a> to grab it.
					If you don't have any idea what's going on then go ahead and get an App ID already.
				</p>
				<button class="instant-articles-button">
					<label>Get App ID</label>
				</button>
			</div>
			<div class="instant-articles-card-content-box instant-articles-card-content-right">
				<label class="instant-articles-label">App ID</label>
				<input name="app_id" class="instant-articles-input-text" type="text"/>
				<label class="instant-articles-label">App Secret</label>
				<input name="app_secret" class="instant-articles-input-text" type="password"/>
				<button id="instant-articles-wizard-save-app" class="instant-articles-button instant-articles-button-highlight instant-articles-button-disabled">
					<label>Submit</label>
				</button>
			</div>
		</div>
	</div>

<?php else: ?>

	<!-- Step 2: log in with Facebook -->
	<div class="instant-articles-card">
		<div class="instant-articles-card-title">
			<h3>Authenticate Plugin</h3>
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
				<label>Connect your Facebook Account</label>
				<p>Login with Facebook to finish activating the Instant Articles Plugin.</p>
				<a href="<?php echo esc_attr( $fb_helper->get_login_url() ); ?>" class="instant-articles-button">
					<span class="instant-articles-button-icon-facebook"></span>
					<label>Login with Facebook</label>
				</a>
			</div>
		</div>
	</div>
<?php endif; ?>
