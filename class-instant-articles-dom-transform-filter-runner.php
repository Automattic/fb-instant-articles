<?php

/**
 * Class for handling registrating and execution of the DOM transformation filters
 *
 * @since 0.1
 */
class Instant_Articles_DOM_Transform_Filter_Runner {

	/** @var  array  Our array for keeping the classnames of the DOM transformation filters */
	protected static $_stack = array();

	/**
	 * Register a DOM transformation filter class
	 *
	 * @since 0.1
	 * @param string  $className  The name of a class that extends Instant_Articles_DOM_Transform_Filter
	 */
	static function register( $className ) {
		if ( ! in_array( $className, self::$_stack ) && is_subclass_of( $className, 'Instant_Articles_DOM_Transform_Filter' ) ) {
			self::$_stack[] = $className;
		}
	}


	/**
	 * Run all the registered DOM tranformation filters
	 *
	 * @since 0.1
	 * @param DOMDocument  $DOMDocument  The DOMDocument we are working on
	 * @param int          $post_id      The current post ID
	 */
	static function run( $DOMDocument, $post_id ) {
		foreach ( self::$_stack as $className ) {
			$obj = new $className( $DOMDocument, $post_id );
			$obj->run();
		}
		return $DOMDocument;
	}

}
