<?php
header( 'Content-Type: ' . feed_content_type( 'rss2' ) . '; charset=' . get_option( 'blog_charset' ), true );
echo '<?xml version="1.0" encoding="' . get_option( 'blog_charset' ) . '"?' . '>';
?>
<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/">
	<title><?php bloginfo_rss( 'name' ); ?> - Instant Articles</title>
	<link><?php bloginfo_rss('url') ?></link>
	<description><?php bloginfo_rss( 'description' ) ?></description>
	<lastBuildDate><?php echo mysql2date( 'c', get_lastpostmodified( 'GMT' ), false ); ?></lastBuildDate>
	<?php while ( have_posts() ) : the_post(); ?>
		<item>
			<title><?php the_title_rss(); ?></title>
			<link><?php the_permalink(); ?></link>
			<content:encoded><![CDATA[<?php instant_articles_render_post( get_the_ID() ); ?>]]></content:encoded>
			<guid isPermaLink="false"><?php the_guid(); ?></guid>
			<description><![CDATA[<?php the_excerpt_rss(); ?>]]></description>
			<pubDate><?php echo mysql2date( 'c', get_post_time( 'Y-m-d H:i:s', true ), false ); ?></pubDate>
			<author><![CDATA[<?php echo esc_html( get_the_author() ); ?>]]></author>
		</item>
	<?php endwhile; ?>
</rss>
<?php
