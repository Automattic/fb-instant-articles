<?php

/**
 * Class responsible for constructing our content and preparing it for rendering
 *
 * @since 0.1
 */
class Instant_Articles_Post {

	/** @var int ID of the post */
	protected $_ID = 0;

	/** @var string Cached version of the Instant Article body */
	protected $_content = null;

	/**
	 * Setup data and build the content
	 *
	 * @since 0.1
	 * @param int  $post_id  ID of the post
	 */
	function __construct( $post_id ) {
		$this->_ID = $post_id;
	}

	/**
	 * Get the ID for this post
	 *
	 * @since 0.1
	 * @return int  The post ID
	 */
	public function get_the_ID() {
		return $this->_ID;
	}

	/**
	 * Get the title for this post
	 *
	 * @since 0.1
	 * @return string The title
	 */
	public function get_the_title() {
		$title = get_the_title( $this->get_the_ID() );

		/**
		 * Filter the post title for use in instant articles
		 *
		 * @since 0.1
		 *
		 * @param string                 $title                 The current post title.
		 * @param Instant_Article_Post   $instant_article_post  The instant article post
		 */
		$title = apply_filters( 'instant_articles_title', $title, $this );

		return $title;
	}

	/**
	 * Get the title for this post
	 *
	 * @since 0.1
	 * @return string  The title
	 */
	public function get_the_title_rss() {
		$title = $this->get_the_title();

		/**
		 * Apply the default WP Filters for the post title for use in a feed. This ensures proper escaping.
		 *
		 * @since 0.1
		 *
		 * @param string  $title  The current post title.
		 */
		$title = apply_filters( 'the_title_rss', $title );

		return $title;
	}

	/**
	 * Get the canonical URL for this post
	 *
	 * A little warning here: It is extremely important that this is the same canonical URL as is used on the web site.
	 * This is the identificator Facebook use to connect the "read" web article with the instant article.
	 * Do not add any querystring params or URL fragments it. Not any. Not even for tracking.
	 *
	 * @since 0.1
	 * @return string  The canonical URL
	 */
	public function get_canonical_url() {
		$url = get_permalink( $this->get_the_ID() );

		return $url;
	}

	/**
	 * Get the excerpt for this post
	 *
	 * @since 0.1
	 * @return string  The excerpt
	 */
	public function get_the_excerpt() {

		$post = get_post( $this->get_the_ID() );

		// This should ideally not happen, but it may do so if someone tampers with the query.
		// Returning the same protected post excerpt as "usual" may help them identify what’s going on.
		if ( post_password_required( $this->get_the_ID() ) ) {
			return __( 'There is no excerpt because this is a protected post.' );
		}

		// Make sure no “read more” link is added
		add_filter( 'excerpt_more', '__return_empty_string', 999 );

		/**
		 * Apply the default WP Filters for the post excerpt
		 *
		 * @since 0.1
		 *
		 * @param string  $post_excerpt  The post excerpt.
		 */
		$excerpt = apply_filters( 'get_the_excerpt', $post->post_excerpt );

		/**
		 * Filter the post excerpt for instant articles
		 *
		 * @since 0.1
		 *
		 * @param string                 $excerpt               The current post excerpt.
		 * @param Instant_Article_Post   $instant_article_post  The instant article post
		 */
		$excerpt = apply_filters( 'instant_articles_excerpt', $excerpt, $this );

		return $excerpt;
	}

	/**
	 * Get the excerpt for this post
	 *
	 * @since 0.1
	 * @return string The excerpt
	 */
	public function get_the_excerpt_rss() {

		$excerpt = $this->get_the_excerpt();

		/**
		 * Apply the default WP Filters for the post excerpt for use in a feed. This ensures proper escaping.
		 *
		 * @since 0.1
		 *
		 * @param string  $excerpt  The current post excerpt.
		 */
		$excerpt = apply_filters( 'the_excerpt_rss', $excerpt );

		return $excerpt;
	}

