jQuery( function () {
	var idEmbedCodeEnabled  = '#' + INSTANT_ARTICLES_OPTION_ANALYTICS['option_field_id_embed_code_enabled'];
	var idEmbedCode         = '#' + INSTANT_ARTICLES_OPTION_ANALYTICS['option_field_id_embed_code'];
	var $rowEmbedCode = jQuery( idEmbedCode ).parents( 'tr' );
	var $embedCodeEnabled = jQuery( idEmbedCodeEnabled );
	$embedCodeEnabled.change( function () {
		if ( $embedCodeEnabled.is( ':checked' ) ) {

			$rowEmbedCode.show();

		} else {

			$rowEmbedCode.hide();

		}
	} ).trigger( 'change' );
} );
