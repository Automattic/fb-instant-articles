<!doctype html>
<html lang="en" prefix="op: http://media.facebook.com/op#">
  <head>
    <meta charset="utf-8">
    <!-- URL of the web version of this article -->
    <!-- TODO: Change the domain to match the domain of your website -->    
    <link rel="canonical" href="http://example.com/article.html">
    <meta property="op:markup_version" content="v1.0">
  </head>
  <body>
    <article>
      <header>
        <!-- The title and subtitle shown in your Instant Article -->
        <h1>Article Title</h1>
        <h2>Article Subtitle</h2>

        <!-- The date and time when your article was originally published -->
        <time class="op-published" datetime="2014-11-11T04:44:16Z">November 11th, 4:44 PM</time>

        <!-- The date and time when your article was last updated -->
        <time class="op-modified" dateTime="2014-12-11T04:44:16Z">December 11th, 4:44 PM</time>

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
        <!-- TODO: Change the URL to a live image from your website -->    
        <figure>
          <img src="http://mydomain.com/path/to/img.jpg" />
          <figcaption>This image is amazing</figcaption>
        </figure>   

        <!-- A kicker for your article --> 
        <details>
          <summary>Kicker</summary>
          This is a kicker.
        </details>

      </header>

      <!-- Article body goes here -->

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
        <!-- Credits for your article -->
        <aside>Acknowledgements</aside>

        <!-- Copyright details for your article -->
        <small>Legal notes</small>
      </footer>
    </article>
  </body>
</html>