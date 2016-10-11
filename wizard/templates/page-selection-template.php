<?php
/**
 * Facebook Instant Articles for WP.
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 *
 * @package default
 */
?>
<div class="instant-articles-card instant-articles-card-collapsed">
	<div class="instant-articles-card-title">
		<h3>Logged In</h3>
		<div class="instant-articles-card-title-right">
			<span class="instant-articles-card-title-checkmark">✔</span>
			<label class="instant-articles-card-title-label">App connected:</label>
			<span class="instant-articles-card-title-value"><?php echo esc_html( $fb_app_settings[ 'app_id' ] ); ?></span>
			<a class="instant-articles-wizard-transition instant-articles-card-title-edit" href="#" data-new-state="<?php echo esc_attr( Instant_Articles_Wizard_State::STATE_APP_SETUP ); ?>"></a>
		</div>
	</div>
</div>

<div class="instant-articles-card">
	<div class="instant-articles-card-title">
		<h3>Which Page Would You Like to Use for Instant Articles?</h3>
	</div>
	<div class="instant-articles-card-content">
		<div class="instant-articles-card-content-box instant-articles-card-content-full">
			<p>
				Select the Page you'd like to use to access the Instant Articles tools.
				Anyone with an admin role will also be able to use the tools.
				Don't have a Page yet?
				<strong><a href="https://www.facebook.com/pages/create" target="_blank">Create one</a>.</strong>
			</p>
			<ul class="instant-articles-wizard-page-selection">
				<?php foreach ( $pages as $page ) { ?>
					<li>
						<input
							type="radio"
							name="page_id"
							value="<?php echo esc_attr( $page[ 'page_id' ] ) ?>"
							data-signed-up="<?php echo $page[ 'supports_instant_articles' ] ? 'yes' : 'no'; ?>"
						/>
						<img class="instant-articles-page-img" src="<?php echo esc_attr( $page[ 'page_picture' ] ) ?>"/>
						<label>
							<?php echo esc_html( $page[ 'page_name' ] ) ?>
							<?php if ( ! $page[ 'supports_instant_articles' ] ) : ?>
								<span class="page-not-enabled">
									First, <a href="https://www.facebook.com/instant_articles/signup?redirect_uri=<?php echo rawurlencode( $settings_url ) ?>&page_id=<?php echo rawurlencode( $page[ 'page_id' ] ) ?>">sign up</a>
									this Page to access the Instant Articles tools through Facebook. Then select it here.
								</span>
							<?php endif; ?>
						</label>
					</li>
				<?php } ?>
			</ul>
			<button id="instant-articles-wizard-select-page" class="instant-articles-button instant-articles-button-highlight instant-articles-button-disabled">
				<label>Select</label>
			</button>
		</div>
	</div>
</div>
