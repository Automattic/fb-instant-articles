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

		++$NodeListIndex;

	}

	return $DOMDocument;

}
add_filter( 'instant_articles_content_dom', 'instant_articles_content_dom_images' );

