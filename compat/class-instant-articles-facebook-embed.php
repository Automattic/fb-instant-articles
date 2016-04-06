<?php
/**
 * Facebook Instant Articles for WP.
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 *
 * @package default
 */

use Facebook\InstantArticles\Elements\Analytics;

/**
 * Support class for Facebook Embeds on Instant Articles
 *
 * @since 0.1
 */
class Instant_Articles_Facebook_Embed {

	/**
	 * Initiator for the embed. Simply adds the filter hook: 'instant_articles_parsed_document'.
	 */
	function init() {
		add_filter( 'instant_articles_parsed_document', array( $this, 'wrap_facebook_embed' ), 10, 1 );
	}

	/**
	 * This returns the string content to the javascript code that consists on the FB startup script code.
	 *
	 * @param DOMDocument $document The document container that will hold the embed.
	 */
	function render_sdk( $document ) {
		$fragment = $document->createDocumentFragment();
		$script = $document->createElement( 'script' );
		$script_code =
			'(function(d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) return;
				js = d.createElement(s); js.id = id;
				js.src = "//connect.facebook.net/pt_BR/sdk.js#xfbml=1&version=v2.5&appId=349537438510572";
				fjs.parentNode.insertBefore(js, fjs);
			}(document, "script", "facebook-jssdk"));';

		$script->appendChild( $document->createTextNode( $script_code ) );
		$root = $document->createElement( 'div' );
		$root->setAttribute( 'id', 'fb-root' );
		$fragment->appendChild( $root );
		$fragment->appendChild( $script );
		return $fragment;
	}

	/**
	 * Wraps the embed into a div with class "embed" to be easily converted into InstantArticle object structure.
	 *
	 * @param DOMDocument $document the document holder of the embed.
	 */
	function wrap_facebook_embed( $document ) {
		$xpath = new DOMXpath( $document );
		$node_list = $xpath->query( "//div[starts-with(@class, 'fb-')]" );
		foreach ( $node_list as $node ) {
			$wrapped = $document->createElement( 'div' );
			$wrapped->setAttribute( 'class', 'embed' );
			$wrapped->appendChild( $this->render_sdk( $document ) );
			$wrapped->appendChild( $node->cloneNode( true ) );
			if ( $node->parentNode ) {
				$node->parentNode->replaceChild( $wrapped, $node );
			}
		}

		return $document;
	}
}
