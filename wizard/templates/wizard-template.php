<div id="instant_articles_wizard">
	<h1>Facebook Instant Articles Settings</h2>

	<?php if ( $current_state !== Instant_Articles_Wizard_State::STATE_OVERVIEW ): ?>
		<p>Breadcrumbs</p>

		<ul>
			<?php foreach ( Instant_Articles_Wizard_State::$breadcrumbs_order as $state => $order ) : ?>
				<?php if ( Instant_Articles_Wizard_State::$breadcrumbs_order[ $current_state ] > $order ) : ?>
					<li><code>[✔] <?php echo $state; ?></code></li>
				<?php elseif ( Instant_Articles_Wizard_State::$breadcrumbs_order[ $current_state ] == $order ) : ?>
					<li><code>[-] <?php echo $state; ?></code></li>
				<?php else : ?>
					<li><code>[ ] <?php echo $state; ?></code></li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>
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
