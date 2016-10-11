function instant_articles_wizard_load ( data ) {
	jQuery( '#instant_articles_wizard' ).addClass( 'loading' );
	jQuery.post( ajaxurl, data, function( response ) {
		jQuery( '#instant_articles_wizard' ).html( response );
		instant_articles_wizard_bind_events();
		jQuery( '#instant_articles_wizard' ).removeClass( 'loading' );
		window.scrollTo(0, 0);
	}, 'html' );
}

function instant_articles_wizard_transition ( new_state, params ) {
	instant_articles_wizard_load ( {
		'action': 'instant_articles_wizard_transition',
		'new_state': new_state,
		'params': JSON.stringify(params)
	} );
}

function instant_articles_wizard_save_app ( app_id, app_secret ) {
	instant_articles_wizard_load ( {
		'action': 'instant_articles_wizard_save_app',
		'app_id': app_id,
		'app_secret': app_secret
	} );
}

function instant_articles_wizard_edit_app () {
	instant_articles_wizard_load ( {
		'action': 'instant_articles_wizard_edit_app',
	} );
}


function instant_articles_wizard_submit_for_review () {
	instant_articles_wizard_load ( {
		'action': 'instant_articles_wizard_submit_for_review',
	} );
}

function instant_articles_wizard_bind_events () {

	jQuery( '#instant_articles_wizard a' ).on( 'click', function () {
		if ( ! jQuery( this ).attr( 'target' ) ) {
			jQuery( '#instant_articles_wizard' ).addClass( 'loading' );
		}
	});

	jQuery( '.instant-articles-wizard-transition' ).on( 'click', function () {
		if ( jQuery( this ).hasClass( 'instant-articles-button-disabled' ) ) {
			return false;
		}
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
		var input = jQuery( this );
		var page_id = jQuery('input[name=page_id]:checked').val();
		var signed_up = ( jQuery('input[name=page_id]:checked').attr( 'data-signed-up' ) === 'yes' );
		if ( page_id && signed_up ) {
			jQuery( '#instant-articles-wizard-select-page' ).removeClass( 'instant-articles-button-disabled' );
		}
		else {
			jQuery( '#instant-articles-wizard-select-page' ).addClass( 'instant-articles-button-disabled' );
		}
	});

	jQuery( '#instant-articles-wizard-save-app' ).on( 'click', function () {
		if ( jQuery( this ).hasClass( 'instant-articles-button-disabled' ) ) {
			return false;
		}
		var app_id = jQuery('input[name=app_id]').val();
		var app_secret = jQuery('input[name=app_secret]').val();
		instant_articles_wizard_save_app( app_id, app_secret );
	});

	jQuery( '#instant-articles-wizard-edit-app' ).on( 'click', function () {
		if ( jQuery( this ).hasClass( 'instant-articles-button-disabled' ) ) {
			return false;
		}
		instant_articles_wizard_edit_app();
	});

	jQuery( '#instant-articles-wizard-submit-for-review' ).on( 'click', function () {
		if ( jQuery( this ).hasClass( 'instant-articles-button-disabled' ) ) {
			return false;
		}
		instant_articles_wizard_submit_for_review();
	});

	jQuery( '#instant-articles-wizard-select-page' ).on( 'click', function () {
		if ( jQuery( this ).hasClass( 'instant-articles-button-disabled' ) ) {
			return false;
		}
		var page_id = jQuery('input[name=page_id]:checked').val();
		instant_articles_wizard_transition( 'STATE_STYLE_SELECTION', { page_id: page_id } );
	});

	jQuery( '#instant-articles-wizard-customize-style' ).on( 'click', function () {
		jQuery( '#instant-articles-wizard-customize-style-next' ).show();
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

	jQuery( '.instant-articles-wizard-toggle a' ).on( 'click', function () {
		$advancedSettingsContainer = jQuery( '.instant-articles-advanced-settings' );
		if ( $advancedSettingsContainer.attr( 'data-state') === 'closed' ) {
			$advancedSettingsContainer.attr( 'data-state', 'opened' );
		}
		else {
			$advancedSettingsContainer.attr( 'data-state', 'closed' );
		}
		return false;
	});
});
