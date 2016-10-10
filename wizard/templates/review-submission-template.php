<?php
/**
 * Facebook Instant Articles for WP.
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 *
 * @package default
 */
?>
<div class="instant-articles-card instant-articles-card-collapsed">
	<div class="instant-articles-card-title">
		<h3>Logged In</h3>
		<div class="instant-articles-card-title-right">
			<span class="instant-articles-card-title-checkmark">✔</span>
			<label class="instant-articles-card-title-label">App connected:</label>
			<span class="instant-articles-card-title-value"><?php echo esc_html( $fb_app_settings[ 'app_id' ] ); ?></span>
			<a class="instant-articles-wizard-transition instant-articles-card-title-edit" href="#" data-new-state="<?php echo esc_attr( Instant_Articles_Wizard_State::STATE_APP_SETUP ); ?>"></a>
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
			<a class="instant-articles-wizard-transition instant-articles-card-title-edit" href="#" data-new-state="<?php echo esc_attr( Instant_Articles_Wizard_State::STATE_PAGE_SELECTION ); ?>"></a>
		</div>
	</div>
</div>

<div class="instant-articles-card instant-articles-card-collapsed">
	<div class="instant-articles-card-title">
		<h3>Style Customized</h3>
		<div class="instant-articles-card-title-right">
			<a class="instant-articles-wizard-transition instant-articles-card-title-edit" href="#" data-new-state="<?php echo esc_attr( Instant_Articles_Wizard_State::STATE_STYLE_SELECTION ); ?>"></a>
		</div>
	</div>
</div>

