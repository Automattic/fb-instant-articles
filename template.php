<!doctype html>
<html lang="en" prefix="op: http://media.facebook.com/op#">
  <head>
    <meta charset="utf-8">
    <link rel="canonical" href="<?php echo esc_url( $this->get_canonical_url() ); ?>">
    <meta property="op:markup_version" content="v1.0">
    <meta property="fb:article_style" content="<?php echo esc_attr( $this->get_article_style() ); ?>">
  </head>
  <body>
    <article>
      <header>

        <h1><?php echo esc_html( $this->get_the_title() ); ?></h1>

        <!-- The date and time when your article was originally published -->
        <time class="op-published" datetime="<?php echo esc_attr( $this->get_the_pubdate_iso() ); ?>"><?php echo esc_html( $this->get_the_pubdate() ); ?></time>

        <!-- The date and time when your article was last updated -->
        <time class="op-modified" datetime="<?php echo esc_attr( $this->get_the_moddate_iso() ); ?>"><?php echo esc_html( $this->get_the_moddate() ); ?></time>

        <!-- The authors of your article -->
        <address>
          <a rel="facebook" href="http://facebook.com/brandon.diamond">Brandon Diamond</a>
          Brandon is a avid zombie hunter.
        </address>
        <address>
          <a>TR Vishwanath</a>
          Vish is a scholar and a gentleman.
        </address>

        <!-- The cover image shown inside your article -->        
        <?php if ( $featured_image = $this->get_the_featured_image() ) : ?>
        <figure>
          <img src="<?php echo esc_url( $featured_image['src'] ); ?>" />
          <figcaption><?php echo esc_html( $featured_image['caption'] ); ?></figcaption>
        </figure> 
      <?php endif; ?>
     

        <?php if ( $kicker_text = $this->get_the_kicker() ) : ?>
        <!-- A kicker for your article --> 
        <h3 class="op-kicker"><?php echo esc_html( $kicker_text); ?></h3>
        <?php endif; ?>

      </header>

      <!-- Article body goes here -->
      <?php $this->get_the_content(); ?>

      <!-- Body text for your article -->
      <p> Article content </p> 

      <!-- A video within your article -->
      <!-- TODO: Change the URL to a live video from your website -->    
      <figure>
        <video>
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
      </footer>
    </article>
  </body>
</html>