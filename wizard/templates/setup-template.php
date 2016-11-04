<?php
/**
 * Facebook Instant Articles for WP.
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 *
 * @package default
 */
?>
<?php if ( ! $ajax ) : ?>
	<h1>Facebook Instant Articles Settings</h1>
	<div id="instant_articles_wizard_messages"><?php settings_errors(); ?></div>
	<div id="instant_articles_wizard">
<?php endif; ?>

<?php
	include( dirname( __FILE__ ) . '/setup-opengraph-template.php' );
?>

<?php if ( ! $ajax ) : ?>
	</div>

	<?php if ( count( get_settings_errors() ) !== 0 ) : ?>
		<p class="instant-articles-advanced-settings" data-state="opened">
	<?php else: ?>
		<p class="instant-articles-advanced-settings" data-state="closed">
	<?php endif; ?>

		<span class="instant-articles-wizard-toggle instant-articles-wizard-toggle-closed">Already set up? <a href="#">Open Advanced Settings now</a></span>
		<span class="instant-articles-wizard-toggle instant-articles-wizard-toggle-opened"><a href="#">Close Advanced Settings</a></span>
	</p>

	<div class="instant-articles-wizard-advanced-settings-box">
		<?php include( dirname( __FILE__ ) . '/advanced-template.php' ); ?>
	</div>
<?php endif; ?>
