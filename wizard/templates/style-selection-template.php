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
			<a class="instant-articles-wizard-transition instant-articles-card-title-edit" href="#" data-new-state="<?php echo esc_html( Instant_Articles_Wizard_State::STATE_APP_SETUP ); ?>"></a>
		</div>
	</div>
</div>

<div class="instant-articles-card instant-articles-card-collapsed">
	<div class="instant-articles-card-title">
		<h3>Page Selected</h3>
		<div class="instant-articles-card-title-right">
			<a href="" class="instant-articles-card-title-link"><?php echo esc_html( $fb_page_settings[ 'page_name' ] ) ?></a>
			<?php if ( $fb_page_settings[ 'page_picture' ] ) : ?>
				<img class="instant-articles-page-img" src="<?php echo esc_attr( $fb_page_settings[ 'page_picture' ] ) ?>"/>
			<?php endif; ?>
			<a class="instant-articles-wizard-transition instant-articles-card-title-edit" href="#" data-new-state="<?php echo esc_html( Instant_Articles_Wizard_State::STATE_PAGE_SELECTION ); ?>"></a>
		</div>
	</div>
</div>

<div class="instant-articles-card">
	<div class="instant-articles-card-title">
		<h3>Customize Your Style with Style Editor</h3>
	</div>
	<div class="instant-articles-card-content">
		<div class="instant-articles-card-content-box instant-articles-card-content-full">
			<p>
				Customize the look and feel of your articles with one or more unique style templates.
				Try to make your Instant Articles look as much like your mobile web articles as possible
				and be sure to upload your publication or blog's logo.
				<a href="https://developers.facebook.com/docs/instant-articles/guides/design" target="_blank">Learn more in our design guidelines</a>.
			</p>
			<p>
				Want to preview your style? Download Facebook Pages Manager on your
				<a href="https://itunes.apple.com/us/app/facebook-pages-manager/id514643583?mt=8" target="_blank">iPhone</a>
				or
				<a href="https://play.google.com/store/apps/details?id=com.facebook.pages.app" target="_blank">Android</a>
				phone.
			</p>
			<a
				href="https://www.facebook.com/<?php echo esc_attr( $fb_page_settings['page_id'] ); ?>/settings/?tab=instant_articles#Style-Editor"
				class="instant-articles-button instant-articles-button-highlight"
				target="_blank">
				Customize
			</a>
			<button class="instant-articles-button instant-articles-wizard-transition" data-new-state="<?php echo esc_html( Instant_Articles_Wizard_State::STATE_REVIEW_SUBMISSION ); ?>">
				<label>Next</label>
			</button>
		</div>
	</div>
</div>
