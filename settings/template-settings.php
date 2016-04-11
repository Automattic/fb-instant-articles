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
	<div id="instant-articles-settings-basic" class="instant-articles-settings-box">
		<h2 class="dashicons-before dashicons-arrow-down"> Plugin Activation</h2>
		<div class="inside">
			<?php include( dirname( __FILE__ ) . '/template-settings-wizard.php' ); ?>
		</div>
	</div>

	<div id="instant-articles-settings-info" class="instant-articles-settings-box">
		<h2 class="dashicons-before dashicons-arrow-down"> Instant Articles Configuration</h2>
		<div class="inside">
			<?php include( dirname( __FILE__ ) . '/template-settings-info.php' ); ?>
		</div>
	</div>

	<div id="instant-articles-settings-advanced" class="instant-articles-settings-box">
		<h2 class="dashicons-before dashicons-arrow-down"> Plugin Configuration</h2>
		<div class="inside">
			<?php include( dirname( __FILE__ ) . '/template-settings-advanced.php' ); ?>
		</div>
	</div>
</div>
