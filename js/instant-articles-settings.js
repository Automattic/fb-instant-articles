jQuery( function () {
	jQuery( '.instant-articles-settings-box > h2' ).click(function () {
		$h2 = jQuery( this );
		$h2.siblings('.inside').toggle();
		$h2.toggleClass('dashicons-arrow-down');
		$h2.toggleClass('dashicons-arrow-right');
	});
} );
