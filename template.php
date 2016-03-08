<!doctype html>
<html lang="<?php echo esc_attr( get_bloginfo( 'language' ) ); ?>" prefix="op: http://media.facebook.com/op#">
	<head>
		<meta charset="<?php echo esc_attr( get_option( 'blog_charset' ) ); ?>">
		<link rel="canonical" href="<?php echo esc_url( $this->get_canonical_url() ); ?>">
		<meta property="op:markup_version" content="v1.0">
		<meta property="fb:article_style" content="<?php echo esc_attr( $this->get_article_style() ); ?>">

		<?php
		/**
         * Fires in the head element of each article
         *
         * @since 0.1
         *
         * @param Instant_Articles_Post  $ia_post  The current article object
         */
		do_action( 'instant_articles_article_head', $this );
		?>
	</head>

	<body>
		<article>
			<header>
				<!-- The cover -->
				<?php $cover_media = $this->get_cover_media(); ?>
				<?php if ( 'image' === $cover_media->type ) : ?>
					<figure>
						<img src="<?php echo esc_url( $cover_media->src ); ?>" />
						<?php if ( strlen( $cover_media->caption ) ) : ?>
							<figcaption><?php echo esc_html( $cover_media->caption ); ?></figcaption>
						<?php endif; ?>
					</figure>
				<?php elseif ( 'video' === $cover_media->type ) : ?>
					<figure>
						<video>
							<source src="<?php echo esc_url( $cover_media->src ); ?>" type="<?php echo esc_attr( $cover_media->mime_type ); ?>" />
						</video>
					</figure>
				<?php elseif ( 'slideshow' === $cover_media->type ) : ?>
					<figure class="op-slideshow">
						<?php foreach ( $cover_media->items as $item ) : ?>
							<figure>
								<img src="<?php echo esc_url( $item->src ); ?>" />
							</figure>
						<?php endforeach; ?>
					</figure>
				<?php endif; ?>

				<h1><?php echo esc_html( $this->get_the_title() ); ?></h1>

				<?php if ( $this->has_subtitle() ) : ?>
					<h2><?php echo esc_html( $this->get_the_subtitle() ); ?></h2>
				<?php endif; ?>

				<!-- The date and time when your article was originally published -->
				<time class="op-published" datetime="<?php echo esc_attr( $this->get_the_pubdate_iso() ); ?>"><?php echo esc_html( $this->get_the_pubdate() ); ?></time>

				<!-- The date and time when your article was last updated -->
				<time class="op-modified" datetime="<?php echo esc_attr( $this->get_the_moddate_iso() ); ?>"><?php echo esc_html( $this->get_the_moddate() ); ?></time>

				<!-- The authors of your article -->
				<?php $authors = $this->get_the_authors(); ?>
				<?php if ( is_array( $authors ) && count( $authors ) ) : ?>
					<?php foreach ( $authors as $author ) : ?>
						<address>
							<?php
							$attributes = '';
							if ( strlen( $author->user_url ) ) {
								$attributes = ' href="' . esc_url( $author->user_url ) . '"';

								if ( isset( $author->role_contribution ) && strlen( $author->role_contribution ) ) {
									$attributes .= ' title="' . esc_attr( $author->role_contribution ) . '"';
								}

								if ( isset( $author->user_url_rel ) && strlen( $author->user_url_rel ) ) {
									$attributes .= ' rel="' . esc_attr( $author->user_url_rel ) . '"';
								}
							}
							?>
							<a<?php echo $attributes; ?>>
								<?php echo esc_html( $author->display_name ); ?>
							</a>
							<?php if ( strlen( $author->bio ) ) : ?>
								<?php echo esc_html( $author->bio ); ?>
							<?php endif; ?>
						</address>
					<?php endforeach; ?>
				<?php endif; ?>

				<?php if ( $kicker_text = $this->get_the_kicker() ) : ?>
					<!-- A kicker for your article -->
					<h3 class="op-kicker"><?php echo esc_html( $kicker_text); ?></h3>
				<?php endif; ?>

				<?php
				/**
		         * Fires in the header element of each article
		         *
		         * @since 0.1
		         *
		         * @param Instant_Articles_Post  $ia_post  The current article object
		         */
				do_action( 'instant_articles_article_header', $this );
				?>

			</header>

			<!-- Article body goes here -->
			<?php echo $this->get_the_content(); ?>

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