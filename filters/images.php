<?php


/**
 * Filter the images in the body DOM element
 *
 * @since 0.1
 * @param DOMDocument  $DOMDocument  The current DOMDocument
 * @param int          $post_id      The post_id to the post
 * @return DOMDocument The filtered DOMDocument
 */
function instant_articles_content_dom_images( DOMDocument $DOMDocument, $post_id ) {

	// The Instant Articles spec says we can only use each image once. Let’s keep track of used images.
	$used_images = array();

	$DOMNodeList = $DOMDocument->getElementsByTagName( 'img' );

	$NodeListIndex = 0;
	while ( $NodeListIndex < $DOMNodeList->length ) {

		$DOMNode = $DOMNodeList->item( $NodeListIndex );

		$src = $DOMNode->getAttribute( 'src' );

		// See how far up the tree we can go
		$replaceNode = $DOMNode;
		while ( 'body' != $replaceNode->parentNode->nodeName && 1 == $replaceNode->parentNode->childNodes->length ) {
			$replaceNode = $replaceNode->parentNode;
		}
		
		// If the image is used already, remove it
		if ( in_array( $src, $used_images ) ) {
			// Please note that when we remove the node, $DOMNodeList->length is n-1. Our $NodeListIndex will now point to the next item in the list.
			$replaceNode->parentNode->removeChild( $replaceNode );
			continue; // we’re done with this image
		}

		// Add the src to the stack so we can check for multiple uses later
		$used_images[] = $src;

		$DOMDocumentFragment = instant_articles_build_dom_element_image( $DOMDocument, $src, $post_id );
		if ( is_a( $DOMDocumentFragment, 'DOMDocumentFragment' ) ) {
			$replaceNode->parentNode->replaceChild( $DOMDocumentFragment, $replaceNode );
		}

		++$NodeListIndex;

	}

	return $DOMDocument;

}
add_filter( 'instant_articles_content_dom', 'instant_articles_content_dom_images', 10, 2 );


/**
 * Build a DOMDocumentFragment for the image element
 *
 * @since 0.1
 * @return DOMDocumentFragment The fragment ready to be inserted into the DOM
 */
function instant_articles_build_dom_element_image( $DOMDocument, $src, $post_id, $attachment_id = 0 ) {

	$element_image = new Instant_Article_Element_Image( $src, $post_id, $attachment_id );

	$DOMDocumentFragment = $DOMDocument->createDocumentFragment();
	$figure = $DOMDocument->createElement( 'figure' );
	$img = $DOMDocument->createElement( 'img' );
	$img->setAttribute( 'src', $element_image->getSrc() );

	$figure->appendChild( $img );
	$DOMDocumentFragment->appendChild( $figure );

	return $DOMDocumentFragment;
}


/**
 * Instant Articles Element: Image
 *
 * @since 0.1
 * @todo Rework the internals in the class. It’s literally (yes) just made for returning the image url now.
 */
class Instant_Article_Element_Image {

	protected $_img_props;

	/**
	 * Instant Articles Element: Image
	 *
	 * @since 0.1
	 * @param string $src            The URL to the image
	 * @param int    $post_id        The post_id to the post
	 * @param int    $attachment_id  Optional. The attachment ID (post ID) to the image
	 */
	function __construct( $src, $post_id, $attachment_id = 0 ) {

		if ( ! $attachment_id ) {
			$attachment_id = attachment_url_to_postid( $src );
		}

		
		/* Try to use WP internals to get an image of the recommended size. Fallback to use the URL from the original img src in the post. */
		$img_props = false;
		if ( $attachment_id ) {
			// The recommended resolution is 2048x2048 pixels. 
			$img_props = wp_get_attachment_image_src( $attachment_id, array( 2048, 2048 ) ); // Returns an array (url, width, height), or false, if no image is available.
		}
		if ( ! $img_props ) {
			$imagesize = getimagesize( $src );
			$img_props = array( $src, $imagesize[0], $imagesize[1] );
		}

		/**
		 * Filter the image properties
		 *
		 * @since 0.1
		 * @param array  $img_props      The image properties defined in an array with three elements (numeric indices): URL, width and height
		 * @param int    $post_id        The post ID of the current post
		 * @param int    $attachment_id  The attachment ID (post ID) to the image (if reverse lookup from url to postid worked)
		 */
		$img_props = apply_filters( 'instant_articles_image_properties', $img_props, $post_id, $attachment_id );

		$this->_img_props = $img_props;

	}

	function getSrc() {
		return $this->_img_props[0];
	}

}




