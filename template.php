<!doctype html>
<html lang="en" prefix="op: http://media.facebook.com/op#">
	<?php
	$featured_image = $this->get_the_featured_image();
	$cover_type = $this->get_newsfeed_cover();
	?>
	<head>
		<meta property="og:title" content="<?php echo esc_html( $this->get_the_title() ); ?>">
		<meta property="og:description" content="<?php echo esc_html( $this->get_the_excerpt() ); ?>">
		<?php if ( $cover_type == "image" ) : ?>
			<meta property="og:image" content="<?php echo esc_url( $featured_image['src'] ); ?>">
		<?php endif; ?>
		<meta charset="utf-8">
		<link rel="canonical" href="<?php echo esc_url( $this->get_canonical_url() ); ?>">
		<meta property="op:markup_version" content="v1.0">
		<meta property="fb:article_style" content="<?php echo esc_attr( $this->get_article_style() ); ?>">
	</head>

	<body>
		<article>
			<header>
				<!-- The cover -->
				<?php $cover_media = $this->get_cover_media(); ?>
				<?php if ( 'image' == $cover_media->type ) : ?>
					<figure>
						<img src="<?php echo esc_url( $cover_media->src ); ?>" />
						<?php if ( strlen( $cover_media->caption ) ) : ?>
							<figcaption><?php echo esc_html( $cover_media->caption ); ?></figcaption>
						<?php endif; ?>
					</figure>
				<?php elseif ( 'video' == $cover_media->type ) : ?>
					<figure>
						<video>
							<source src="<?php echo esc_url( $cover_media->src ); ?>" type="<?php echo esc_attr( $cover_media->mime_type ); ?>" />
						</video>
					</figure>
				<?php elseif ( 'slideshow' == $cover_media->type ) : ?>
					<figure class="op-slideshow">
						<?php foreach ( $cover_media->items as $item ) : ?>
							<figure>
								<img src="<?php echo esc_url( $item->$src ); ?>" />
							</figure>
						<?php endforeach; ?>
					</figure>
				<?php endif; ?>

				<h1><?php echo esc_html( $this->get_the_title() ); ?></h1>

				<!-- The date and time when your article was originally published -->
				<time class="op-published" datetime="<?php echo esc_attr( $this->get_the_pubdate_iso() ); ?>"><?php echo esc_html( $this->get_the_pubdate() ); ?></time>

				<!-- The date and time when your article was last updated -->
				<time class="op-modified" datetime="<?php echo esc_attr( $this->get_the_moddate_iso() ); ?>"><?php echo esc_html( $this->get_the_moddate() ); ?></time>

				<!-- The authors of your article -->
				<?php
				if ( is_array( $this->get_the_authors() ) && count( $this->get_the_authors() ) ) {

					foreach ( $this->get_the_authors() as $author ) {

						$display_name = apply_filters( 'instant_articles_author_display_name', $author->display_name, $author );
						$link = apply_filters( 'instant_articles_author_link', get_author_posts_url( $author->ID, $author->user_nicename ), $author );

						if ( ! empty( $link ) ) {
							$output = sprintf( '<address><a href="%1$s">%2$s</a>%3$s</address>',
								esc_url( $link ),
								esc_html( $display_name ),
								esc_html( $author->bio )
							);
						} else {
							$output = '<address><a>' . esc_html( $display_name ) . '</a>' . esc_html ( $author->bio ) . '</address>';
						}

						echo apply_filters( 'instant_articles_author_content', $output, $author );
					}
				}
				?>

				<?php if ( $kicker_text = $this->get_the_kicker() ) : ?>
					<!-- A kicker for your article -->
					<h3 class="op-kicker"><?php echo esc_html( $kicker_text); ?></h3>
				<?php endif; ?>
			</header>

			<!-- Article body goes here -->
			<?php echo $this->get_the_content(); ?>

			<!-- Body text for your article -->
			<p> Article content </p>

			<!-- A video within your article -->
			<!-- TODO: Change the URL to a live video from your website -->
			<figure<?php if ( $cover_type == "video" ) echo ' class="fb-feed-cover"' ?>>
				<video autoplay>
					<source src="http://mydomain.com/path/to/video.mp4" type="video/mp4" />
				</video>
			</figure>

			<!-- An ad within your article -->
			<!-- TODO: Change the URL to a live ad from your website -->
			<figure class="op-ad">
				<iframe src="https://www.adserver.com/ss;adtype=banner320x50" height="60" width="320"></iframe>
			</figure>

			<!-- Analytics code for your article -->
			<figure class="op-tracker">
				<iframe src="" hidden></iframe>
			</figure>

			<footer>
				<?php if ( $footer_credits = $this->get_the_footer_credits( ) ) : ?>
					<!-- Credits for your article -->
					<aside><?php echo esc_html( $footer_credits ); ?></aside>
				<?php endif; ?>

				<?php if ( $footer_copyright = $this->get_the_footer_copyright( ) ) : ?>
					<!-- Copyright details for your article -->
					<small><?php echo esc_html ( $footer_copyright ); ?></small>
				<?php endif; ?>
			</footer>
		</article>
	</body>
</html>