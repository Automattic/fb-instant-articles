function instant_articles_force_submit ( post_ID ) {
	var data = {
		'action': 'instant_articles_force_submit',
		'post_ID': post_ID,
		'force': jQuery( '#instant_articles_force_submit' ).is( ':checked' ),
		'security': jQuery( '#instant_articles_force_submit' ).attr( 'data-security' )
	};
	jQuery.post( ajaxurl, data, function(response) {
		instant_articles_load_meta_box( post_ID );
	});
}
function instant_articles_load_meta_box ( post_ID ) {
	jQuery( document ).ready( function( $ ) {
		var data = {
			'action': 'instant_articles_meta_box',
			'post_ID': post_ID
		};
		jQuery.post( ajaxurl, data, function(response) {
			jQuery( '#instant_article_meta_box .inside' ).html( response );
			jQuery( '#instant_articles_force_submit').click( function () {
				instant_articles_force_submit( post_ID );
			} );
		}, 'html' );
		jQuery( '#instant_article_meta_box' ).delegate( '.instant-articles-toggle-debug', 'click', function () {
			jQuery( '#instant_article_meta_box' ).toggleClass( 'instant-articles-show-debug' );
			return false;
		} );
	});
}
