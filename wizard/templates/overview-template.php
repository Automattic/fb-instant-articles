<?php
/**
 * Facebook Instant Articles for WP.
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 *
 * @package default
 */
?>
<div class="instant-articles-card">
	<div class="instant-articles-card-content">
		<div class="instant-articles-card-content-box instant-articles-card-content-full">
			<h3>Get Started with the Instant Articles WordPress Plugin</h3>
			<div class="instant-articles-card-steps">
				<div class="instant-articles-card-step">
					<img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) ); ?>../../assets/key@2x.png">
					<h4>Set Up and Log In</h4>
					<p>If you don't have one already, set up your Facebook Developers App. This will allow you to log in and connect your plugin.</p>
				</div>
				<div class="instant-articles-card-step">
					<img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) ); ?>../../assets/connect@2x.png">
					<h4>Select Your Page</h4>
					<p>Select the Page you'll use to publish your Instant Articles.</p>
				</div>
				<div class="instant-articles-card-step">
					<img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) ); ?>../../assets/customize@2x.png">
					<h4>Customize Your Style</h4>
					<p>Use our Style Editor to make your Instant Articles look just how you want them to.</p>
				</div>
				<div class="instant-articles-card-step">
					<img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) ); ?>../../assets/check@2x.png">
					<h4>Submit for Review</h4>
					<p>Submit your Instant Articles for review and start publishing.</p>
				</div>
			</div>
			<button
				class="instant-articles-button instant-articles-button-highlight instant-articles-button-centered instant-articles-wizard-transition"
				data-new-state="STATE_APP_SETUP">
				<label>Get Started</label>
			</button>
		</div>
	</div>
</div>
