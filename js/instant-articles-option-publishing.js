jQuery( function () {
	var idCustomRulesEnabled  = '#' + INSTANT_ARTICLES_OPTION_PUBLISHING['option_field_id_custom_rules_enabled'];
	var idCustomRules         = '#' + INSTANT_ARTICLES_OPTION_PUBLISHING['option_field_id_custom_rules'];
	var $rowCustomRules = jQuery( idCustomRules ).parents( 'tr' );
	var $customRulesEnabled = jQuery( idCustomRulesEnabled );
	$customRulesEnabled.change( function () {
		if ( $customRulesEnabled.is( ':checked' ) ) {

			$rowCustomRules.show();

		} else {

			$rowCustomRules.hide();

		}
	} ).trigger( 'change' );
} );
