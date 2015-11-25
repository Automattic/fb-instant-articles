<?php

/**
 * Instant Articles DOM Tranformation Filter for empty elements
 *
 * @since 0.1
 */
class Instant_Articles_DOM_Transform_Filter_Emptyelements extends Instant_Articles_DOM_Transform_Filter {

	/**
	 * Run the transformation
	 *
	 * Implements the abstract method from Instant_Articles_DOM_Transform_Filter
	 *
	 * @since 0.1
	 * @return DOMDocument
	 */
	public function run() {

		// Elements excluded from checking: img, iframe, html, head, body, base, br, hr, link, meta, meter, script, audio, video, map, canvas, col, data, embed, input, keygen, menuitem, object, output, param, progress, source, textarea, track
		$xpathQuery = '//a | //abbr | //address | //article | //aside | //b | //bdi | //bdo | //blockquote | //button | //caption | //cite | //code | //colgroup | //datalist | //dd | //del | //details | //dfn | //dialog | //div | //dl | //dt | //em | //fieldset | //figcaption | //figure | //footer | //form | //h1 | //h2 | //h3 | //h4 | //h5 | //h6 | //header | //hgroup | //i | //ins | //kbd | //label | //legend | //li  | //main | //map | //mark | //menu | //nav | //noscript | //ol | //optgroup | //option | //p | //pre | //q | //rb | //rp | //rt | //rtc | //ruby | //s | //samp | //section | //select | //small | //span | //strong | //style | //sub | //summary | //sup | //table | //tbody | //td | //template | //tfoot | //th | //thead | //time | //title | //tr | //u | //ul | //var | //wbr';

		$xpath = new DOMXpath( $this->_DOMDocument );
		$DOMNodeList = $xpath->query( $xpathQuery );

		// Filter out empty elements
		$this->_filter_empty_elements( $DOMNodeList );

		return $this->_DOMDocument;

	}

	/**
	 * Remove all empty elements
	 */
	protected function _filter_empty_elements( DOMNodeList $DOMNodeList ) {

		$NodeListIndex = 0;

		// We’ll increase $NodeListIndex and/or reduce $DOMNodeList->length
		while ( $NodeListIndex < $DOMNodeList->length ) {

			$DOMNode = $DOMNodeList->item( $NodeListIndex );

			// Check all childnodes first
			if ( is_a( $DOMNode, 'DOMElement' ) && isset( $DOMNode->childNodes ) && is_a( $DOMNode->childNodes, 'DOMNodeList' ) ) {
				$this->_filter_empty_elements( $DOMNode->childNodes );
			}

			
			if ( isset( $DOMNode->nodeValue ) && '' == trim( $DOMNode->nodeValue ) ) {

				if ( ! isset( $DOMNode->childnodes ) || is_null( $DOMNode->childnodes ) || ( is_a( $DOMNode->childnodes, 'DOMNodeList' ) && ! $DOMNode->childnodes->length ) ) {

					// If the element is an empty node, remove it. But we must have a parentNode to remove a node
					if ( is_a( $DOMNode->parentNode, 'DOMElement' ) ) {
						$DOMNode->parentNode->removeChild( $DOMNode );
					}
				}

			}


			++$NodeListIndex;

		}


		return $DOMNodeList;
	}

	/**
	 * Find the element properties
	 *
	 * Implements the abstract method from Instant_Articles_DOM_Transform_Filter
	 *
	 * @since 0.1
	 * @param $DOMNode  $DOMNode The original domnode
	 */
	protected function get_properties( $DOMNode ) {

		$properties = new stdClass;
		return $properties;

	}

	/**
	 * Build a DOMDocumentFragment for the image element
	 *
	 * Implements the abstract method from Instant_Articles_DOM_Transform_Filter
	 *
	 * @since 0.1
	 * @return DOMDocumentFragment|false  The fragment ready to be inserted into the DOM. False if no replacement should happen.
	 */
	protected function _build_fragment( $properties) {
		return false;
	}


}


