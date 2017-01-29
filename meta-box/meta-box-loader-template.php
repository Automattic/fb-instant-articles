<?php
/**
 * Facebook Instant Articles for WP.
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 *
 * @package default
 */

?>
<span class="instant_articles_spinner" ></span>

<script>
	instant_articles_load_meta_box( <?php echo absint( $post->ID ); ?> );
</script>