	/**
	 * Get the article body for this post
	 *
	 * @since 0.1
	 * @return string The content
	 */
	public function get_the_content() {

		if ( is_null( $this->_content ) ) {
			$this->_content = $this->_get_the_content();
		}

		return $this->_content;

	}

	/**
	 * Put together the article body for this post
	 *
	 * @since 0.1
	 * @return string The content
	 */
	protected function _get_the_content() {

		// Try to get the content from a transient, but only if the cached version have the same modtime
		$cache_mod_time = get_transient( 'instantarticles_mod_' . $this->get_the_ID() );
		if ( $cache_mod_time == get_post_modified_time( 'Y-m-d H:i:s', true, $this->get_the_ID() ) ) {
			$content = get_transient( 'instantarticles_content_' . $this->get_the_ID() );
			if ( $content !== false && strlen( $content ) ) {
				return $content;
			}
		}

		global $post, $more;

		// force $more
		$orig_more = $more;
		$more = 1;

		// If we’re not it the loop or otherwise properly setup
		$reset_postdata = false;
		if ( $this->get_the_ID() !== $post->ID ) {
			$post = get_post( $this->get_the_ID() );
			setup_postdata( $post );
			$reset_postdata = true;
		}

		// Now get the content
		$content = get_the_content();

		/**
		 * Apply the default filter 'the_content' for the post content
		 *
		 * @since 0.1
		 * @param string  $content  The current post content.
		 */
		$content = apply_filters( 'the_content', $content );

		// Maybe cleanup some globals after us?
		$more = $orig_more;
		if ( $reset_postdata ) {
			wp_reset_postdata();
		}

		// Some people choose to disable wpautop. Due to the Instant Articles spec, we really want it in!
		$content = wpautop( $content );

		/**
		 * Filter the post content for Instant Articles
		 *
		 * @since 0.1
		 * @param string  $content  The post content.
		 */
		$content = apply_filters( 'instant_articles_content', $content );


		if ( class_exists( 'DOMDocument' ) && has_action( 'instant_articles_register_dom_transformation_filters' ) ) {

			/* If we have filters that wants to work on the DOM, we generate one instance of DOMDocument
			   they can all work on, instead of having to handle the conversion themselves. */

			$libxml_previous_state = libxml_use_internal_errors( true );
			$DOMDocument = new DOMDocument( '1.0', get_option( 'blog_charset' ) );
			
			// DOMDocument isn’t handling encodings too well, so let’s help it a little
			if ( function_exists( 'mb_convert_encoding' ) ) {
				$content = mb_convert_encoding( $content, 'HTML-ENTITIES', get_option( 'blog_charset' ) );
			}
			
			$result = $DOMDocument->loadHTML( '<!doctype html><html><body>' . $content . '</body></html>' );
			libxml_clear_errors();
			libxml_use_internal_errors( $libxml_previous_state );

			if ( $result ) {

				// Register the DOM transformation filters if not done yet
				if ( ! did_action( 'instant_articles_register_dom_transformation_filters' ) ) {
					do_action( 'instant_articles_register_dom_transformation_filters' );
				}
				Instant_Articles_DOM_Transform_Filter_Runner::run( $DOMDocument, $this->get_the_ID() );


				$body = $DOMDocument->getElementsByTagName( 'body' )->item( 0 );

				$filtered_content = '';
				foreach ( $body->childNodes as $node ) {
					if ( method_exists( $DOMDocument, 'saveHTML' ) ) { // Requires PHP 5.3.6
						$filtered_content .= $DOMDocument->saveHTML( $node );
					} else {
						$temp_content = $DOMDocument->saveXML( $node );
						$iframe_pattern = "#<iframe([^>]+)/>#is"; // self-closing iframe element
						$temp_content = preg_replace( $iframe_pattern, "<iframe$1></iframe>", $temp_content );
						$filtered_content .= $temp_content;
					}
				}

				$content = $filtered_content;
				unset( $filtered_content );

			}

		}

		// Cache the content
		set_transient( 'instantarticles_mod_' . $this->get_the_ID(), get_post_modified_time( 'Y-m-d H:i:s', true, $this->get_the_ID() ), WEEK_IN_SECONDS );
		set_transient( 'instantarticles_content_' . $this->get_the_ID(), $content, WEEK_IN_SECONDS );

		return $content;
	}

