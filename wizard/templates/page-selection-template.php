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

<div class="instant-articles-card">
	<div class="instant-articles-card-title">
		<h3>Select Facebook Page</h3>
	</div>
	<div class="instant-articles-card-content">
		<div class="instant-articles-card-content-box instant-articles-card-content-full">
			<p>Select the Facebook Page that you want to enable Instant Articles tools. Anyone with an admin role on that Page will also be able to access your Instant Articles tools.</p>
			<ul>
				<?php foreach ( $fb_helper->get_pages() as $page ) { ?>
					<li>
						<input
							type="radio"
							name="page_id"
							value="<?php echo esc_attr( $page[ 'page_id' ] ) ?>"
							data-signed-up="<?php echo $page[ 'supports_instant_articles' ] ? 'yes' : 'no'; ?>"
						/>
						<img class="instant-articles-page-img" src="<?php echo esc_attr( $page[ 'page_picture' ] ) ?>"/>
						<label><?php echo esc_html( $page[ 'page_name' ] ) ?></label>
					</li>
				<?php } ?>
			</ul>
			<button id="instant-articles-wizard-select-page" class="instant-articles-button instant-articles-button-highlight instant-articles-button-disabled">
				<label>Select</label>
			</button>
		</div>
	</div>
</div>
