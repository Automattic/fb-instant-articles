function instant_articles_wizard_transition ( new_state, params ) {
	var data = {
		'action': 'instant_articles_wizard_transition',
		'new_state': new_state,
		'params': JSON.stringify(params)
	};
	jQuery( '#instant_articles_wizard' ).addClass( 'loading' );
	jQuery.post( ajaxurl, data, function(response) {
		jQuery( '#instant_articles_wizard' ).html( response );
		instant_articles_wizard_bind_events();
		jQuery( '#instant_articles_wizard' ).removeClass( 'loading' );
	}, 'html' );
}

function instant_articles_wizard_save_app ( app_id, app_secret ) {
	var data = {
		'action': 'instant_articles_wizard_save_app',
		'app_id': app_id,
		'app_secret': app_secret
	};
	jQuery( '#instant_articles_wizard' ).addClass( 'loading' );
	jQuery.post( ajaxurl, data, function(response) {
		jQuery( '#instant_articles_wizard' ).html( response );
		instant_articles_wizard_bind_events();
		jQuery( '#instant_articles_wizard' ).removeClass( 'loading' );
	}, 'html' );
}

function instant_articles_wizard_edit_app ( app_id, app_secret ) {
	var data = {
		'action': 'instant_articles_wizard_edit_app',
	};
	jQuery( '#instant_articles_wizard' ).addClass( 'loading' );
	jQuery.post( ajaxurl, data, function(response) {
		jQuery( '#instant_articles_wizard' ).html( response );
		instant_articles_wizard_bind_events();
		jQuery( '#instant_articles_wizard' ).removeClass( 'loading' );
	}, 'html' );
}

function instant_articles_wizard_bind_events () {

	jQuery( '#instant_articles_wizard a' ).on( 'click', function () {
		jQuery( '#instant_articles_wizard' ).addClass( 'loading' );
	});

	jQuery( '.instant-articles-wizard-transition' ).on( 'click', function () {
		instant_articles_wizard_transition( jQuery( this ).attr( 'data-new-state' ) );
	});

	jQuery('input[name=app_id], input[name=app_secret]').on( 'change',  function () {
		var app_id = jQuery('input[name=app_id]').val();
		var app_secret = jQuery('input[name=app_secret]').val();
		if (app_id && app_secret) {
			jQuery( '#instant-articles-wizard-save-app' ).removeClass( 'instant-articles-button-disabled' );
		}
		else {
			jQuery( '#instant-articles-wizard-save-app' ).addClass( 'instant-articles-button-disabled' );
		}
	});

	jQuery('input[name=page_id]').on( 'change',  function () {
		var page_id = jQuery('input[name=page_id]:checked').val();
		if (page_id) {
			jQuery( '#instant-articles-wizard-select-page' ).removeClass( 'instant-articles-button-disabled' );
		}
		else {
			jQuery( '#instant-articles-wizard-select-page' ).addClass( 'instant-articles-button-disabled' );
		}
	});

	jQuery( '#instant-articles-wizard-save-app' ).on( 'click', function () {
		var app_id = jQuery('input[name=app_id]').val();
		var app_secret = jQuery('input[name=app_secret]').val();
		instant_articles_wizard_save_app( app_id, app_secret );
	});

	jQuery( '#instant-articles-wizard-edit-app' ).on( 'click', function () {
		instant_articles_wizard_edit_app();
	});

	jQuery( '#instant-articles-wizard-select-page' ).on( 'click', function () {
		var page_id = jQuery('input[name=page_id]:checked').val();
		instant_articles_wizard_transition( 'STATE_STYLE_SELECTION', { page_id: page_id } );
	});

	jQuery( '.instant-articles-card-content-box li' ).on( 'click', function () {
		jQuery( '.instant-articles-card-content-box li.instant-articles-radio-selected' ).removeClass( 'instant-articles-radio-selected' );
		jQuery( this ).find( 'input' ).attr( 'checked', 'checked' );
		jQuery( this ).find( 'input' ).trigger( 'change' );
		jQuery( this ).toggleClass( 'instant-articles-radio-selected' );
	});
}


jQuery( document ).ready( function () {
	instant_articles_wizard_bind_events();

	jQuery( '.instant-articles-wizard-toggle' ).on( 'click', function () {
		var text = jQuery( this ).text();
		if ( text.indexOf( '►' ) !==  -1 ) {
			text = text.replace( '►', '▼' );
		}
		else {
			text = text.replace( '▼', '►' );
		}
		jQuery( this ).text( text ).next().slideToggle();
		return false;
	});
});
