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
<pre>
Progress: 5 [#############################################] 10

[ Submit for review ]
</pre>
<pre>
Progress: 5 [#####################------------------------] 10

Articles not on Instant Articles:
_______________________________________________________
Title                      | Submit to Instant Articles
_______________________________________________________
Title                      | Submit to Instant Articles
_______________________________________________________
Title                      | Submit to Instant Articles
_______________________________________________________
Title                      | Submit to Instant Articles

You need to submit 5 more articles before review.
</pre>
<?php endswitch ?>
