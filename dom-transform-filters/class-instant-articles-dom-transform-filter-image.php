<?php

/**
 * Instant Articles DOM Tranformation Filter for Images
 *
 * @since 0.1
 */
class Instant_Articles_DOM_Transform_Filter_Image extends Instant_Articles_DOM_Transform_Filter {

	/**
	 * Run the transformation
	 *
	 * Implements the abstract method from Instant_Articles_DOM_Transform_Filter
	 *
	 * @since 0.1
	 * @return DOMDocument
	 */
	public function run() {

		$DOMNodeList = $this->_DOMDocument->getElementsByTagName( 'img' );

		// Filter out multiple uses of single images
		$this->_filter_multiple_uses( $DOMNodeList );

		// Transform all images left
		$this->_transform_elements( $DOMNodeList );

		return $this->_DOMDocument;

	}

	/**
	 * The Instant Articles spec says we can only use each image once.
	 */
	protected function _filter_multiple_uses( DOMNodeList $DOMNodeList ) {

		$used_images = array();

		$NodeListIndex = 0;

		// We’ll increase $NodeListIndex and/or reduce $DOMNodeList->length
		while ( $NodeListIndex < $DOMNodeList->length ) {

			$DOMNode = $DOMNodeList->item( $NodeListIndex );

			$src = $DOMNode->getAttribute( 'src' );

			// See how far up the tree we can go
			$removeDOMNode = $DOMNode;
			while ( 'body' != $removeDOMNode->parentNode->nodeName && 1 === $removeDOMNode->parentNode->childNodes->length ) {
				$removeDOMNode = $removeDOMNode->parentNode;
			}
			
			// If the image is used already, remove it
			if ( in_array( $src, $used_images, true ) ) {
				// Please note that when we remove the node, $DOMNodeList->length is n-1. Our $NodeListIndex will thus point to the next item in the list.
				$removeDOMNode->parentNode->removeChild( $removeDOMNode );
			}

			// Add the src to the stack so we can check for multiple uses later
			$used_images[] = $src;

			++$NodeListIndex;

		}


		return $DOMNodeList;
	}

	/**
	 * Build a DOMDocumentFragment for the image element
	 *
	 * @since 0.1
	 * @return DOMDocumentFragment The fragment ready to be inserted into the DOM
	 */
	protected function _build_fragment( $properties) {

		$DOMDocumentFragment = $this->_DOMDocument->createDocumentFragment();
		$figure = $this->_DOMDocument->createElement( 'figure' );
		$img = $this->_DOMDocument->createElement( 'img' );
		$img->setAttribute( 'src', $properties->img->url );

		$figure->appendChild( $img );
		$DOMDocumentFragment->appendChild( $figure );

		return $DOMDocumentFragment;
	}

	/**
	 * Find the element properties
	 *
	 * Implements the abstract method from Instant_Articles_DOM_Transform_Filter
	 *
	 * @since 0.1
	 * @param $DOMNode  $DOMNode        The original domnode
	 * @todo Get the rest of the properties
	 */
	protected function get_properties( $DOMNode ) {

		$src = $DOMNode->getAttribute( 'src' );
		if ( function_exists( 'wpcom_vip_attachment_url_to_postid' ) ) {
			$attachment_id = wpcom_vip_attachment_url_to_postid( $src );
		} else {
			$attachment_id = attachment_url_to_postid( $src );
		}

		$properties = new stdClass;
		$properties->img = new stdClass;
		
		/* Try to use WP internals to get an image of the recommended size. Fallback to use the URL from the original img src in the post. */
		

		if ( $attachment_id ) {
			// The recommended resolution is 2048x2048 pixels. 
			$img_props = wp_get_attachment_image_src( $attachment_id, array( 2048, 2048 ) ); // Returns an array (url, width, height), or false, if no image is available.
		} else {
			$imagesize = getimagesize( $src );
			if ( is_array( $imagesize ) ) {
				$img_props = array( $src, $imagesize[0], $imagesize[1] );
			}
		}
		if ( is_array( $img_props ) ) {
			list( $properties->img->url, $properties->img->width, $properties->img->height ) = $img_props;
		} else {
			$properties->img->url = $src;
			$properties->img->width = '';
			$properties->img->height = '';
		}

		/**
		 * Filter the image properties
		 *
		 * @since 0.1
		 * @param object  $img_props      The element properties
		 * @param int     $post_id        The post ID of the current post
		 * @param int     $attachment_id  The attachment ID (post ID) to the image (if reverse lookup from url to postid worked)
		 */
		$properties = apply_filters( 'instant_articles_image_properties', $properties, $this->_post_id, $attachment_id );

		return $properties;

	}


}


