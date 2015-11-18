<?php


/**
 * Filter the images in the body DOM element
 *
 * @since 0.1
 * @param DOMDocument $DOMDocument The current DOMDocument
 * @return DOMDocument The filtered DOMDocument
 */
function instant_articles_content_dom_images( DOMDocument $DOMDocument ) {

	// The Instant Articles spec says we can only use each image once. Let’s keep track of used images.
	$used_images = array();

	$DOMNodeList = $DOMDocument->getElementsByTagName( 'img' );

	foreach ( $DOMNodeList as $DOMNode ) {


		$src = $DOMNode->attributes->getNamedItem( 'src' );
		
		// If the image is used already, remove it
		if ( in_array( $src, $used_images ) ) {

			// If the parent node will end up empty, remove it instead (unless it is the body element)
			if ( 'body' != $DOMNode->parentNode->nodeName && 1 == $DOMNode->parentNode->childNodes->length ) {
				$DOMNode->parentNode->parentNode->removeChild( $DOMNode->parentNode );
			} else {
				$DOMNode->parentNode->removeChild( $DOMNode );
			}

			continue; // we’re done with this image
		}

		// Add the src to the stack so we can check for multiple uses later
		$used_images[] = $src;
	}

	return $DOMDocument;

}
add_filter( 'instant_articles_content_dom', 'instant_articles_content_dom_images' );

