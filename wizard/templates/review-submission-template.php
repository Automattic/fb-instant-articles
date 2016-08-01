<div class="instant-articles-card instant-articles-card-collapsed">
	<div class="instant-articles-card-title">
		<h3>Authenticate Plugin</h3>
		<div class="instant-articles-card-title-right">
			<span class="instant-articles-card-title-checkmark">✔</span>
			<label class="instant-articles-card-title-label">App connected:</label>
			<span class="instant-articles-card-title-value"><?php echo esc_html( $fb_app_settings[ 'app_id' ] ); ?></span>
			<a class="instant-articles-wizard-transition instant-articles-card-title-edit" href="#" data-new-state="<?php echo esc_html( Instant_Articles_Wizard_State::STATE_APP_SETUP ); ?>"></a>
		</div>
	</div>
</div>

<div class="instant-articles-card instant-articles-card-collapsed">
	<div class="instant-articles-card-title">
		<h3>Facebook Page Enabled</h3>
		<div class="instant-articles-card-title-right">
			<a href="" class="instant-articles-card-title-link"><?php echo esc_html( $fb_page_settings[ 'page_name' ] ) ?></a>
			<?php if ( $fb_page_settings[ 'page_picture' ] ) : ?>
				<img class="instant-articles-page-img" src="<?php echo esc_attr( $fb_page_settings[ 'page_picture' ] ) ?>"/>
			<?php endif; ?>
			<a class="instant-articles-wizard-transition instant-articles-card-title-edit" href="#" data-new-state="<?php echo esc_html( Instant_Articles_Wizard_State::STATE_PAGE_SELECTION ); ?>"></a>
		</div>
	</div>
</div>

<div class="instant-articles-card instant-articles-card-collapsed">
	<div class="instant-articles-card-title">
		<h3>Style Selected</h3>
		<div class="instant-articles-card-title-right">
			<a class="instant-articles-wizard-transition instant-articles-card-title-edit" href="#" data-new-state="<?php echo esc_html( Instant_Articles_Wizard_State::STATE_STYLE_SELECTION ); ?>"></a>
		</div>
	</div>
</div>

<div class="instant-articles-card">
	<div class="instant-articles-card-title">
		<h3>Submit for Review</h3>
	</div>
	<div class="instant-articles-card-content">
		<div class="instant-articles-card-content-box instant-articles-card-content-full">
			<p>Lorem ipsum dolor sit amet, consectetuer adipscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis notoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim.</p>
			<button class="instant-articles-button instant-articles-button-highlight">
				<label>Submit for Review</label>
			</button>
		</div>
	</div>
</div>
