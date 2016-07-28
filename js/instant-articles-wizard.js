function instant_articles_wizard_transition ( $new_state, $params ) {
	var data = {
		'action': 'instant_articles_wizard_transition',
		'new_state': $new_state,
		'params': $params
	};
	jQuery.post( ajaxurl, data, function(response) {
		jQuery( '#instant_articles_wizard' ).html( response );
		instant_articles_wizard_bind_events();
	}, 'html' );
}

function instant_articles_wizard_bind_events () {
	jQuery( document ).ready( function( $ ) {
		$( '.instant-articles-wizard-transition' ).on( 'click', function () {
			instant_articles_wizard_transition( $( this ).attr( 'data-new-state' ) );
		});
		$( '.instant-articles-card-content-box li' ).on( 'click', function () {
			$( '.instant-articles-card-content-box li.instant-articles-radio-selected' ).removeClass( 'instant-articles-radio-selected' );
			$( this ).find( 'input' ).attr( 'checked', 'checked' );
			$( this ).toggleClass( 'instant-articles-radio-selected' );
		});
	});
}

instant_articles_wizard_bind_events();