	/**
	 * Get the published date for this post
	 *
	 * @since 0.1
	 * @return string  The published date
	 */
	public function get_the_pubdate() {

		$date = get_post_time( get_option( 'date_format' ), true, $this->get_the_ID() );

		/**
		 * Filter the post date for instant articles
		 *
		 * @since 0.1
		 *
		 * @param string                 $date               The current post date.
		 * @param Instant_Article_Post   $instant_article_post  The instant article post
		 */
		$date = apply_filters( 'instant_articles_date', $date, $this );

		return $date;

	}

	/**
	 * Get the modified date for this post
	 *
	 * @since 0.1
	 * @return string  The modified date
	 */
	public function get_the_moddate() {

		$modified_date = get_post_modified_time( get_option('date_format'), true, $this->get_the_ID() );

		/**
		 * Filter the post modified date for instant articles
		 *
		 * @since 0.1
		 *
		 * @param string                 $modified_date         The current post modified date.
		 * @param Instant_Article_Post   $instant_article_post  The instant article post
		 */
		$modified_date = apply_filters( 'instant_articles_modified_date', $modified_date, $this );

		return $modified_date;

	}

	/**
	 * Get the published date for this post (ISO 8601)
	 *
	 * @since 0.1
	 * @return string  The published date formatted suitable for use in the RSS feed and the html time elements (ISO 8601)
	 */
	public function get_the_pubdate_iso() {

		$published_date = mysql2date( 'c', get_post_time( 'Y-m-d H:i:s', true, $this->get_the_ID() ), false );

		/**
		 * Filter the post published date (ISO 8601)
		 *
		 * @since 0.1
		 *
		 * @param string                 $published_date        The current post published date.
		 * @param Instant_Article_Post   $instant_article_post  The instant article post
		 */
		$published_date = apply_filters( 'instant_articles_published_date_iso', $published_date, $this );

		return $published_date;

	}

	/**
	 * Get the modified date for this post (ISO 8601)
	 *
	 * @since 0.1
	 * @return string  The modified date formatted suitable for use in the RSS feed and the html time elements (ISO 8601)
	 */
	public function get_the_moddate_iso() {

		$modified_date = mysql2date( 'c', get_post_modified_time( 'Y-m-d H:i:s', true, $this->get_the_ID() ), false );

		/**
		 * Filter the post modified date (ISO 8601)
		 *
		 * @since 0.1
		 *
		 * @param string                 $modified_date         The current post modified date.
		 * @param Instant_Article_Post   $instant_article_post  The instant article post
		 */
		$modified_date = apply_filters( 'instant_articles_modified_date_iso', $modified_date, $this );

		return $modified_date;

	}
	/**
	 * Get the author(s)
	 *
	 * @since 0.1
	 * @return array  $authors  The post author(s)
	 */
	public function get_the_authors() {

		$authors = array();

		$post = get_post( $this->get_the_ID() );

		$WP_User = get_userdata( $post->post_author );

		if ( is_a( $WP_User, 'WP_User' ) ) {
			$author = new stdClass;
			$author->ID            = $WP_User->ID;
			$author->display_name  = $WP_User->data->display_name;
			$author->first_name    = $WP_User->first_name;
			$author->last_name     = $WP_User->last_name;
			$author->user_login    = $WP_User->data->user_login;
			$author->user_nicename = $WP_User->data->user_nicename;
			$author->user_email    = $WP_User->data->user_email;
			$author->user_url      = $WP_User->data->user_url;
			$author->bio           = $WP_User->description;

			$authors[] = $author;
		}

		/**
		 * Filter the post author(s)
		 *
		 * @since 0.1
		 *
		 * @param array  $authors  The current post author(s).
		 * @param int    $post_id  The instant article post
		 */
		$authors = apply_filters( 'instant_articles_authors', $authors, $this->get_the_ID() );

		return $authors;
	}

