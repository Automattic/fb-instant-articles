<?php

/**
 * Class responsible for constructing our content and preparing it for rendering
 *
 * @since 0.1
 */
class Instant_Articles_Post {

	/** @var int ID of the post */
	protected $_ID = 0;

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
		 * @param Instant_Arcticle_Post  $instant_article_post  The instant article post
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

		return get_the_title( $this->get_the_ID() );
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
		 * @param Instant_Arcticle_Post  $instant_article_post  The instant article post
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
	 * Get the content for this post
	 *
	 * @since 0.1
	 * @return string The content
	 */
	public function get_the_content() {

		global $post, $more;

		// force $more
		$orig_more = $more;
		$more = 1;

		// If we’re not it the loop or otherwise properly setup
		$reset_postdata = false;
		if ( $this->get_the_ID() != $post->ID ) {
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
			$DOMDocument = new DOMDocument;
			$result = $DOMDocument->loadHTML( '<html><body>' . $content . '</body></html>' );
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
					$filtered_content .= $DOMDocument->saveXML( $node );
				}

				$content = $filtered_content;
				unset( $filtered_content );

			}

		}

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
		 * @param Instant_Arcticle_Post  $instant_article_post  The instant article post
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
		 * @param Instant_Arcticle_Post  $instant_article_post  The instant article post
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
		 * @param Instant_Arcticle_Post  $instant_article_post  The instant article post
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
		 * @param string                 $modified_date        The current post modified date.
		 * @param Instant_Arcticle_Post  $instant_article_post  The instant article post
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
		if ( $userdata = get_userdata( $post->post_author ) ) {
			$authors[] = $userdata;
		}

		/**
		 * Filter the post author(s)
		 *
		 * @since 0.1
		 *
		 * @param array                  $authors         The current post author(s).
		 * @param Instant_Arcticle_Post  $instant_article_post  The instant article post
		 */
		$authors = apply_filters( 'instant_articles_authors', $authors, $this );

		return $authors;
	}

	/**
	 * Get featured image url for cover
	 *
	 * @since 0.1
	 * @return string  
	 */
	public function get_the_featured_image( ) {

		$image_data = array();
		if ( has_post_thumbnail( $this->get_the_ID() ) ) {			

			$image_array = wp_get_attachment_image_src( get_post_thumbnail_id( $this->get_the_ID() ), 'full' ); 
			$attachment_id   = get_post_thumbnail_id( $this->get_the_ID() );
			
			$image_data['src'] = $image_array[0];
			$image_data['caption'] = get_post( $attachment_id  )->post_excerpt; 		

			/**
			 * Filter
			 *
			 * @since 0.1
			 *
			 * @param array                 $image_data        The first category returned from get_the_category().
			 * @param int  									   The post ID
			 */
			$image_data = apply_filters( 'instant_articles_featured_image', $image_data, $this->get_the_ID() ); 				
			return $image_data;   
		}	
	}

	/**
	 * Get kicker text
	 *
	 * @since 0.1
	 * @return string  
	 */
	public function get_the_kicker() {

		if ( has_category() ) {
			$categories = get_the_category();
			$category = $categories[0]->name;
		}
		else {
			$category = '';
		}

		/**
		 * Filter the kicker text
		 *
		 * @since 0.1
		 *
		 * @param string                 $category        The first category returned from get_the_category().
		 * @param int  									  The post ID
		 */
		$category_kicker = apply_filters('instant_articles_cover_kicker', $category,  $this->get_the_ID() );

		return $category_kicker ? $category_kicker : '';
	}

	/**
	 * Render post
	 *
	 * @since 0.1
	 */
	function render() {
		
		$post_id = $this->get_the_ID();

		/**
	     * Fires before the instant article is rendered
	     *
	     * @since 0.1
	     * @param Instant_Arcticle_Post  $instant_article_post  The instant article post
	     */
		do_action( 'pre_instant_article_render', $this );
		
		$default_template = dirname( __FILE__ ) . '/template.php';

		/**
	     * Filter the path to the template to use to render the instant article
	     *
	     * @since 0.1
	     * @param string                    $template               Path to the current (default) template.
	     * @param Instant_Arcticle_Post     $instant_article_post   The instant article post
	     */
		$template = apply_filters( 'instant_articles_render_post_template', $default_template, $this );
		
		// Make sure the template exists. Devs do the darndest things.
		if ( ! file_exists( $template ) ) {
			$template = $default_template;
		}
		include $template;
		
		/**
	     * Fires after the instant article is rendered
	     *
	     * @since 0.1
	     * @param Instant_Arcticle_Post  $instant_article_post  The instant article post
	     */
		do_action( 'after_instant_article_render', $this );
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
	     * @param Instant_Arcticle_Post     $instant_article_post   The instant article post
	     */
		$article_style = apply_filters( 'instant_articles_style', 'default', $this );

		return $article_style;
	}

}



