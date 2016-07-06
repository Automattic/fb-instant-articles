<?php
/**
 * Facebook Instant Articles for WP.
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 *
 * @package default
 */

	?>
<form method="post" action="options.php">
<?php settings_fields( Instant_Articles_Option::PAGE_OPTION_GROUP ); ?>

<p>Configure settings for your styles, ads, analytics and publishing in Instant Articles. Review our <a href="https://developers.facebook.com/docs/instant-articles" target="_blank">developer documentation</a> to learn more.</p>
<hr />
<?php do_settings_sections( Instant_Articles_Option_Styles::OPTION_KEY ); ?>
<hr />
<?php do_settings_sections( Instant_Articles_Option_Ads::OPTION_KEY ); ?>
<hr />
<?php do_settings_sections( Instant_Articles_Option_Analytics::OPTION_KEY ); ?>
<hr />
<?php do_settings_sections( Instant_Articles_Option_Publishing::OPTION_KEY ); ?>
<hr />
<?php submit_button( __( 'Save changes' ) ); ?>
</form>
