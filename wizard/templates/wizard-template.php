<div id="instant_articles_wizard">
	<h1>Facebook Instant Articles Settings</h2>

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

	<a class="instant-articles-advanced-settings" href="#">► Advanced Settings</a>

	<!-- Stub transition buttons -->
	<hr />
	<p>Stub transition buttons:</p>
	<?php foreach ( Instant_Articles_Wizard_State::$transition_vectors[ $current_state ] as $new_state => $transition ) : ?>
		<button class="instant-articles-wizard-transition" data-new-state="<?php echo $new_state; ?>">
			<?php echo $transition; ?>
		</button>
	<?php endforeach; ?>
	<button class="instant-articles-wizard-transition" data-new-state="RESET">
		RESET
	</button>

</div>
