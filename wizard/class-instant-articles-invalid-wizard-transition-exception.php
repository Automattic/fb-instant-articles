<?php
/**
 * Facebook Instant Articles for WP.
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 *
 * @package default
 */

/**
 * Exception thrown when triggering an invalid transition on the state machine.
 *
 * @since 3.1
 */
class Instant_Articles_Invalid_Wizard_Transition_Exception extends Exception {
	public function __construct ( $original_state, $new_state ) {
		parent::__construct( "Invalid transition: $original_state => $new_state." );
	}
}
