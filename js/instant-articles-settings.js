jQuery( function () {
	jQuery( '#instant-articles-fb-page-selector' ).change(function () {
		var val = jQuery( this ).val();
		var obj = jQuery.parseJSON( val );

		if ( val ) {
			jQuery( 'input[name=instant-articles-select-page]' ).attr( 'disabled', false );
			jQuery.each( obj, function ( key, value ) {
				jQuery( 'input[name="instant-articles-option-fb-page[' + key + ']"]' )
					.val( value );
			});
		}
		else {
			jQuery( 'input[name=instant-articles-select-page]' ).attr( 'disabled', true );
		}
	} ).trigger( 'change' );


	jQuery( '.instant-articles-settings-box > h2' ).click(function () {
		$h2 = jQuery( this );
		$h2.siblings('.inside').toggle();
		$h2.toggleClass('dashicons-arrow-down');
		$h2.toggleClass('dashicons-arrow-right');
	})
} );