<?php
switch ( $review_submission_status ) :

	case Instant_Articles_Wizard_Review_Submission::STATUS_NOT_SUBMITTED : ?>

		<?php if ( count( $articles_for_review ) >=  Instant_Articles_Wizard_Review_Submission::MIN_ARTICLES ) : ?>
			<?php if ( count( $instant_articles_with_warnings ) > 0 ) : ?>
				<div class="instant-articles-card">
					<div class="instant-articles-card-title">
						<h3>Submit for Review</h3>
					</div>
					<div class="instant-articles-card-content">
						<div class="instant-articles-card-content-box instant-articles-card-content-full">
							<p>
								In order to begin publishing Instant Articles, our team needs to review a sample
								batch of <?php echo esc_html( Instant_Articles_Wizard_Review_Submission::MIN_ARTICLES ); ?> of your Instant Articles.
							</p>
							<p>
								The plugin tried to automatically transform your last <?php echo esc_html( Instant_Articles_Wizard_Review_Submission::MIN_ARTICLES ); ?>
								posts into Instant Articles as a sample to submit for review, but some of these posts contained elements the plugin didn't know how to convert.
							</p>
							<p>
								Before submitting for review, you'll need to handle the warnings on these articles by looking at the "Instant Articles" box on the post edit screen:
							</p>
							<ul>
								<?php foreach ($instant_articles_with_warnings as $article): ?>
									<li><?php edit_post_link( $article->get_the_title(), '- ', '', $article->get_the_id() ); ?> </li>
								<?php endforeach; ?>
							</ul>
							<p>
								Once you've handled these warnings, please return to this page and click the 'Submit for Review" button below.
							</p>
							<button id="instant-articles-wizard-submit-for-review" class="instant-articles-button-disabled instant-articles-button instant-articles-button-highlight">
								<label>Submit for Review</label>
							</button>
						</div>
					</div>
				</div>
			<?php else: ?>
				<div class="instant-articles-card">
					<div class="instant-articles-card-title">
						<h3>Submit for Review</h3>
					</div>
					<div class="instant-articles-card-content">
						<div class="instant-articles-card-content-box instant-articles-card-content-full">
							<p>
								The Instant Articles team will review a sample batch of your Instant Articles before you can begin to publish.
								Click the 'Submit for Review" button below to send the last <?php echo esc_html( Instant_Articles_Wizard_Review_Submission::MIN_ARTICLES ); ?>
								articles you've published to the team for review.
							</p>
							<p>
								It will take us 2 business days to complete the review.
								Once we've had a chance to take a look, we'll let you know if you're ready to start publishing or if you need to make some updates.
							</p>
							<button id="instant-articles-wizard-submit-for-review" class="instant-articles-button instant-articles-button-highlight">
								<label>Submit for Review</label>
							</button>
						</div>
					</div>
				</div>
			<?php endif; ?>
		<?php else: ?>
			<div class="instant-articles-card">
				<div class="instant-articles-card-title">
					<h3>Create More Articles to Submit for Review</h3>
				</div>
				<div class="instant-articles-card-content">
					<div class="instant-articles-card-content-box instant-articles-card-content-full">
						<p>
							In order to begin publishing Instant Articles, our team needs to review a sample batch of <?php echo esc_html( Instant_Articles_Wizard_Review_Submission::MIN_ARTICLES ); ?>
							of your Instant Articles. It looks like you don't have <?php echo esc_html( Instant_Articles_Wizard_Review_Submission::MIN_ARTICLES ); ?> articles available yet.
						</p>
						<p>
							Once you've created the additional articles and have <?php echo esc_html( Instant_Articles_Wizard_Review_Submission::MIN_ARTICLES ); ?> available to send,
							please return to this page and click the 'Submit for Review" button below.
						</p>
						<button id="instant-articles-wizard-submit-for-review" class="instant-articles-button-disabled instant-articles-button instant-articles-button-highlight">
							<label>Submit for Review</label>
						</button>
					</div>
				</div>
			</div>
		<?php endif; ?>


	<?php break; ?>

	<?php case Instant_Articles_Wizard_Review_Submission::STATUS_APPROVED : ?>
		<div class="instant-articles-card instant-articles-card-success">
			<div class="instant-articles-card-title">
				<h3>Review Complete: Begin Publishing Your Instant Articles</h3>
			</div>
			<div class="instant-articles-card-content">
				<div class="instant-articles-card-content-box instant-articles-card-content-full">
					<p>
						We reviewed some of your articles and they look great.
						You're now ready to begin publishing your Instant Articles to Facebook and sharing them with your audience.
					</p>
					<p>
						Next, set up monetization and analytics in Advanced Settings. Or explore the
						<a href="<?php echo esc_url( 'https://www.facebook.com/' . $fb_page_settings['page_id'] . '/publishing_tools/?section=INSTANT_ARTICLES_SETTINGS' ); ?>" target="_blank">Instant Articles publishing tools</a>
						through your selected Facebook Page.
					</p>
				</div>
			</div>
		</div>
	<?php break; ?>

	<?php case Instant_Articles_Wizard_Review_Submission::STATUS_REJECTED : ?>
		<div class="instant-articles-card instant-articles-card-fail">
			<div class="instant-articles-card-title">
				<h3>Review Complete: Updates Needed</h3>
			</div>
			<div class="instant-articles-card-content">
				<div class="instant-articles-card-content-box instant-articles-card-content-full">
					<p>
						We reviewed some of your articles and found a few things that need to be updated.
					</p>
					<p>
						Please go to your selected
						<a href="<?php echo esc_url( 'https://www.facebook.com/' . $fb_page_settings['page_id'] . '/publishing_tools/?section=INSTANT_ARTICLES_SETTINGS#Step-2' ); ?>" target="_blank">Facebook Page's Publishing Tools</a>
						to get more specifics on the issues identified. Once these have been cleared up in WordPress, you'll be ready to begin publishing your Instant Articles.
					</p>
				</div>
			</div>
		</div>
	<?php break; ?>

	<?php case Instant_Articles_Wizard_Review_Submission::STATUS_PENDING : ?>
		<div class="instant-articles-card">
			<div class="instant-articles-card-title">
				<h3>Review Articles Submitted</h3>
			</div>
			<div class="instant-articles-card-content">
				<div class="instant-articles-card-content-box instant-articles-card-content-full">
					<p>
						Your articles have been submitted for review.
					</p>
					<p>
						It will take us 2 business days to complete the review. Check back here to see the status of your review submission.
					</p>
				</div>
			</div>
		</div>
	<?php break; ?>

<?php endswitch; ?>
