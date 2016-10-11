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

<?php if ( $current_state !== Instant_Articles_Wizard_State::STATE_OVERVIEW ): ?>
	<?php
	// Calculate classes for the timeline
	$state_css_classes = array();
	foreach ( Instant_Articles_Wizard_State::$timeline as $state => $order ) {
		switch ( Instant_Articles_Wizard_State::get_timeline_position( $state ) ) {
			case Instant_Articles_Wizard_State::TIMELINE_PAST:
				$state_css_classes[ $state ] = 'instant-articles-card-bullet-step-completed';
				break;
			case Instant_Articles_Wizard_State::TIMELINE_CURRENT:
				$state_css_classes[ $state ] = 'instant-articles-card-bullet-step-current';
				break;
			case Instant_Articles_Wizard_State::TIMELINE_FUTURE:
				$state_css_classes[ $state ] = '';
				break;
		}
	}
	?>

	<div class="instant-articles-card-bullet-bar">
		<div class="instant-articles-card-bullet-step <?php echo esc_attr( $state_css_classes[ Instant_Articles_Wizard_State::STATE_APP_SETUP ] ); ?>">
			<div class="instant-articles-card-bullet"></div>
			<div class="instant-articles-card-bullet-path"></div>
			<?php if ( Instant_Articles_Wizard_State::get_timeline_position( Instant_Articles_Wizard_State::STATE_APP_SETUP  ) === Instant_Articles_Wizard_State::TIMELINE_PAST ) : ?>
				<h4>Logged In</h4>
			<?php else : ?>
				<h4>Set Up and Log In</h4>
			<?php endif; ?>
			<p>If you don't have one already, set up your Facebook Developers App. This will allow you to log in and connect your plugin.</p>
		</div>
		<div class="instant-articles-card-bullet-step <?php echo esc_attr( $state_css_classes[ Instant_Articles_Wizard_State::STATE_PAGE_SELECTION ] ); ?>">
			<div class="instant-articles-card-bullet"></div>
			<div class="instant-articles-card-bullet-path"></div>
			<?php if ( Instant_Articles_Wizard_State::get_timeline_position( Instant_Articles_Wizard_State::STATE_PAGE_SELECTION  ) === Instant_Articles_Wizard_State::TIMELINE_PAST ) : ?>
				<h4>Page Selected</h4>
			<?php else : ?>
				<h4>Select Your Page</h4>
			<?php endif; ?>
			<p>Select the Page you'll use to publish your Instant Articles.</p>
		</div>
		<div class="instant-articles-card-bullet-step <?php echo esc_attr( $state_css_classes[ Instant_Articles_Wizard_State::STATE_STYLE_SELECTION ] ); ?>">
			<div class="instant-articles-card-bullet"></div>
			<div class="instant-articles-card-bullet-path"></div>
			<?php if ( Instant_Articles_Wizard_State::get_timeline_position( Instant_Articles_Wizard_State::STATE_STYLE_SELECTION  ) === Instant_Articles_Wizard_State::TIMELINE_PAST ) : ?>
				<h4>Style Customized</h4>
			<?php else : ?>
				<h4>Customize Your Style</h4>
			<?php endif; ?>
			<p>Use our Style Editor to make your Instant Articles look just how you want them to.</p>
		</div>
		<div class="instant-articles-card-bullet-step <?php echo esc_attr( $state_css_classes[ Instant_Articles_Wizard_State::STATE_REVIEW_SUBMISSION ] ); ?>">
			<div class="instant-articles-card-bullet"></div>
			<?php if ( Instant_Articles_Wizard_State::get_timeline_position( Instant_Articles_Wizard_State::STATE_REVIEW_SUBMISSION  ) === Instant_Articles_Wizard_State::TIMELINE_PAST ) : ?>
				<h4>Review Complete</h4>
			<?php else : ?>
				<h4>Submit for Review</h4>
			<?php endif; ?>
			<p>Submit your Instant Articles for review and start publishing.</p>
		</div>
	</div>

<?php endif; ?>

<?php
	switch ( $current_state ) {
		case Instant_Articles_Wizard_State::STATE_OVERVIEW:
			include( dirname( __FILE__ ) . '/overview-template.php' );
			break;
		case Instant_Articles_Wizard_State::STATE_APP_SETUP:
			include( dirname( __FILE__ ) . '/app-setup-template.php' );
			break;
		case Instant_Articles_Wizard_State::STATE_PAGE_SELECTION:
			include( dirname( __FILE__ ) . '/page-selection-template.php' );
			break;
		case Instant_Articles_Wizard_State::STATE_STYLE_SELECTION:
			include( dirname( __FILE__ ) . '/style-selection-template.php' );
			break;
		case Instant_Articles_Wizard_State::STATE_REVIEW_SUBMISSION:
			include( dirname( __FILE__ ) . '/review-submission-template.php' );
			break;
	}
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
