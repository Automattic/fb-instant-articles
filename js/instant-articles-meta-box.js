function instant_articles_load_meta_box ( post_ID ) {
	jQuery( document ).ready( function( $ ) {
		var data = {
			'action': 'instant_articles_meta_box',
			'post_ID': post_ID
		};
		jQuery.post( ajaxurl, data, function(response) {
			jQuery( '#instant_article_meta_box .inside' ).html( response );
		}, 'html' );
		jQuery( '#instant_article_meta_box' ).delegate( '.instant-articles-toggle-debug', 'click', function () {
			jQuery( '#instant_article_meta_box' ).toggleClass( 'instant-articles-show-debug' );
			return false;
		} );
	});
}