	/**
	 * Get featured image for cover
	 *
	 * @since 0.1
	 * @return array {
	 *     Array containing image source and caption.
	 *
	 *     @type string $src Image URL
	 *     @type string $caption Image caption
	 * }
	 */
	public function get_the_featured_image() {

		$image_data = array(
			'src' => '',
			'caption' => '',
		);
		if ( has_post_thumbnail( $this->get_the_ID() ) ) {

			$image_array = wp_get_attachment_image_src( get_post_thumbnail_id( $this->get_the_ID() ), 'full' );
			$attachment_id   = get_post_thumbnail_id( $this->get_the_ID() );
			
			if ( is_array( $image_array ) ) {
				$image_data['src'] = $image_array[0];
				$attachment_post = get_post( $attachment_id );
				if ( is_a( $attachment_post, 'WP_Post' ) ) {
					$image_data['caption'] = $attachment_post->post_excerpt;
				}
			}
		}

		/**
		 * Filter the featured image
		 *
		 * @since 0.1
		 * @param array $image_data {
		 *     Array containg image source and caption.
		 *
		 *     @type string $src Image URL
		 *     @type string $caption Image caption
		 * }
		 * @param int $post_id The post ID
		 */
		$image_data = apply_filters( 'instant_articles_featured_image', $image_data, $this->get_the_ID() );
		return $image_data;
	}

	/**
	 * Get the cover media
	 *
	 * @since 0.1
	 * @return array  
	 */
	public function get_cover_media() {

		$cover_media = new stdClass;
		$cover_media->type = 'none';

		// If someone else is handling this, let them. Otherwise fall back to us trying to use the featured image.
		if ( has_filter( 'instant_articles_cover_media' ) ) {
			/**
			 * Filter the cover media
			 *
			 * @since 0.1
			 * @param stdClass  $cover_media  The cover media object
			 * @param int       $post_id      The current post ID
			 */
			$cover_media = apply_filters( 'instant_articles_cover_media', $cover_media, $this->get_the_ID() );
		} else {
			$featured_image_data = $this->get_the_featured_image();
			if ( isset( $featured_image_data['src'] ) && strlen( $featured_image_data['src'] ) ) {
				$cover_media->type = 'image';
				$cover_media->src = $featured_image_data['src'];
				$cover_media->caption = $featured_image_data['caption'];
			}
		}

		return $cover_media;
	}

	/**
	 * Get kicker text
	 *
	 * @since 0.1
	 * @return string  
	 */
	public function get_the_kicker() {

		$category = '';

		if ( has_category() ) {
			$categories = get_the_category();

			if ( is_array( $categories ) && isset( $categories[0]->name ) && __( 'Uncategorized' ) !== $categories[0]->name ) {
				$category = $categories[0]->name;
			}
		}

		/**
		 * Filter the kicker text
		 *
		 * @since 0.1
		 *
		 * @param string  $category  The first category returned from get_the_category().
		 * @param int     $post_id   The post ID
		 */
		$category_kicker = apply_filters('instant_articles_cover_kicker', $category,  $this->get_the_ID() );

		return $category_kicker ? $category_kicker : '';
	}

	/**
	 * Get newsfeed cover type, image or video
	 *
	 * @since 0.1
	 * @return string  
	 */
	public function get_newsfeed_cover() {

		$type = 'image';

		/**
		 * Filter the cover type property 
		 *
		 * @since 0.1
		 *
		 * @param string  $type     Set to 'video' for video cover. Featured image (image) is default.
		 * @param int     $post_id  The post ID
		 */
		$type = apply_filters( 'instant_articles_cover_type', $type,  $this->get_the_ID() );

		return $type;
	}

	/**
	 * Get credits for footer. 
	 *
	 * @since 0.1
	 * @return string  
	 */
	public function get_the_footer_credits() {

		/**
		* Filter credits 
		*
		* @since 0.1
		*
		* @param string  No credits set by default.
		* @param int     The post ID
		*/
		$footer_credits = apply_filters( 'instant_articles_footer_credits', '', $this->get_the_ID() );
		return $footer_credits; 
	}


