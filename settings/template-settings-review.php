<?php
/**
 * Facebook Instant Articles for WP.
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 *
 * @package default
 */
?>
<?php $page_id = Instant_Articles_Settings_Review::getPageID(); ?>
<?php $status = Instant_Articles_Settings_Review::getReviewSubmissionStatus(); ?>
<?php $articleURLs = Instant_Articles_Settings_Review::getArticlesURLs(); ?>
<?php $unsubmitted_articles = Instant_Articles_Settings_Review::getUnsubmittedArticles( $articleURLs ); ?>
<?php $pending_submission = Instant_Articles_Settings_Review::MIN_ARTICLES - count($articleURLs); ?>
<?php $pending_writing = $pending_submission - count( $unsubmitted_articles ); ?>

<?php switch ( $status ) :
	case 'REJECTED': ?>
<p>
	Your site was not approved to publish Instant Articles.
	Read the review feedback
	<a target="_blank" href="https://www.facebook.com/<?php echo esc_attr( $page_id ); ?>/publishing_tools/?section=INSTANT_ARTICLES_SETTINGS">here</a>,
	and resolve any feedback on
	<a target="_blank" href="https://developers.facebook.com/docs/instant-articles/guides/design#design-guidelines/">design</a>
	or
	<a target="_blank" href="https://developers.facebook.com/docs/instant-articles/policy/">policy</a>
	violations before submitting for review again.
</p>
<?php break; ?>
<?php case 'APPROVED': ?>
<p>
Your site was approved to publish Instant Articles. All new articles will be automatically published as Instant Articles.
</p>
<?php break; ?>
<?php case 'PENDING': ?>
<p>
Your site is currently under review. Our team will review your articles and provide feedback within 3-5 business days.
</p>
<?php break; ?>
<?php case 'NOT_SUBMITTED': ?>

<?php if ( count($articleURLs) >= Instant_Articles_Settings_Review::MIN_ARTICLES ) : ?>
	You already have <?php echo Instant_Articles_Settings_Review::MIN_ARTICLES; ?> or more articles submitted to Instant Articles.
	<div class="progressbar">
		<div style="width: 100%">
			<div><?php echo Instant_Articles_Settings_Review::MIN_ARTICLES; ?> of <?php echo Instant_Articles_Settings_Review::MIN_ARTICLES; ?> articles</div>
		</div>
	</div>
	<p>
		Submit your articles for review
		<a target="_blank" href="https://www.facebook.com/<?php echo esc_attr( $page_id ); ?>/publishing_tools/?section=INSTANT_ARTICLES_SETTINGS">here</a>.
	</p>
<?php else : ?>
	You must submit at least <?php echo Instant_Articles_Settings_Review::MIN_ARTICLES; ?> articles before submitting for review:
	<div class="progressbar">
		<div style="width: <?php echo intval( ( count( $articleURLs ) / Instant_Articles_Settings_Review::MIN_ARTICLES ) * 100 ); ?>%">
			<div><?php echo count( $articleURLs ); ?> of <?php echo Instant_Articles_Settings_Review::MIN_ARTICLES; ?> articles</div>
		</div>
	</div>
	<?php if ( count( $unsubmitted_articles ) >  0 ) : ?>
		<p>
			Open the following articles and click on "Update" to submit them to Instant Articles:
		</p>

		<ul>
		<?php foreach ( $unsubmitted_articles as $post ) : ?>
			<li><span class="dashicons dashicons-media-document"></span> <?php echo edit_post_link( $post->post_title, null, null, $post->ID ); ?></li>
		<?php endforeach; ?>
		</ul>
	<?php else : ?>
		<p>You still need to create at least <?php echo $pending_submission; ?> more articles.</p>
		<p><a target="_blank" href="<?php echo esc_attr(admin_url('post-new.php')); ?>">+ Create a new post</a></p>
	<?php endif; ?>
<?php endif; ?>
<?php endswitch; ?>
