jQuery( function () {
	var idAdSource             = '#' + INSTANT_ARTICLES_OPTION_ADS['option_field_id_source'];
	var idFanPlacementId       = '#' + INSTANT_ARTICLES_OPTION_ADS['option_field_id_fan'];
	var idIframeUrl            = '#' + INSTANT_ARTICLES_OPTION_ADS['option_field_id_iframe'];
	var idEmbedCode            = '#' + INSTANT_ARTICLES_OPTION_ADS['option_field_id_embed'];
	var idDimensions           = '#' + INSTANT_ARTICLES_OPTION_ADS['option_field_id_dimensions'];
  var idDefaultPlacement     = '#' + INSTANT_ARTICLES_OPTION_ADS['option_field_id_default_placement'];
	var $rowFanPlacementId     = jQuery( idFanPlacementId ).parents( 'tr' );
	var $rowIframeUrl          = jQuery( idIframeUrl )     .parents( 'tr' );
	var $rowEmbedCode          = jQuery( idEmbedCode )     .parents( 'tr' );
	var $rowDimensions         = jQuery( idDimensions )    .parents( 'tr' );
  var $rowDefaultPlacement   = jQuery( idDefaultPlacement )    .parents( 'tr' );
	jQuery( idAdSource ).change( function () {
		switch (jQuery( this ).val()) {
			case 'fan':
				$rowFanPlacementId   .show();
				$rowIframeUrl        .hide();
				$rowEmbedCode        .hide();
				$rowDimensions       .show();
        $rowDefaultPlacement .show();
			break;

			case 'iframe':
				$rowFanPlacementId   .hide();
				$rowIframeUrl        .show();
				$rowEmbedCode        .hide();
				$rowDimensions       .show();
        $rowDefaultPlacement .show();
			break;

			case 'embed':
				$rowFanPlacementId   .hide();
				$rowIframeUrl        .hide();
				$rowEmbedCode        .show();
				$rowDimensions       .show();
        $rowDefaultPlacement .show();
			break;

			default:
				$rowFanPlacementId   .hide();
				$rowIframeUrl        .hide();
				$rowEmbedCode        .hide();
				$rowDimensions       .hide();
        $rowDefaultPlacement .show();
			break;
		}
	} ).trigger( 'change' );
} );
