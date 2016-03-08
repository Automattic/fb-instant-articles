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
	 * @param int     $priority   Optional. Used to specify the order in which the filters are executed.
	 *                            Default 10. Lower numbers correspond with earlier execution, and filters 
	 *                            with the same priority are executed in the order in which they were added.
	 */
	static function register( $className, $priority = 10 ) {
		if ( ! isset( self::$_stack[ $priority ] ) || ! is_array( self::$_stack[ $priority ] ) ) {
			self::$_stack[ $priority ] = array();
		}
		if ( ! in_array( $className, self::$_stack[ $priority ], true ) && is_subclass_of( $className, 'Instant_Articles_DOM_Transform_Filter' ) ) {
			self::$_stack[ $priority ][] = $className;
		}
	}


	/**
	 * Run all the registered DOM tranformation filters
	 *
	 * @since 0.1
	 * @param DOMDocument  $DOMDocument  The DOMDocument we are working on
	 * @param int          $post_id      The current post ID
	 * @return DOMDocument  The modified DOMDocument
	 */
	static function run( $DOMDocument, $post_id ) {
		ksort( self::$_stack );
		foreach ( self::$_stack as $priority => $filters ) {
			foreach ( $filters as $className ) {
				$obj = new $className( $DOMDocument, $post_id );
				$obj->run();
			}
		}
		return $DOMDocument;
	}

}
