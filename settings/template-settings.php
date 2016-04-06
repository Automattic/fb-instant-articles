<?php
/**
 * Facebook Instant Articles for WP.
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 *
 * @package default
 */

?>
<div class="wrap">
	<h1>Facebook Instant Articles Settings</h1>

	<h2 id="instant-articles-settings-tabs" class="nav-tab-wrapper">
		<a
			class="nav-tab <?php if ( 'basic' === $tab ) : ?>nav-tab-active<?php endif; ?>"
			href="<?php echo esc_attr( $settings_page_href . '&current_tab=basic' ) ?>"
		>
			Initial Setup
		</a>
		<a
			class="nav-tab <?php if ( 'advanced' === $tab ) : ?>nav-tab-active<?php endif; ?>"
			href="<?php echo esc_attr( $settings_page_href . '&current_tab=advanced' ) ?>"
		>
			Advanced
		</a>
	</h2>

	<div
		id="instant-articles-settings-basic"
		<?php if ( 'basic' !== $tab ) : ?>style="display: none;"<?php endif; ?>
	>
		<?php include( dirname( __FILE__ ) . '/template-settings-wizard.php' ); ?>
	</div>
	<div
		id="instant-articles-settings-advanced"
		<?php if ( 'advanced' !== $tab ) : ?>style="display: none;"<?php endif; ?>
	>
		<?php include( dirname( __FILE__ ) . '/template-settings-advanced.php' ); ?>
	</div>
</div>
