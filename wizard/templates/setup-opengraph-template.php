<?php
/**
 * Facebook Instant Articles for WP.
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 *
 * @package default
 */
?>

<?php if ( empty( $fb_page_settings['page_id'] ) ) : ?>
<div class="instant-articles-card">
	<div class="instant-articles-card-title">
		<h3>Which Page Would You Like to Use for Instant Articles?</h3>
	</div>

	<div class="instant-articles-card-content">
		<div class="instant-articles-card-content-box instant-articles-card-content-left">
			<label>Enter your Page ID or your page URL</label>

			<p>You'll need to input the page URL or the page ID in order to setup.</p>

			<p><strong>Already have the Page ID?</strong> Just enter it on the right.</p>

			<p><strong>Need to find yours?</strong></p>

			<p>Click on the 'List my Pages' button below to list the pages you have.</p>

			<ol>
				<li>Remember you need to be Admin in this page in order to setup properly.</li>
				<li>Click with the right button on the page listed > "Copy Link"</li>
				<li>Paste the URL into the field on the right.</li>
			</ol>

			<p><strong>Don't have a Page yet?</strong></p>

			<p>
				<strong><a href="https://www.facebook.com/pages/create" target="_blank">Create one</a>.</strong>
			</p>

			<p>For more detailed instructions on setting, <a href="https://developers.facebook.com/docs/instant-articles/wordpress-quickstart#opengraph" target="_blank">check out the docs</a>.</p>

			<a class="instant-articles-button" href="https://www.facebook.com/bookmarks/pages?__mref=facebook-instant-articles-wp" target="_blank">
				List my Pages
			</a>
		</div>
		<div class="instant-articles-card-content-box instant-articles-card-content-right">
			<label class="instant-articles-label">Page ID</label>
			<input name="page_id" class="instant-articles-input-text" type="text" />
			<button id="instant-articles-opengraph-save-page" class="instant-articles-button instant-articles-button-highlight instant-articles-button-disabled">
				<label>Update</label>
			</button>
		</div>
		<br clear="both" />
	</div>
</div>

<?php else: ?>
<div class="instant-articles-card instant-articles-card-collapsed">
	<div class="instant-articles-card-title">
		<h3>Page already selected</h3>
		<div class="instant-articles-card-title-right">
			<span class="instant-articles-card-title-checkmark">✔</span>
			<label class="instant-articles-card-title-label">Page ID:</label>
			<span class="instant-articles-card-title-value"><?php echo esc_html( $fb_page_settings[ 'page_id' ] ); ?></span>
			<a id="instant-articles-opengraph-edit-page" class="instant-articles-wizard-transition instant-articles-card-title-edit" href="#"></a>
		</div>
	</div>
</div>

<?php endif; ?>
