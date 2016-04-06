jQuery( function () {
	jQuery( '#instant-articles-fb-page-selector' ).change(function () {
		var val = jQuery( this ).val();
		var obj = jQuery.parseJSON( val );

		if ( val ) {
			jQuery( '[data-step-id="page-selection"] input[type="submit"]' ).attr( 'disabled', false );
			jQuery.each( obj, function ( key, value ) {
				jQuery( 'input[name="instant-articles-option-fb-page[' + key + ']"]' )
					.val( value );
			});
		}
		else {
			jQuery( '[data-step-id="page-selection"] input[type="submit"]' ).attr( 'disabled', true );
		}
	} ).trigger( 'change' );
} );
