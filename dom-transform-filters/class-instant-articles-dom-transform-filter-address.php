<?php

/**
 * Instant Articles DOM Transformation Filter for Address elements
 *
 * @since 0.1
 */
class Instant_Articles_DOM_Transform_Filter_Address extends Instant_Articles_DOM_Transform_Filter {

	/**
	 * Run the transformation
	 *
	 * Implements the abstract method from Instant_Articles_DOM_Transform_Filter
	 *
	 * @since 0.1
	 * @return DOMDocument
	 */
	public function run() {

		$DOMNodeList = $this->_DOMDocument->getElementsByTagName( 'address' );

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

		$element = $this->_DOMDocument->createElement( 'p' );
		while ( $properties->childNodes->length ) {
			$element->appendChild( $properties->childNodes->item( 0 ) );
		}

		$DOMDocumentFragment->appendChild( $element );

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

		$properties->childNodes = $DOMNode->childNodes;

		/**
		 * Filter the video element properties
		 *
		 * @since 0.1
		 * @param object  $properties     The element properties
		 * @param int     $post_id        The post ID of the current post
		 */
		$properties = apply_filters( 'instant_articles_address_properties', $properties, $this->_post_id );

		return $properties;

	}

}
