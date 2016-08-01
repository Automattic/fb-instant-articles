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
				<p>Enter Facebook App ID and connect to Facebook to Enable Plugin</p>
			</div>
			<div class="instant-articles-card-bullet-step <?php echo esc_attr( $state_css_classes[ Instant_Articles_Wizard_State::STATE_PAGE_SELECTION ] ); ?>">
				<div class="instant-articles-card-bullet"></div>
				<div class="instant-articles-card-bullet-path"></div>
				<p>Sign up for Instant Articles and select your Facebook Page</p>
			</div>
			<div class="instant-articles-card-bullet-step <?php echo esc_attr( $state_css_classes[ Instant_Articles_Wizard_State::STATE_STYLE_SELECTION ] ); ?>">
				<div class="instant-articles-card-bullet"></div>
				<div class="instant-articles-card-bullet-path"></div>
				<p>Choose how you want your Instant Articles to look using the Style Editor</p>
			</div>
			<div class="instant-articles-card-bullet-step <?php echo esc_attr( $state_css_classes[ Instant_Articles_Wizard_State::STATE_REVIEW_SUBMISSION ] ); ?>">
				<div class="instant-articles-card-bullet"></div>
				<p>Submit your Instant Articles for review and start publishing</p>
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
	<?php if ( ! empty( get_settings_errors() ) ) : ?>
		<a class="instant-articles-advanced-settings instant-articles-wizard-toggle" href="#">▼ Advanced Settings</a>
		<div class="instant-articles-wizard-advanced-settings-box" style="display: block;">
			<?php include( dirname( __FILE__ ) . '/advanced-template.php' ); ?>
		</div>
	<?php else: ?>
		<a class="instant-articles-advanced-settings instant-articles-wizard-toggle" href="#">► Advanced Settings</a>
		<div class="instant-articles-wizard-advanced-settings-box" style="display: none;">
			<?php include( dirname( __FILE__ ) . '/advanced-template.php' ); ?>
		</div>
	<?php endif; ?>
<?php endif; ?>
