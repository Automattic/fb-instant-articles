<?php

/**
 * Instant Articles DOM Transformation Filter for Tables
 *
 * @since 0.1
 */
class Instant_Articles_DOM_Transform_Filter_Table extends Instant_Articles_DOM_Transform_Filter {

	/**
	 * Run the transformation
	 *
	 * Implements the abstract method from Instant_Articles_DOM_Transform_Filter
	 *
	 * @since 0.1
	 * @return DOMDocument
	 */
	public function run() {

		$DOMNodeList = $this->_DOMDocument->getElementsByTagName( 'table' );

		// Transform all nodes found
		$this->_transform_elements( $DOMNodeList );

		return $this->_DOMDocument;

	}

	/**
	 * Build a DOMDocumentFragment for the element
	 *
	 * @since 0.1
	 * @return DOMDocumentFragment|false  The fragment ready to be inserted into the DOM. False if no replacement should happen.
	 */
	protected function _build_fragment( $properties ) {

		if ( ! is_a( $properties->table, 'DOMElement' ) ) {
			return false;
		}

		$DOMDocumentFragment = $this->_DOMDocument->createDocumentFragment();
		
		$figure = $this->_DOMDocument->createElement( 'figure' );
		$figure->setAttribute( 'class', 'op-interactive' );
		$DOMDocumentFragment->appendChild( $figure );

		$iframe = $this->_DOMDocument->createElement( 'iframe' );
		$figure->appendChild( $iframe );

		$iframe->appendChild( $properties->table );

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

		$properties->table = clone $DOMNode;

		/**
		 * Filter the blockquote element properties
		 *
		 * @since 0.1
		 * @param object  $properties     The element properties
		 * @param int     $post_id        The post ID of the current post
		 */
		$properties = apply_filters( 'instant_articles_table_properties', $properties, $this->_post_id );

		return $properties;

	}

}
