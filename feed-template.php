<?php
header( 'Content-Type: ' . feed_content_type( 'rss2' ) . '; charset=' . get_option( 'blog_charset' ), true );
echo '<?xml version="1.0" encoding="' . esc_attr( get_option( 'blog_charset' ) ) . '"?' . '>';
?>
<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/">
	<channel>
		<title><?php bloginfo_rss( 'name' ); ?> - <?php esc_html_e( 'Instant Articles', 'instant-articles' ); ?></title>
		<link><?php bloginfo_rss('url') ?></link>
		<description><?php bloginfo_rss( 'description' ) ?></description>
		<lastBuildDate><?php echo mysql2date( 'c', get_lastpostmodified( 'GMT' ), false ); ?></lastBuildDate>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php
			$instant_article_post = new Instant_Articles_Post( get_the_ID() );
			
			$content = $instant_article_post->get_the_content();
			if ( ! strlen( trim( $content ) ) ) {
				// We don’t want to include articles without content -- FB will complain
				continue;
			}
			?>
			<item>
				<title><?php echo $instant_article_post->get_the_title_rss(); ?></title>
				<link><?php echo $instant_article_post->get_canonical_url(); ?></link>
				<content:encoded><![CDATA[<?php $instant_article_post->render(); ?>]]></content:encoded>
				<guid isPermaLink="false"><?php the_guid(); ?></guid>
				<description><![CDATA[<?php echo $instant_article_post->get_the_excerpt_rss(); ?>]]></description>
				<pubDate><?php echo $instant_article_post->get_the_pubdate_iso(); ?></pubDate>
				<modDate><?php echo $instant_article_post->get_the_moddate_iso(); ?></modDate>
				<?php $authors = $instant_article_post->get_the_authors(); ?>
				<?php if ( is_array( $authors ) && count( $authors ) ) : ?>
					<?php foreach ( $authors as $author ) : ?>
						<author><?php echo esc_html( $author->display_name ); ?></author>
					<?php endforeach; ?>
				<?php endif; ?>
			</item>
		<?php endwhile; ?>
	</channel>
</rss>
