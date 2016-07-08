<?php
/**
 * Facebook Instant Articles for WP.
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 *
 * @package default
 */
?>
<?php $status = Instant_Articles_Settings_Review::getReviewSubmissionStatus(); ?>
<?php $articleURLs = Instant_Articles_Settings_Review::getArticlesURLs(); ?>
<?php $unsubmitted_articles = Instant_Articles_Settings_Review::getUnsubmittedArticles( $articleURLs ); ?>
<?php $pending_submission = Instant_Articles_Settings_Review::MIN_ARTICLES - count($articleURLs); ?>
<?php $pending_writing = $pending_submission - count( $unsubmitted_articles ); ?>

<?php switch ( $status ) :
	case 'REJECTED': ?>
<pre>
Your site was rejected. Please read the review feedback [here], fix the reported issues
and submit it for review again.

[ Submit for review ]
</pre>
<?php break; ?>
<?php case 'APPROVED': ?>
<pre>
Your site was approved. All new articles will be automatically pushed to Instant Articles.
</pre>
<?php break; ?>
<?php case 'PENDING': ?>
<pre>
Your site is currently under review.
</pre>
<?php break; ?>
<?php case 'NOT_SUBMITTED': ?>

<?php if ( count($articleURLs) >= Instant_Articles_Settings_Review::MIN_ARTICLES ) : ?>
<pre>
Progress: [<?php for ($i = 0; $i < Instant_Articles_Settings_Review::MIN_ARTICLES; $i++) : ?>#<?php endfor; ?>]
[ Submit for review ]
</pre>
<?php else : ?>
<pre>
Progress: [<?php
	for ($i = 0; $i < count( $articleURLs ); $i++) : ?>#<?php endfor;
	for ($i = 0; $i < $pending_submission; $i++) : ?>-<?php endfor;
?>]

<?php if ( count( $unsubmitted_articles ) >  0 ) : ?>
You need to submit <?php echo $pending_submission; ?> more articles before review:

<?php foreach ( $unsubmitted_articles as $post ) : ?>
[ Submit to Instant Articles ] <?php echo $post->post_title ?>

<?php endforeach; ?>
<?php if ( $pending_writing > 0 ) ?>

You still need to create at least more <?php echo $pending_writing; ?> articles.
<?php else : ?>

You still need to create at least more <?php echo $pending_submission; ?> articles.
<?php endif; ?>
</pre>
<?php endif; ?>
<?php endswitch; ?>
