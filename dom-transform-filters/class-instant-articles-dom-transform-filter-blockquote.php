<?php

/**
 * Instant Articles DOM Tranformation Filter for Blockquotes
 *
 * @since 0.1
 */
class Instant_Articles_DOM_Transform_Filter_Blockquote extends Instant_Articles_DOM_Transform_Filter {

	/**
	 * Run the transformation
	 *
	 * Implements the abstract method from Instant_Articles_DOM_Transform_Filter
	 *
	 * @since 0.1
	 * @return DOMDocument
	 */
	public function run() {

		$DOMNodeList = $this->_DOMDocument->getElementsByTagName( 'blockquote' );

		// Transform all nodes found
		$this->_transform_elements( $DOMNodeList );

		return $this->_DOMDocument;

	}

	/**
	 * Build a DOMDocumentFragment for the element
	 *
	 * @since 0.1
	 * @return DOMDocumentFragment The fragment ready to be inserted into the DOM
	 */
	protected function _build_fragment( $properties ) {

		$DOMDocumentFragment = $this->_DOMDocument->createDocumentFragment();
		
		$quoteContainerType = 'blockquote';
		if ( strlen( $properties->cite ) ) {
			$quoteContainerType = 'aside';
		}

		$quoteContainer = $this->_DOMDocument->createElement( $quoteContainerType );
		$quoteContainer->appendChild( $this->_DOMDocument->createTextNode( $properties->quote ) );
		
		if ( is_a( $properties->childNodes, 'DOMNodeList' ) ) {
			foreach( $properties->childNodes as $pNode ) {
				$newNode = $this->_DOMDocument->createElement( 'p' );
				$newNode->appendChild( $this->_DOMDocument->createTextNode( trim( $pNode->nodeValue ) ) );
				$quoteContainer->appendChild( $newNode );
			}
		}
		
		if ( strlen( $properties->cite ) ) {
			$citeNode = $this->_DOMDocument->createElement( 'cite' );
			$citeNode->appendChild( $this->_DOMDocument->createTextNode( $properties->cite ) );
			$quoteContainer->appendChild( $citeNode );
		}
		
		$DOMDocumentFragment->appendChild( $quoteContainer );

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

		$properties = new stdClass;

		$properties->quote = $DOMNode->nodeValue;
		$properties->cite = '';
		$properties->childNodes = null;

		$cite = $DOMNode->getAttribute( 'cite' );
		if ( strlen( $cite ) ) {
			$properties->cite = $cite;
		}

		$citeNodeList = $DOMNode->getElementsByTagName( 'cite' );
		if ( $citeNodeList->length ) {
			$citeNode = $citeNodeList->item( 0 );
			if ( strlen( $citeNode->nodeValue ) ) {
				$properties->cite = $citeNode->nodeValue;
			}
			while ( $citeNodeList->length ) {
				$citeNode = $citeNodeList->item( 0 );
				$citeNode->parentNode->removeChild( $citeNode );
			}
		}

		$pNodeList = $DOMNode->getElementsByTagName( 'p' );
		if ( 1 === $pNodeList->length ) {
			$properties->quote = $pNodeList->item( 0 )->nodeValue;
		} elseif ( $pNodeList->length ) {
			$properties->quote = '';
			$properties->childNodes = $pNodeList;
		}

		/**
		 * Filter the blockquote element properties
		 *
		 * @since 0.1
		 * @param object  $properties     The element properties
		 * @param int     $post_id        The post ID of the current post
		 */
		$properties = apply_filters( 'instant_articles_blockquote_properties', $properties, $this->_post_id );

		return $properties;

	}

}
