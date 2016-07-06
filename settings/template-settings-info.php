<?php
/**
 * Facebook Instant Articles for WP.
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 *
 * @package default
 */
	?>
	<p>
	Once you've activated this WordPress plugin, set up your Instant Articles and submit them to Facebook for a one-time review. The review is required before you can begin publishing. Follow these steps to get started:
</p>
<ol>
	<li><a href="https://www.facebook.com/instant_articles/signup" target="_blank">Sign up</a> for Instant Articles, if you haven't already, and come back to activate the plugin using the page you enabled.
	<li>Claim the URL you will use to publish articles. Right now, we think the URL you will use to publish to this Page is: <code><?php echo esc_url( home_url() ); ?></code>.
		<?php if ( isset( $fb_page_settings['page_id'] ) && ! empty( $fb_page_settings['page_id'] ) ) : ?>
			Claim your URL
			<a
				href="https://www.facebook.com/<?php echo esc_attr( $fb_page_settings['page_id'] ); ?>/settings/?tab=instant_articles#URL" target="_blank">here</a>.
		<?php endif; ?>
	<li>Install the Pages Manager app to preview your articles and styles on <a href="http://itunes.apple.com/app/facebook-pages-manager/id514643583?ls=1&mt=8&ign-mscache=1" target="_blank">iOS</a> or <a href="https://play.google.com/store/apps/details?id=com.facebook.pages.app" target="_blank">Android</a>.
	<li>Create a style template for your articles, using the Style Editor. Be sure to provide the name of the template you want to use in the Plugin Configuration settings below.
	<li>[Optional] Enable Audience Network, if you choose. Learn more about <a href="https://fbinstantarticles.files.wordpress.com/2016/03/audience-network_wp_instant-articles-2-2-web_self-serve.pdf" target="_blank">Audience Network</a> for Instant Articles.
		<?php if ( isset( $fb_page_settings['page_id'] ) && ! empty( $fb_page_settings['page_id'] ) ) : ?>
			Sign up for Audience Network
			<a
				href="https://www.facebook.com/<?php echo esc_attr( $fb_page_settings['page_id'] ); ?>/settings/?tab=instant_articles#Audience-Network" target="_blank">here</a>.
		<?php endif; ?>
	<li>[Optional] Set up your ads and analytics, including Audience Network, in the Configuration area, below.
	<?php if ( isset( $fb_page_settings['page_id'] ) && ! empty( $fb_page_settings['page_id'] ) ) : ?>
		<li>
			<a
			href="https://www.facebook.com/<?php echo esc_attr( $fb_page_settings['page_id'] ); ?>/settings/?tab=instant_articles#Setup-Step2" target="_blank">Submit your articles for review</a>.
		</li>
	<?php else : ?>
		<li>Submit your articles for review.</li>
	<?php endif; ?>

</ol>

<p>Other Resources:</p>
<ol>
	<li>See the Instant Articles <a href="https://developers.facebook.com/docs/instant-articles" target="_blank">documentation</a> to answer any questions you might have about Instant Articles.
	<li>Check out the Instant Articles <a href="https://developers.facebook.com/ia/blog/" target="_blank">blog</a> and sign up to receive notifications of important updates.
	<?php if ( isset( $fb_page_settings['page_id'] ) && ! empty( $fb_page_settings['page_id'] ) ) : ?>
		<li>
			To give other members of your team access to your Instant Articles
			<a
				href="https://www.facebook.com/<?php echo esc_attr( $fb_page_settings['page_id'] ); ?>/settings/?tab=instant_articles" target="_blank">tools</a>
			on your Facebook Page, assign them Page roles
			<a
				href="https://www.facebook.com/<?php echo esc_attr( $fb_page_settings['page_id'] ); ?>/settings/?tab=admin_roles" target="_blank">here</a>.
		</li>
	<?php else : ?>
		<li>To give other members of your team access to your Instant Articles tools on your Facebook Page, assign them Page roles.
	<?php endif; ?>

</ol>
