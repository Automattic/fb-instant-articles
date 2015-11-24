<?php

/**
 * Instant Articles DOM Tranformation Filter for ordered List
 *
 * @since 0.1
 */
class Instant_Articles_DOM_Transform_Filter_Ordered_List extends Instant_Articles_DOM_Transform_Filter {

	/**
	 * Run the transformation
	 *
	 * Implements the abstract method from Instant_Articles_DOM_Transform_Filter
	 *
	 * @since 0.1
	 * @return DOMDocument
	 */
	public function run() {

		$DOMNodeList = $this->_DOMDocument->getElementsByTagName( 'ol' );

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
		$ol = $this->_DOMDocument->createElement( 'ol' );

		foreach ( $properties->childNodes as $childNode ) {
			$ol->appendChild( $childNode );
		}
		
		$DOMDocumentFragment->appendChild( $ol );

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
		 * Filter the ol element properties
		 *
		 * @since 0.1
		 * @param object  $properties     The element properties
		 * @param int     $post_id        The post ID of the current post
		 */
		$properties = apply_filters( 'instant_articles_ol_properties', $properties, $this->_post_id );

		return $properties;

	}

}