	/**
	 * Get copyright for footer
	 *
	 * @since 0.1
	 * @return string  
	 */
	public function get_the_footer_copyright() {

		/**
		* Filter copyright
		*
		* @since 0.1
		*
		* @param string  No copyright set by default.
		* @param int  	 The post ID
		*/
		$footer_copyright = apply_filters( 'instant_articles_footer_copyright', '', $this->get_the_ID() );
		return $footer_copyright; 
	}

	/**
	 * Render post
	 *
	 * @since 0.1
	 */
	function render() {

		/**
	     * Fires before the instant article is rendered
	     *
	     * @since 0.1
	     * @param Instant_Article_Post  $instant_article_post  The instant article post
	     */
		do_action( 'instant_articles_before_render_post', $this );
		
		$default_template = dirname( __FILE__ ) . '/template.php';

		/**
	     * Filter the path to the template to use to render the instant article
	     *
	     * @since 0.1
	     * @param string                    $template               Path to the current (default) template.
	     * @param Instant_Article_Post      $instant_article_post   The instant article post
	     */
		$template = apply_filters( 'instant_articles_render_post_template', $default_template, $this );
		
		// Make sure the template exists. Devs do the darndest things.
		// Note on validate_file(): Return value of 0 means nothing is wrong, greater than 0 means something was wrong.
		if ( ! file_exists( $template ) || validate_file( $template ) ) {
			$template = $default_template;
		}
		include $template;
		
		/**
	     * Fires after the instant article is rendered
	     *
	     * @since 0.1
	     * @param Instant_Article_Post  $instant_article_post  The instant article post
	     */
		do_action( 'instant_articles_after_render_post', $this );
	}

	/**
	 * Article <head> style
	 *
	 * @since 0.1
	 * @return string The article style
	 */
	function get_article_style() {

		/**
	     * Filter the article style to use
	     *
	     * @since 0.1
	     * @param string                    $template               Path to the current (default) template.
	     * @param Instant_Article_Post      $instant_article_post   The instant article post
	     */
		$article_style = apply_filters( 'instant_articles_style', 'default', $this );

		return $article_style;
	}

	/**
	 * Get whether we shall output a list of related articles in the footer
	 *
	 * @since 0.2
	 * @return bool Whether we shall output a list of related articles in the footer.
	 */
	function use_related_articles_in_footer() {

		$use_related_articles_in_footer = false;

		/**
	     * Filter whether we shall output a list of related articles in the footer
	     *
	     * @since 0.2
	     * @param bool                    $use_related_articles_in_footer   Whether we shall output a list of related articles in the footer
	     * @param Instant_Article_Post    $instant_article_post             The instant article post
	     */
		$use_related_articles_in_footer = apply_filters( 'instant_articles_use_related_articles_in_footer', $use_related_articles_in_footer, $this );

		return (bool) $use_related_articles_in_footer;
	}

	/**
	 * Get the related articles
	 *
	 * @since 0.2
	 * @return array {
	 *     Array of objects containing the related articles
	 *
	 *     @type stdClass {
	 *         The object with the data for the related article
	 *         
	 *         @type string  $url           The URL to the related article
	 *         @type bool    $is_sponsored  Whether this is a sponsored link or not
	 *     }
	 * }
	 */
	function get_related_articles() {

		$related_articles = array();

		/**
	     * Filter whether we shall output a list of related articles in the footer
	     *
	     * @since 0.2
	     * @param array                   $related_articles       Whether we shall output a list of related articles in the footer
	     * @param Instant_Article_Post    $instant_article_post   The instant article post
	     */
		$related_articles = apply_filters( 'instant_articles_related_articles', $related_articles, $this );

		if ( ! is_array( $related_articles ) ) {
			$related_articles = array();
		}

		// Max 3 elements according to the spec: https://developers.facebook.com/docs/instant-articles/reference/related-articles
		if ( 3 < count( $related_articles ) ) {
			$related_articles = array_slice( $related_articles, 0, 3 );
		}

		return $related_articles;
	}

}



