function instant_articles_setup_opengraph_load ( data ) {
	jQuery( '#instant_articles_wizard' ).addClass( 'loading' );
	jQuery.post( ajaxurl, data, function( response ) {
		jQuery( '#instant_articles_wizard' ).html( response );
		instant_articles_setup_opengraph_bind_events();
		jQuery( '#instant_articles_wizard' ).removeClass( 'loading' );
		window.scrollTo(0, 0);
	}, 'html' );
}

function instant_articles_setup_opengraph_save_page ( page_id ) {
	instant_articles_setup_opengraph_load ( {
		'action': 'instant_articles_setup_opengraph_save_page',
		'page_id': page_id
	} );
}

function instant_articles_opengraph_edit_page () {
	instant_articles_setup_opengraph_load ( {
		'action': 'instant_articles_setup_opengraph_edit_page',
	} );
}

function instant_articles_setup_opengraph_bind_events () {

	jQuery( 'input[name=page_id]' ).on( 'input',  function () {
		input = jQuery( 'input[name=page_id]' );
		input.val( input.val().replace( /[^\d\.\-]/g, '' ) );
		var page_id = input.val();

		if ( page_id && page_id !== '' ) {
			jQuery( '#instant-articles-opengraph-save-page' ).removeClass( 'instant-articles-button-disabled' );
		}
		else {
			jQuery( '#instant-articles-opengraph-save-page' ).addClass( 'instant-articles-button-disabled' );
		}
	});

	jQuery( '#instant-articles-opengraph-save-page' ).on( 'click', function () {
		if ( jQuery( this ).hasClass( 'instant-articles-button-disabled' ) ) {
			return false;
		}
		var page_id = jQuery( 'input[name=page_id]' ).val();
		instant_articles_setup_opengraph_save_page( page_id );
	});

	jQuery( '#instant-articles-opengraph-edit-page' ).on( 'click', function () {
		if ( jQuery( this ).hasClass( 'instant-articles-button-disabled' ) ) {
			return false;
		}
		instant_articles_opengraph_edit_page();
	});
}


jQuery( document ).ready( function () {
	instant_articles_setup_opengraph_bind_events();

	jQuery( '.instant-articles-wizard-toggle a' ).on( 'click', function () {
		$advancedSettingsContainer = jQuery( '.instant-articles-advanced-settings' );
		if ( $advancedSettingsContainer.prop( 'data-state') === 'closed' ) {
			$advancedSettingsContainer.prop( 'data-state', 'opened' );
		}
		else {
			$advancedSettingsContainer.prop( 'data-state', 'closed' );
		}
		return false;
	});
});
